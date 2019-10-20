<?php
require_once "../shared/db_conn.php";
require_once "../models/m_trades.php";

class trades {    
    function display() {
        //If logged in
        if (isset($_SESSION["logged"])) {
            //Allow access
            require_once "../views/v_trades.php";            
        } else {
            //Redirect accordingly
            require_once "../views/v_login.php";
        }
    }
    function get() {
        if (isset($_SESSION["logged"])) {
            //If db connection does not exist
            if(!isset($db)) {
                //Create db connection
                $db = new database_conn;
                $db->connect();
            }
            //If model instance does not exist
            if (!isset($trades)) {
                //Create model instance
                $trades = new m_trades($db->conn);
            }
            //Get data
            $result = $trades->getTradableCourses($courseId);    
            $rows = array();        
            //Fetch data in assoc array
            while($row = $result->fetch_assoc()) {
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
    function getTradableCourses($courseId) {
        //If logged in
        if (isset($_SESSION["logged"])) {
            //If db connection does not exist
            if(!isset($db)) {
                //Create db connection
                $db = new database_conn;
                $db->connect();
            }
            //If model instance does not exist
            if (!isset($trades)) {
                //Create model instance
                $trades = new m_trades($db->conn);
            }
            //Get data
            $result = $trades->getTradableCourses($courseId);    
            $rows = array();        
            //Fetch data in assoc array
            while($row = $result->fetch_assoc()) {
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

    function insert($chosenCourses) {
        $courses = explode('.', $chosenCourses);
        if(isset($_SESSION["logged"])) {
            if (!isset($db)) {
                $db = new database_conn;
                $db->connect();
            }
            if (!isset($trades)) {
                //Create model instance
                $trades = new m_trades($db->conn);
            }
            ;
            if ($trades->insertTrade($courses[0])) {
                foreach ($courses as $course) {
                    if ($course != $courses[0]) {
                        $trades->insertOption($courses[0], $course);
                    }
                }
                $response = array(  "status"=>"Success",
                                    "msg" => "Your request has been successfully registered!"
                                );
            } else {
                $response = array(  "status"=>"Error",
                                    "msg" => "You already have a request for this course!"
                                );
            }
            echo json_encode($response);
        } else {
            //Redirect accordingly
            require_once "../views/v_login.php";
        }
    }
}
?>