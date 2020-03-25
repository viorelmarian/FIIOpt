<?php
require_once "../shared/db_conn.php";
require_once "../models/m_notifications.php";
require_once "../models/m_offers.php";

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

    function sendEmailNewTradeOffer($tradeId, $courseId) {
        if (isset($_SESSION["logged"])) {
            //If db connection does not exist
            if(!isset($db)) {
                //Create db connection
                $db = new database_conn;
                $db->connect();
            }
            //If model instance does not exist
            if (!isset($notifications)) {
                //Create model instance
                $notifications = new m_notifications($db->conn);                
            }

            $senderUsername = $notifications->getSenderUsername()->fetch_assoc()["username"];
            $receiverUsername = $notifications->getReceiverUsername($tradeId)->fetch_assoc()["username"];
            $donorCourseName = $notifications->getDonorCourseName($tradeId)->fetch_assoc()["name"];
            $receiverCourseName = $notifications->getReceiverCourseName($courseId)->fetch_assoc()["name"];

            $senderCompleteName = explode('.', $senderUsername);
            $senderCompleteName = ucfirst($senderCompleteName[0]) . " " . ucfirst($senderCompleteName[1]);
            $message = $senderCompleteName . ' vrea sa schimbe ' . $receiverCourseName . ' pentru ' . $donorCourseName . "."; 
            $recipient = $receiverUsername . '@info.uaic.ro';
            $subject = "FIIOpt Notification";
            mail($recipient,$subject,$message);
        } else {
            //Redirect accordingly
            require_once "../views/v_login.php";
        }
    }

    function sendEmailAcceptTradeOffer($offerId) {
        if (isset($_SESSION["logged"])) {
            //If db connection does not exist
            if(!isset($db)) {
                //Create db connection
                $db = new database_conn;
                $db->connect();
            }
            //If model instance does not exist
            if (!isset($notifications)) {
                //Create model instance
                $notifications = new m_notifications($db->conn);                
            }
            if (!isset($offers)) {
                //Create model instance
                $offers = new m_offers($db->conn);                
            }
            if (!isset($users)) {
                //Create model instance
                $users = new m_users($db->conn);                
            }
            $offer = $offers->getOfferById($offerId)->fetch_assoc();

            $senderUsername = $notifications->getSenderUsername()->fetch_assoc()["username"];
            $receiverUsername = $users->getById($offer["offer_student_id"])->fetch_assoc()["username"];
            $donorCourseName = $notifications->getDonorCourseName($offer["trade_id"])->fetch_assoc()["name"];
            $receiverCourseName = $notifications->getReceiverCourseName($offer["offer_course_id"])->fetch_assoc()["name"];

            $senderCompleteName = explode('.', $senderUsername);
            $senderCompleteName = ucfirst($senderCompleteName[0]) . " " . ucfirst($senderCompleteName[1]);
            $message = $senderCompleteName . ' a acceptat sa schimbe "' . $donorCourseName . '" pentru "' . $receiverCourseName . '".'; 
            $recipient = $receiverUsername . '@info.uaic.ro';
            $subject = "FIIOpt Notification";
            mail($recipient,$subject,$message);
        } else {
            //Redirect accordingly
            require_once "../views/v_login.php";
        }
    }
    function sendEmailDeclineTradeOffer() {
        if (isset($_SESSION["logged"])) {
            //If db connection does not exist
            if(!isset($db)) {
                //Create db connection
                $db = new database_conn;
                $db->connect();
            }
            //If model instance does not exist
            if (!isset($notifications)) {
                //Create model instance
                $notifications = new m_notifications($db->conn);                
            }
            if (!isset($offers)) {
                //Create model instance
                $offers = new m_offers($db->conn);                
            }
            if (!isset($users)) {
                //Create model instance
                $users = new m_users($db->conn);                
            }
            $offer = $offers->getOfferById($offerId)->fetch_assoc();

            $senderUsername = $notifications->getSenderUsername()->fetch_assoc()["username"];
            $receiverUsername = $users->getById($offer["offer_student_id"])->fetch_assoc()["username"];
            $donorCourseName = $notifications->getDonorCourseName($offer["trade_id"])->fetch_assoc()["name"];
            $receiverCourseName = $notifications->getReceiverCourseName($offer["offer_course_id"])->fetch_assoc()["name"];

            $senderCompleteName = explode('.', $senderUsername);
            $senderCompleteName = ucfirst($senderCompleteName[0]) . " " . ucfirst($senderCompleteName[1]);
            $message = $senderCompleteName . ' a refuzat sa schimbe "' . $donorCourseName . '" pentru "' . $receiverCourseName . '".'; 
            $recipient = $receiverUsername . '@info.uaic.ro';
            $subject = "FIIOpt Notification";
            mail($recipient,$subject,$message);
        } else {
            //Redirect accordingly
            require_once "../views/v_login.php";
        }
    }
    function sendEmailAcceptTransferRequest($transferId) {
        //If db connection does not exist
        if(!isset($db)) {
            //Create db connection
            $db = new database_conn;
            $db->connect();
        }
        //If model instance does not exist
        if (!isset($notifications)) {
            //Create model instance
            $notifications = new m_notifications($db->conn);                
        }

        $receiverUsername = $notifications->getTransferredUsername($transferId)->fetch_assoc()["username"];
        $fromCourseName = $notifications->getTransferFromCourseName($transferId)->fetch_assoc()["name"];
        $toCourseName = $notifications->getTransferToCourseName($transferId)->fetch_assoc()["name"];

        $message = 'Cererea de transfer de la "' . $fromCourseName . '" catre "' . $toCourseName . '" a fost acceptata.'; 
        $recipient = $receiverUsername . '@info.uaic.ro';
        $subject = "FIIOpt Notification";
        mail($recipient,$subject,$message);        
    }
    function sendEmailDeclineTraansferRequest($tradeId) {
        //If db connection does not exist
        if(!isset($db)) {
            //Create db connection
            $db = new database_conn;
            $db->connect();
        }
        //If model instance does not exist
        if (!isset($notifications)) {
            //Create model instance
            $notifications = new m_notifications($db->conn);                
        }

        $receiverUsername = $notifications->getTransferredUsername($transferId)->fetch_assoc()["username"];
        $fromCourseName = $notifications->getTransferFromCourseName($transferId)->fetch_assoc()["name"];
        $toCourseName = $notifications->getTransferToCourseName($transferId)->fetch_assoc()["name"];

        $message = 'Cererea de transfer de la "' . $fromCourseName . '" catre "' . $toCourseName . '" a fost refuzata.'; 
        $recipient = $receiverUsername . '@info.uaic.ro';
        $subject = "FIIOpt Notification";
        mail($recipient,$subject,$message);        
    }
}