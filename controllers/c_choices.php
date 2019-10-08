<?php
require_once "../shared/db_conn.php";
require_once "../models/m_choices.php";

class choices {
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
    function get() {    
        //If logged in    
        if (isset($_SESSION["logged"])) {  
            //If db connection does not exist
            if (!isset($db)) {
                //Create db connection
                $db = new database_conn;
                $db->connect();
            }
            //If model instance does not exist
            if (!isset($choices)) {
                //Create model instance
                $choices = new m_choices($db->conn);
            }
            //Get data
            $result = $choices->getChoices(); 
            $rows = array();        
            //Fetch data in assoc array
            while( $row = $result->fetch_assoc()) {
                foreach($row as $key => $value) {
                    //Encode each row in utf8
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
            if (!isset($choices)) {
                //Create model instance
                $choices = new m_choices($db->conn);
            }
            //Validations
            $result = $choices->validateChoice($courseId);
            if (array_values($result->fetch_assoc())[0] == 0) {
                $choices->insert($courseId);
                $response = array(  "status"=>"Success",
                                    "msg" => "Your choice has been successfully registered!"
                                );
            } else {
                $response = array(  "status"=>"Error",
                                    "msg" => "A course from this package was already choosen!"
                                );
            }
            echo json_encode($response);
            //Insert choosen option
        } else {
            //Redirect accordingly
            require_once "../views/v_login.php";
        }
    }
}
