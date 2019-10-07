<?php
require_once "../shared/db_conn.php";
require_once "../models/m_courses.php";

class courses
{    
    function display() {
        if (isset($_SESSION["logged"])) {
            require_once "../views/v_courses.php";            
        } else {
            require_once "../views/v_login.php";
        }
    }

    function get($src) {        
        if (isset($_SESSION["logged"])) {  
            $db = new database_conn;
            $db->connect();

            $courses = new m_courses($db->conn);
            $result = $courses->get($src);

            while( $row = $result->fetch_assoc()) {
                foreach($row as $key => $value) {
                    $row[$key] = utf8_encode($value);
                }
            $rows[] = $row;
            }
            echo json_encode($rows);
        } else {
            require_once "../views/v_login.php";
        }
    }
}
?>