<?php

/*
This file will be executed it when the page loads.
 */



// All the following process will verify if the 
$pages = array("home.php");
global $DB,$VA,$DP,$global_ip,$global_sess,$global_current_url;
$global_ip = $VA->getRealIPAddress();
$global_sess= session_id();
$global_domain = $DP->getDomain();
$global_current_url = $global_domain.$_SERVER["PHP_SELF"];
$ref = (isset($_GET["ref"]))?$_GET["ref"]:"";
$ref = str_replace("'","\'",$ref);
$global_current_url = str_replace("'","\'",$global_current_url);
$track = false;

if(Session::verifySession("track") == "yes"){
    $track = true;
}

if($track == false){
        if(in_array(getSegment(1), $pages)    ){
                $query = "SELECT reg_id FROM visit_registry WHERE reg_ip = '$global_ip' AND reg_sess = '$global_sess'";
                $result = $DB->query($query);
                if( $DB->getLastQueryCount() == 0 ){
                        $query = "INSERT INTO 
                                        visit_registry
                                        (reg_url,reg_ref,reg_date,reg_ip,reg_sess)
                                  VALUES
                                        ('$global_current_url','$ref',CURRENT_TIMESTAMP,'$global_ip','$global_sess')
                                 ";
                        //echo $query;
                        $DB->query($query);
                        Session::setSession("track", "yes");
                }
        }
}



?>
