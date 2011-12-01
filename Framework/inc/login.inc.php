<?php
/*
 * Login and session includes
 */

function validUser($username, $password) {
    $DB_LOGIN = new MySQLdatabase(MYSQL_HOST, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE, true);
    $username = str_replace("'", "\'", ($username));
    $password = str_replace("'", "\'", ($password));
    $functionQuery = ("SELECT user_id AS userid FROM users WHERE user_name = '$username' AND user_password = MD5('$password') AND user_active = 1");
    $result = $DB_LOGIN->query($functionQuery);
    $userId = 0;
    while ($row = mysql_fetch_array($result)) {
        $userId = $row["userid"];
    }
    return $userId;
}

function validUserAndLog($username, $password, $page = "") {
    $DB = new MySQLdatabase(MYSQL_HOST, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE, true);
    $DP = new DataProcessor();
    $VA = new Validator();
    $userId = 0;
    $page = str_replace("'", "\'", $page);
    $userId = validUser($username, $password);
    $query = "INSERT INTO login_logs(log_time, log_session, user_id, log_ip, log_page)
	VALUES(CURRENT_TIMESTAMP, '".  session_id()."', $userId, '".$VA->getRealIPAddress()."', '$page')";
    $DB->query($query);
    $status = null;
    if ($userId != 0) {
        $status = setUserSession($userId);
    }
    return $status;
}

function isLogged() {
    $status = null;
    $info = Session::verifySession(SESSION_NAME);
    if ($info != null) {
        $status = @unserialize(DataProcessor::encryptInfo($info, CRYPTHASH));
        if (!is_array($status)) {
            $status = null;
        }
    }
    return $status;
}

function logOut() {
    Session::destroySession(SESSION_NAME);
}


function setUserSession($userId) {
    $DB_LOGIN = new MySQLdatabase(MYSQL_HOST, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE, true);
    //It is valid
    $userInfo = array();
    $loginQuery = "SELECT
                            u.user_id,
                            u.user_name,
                            u.user_email,
                            u.user_type,
                            ut.user_type_name,
                            u.user_created,
                            u.user_firstname,
                            u.user_lastname,
                            u.user_avatar,
                            (SELECT log_time FROM login_logs WHERE user_id = u.user_id ORDER BY log_time DESC LIMIT 1) AS last_login
                    FROM
                            users AS u
                            JOIN user_types AS ut ON ut.user_type = u.user_type
                    WHERE
                            u.user_id = $userId ";
    $result = $DB_LOGIN->query($loginQuery);
    while ($row = mysql_fetch_array($result)) {
        foreach ($row as $key => $value) {
            $userInfo[$key] = $value;
        }
    }
    $status = SESSION::setSession(SESSION_NAME, DataProcessor::encryptInfo(serialize($userInfo), CRYPTHASH));
    SESSION::setSession(SESSION_CONTROL, time());
    return $status;
}

function isAdmin(){
   $is = FALSE;
   if(isLogged ()){
       $userInfo = isLogged();
       if($userInfo["user_type"] == "1"){
            $is = TRUE;
       }
   }
   return $is;
}

function getUserType(){
    return $userInfo["user_type"];
}
?>
