<?php
require_once "../shared/db_conn.php";
require_once "../models/m_assignations.php";

class assignations {
    function display() {
        //If logged in
        if (isset($_SESSION["logged"])) {
            //Allow access
            require_once "../views/v_choices.php";            
        } else {
            //Redirect accordingly
            require_once "../views/v_login.php";
        }
    }
    function get($option) {    
        //If logged in    
        if (isset($_SESSION["logged"])) {  
            //If db connection does not exist
            if (!isset($db)) {
                //Create db connection
                $db = new database_conn;
                $db->connect();
            }
            //If model instance does not exist
            if (!isset($assignations)) {
                //Create model instance
                $assignations = new m_assignations($db->conn);
            }
            if (!isset($courses)) {
                //Create model instance
                $courses = new m_courses($db->conn);
            }
            //Get data
            if($option == 'display') {
                $result = $assignations->getAssignations(); 
            } elseif ($option == 'trade') {
                $result = $assignations->getTradeCourses(); 
            }            
            $rows = array();        
            //Fetch data in assoc array
            while( $row = $result->fetch_assoc()) {
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
            //Add rows to Array
            $rows[] = $row;
            }
            //Encode data in JSON Format and return
            echo json_encode($rows);
        } else {
            //Redirect accordingly
            require_once "../views/v_login.php";
        }
    }
    function insert($courseId) {
        //If logged in
        if (isset($_SESSION["logged"])) { 
            //If db connection does not exist 
            if (!isset($db)) {
                //Create db connection
                $db = new database_conn;
                $db->connect();
            }
            //If model instance does not exist
            if (!isset($assignations)) {
                //Create model instance
                $assignations = new m_assignations($db->conn);
            }
            //Validations
            $result = $assignations->validateChoice($courseId);
            
            if (array_values($result->fetch_assoc())[0] == 0) {
                //Insert choosen option
                $assignations->insert($courseId);
                //Generate response
                $response = array(  "status"=>"Success",
                                    "msg" => "Your choice has been successfully registered!"
                                );
            } else {
                //Generate response
                $response = array(  "status"=>"Error",
                                    "msg" => "A course from this package was already choosen!"
                                );
            }
            echo json_encode($response);
        } else {
            //Redirect accordingly
            require_once "../views/v_login.php";
        }
    }
}
