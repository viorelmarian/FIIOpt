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
            $result = $trades->get();    
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
    function getAcceptedCourses($trade_id) {
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
            $result = $trades->getAcceptedCourses($trade_id);    
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
        //Get course id's from param
        $courses = explode('.', $chosenCourses);
        //If logged in
        if(isset($_SESSION["logged"])) {
            //If db connection does not exist
            if (!isset($db)) {
                //Create db connection
                $db = new database_conn;
                $db->connect();
            }
            //If model instance does not exist
            if (!isset($trades)) {
                //Create model instance
                $trades = new m_trades($db->conn);
            }
            //If trade is not already posted, insert trade in db
            if ($trades->insertTrade($courses[0])) {
                //If trade is not already posted insert options
                foreach ($courses as $course) {
                    //Do not insert the caourse that you trade as a trade option
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