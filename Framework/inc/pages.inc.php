<?php

function send_404($ex){    header("Location: /404/?".$ex);   }


function get_pages_views(){
    $information = array();
    $DB = new MySQLdatabase(MYSQL_HOST, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE, false);
    $query = "SELECT
		COUNT(tp.page_id) AS views, p.page_name,p.page_url
                FROM
                        tracking_pages AS tp
                        JOIN pages AS p ON tp.page_id = p.page_id
                GROUP BY
                                tp.page_id
                ";
  $result = $DB->query($query);
  if($DB->getLastQueryCount() > 0){
    while($row = mysql_fetch_array($result)){
        $aux = array();
        $aux["title"] = $row["page_name"];
        $aux["views"] = $row["views"];
        $information[] = $aux;
    }
  }
  return $information;
}

?>