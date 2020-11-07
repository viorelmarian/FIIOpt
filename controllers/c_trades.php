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
    function get($src) {
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
            $result = $trades->getTrades($src);

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
            //If options is not empty
            if(count($courses) > 1) {
                //If trade is not already posted, insert trade in db
                if ($trades->insertTrade($courses[0])) {
                    //If trade is not already posted insert options
                    foreach ($courses as $course) {
                        //Do not insert the course that you trade as a trade option
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
            } else {
                $response = array(  "status"=>"Error",
                                    "msg" => "You must choose trade options!"
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
            $msg = "Are you sure you want to trade <b>'" . $rows[0]["name"] . "'</b> for this course?";
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
            $donor_user = $result->fetch_assoc()["donor_student_id"];
            if ($donor_user != $_SESSION["login_usr"]) {
                //Check user offer exists
                $result = $offers->getOffersForTrade($tradeId);
                if(array_values($result->fetch_assoc())[0] == 0) {
                    //Check valid offer
                    $result = $offers->checkValidOffer($tradeId, $courseId);
                    if(array_values($result->fetch_assoc())[0] != 0) {

                        $result = $offers->getTradeStatus($tradeId);
                        if($result->fetch_assoc()["status"] != "Completed") {
                            //Insert Offer
                            $offers->insertOffer($tradeId, $courseId);
                            //Generate response
                            $response = array(  "status"=>"Success",
                                                "msg" => "Your offer has been successfully registered!"
                            );
                            if (!isset($notifications)) {
                                $notifications = new notifications;
                            }
                            $notifications->sendEmailNewTradeOffer($tradeId, $courseId);
                        } else {
                            $response = array(  "status"=>"Error",
                                                "msg" => "Trade is no longer available!"
                            );
                        }                        
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

    function getTradeOffers() {
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
            $tradeOffers = $offers->getOffers();
            echo json_encode($tradeOffers);    
        } else {
            //Redirect accordingly
            require_once "../views/v_login.php";
        }
    }

    function acceptTrade($offerId) {
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
            $tradeOffers = $offers->acceptOffer($offerId);
            if (!isset($notifications)) {
                $notifications = new notifications;
            }
            $notifications->sendEmailAcceptTradeOffer($offerId);
            $response = array(  
                "status"=>"Success",
                "msg" => "Trade completed successfully!"
            );
            echo json_encode($response);    
        } else {
            //Redirect accordingly
            require_once "../views/v_login.php";
        }
    }

    function declineTrade($offerId) {
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
            $offers->declineOffer($offerId);
            if (!isset($notifications)) {
                $notifications = new notifications;
            }
            $notifications->sendEmailDeclineTradeOffer($offerId);
            $response = array(  
                "status"=>"Success",
                "msg" => "Trade declined!"
            );
            echo json_encode($response);    
        } else {
            //Redirect accordingly
            require_once "../views/v_login.php";
        }
    }
    function insertTransferRequest($chosenCourses) {
        //Get course id's from param
        $courses = explode('.', $chosenCourses);
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

            if(count($courses) > 1) { 
                //Check if transfer request already exists for this course
                $transferRequests = $trades->getTransferRequestsForCourse($courses[0]);

                if (array_values($transferRequests->fetch_assoc())[0] == 0) {
                    $trades->insertTransferRequest($courses[0], $courses[1]);
                    $response = array(  
                        "status"=>"Success",
                        "msg" => "Transfer requested successfully!"
                    );
                } else {
                    $response = array(  
                        "status"=>"Error",
                        "msg" => "A transfer request already exists for this course!"
                    ); 
                }
                
            } else { 
                $response = array(  
                    "status"=>"Error",
                    "msg" => "Your selections are invalid!"
                );
            }
            echo json_encode($response); 
              
        } else {
            //Redirect accordingly
            require_once "../views/v_login.php";
        }
    }
    function getTransferRequests() {
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
            $result = $trades->getTransferRequestsForUser();
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
            echo json_encode($rows);    
        } elseif (isset($_SESSION["logged_adm"])) {
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
            $result = $trades->getTransferRequests();
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
            echo json_encode($rows);    
        } else {
            //Redirect accordingly
            require_once "../views/v_login.php";
        }
    }
    function cancelTransferRequest($transferId) {
        if (isset($_SESSION["logged_adm"])) {
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
            $trades->cancelTransferRequest($transferId);
            $response = array(  
                "status"=>"Success",
                "msg" => "Transfer canceled successfully!"
            );
            echo json_encode($response);    
        } else {
            //Redirect accordingly
            require_once "../views/v_login.php";
        }
    }
    function acceptTransferRequest($transferId) {
        if (isset($_SESSION["logged_adm"])) {
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
            $trades->acceptTransferRequest($transferId);
            if (!isset($notifications)) {
                $notifications = new notifications;
            }
            $notifications->sendEmailAcceptTransferRequest($transferId);
            $response = array(  
                "status"=>"Success",
                "msg" => "Transfer accepted successfully!"
            );
            echo json_encode($response);    
        } else {
            //Redirect accordingly
            require_once "../views/v_login.php";
        }
    }
    function declineTransferRequest($transferId) {
        if (isset($_SESSION["logged_adm"])) {
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
            $trades->declineTransferRequest($transferId);
            if (!isset($notifications)) {
                $notifications = new notifications;
            }
            $notifications->sendEmailDeclineTransferRequest($transferId);
            $response = array(  
                "status"=>"Success",
                "msg" => "Transfer declined successfully!"
            );
            echo json_encode($response);    
        } else {
            //Redirect accordingly
            require_once "../views/v_login.php";
        }
    }
}
?>