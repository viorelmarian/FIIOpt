<?php
require_once "../shared/db_conn.php";
require_once "../models/m_courses.php";

class courses {    
    function display() {
        //If logged in
        if (isset($_SESSION["logged"])) {
            //Allow access
            require_once "../views/v_courses.php";            
        } else {
            //Redirect accordingly
            require_once "../views/v_login.php";
        }
    }
    function get($src) {        
        //If logged in
        if (isset($_SESSION["logged"])) {
            //If db connection does not exist
            if(!isset($db)) {
                //Create db connection
                $db = new database_conn;
                $db->connect();
            }
            //If model instance does not exist
            if (!isset($courses)) {
                //Create model instance
                $courses = new m_courses($db->conn);
            }
            //Get data
            $result = $courses->getCourses($src);    
            $rows = array();        
            //Fetch data in assoc array
            while($row = $result->fetch_assoc()) {                
                //For each Course get the corresponding professors
                $professors = $courses->getProfessors($row["course_id"]);
                //Add professors to the rest of the data
                $i = 1;
                while($professor = $professors->fetch_assoc()) {
                    $row["professor_" . $i++] = $professor["title"] . " " . $professor["l_name"] . " " . $professor["f_name"];
                }
                foreach($row as $key => $value) {
                    //Encode each value of the row in utf8
                    $row[$key] = utf8_encode($value);
                }
            //Add rows in Array
            $rows[] = $row;
            }
            //Encode in JSON Format and return
            echo json_encode($rows);
        } else {
            //Redirect accordingly
            require_once "../views/v_login.php";
        }
    }
}
?>