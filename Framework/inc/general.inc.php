<?php
function getSegment($number){
    $longURL = $_SERVER["PHP_SELF"];
    $urlArray = explode('/', $longURL);
    return @$urlArray[$number];
}

function get_administrative_emails(){
    $DB = new MySQLdatabase(MYSQL_HOST, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE, true);
    $emails = array();
    $query = "SELECT user_email FROM users WHERE user_type = 1";
    $result = $DB->query($query);
    while($row = mysql_fetch_array($result)){
        $emails[] = $row[0];
    }
    return $emails;
}

?>
