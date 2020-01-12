<?php
class m_offers {
    private $conn;
    function m_offers($conn) {
        $this->conn = $conn;
    }
    function insertOffer($tradeId, $courseId){
        $stmt = $this->conn->prepare("INSERT    INTO   `trade_offers`(`trade_id`, `offer_student_id`, `offer_course_id`, `status`) 
                                                VALUES  (?,
                                                            (
                                                                SELECT  `student_id` 
                                                                FROM    `students` 
                                                                WHERE   `username` = ?
                                                            ),
                                                        ?,
                                                        ?)"
                                        );
        $user = $_SESSION["login_usr"];   
        $status = "Pending";
        $stmt->bind_param("isis", $tradeId, $user, $courseId, $status);
        $stmt->execute();
    }
    function determineTradeOffer($tradeId) {
        $stmt = $this->conn->prepare("  SELECT  `courses`.`course_id`, 
                                                `name`   
                                        FROM    `assigned_courses` 
                                        JOIN    `courses`   ON `assigned_courses`.`course_id` = `courses`.`course_id` 
                                        WHERE   `courses`.`package` IN (
                                                                        SELECT  `package` 
                                                                        FROM    `courses` 
                                                                        WHERE   `course_id` IN (
                                                                                                SELECT  `donor_course_id` 
                                                                                                FROM    `trades` 
                                                                                                WHERE   `trade_id` = ?
                                                                                            )
                                                                    )
                                        AND     `courses`.`year`    IN (
                                                                        SELECT  `year` 
                                                                        FROM    `courses` 
                                                                        WHERE   `course_id` IN (
                                                                                                SELECT  `donor_course_id` 
                                                                                                FROM    `trades` 
                                                                                                WHERE   `trade_id` = ?
                                                                                            )
                                                                    )
                                        AND     `student_id`         IN (
                                                                            SELECT  `student_id` 
                                                                            FROM    `students` 
                                                                            WHERE   `username` = ? 
                                                                        )");
        
        $user = $_SESSION["login_usr"];                                
        $stmt->bind_param("iis", $tradeId, $tradeId, $user);
        $stmt->execute();
        return $stmt->get_result();                                    
    }

    function getOffersForTrade($tradeId){
        $stmt = $this->conn->prepare("  SELECT COUNT(*) FROM `trade_offers` 
                                        WHERE   `trade_id` = ? 
                                        AND     `offer_student_id` 
                                        IN (
                                                SELECT `student_id` 
                                                FROM `students` 
                                                WHERE `username` = ?
                                            )");
        $user = $_SESSION["login_usr"];                                
        $stmt->bind_param("is", $tradeId, $user);
        $stmt->execute();
        return $stmt->get_result();   
    }

    function checkValidOffer($tradeId, $courseId){
        $stmt = $this->conn->prepare("  SELECT COUNT(*) FROM `trade_options` 
                                        WHERE   `trade_id` = ? 
                                        AND     `option_course_id` = ?");
        $stmt->bind_param("ii", $tradeId, $courseId);
        $stmt->execute();
        return $stmt->get_result();   
    }

    function getTradeStatus($tradeId) {
        $stmt = $this->conn->prepare("  SELECT  `status` FROM `trades` 
                                        WHERE   `trade_id` = ?");
        $stmt->bind_param("i", $tradeId);
        $stmt->execute();
        return $stmt->get_result();  
    }

    function getOffers() {
        $stmt = $this->conn->prepare("  SELECT * FROM   `trades` 
                                        JOIN            `trade_offers`
                                        ON              `trades`.`trade_id` = `trade_offers`.`trade_id`
                                        WHERE           `donor_student_id`  IN (
                                                                                SELECT `student_id` 
                                                                                FROM `students` 
                                                                                WHERE `username` = ?
                                                                            )
                                        AND             `trade_offers`.`status` = 'Pending'");
        $user = $_SESSION["login_usr"];
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $result = $stmt->get_result();          
        $stmt->close(); 
        $tradeOffers = array();
        while($row = $result->fetch_assoc()) {
            $tradeOffer["offer_id"] = $row["offer_id"];
            $stmt = $this->conn->prepare("  SELECT  `name` 
                                            FROM    `courses` 
                                            WHERE   `course_id` = ?");
            $stmt->bind_param("i", $row["donor_course_id"]);
            $stmt->execute();
            $result2 = $stmt->get_result();
            $tradeOffer["donor_course_name"] = $result2->fetch_assoc()["name"];

            $stmt->bind_param("i", $row["offer_course_id"]);
            $stmt->execute();
            $result2 = $stmt->get_result();
            $tradeOffer["offer_course_name"] = $result2->fetch_assoc()["name"];

            $stmt = $this->conn->prepare("  SELECT  `username` 
                                            FROM    `students` 
                                            WHERE   `student_id` = ?");
            $stmt->bind_param("i", $row["offer_student_id"]);
            $stmt->execute();
            $result2 = $stmt->get_result();
            $tradeOffer["offer_student_name"] = $result2->fetch_assoc()["username"];

        //Add rows in Array
        $tradeOffers[] = $tradeOffer;
        }

        return($tradeOffers);
    }

    function acceptOffer($offerId) {
        $stmt = $this->conn->prepare("  UPDATE  `trade_offers`
                                        SET     `status` = 'Accepted'
                                        WHERE   `offer_id` = ?");
        
        $stmt->bind_param("i", $offerId);
        $stmt->execute();          
        $stmt->close(); 

        $stmt = $this->conn->prepare("  SELECT  *
                                        FROM    `trade_offers` 
                                        WHERE   `offer_id` = ?");
        
        $stmt->bind_param("i", $offerId);
        $stmt->execute();        
        
        $result = $stmt->get_result();  
        $result = $result->fetch_assoc();

        $tradeId        = $result["trade_id"];
        $offerCourseId  = $result["offer_course_id"];
        $offerStudentId = $result["offer_student_id"];

        $stmt->close(); 

        $stmt = $this->conn->prepare("  UPDATE  `trade_offers`
                                        SET     `status` = 'Declined'
                                        WHERE   `trade_id` = ?
                                        AND     `status` <> 'Accepted' ");
        
        $stmt->bind_param("i", $tradeId);
        $stmt->execute();          
        $stmt->close(); 

        $stmt = $this->conn->prepare("  UPDATE  `trades`
                                        SET     `receiver_course_id` = ?, 
                                                `receiver_student_id` = ?,
                                                `status` = 'Completed'
                                        WHERE   `trade_id` = ?");
        
        $stmt->bind_param("iii", $offerCourseId, $offerStudentId, $tradeId);
        $stmt->execute();          
        $stmt->close(); 

        $stmt = $this->conn->prepare("  SELECT  `donor_student_id`,
                                                `donor_course_id`
                                        FROM    `trades`
                                        WHERE   `trade_id` = ?");
        
        $stmt->bind_param("i", $tradeId);
        $stmt->execute();   
        $result = $stmt->get_result();  
        $result = $result->fetch_assoc();

        $donorCourseId  = $result["donor_course_id"];
        $donorStudentId = $result["donor_student_id"];
        
        $stmt->close(); 

        $stmt = $this->conn->prepare("  UPDATE  `assigned_courses`
                                        SET     `course_id`  = ?, 
                                                `status`     = 'Traded'
                                        WHERE   `student_id` = ?
                                        AND     `course_id`  = ?");
        
        $stmt->bind_param("iii", $offerCourseId, $donorStudentId, $donorCourseId);
        $stmt->execute();          
        $stmt->close();

        $stmt = $this->conn->prepare("  UPDATE  `assigned_courses`
                                        SET     `course_id`  = ?, 
                                                `status`     = 'Traded'
                                        WHERE   `student_id` = ?
                                        AND     `course_id`  = ?");
        
        $stmt->bind_param("iii", $donorCourseId, $offerStudentId, $offerCourseId);
        $stmt->execute();          
        $stmt->close();
    }

    function declineOffer($offerId) {
        $stmt = $this->conn->prepare("  UPDATE  `trade_offers`
                                        SET     `status` = 'Declined'
                                        WHERE   `offer_id` = ?");
        
        $stmt->bind_param("i", $offerId);
        $stmt->execute();          
        $stmt->close(); 
    }
}