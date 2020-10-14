<?php
class m_trades {
    private $conn;
    function m_trades($conn) {
        $this->conn = $conn;
    }
    function getTrades($src) {
        $stmt = $this->conn->prepare("SELECT * FROM `trades`    JOIN    `students`         ON `students`.`student_id`      = `trades`.`donor_student_id` 
                                                                JOIN    `courses`          ON `courses`.`course_id`        = `trades`.`donor_course_id`
                                                                WHERE   `name` LIKE ?
                                                                AND     `status` = 'Pending'
                                                                ORDER BY `trade_id` DESC ");
        $src = '%' . $src . '%';
        $stmt->bind_param("s", $src);
        $stmt->execute();
        return $stmt->get_result();
    }
    function getTradeOptions($trade_id) {
        $stmt = $this->conn->prepare("SELECT *  FROM    `trade_options`
                                                JOIN    `trades`    ON `trade_options`.`trade_id` = `trades`.`trade_id` 
                                                JOIN    `courses`   ON `trade_options`.`option_course_id` = `courses`.`course_id`
                                                WHERE   `trade_options`.`trade_id` = ?");
        $stmt->bind_param("s", $trade_id);
        $stmt->execute();
        return $stmt->get_result();
    }
    function getAcceptedCourses($trade_id) {
        $stmt = $this->conn->prepare("SELECT * FROM `trade_options` WHERE `trade_id` = ?");
        $stmt->bind_param("s", $trade_id);
        $stmt->execute();
        return $stmt->get_result();
    }
    function insertTrade($course) {
        $stmt = $this->conn->prepare("SELECT    COUNT(*) 
                                                FROM    `trades` 
                                                WHERE   `donor_student_id` = ?
                                                AND     `donor_course_id` = ?");
        $stmt->bind_param("ss", $_SESSION["login_usr"], $course);
        $stmt->execute();
        if (array_values($stmt->get_result()->fetch_assoc())[0] == 0) {
            $stmt = $this->conn->prepare("INSERT    INTO    `trades`(`trade_id`, `donor_student_id`, `donor_course_id`, `status`) 
                                                    VALUES  (
                                                            ?,
                                                            ?,
                                                            ?,
                                                            ?
                                                            )"
                                        );
            $status = "Pending";
            $trade_id = sha1(microtime(true).mt_rand(10000,90000));
            $stmt->bind_param("ssss", $trade_id, $_SESSION["login_usr"], $course, $status);
            $stmt->execute();
            return True;
        } else {
            return False;
        }        
    }
    function insertOption($donorCourseId, $receiverCourseId) {
        $stmt = $this->conn->prepare("INSERT    INTO    `trade_options`(`trade_opt_id`, `trade_id`, `option_course_id`) 
                                                VALUES  (
                                                        ?,
                                                            (   SELECT  `trade_id`  FROM    `trades` 
                                                                                    WHERE   `donor_student_id` = ?
                                                                                    AND `donor_course_id` = ?
                                                            ),
                                                            ?
                                                        )"
                                    );
        $trade_opt_id = sha1(microtime(true).mt_rand(10000,90000));
        $stmt->bind_param("ssss", $trade_opt_id, $_SESSION["login_usr"], $donorCourseId, $receiverCourseId);
        $stmt->execute();
        return $stmt->get_result(); 
    }
    function getTradableCourses($courseId) {
        
        $stmt = $this->conn->prepare("SELECT *  FROM    `courses` 
                                                WHERE   `courses`.`package`     IN ( 
                                                                                SELECT  `package` 
                                                                                FROM    `courses`
                                                                                WHERE   `course_id` = ?) 
                                                AND     `courses`.`year`        IN ( 
                                                                                SELECT  `year` 
                                                                                FROM    `courses` 
                                                                                WHERE   `course_id` = ?) 
                                                AND     `course_id` <> ?");        
        $stmt->bind_param("sss", $courseId, $courseId, $courseId);
        $stmt->execute();
        return $stmt->get_result(); 
    }
    function getUserForTrade($tradeId) {
        $stmt = $this->conn->prepare("  SELECT  `donor_student_id`   FROM   `trades`                                     
                                        WHERE   `trade_id` = ?");     
        $stmt->bind_param("s", $tradeId);
        $stmt->execute();
        return $stmt->get_result(); 
    }
    
    function insertTransferRequest($fromCourseId, $toCourseId) {
        $stmt = $this->conn->prepare("  INSERT INTO `transfer_requests`(`transfer_id`,`transfer_student_id`, `transfer_from_course_id`, `transfer_to_course_id`, `status`) 
                                        VALUES (?,?,?,?,?)");
        $user = $_SESSION["login_usr"];
        $status = 'Pending';
        $transfer_id = sha1(microtime(true).mt_rand(10000,90000));
        $stmt->bind_param("sssss", $transfer_id, $user, $fromCourseId, $toCourseId, $status);
        $stmt->execute();
        return $stmt->get_result(); 
    }

    function getTransferRequestsForUser() {
        $stmt = $this->conn->prepare("  SELECT * 
                                        FROM    `transfer_requests`
                                        JOIN    `courses`
                                        ON      `transfer_requests`.`transfer_to_course_id` = `courses`.`course_id`
                                        WHERE   `status` != 'Canceled' 
                                        AND     `transfer_student_id` = ?");
        $user = $_SESSION["login_usr"];
        $stmt->bind_param("s", $user);
        $stmt->execute();
        return $stmt->get_result();
    }

    function getTransferRequests() {
        $stmt = $this->conn->prepare("  SELECT * 
                                        FROM    `transfer_requests`
                                        JOIN    `courses`
                                        ON      `transfer_requests`.`transfer_to_course_id` = `courses`.`course_id`
                                        JOIN    `students`
                                        ON      `transfer_requests`.`transfer_student_id` = `students`.`student_id`
                                        WHERE   `status` = 'Pending'");
                                        
        $stmt->execute();
        return $stmt->get_result();
    }

    function cancelTransferRequest($transferId) {
        $stmt = $this->conn->prepare("  UPDATE  `transfer_requests` 
                                        SET     `status` = 'Canceled'
                                        WHERE   `transfer_id` = ?");
        $stmt->bind_param("s", $transferId);
        $stmt->execute();
        return $stmt->get_result();
    }
    function acceptTransferRequest($transferId) {

        $stmt = $this->conn->prepare("  SELECT  * 
                                        FROM    `transfer_requests`
                                        WHERE   `transfer_id` = ?");
        $stmt->bind_param("s", $transferId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        $stmt = $this->conn->prepare("  UPDATE  `transfer_requests` 
                                        SET     `status` = 'Accepted'
                                        WHERE   `transfer_id` = ?");
        $stmt->bind_param("s", $transferId);
        $stmt->execute();

        $stmt = $this->conn->prepare("  UPDATE  `assigned_courses` 
                                        SET     `course_id`  = ?,
                                                `status`     = 'Transferred'
                                        WHERE   `course_id`  = ?
                                        AND     `student_id` = ?");
        $stmt->bind_param("sss", $result["transfer_to_course_id"], $result["transfer_from_course_id"], $result["transfer_student_id"]);
        $stmt->execute();

        return $stmt->get_result();
    }
    function declineTransferRequest($transferId) {
        $stmt = $this->conn->prepare("  UPDATE  `transfer_requests` 
                                        SET     `status` = 'Declined'
                                        WHERE   `transfer_id` = ?");
        $stmt->bind_param("s", $transferId);
        $stmt->execute();
        return $stmt->get_result();
    }

    function getTransferRequestsForCourse($courseId) {
        $stmt = $this->conn->prepare("  SELECT    COUNT(*) 
                                        FROM    `transfer_requests` 
                                        WHERE   `transfer_student_id` = ?
                                        AND     `transfer_from_course_id` = ?
                                        AND     `status` != 'Canceled'");
        $stmt->bind_param("ss", $_SESSION["login_usr"], $courseId);
        $stmt->execute();
        return $stmt->get_result();
    }
}
?>