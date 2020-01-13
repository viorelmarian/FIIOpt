<?php
require_once "../shared/db_conn.php";
require_once "../models/m_administration.php";

class administration {    
    function display() {
        //If logged in
        if (isset($_SESSION["logged"])) {
            //Allow access
            require_once "../views/v_administration.php";            
        } else {
            //Redirect accordingly
            require_once "../views/v_admin.php";
        }
    }
    
}
?>