<?php
require_once "../shared/db_conn.php";
require_once "../models/m_trades.php";
require_once "../models/m_offers.php";

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
            $result = $trades->getTrades();

            $rows = array();
            //Fetch data in assoc array
            while($row = $result->fetch_assoc()) {
                $tradeOptions = $trades->getTradeOptions($row["trade_id"]);
                $i = 1;
                while($tradeOption = $tradeOptions->fetch_assoc()) {
                    $row["option_" . $i++] = $tradeOption["name"];
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

    function determineTradeOffer($tradeId) {
         //If logged in
         if (isset($_SESSION["logged"])) {
            //If db connection does not exist
            if(!isset($db)) {
                //Create db connection
                $db = new database_conn;
                $db->connect();
            }
            //If model instance does not exist
            if (!isset($offers)) {
                //Create model instance
                $offers = new m_offers($db->conn);                
            }
            //Get Data
            $result = $offers->determineTradeOffer($tradeId);
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
            $msg = "Are you sure you want to trade '" . $rows[0]["name"] . "' for this course?";
            $response = array(  "status"=>"Success",
                                "msg" => $msg
                            );  
            echo json_encode($response);
        } else {
            //Redirect accordingly
            require_once "../views/v_login.php";
        }
    }

    function insertTradeOffer($tradeId) {
        //If logged in
        if (isset($_SESSION["logged"])) {
            //If db connection does not exist
            if(!isset($db)) {
                //Create db connection
                $db = new database_conn;
                $db->connect();
            }
            //If model instance does not exist
            if (!isset($offers)) {
                //Create model instance
                $offers = new m_offers($db->conn);                
            }
            if (!isset($trades)) {
                //Create model instance
                $trades = new m_trades($db->conn);                
            }
            //Determine Course to be offered
            $result = $offers->determineTradeOffer($tradeId);
            $courseId = $result->fetch_assoc()["course_id"];

            /************Validations************/

            //Check user own offer
            $result = $trades->getUserForTrade($tradeId);
            $donor_user = $result->fetch_assoc()["username"];
            if ($donor_user != $_SESSION["login_usr"]) {
                //Check user offer exists
                $result = $offers->getOffersForTrade($tradeId);
                if(array_values($result->fetch_assoc())[0] == 0) {
                    //Check valid offer
                    $result = $offers->checkValidOffer($tradeId, $courseId);
                    if(array_values($result->fetch_assoc())[0] != 0) {
                        //Insert Offer
                        $offers->insertOffer($tradeId, $courseId);
                        //Generate response
                        $response = array(  "status"=>"Success",
                                            "msg" => "Your offer has been successfully registered!"
                                        );
                    } else {
                        $response = array(  
                            "status"=>"Error",
                            "msg" => "You do not have a valid course to trade!"
                        );
                    }
                } else {
                    $response = array(  
                        "status"=>"Error",
                        "msg" => "You have already made an offer for this course!"
                    );
                }
            } else {
                $response = array(  
                                "status"=>"Error",
                                "msg" => "You can not make an offer for your own course!"
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