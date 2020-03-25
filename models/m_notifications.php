<?php
class m_notifications {
    private $conn;
    function m_notifications($conn) {
        $this->conn = $conn;
    }
    function getSenderUsername() {
        $stmt = $this->conn->prepare("  SELECT  `username`  FROM    `students` 
                                        WHERE   `student_id` = ?");        
        $stmt->bind_param("s", $_SESSION["login_usr"]);
        $stmt->execute();
        return $stmt->get_result(); 
    }
    function getReceiverUsername($tradeId) {
        $stmt = $this->conn->prepare("  SELECT  `username`  FROM    `students` 
                                        JOIN    `trades` 
                                        ON      `donor_student_id` = `student_id`
                                        WHERE   `trade_id` = ?");        
        $stmt->bind_param("s", $tradeId);
        $stmt->execute();
        return $stmt->get_result();
    }
    function getDonorCourseName($tradeId) {
        $stmt = $this->conn->prepare("  SELECT  `name`  FROM    `courses` 
                                        JOIN    `trades` 
                                        ON      `course_id` = `donor_course_id`
                                        WHERE   `trade_id` = ?");        
        $stmt->bind_param("s", $tradeId);
        $stmt->execute();
        return $stmt->get_result(); 
    }
    function getReceiverCourseName($courseId) {
        $stmt = $this->conn->prepare("  SELECT  `name`
                                        FROM    `courses` 
                                        WHERE   `course_id` = ?");        
        $stmt->bind_param("s", $courseId);
        $stmt->execute();
        return $stmt->get_result(); 
    }
    function getTransferredUsername($transferId) {
        $stmt = $this->conn->prepare("  SELECT  `username`
                                        FROM    `students` 
                                        JOIN    `transfer_requests`
                                        ON      `transfer_student_id` = `student_id`
                                        WHERE   `transfer_id` = ?");        
        $stmt->bind_param("s", $transferId);
        $stmt->execute();
        return $stmt->get_result(); 
    }
    function getTransferFromCourseName($transferId) {
        $stmt = $this->conn->prepare("  SELECT  `name`
                                        FROM    `courses` 
                                        JOIN    `transfer_requests`
                                        ON      `transfer_from_course_id` = `course_id`
                                        WHERE   `transfer_id` = ?");        
        $stmt->bind_param("s", $transferId);
        $stmt->execute();
        return $stmt->get_result(); 
    }
    function getTransferToCourseName($transferId) {
        $stmt = $this->conn->prepare("  SELECT  `name`
                                        FROM    `courses` 
                                        JOIN    `transfer_requests`
                                        ON      `transfer_to_course_id` = `course_id`
                                        WHERE   `transfer_id` = ?");
        $stmt->bind_param("s", $transferId);
        $stmt->execute();
        return $stmt->get_result();
    }

}