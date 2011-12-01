<?php

/*
here will be all global variables that we have to use.
 */

global $DB,$VA,$DP,$global_ip,$global_sess,$global_domain,$global_current_url;

$global_ip = $VA->getRealIPAddress();
$global_sess= session_id();
$global_domain = $DP->getDomain();
$global_current_url = $global_domain.$_SERVER["PHP_SELF"];
?>
