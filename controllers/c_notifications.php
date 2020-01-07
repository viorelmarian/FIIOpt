<?php
require_once "../shared/db_conn.php";
require_once "../models/m_notifications.php";

class notifications {    
    function display() {
        //If logged in
        if (isset($_SESSION["logged"])) {
            //Allow access
            require_once "../views/v_notifications.php";            
        } else {
            //Redirect accordingly
            require_once "../views/v_login.php";
        }
    }
}