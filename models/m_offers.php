<?php
class m_offers {
    private $conn;
    function m_offers($conn) {
        $this->conn = $conn;
    }
    function insertOffer($tradeId, $courseId){
        $stmt = $this->conn->prepare("INSERT    INTO   `trade_offers`(`trade_id`, `offer_student_id`, `offer_course_id`) 
                                                VALUES  (?,
                                                            (
                                                                SELECT  `student_id` 
                                                                FROM    `students` 
                                                                WHERE   `username` = ?
                                                            ),
                                                        ?)"
                                        );
        $user = $_SESSION["login_usr"];   
        $stmt->bind_param("isi", $tradeId, $user, $courseId);
        $stmt->execute();
    }
    function determineTradeOffer($tradeId) {
        $stmt = $this->conn->prepare("  SELECT  `courses`.`course_id`, 
                                                `name`   
                                        FROM    `choices` 
                                        JOIN    `courses`   ON `choices`.`course_id` = `courses`.`course_id` 
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
                                        AND     `receiver_course_id` = ?");
        $stmt->bind_param("ii", $tradeId, $courseId);
        $stmt->execute();
        return $stmt->get_result();   
    }
}