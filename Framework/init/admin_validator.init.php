<?php
    //This is the process to verify the admin section

    if(getSegment(ADMIN_FOLDER_SEGMENT) == ADMIN_FOLDER ){
        if(!isAdmin ()){
            header("Location: /login.php?per=".$_SERVER["PHP_SELF"]);
        }
    }

?>
