<?php

//Get the block content
function include_block($name){
    include(BLOCKS_FOLDER."/".$name.".php");
}

//Get specifict page content
function get_page_field($id,$field){
    $query = '';
    $fieldName = $field;
    $return = "";
    if(is_int($id)  ){  $query = "SELECT $fieldName FROM pages WHERE page_id = $id AND page_active = 1";
    }elseif(is_string($id) ){   $id = str_replace("'","\'",$id);  $query = "SELECT $fieldName FROM pages WHERE page_url = '$id' AND page_active = 1";
    }else{ return NULL; }
    $DB = new MySQLdatabase(MYSQL_HOST, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE, TRUE);
    //echo $query."<br />";
    $result = $DB->query($query);
    if($DB->getLastQueryCount() > 0){  $row = mysql_fetch_array($result);  $return = utf8_decode($row[0]);  }
    return ($return);
}


//Get the main page content
function get_page_content($id){
    return get_page_field($id,"page_main_content");
}
function print_page_content($id){  echo (get_page_content($id)); }

//Get the second page content
function get_page_second_content($id){
    return get_page_field($id,"page_second_content");
}
function print_page_second_content($id){  echo (get_page_second_content($id)); }

//Get the page title
function get_page_title($id){
    return get_page_field($id,"page_title");
}
function print_page_title($id){  echo (get_page_title($id)); }

//Get the page url
function get_page_url($id){
    return get_page_url($id,"page_url");
}
function print_page_url($id){  echo (get_page_url($id)); }

//Get the page subtitle
function get_page_subtitle($id){
    return get_page_field($id,"page_subtitle");
}
function print_page_subtitle($id){  echo (get_page_subtitle($id)); }

//Get page name
function get_page_name($id){
    return get_page_field($id,"page_name");
}
function print_page_name($id){  echo (get_page_name($id)); }

function track_page($id){
    $DB = new MySQLdatabase(MYSQL_HOST, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE, TRUE);
    if(is_string($id)){
        $id = str_replace("'", "\'", $id);
        $query = "SELECT page_id FROM pages WHERE page_url = '$id';";
        $result = $DB->query($query);
        $row = mysql_fetch_array($result);
        $id = (int)$row[0];
    }

    $VA = new Validator();
    $DP = new DataProcessor();
    @session_start();
    $sessionId = session_id();
    $ip = $VA->getRealIPAddress();
    $query = "SELECT track_id FROM tracking_pages WHERE track_session = '$sessionId' AND track_ip = '$ip' AND page_id = $id";
    $DB->query($query);
    if($DB->getLastQueryCount() == 0){
        $query = "INSERT INTO tracking_pages
                    (
                    page_id,
                    track_time,
                    track_session,
                    track_ip)
                    VALUES
                    (
                    $id,
                    CURRENT_TIMESTAMP,
                    '$sessionId',
                    '$ip'
                    );
                    ";
        $DB->query($query);
    }
}

function page_active($id){
    if(is_integer($id)){
        $query = "SELECT page_active FROM pages WHERE page_id = $id";
       $DB = new MySQLdatabase(MYSQL_HOST, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE, TRUE);
       $result = $DB->query($query);
       $row = mysql_fetch_array($result);
       if($row[0] == "1"){
           return TRUE;
       }else{
           return FALSE;
       }
    }elseif(is_string($id)){
        $id = str_replace("'", "\'", $id);
        $query = "SELECT page_active FROM pages WHERE page_url = '$id'";
        $DB = new MySQLdatabase(MYSQL_HOST, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE, TRUE);
       $result = $DB->query($query);
       $row = mysql_fetch_array($result);
       if($row[0] == "1"){
           return TRUE;
       }else{
           return FALSE;
       }
    }else{
        return FALSE;
    }
}


function page_generic($id){
    if(is_integer($id)){
        $query = "SELECT page_generic FROM pages WHERE page_id = $id";
       $DB = new MySQLdatabase(MYSQL_HOST, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE, TRUE);
       $result = $DB->query($query);
       $row = mysql_fetch_array($result);
       if($row[0] == "1"){
           return TRUE;
       }else{
           return FALSE;
       }
    }elseif(is_string($id)){
        $id = str_replace("'", "\'", $id);
        $query = "SELECT page_generic FROM pages WHERE page_url = '$id'";
        $DB = new MySQLdatabase(MYSQL_HOST, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE, TRUE);
       $result = $DB->query($query);
       $row = mysql_fetch_array($result);
       if($row[0] == "1"){
           return TRUE;
       }else{
           return FALSE;
       }
    }else{
        return FALSE;
    }
}

?>
