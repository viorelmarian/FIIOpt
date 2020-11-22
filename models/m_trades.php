<?php
class m_trades
{
    private $conn;
    function m_trades($conn)
    {
        $this->conn = $conn;
    }
    function getTrades($src)
    {
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
    function getTradeOptions($trade_id)
    {
        $stmt = $this->conn->prepare("SELECT *  FROM    `trade_options`
                                                JOIN    `trades`    ON `trade_options`.`trade_id` = `trades`.`trade_id` 
                                                JOIN    `courses`   ON `trade_options`.`option_course_id` = `courses`.`course_id`
                                                WHERE   `trade_options`.`trade_id` = ?");
        $stmt->bind_param("s", $trade_id);
        $stmt->execute();
        return $stmt->get_result();
    }
    function getAcceptedCourses($trade_id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM `trade_options` WHERE `trade_id` = ?");
        $stmt->bind_param("s", $trade_id);
        $stmt->execute();
        return $stmt->get_result();
    }
    function insertTrade($course)
    {
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
                                                            )");
            $status = "Pending";
            $trade_id = sha1(microtime(true) . mt_rand(10000, 90000));
            $stmt->bind_param("ssss", $trade_id, $_SESSION["login_usr"], $course, $status);
            $stmt->execute();
            return True;
        } else {
            return False;
        }
    }
    function insertOption($donorCourseId, $receiverCourseId)
    {
        $stmt = $this->conn->prepare("INSERT    INTO    `trade_options`(`trade_opt_id`, `trade_id`, `option_course_id`) 
                                                VALUES  (
                                                        ?,
                                                            (   SELECT  `trade_id`  FROM    `trades` 
                                                                                    WHERE   `donor_student_id` = ?
                                                                                    AND `donor_course_id` = ?
                                                            ),
                                                            ?
                                                        )");
        $trade_opt_id = sha1(microtime(true) . mt_rand(10000, 90000));
        $stmt->bind_param("ssss", $trade_opt_id, $_SESSION["login_usr"], $donorCourseId, $receiverCourseId);
        $stmt->execute();
        return $stmt->get_result();
    }
    function getTradableCourses($courseId)
    {

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
    function getUserForTrade($tradeId)
    {
        $stmt = $this->conn->prepare("  SELECT  `donor_student_id`   FROM   `trades`                                     
                                        WHERE   `trade_id` = ?");
        $stmt->bind_param("s", $tradeId);
        $stmt->execute();
        return $stmt->get_result();
    }
    function insertTransferRequest($fromCourseId, $toCourseId)
    {
        $stmt = $this->conn->prepare("  INSERT INTO `transfer_requests`(`transfer_id`,`transfer_student_id`, `transfer_from_course_id`, `transfer_to_course_id`, `status`) 
                                        VALUES (?,?,?,?,?)");
        $user = $_SESSION["login_usr"];
        $status = 'Pending';
        $transfer_id = sha1(microtime(true) . mt_rand(10000, 90000));
        $stmt->bind_param("sssss", $transfer_id, $user, $fromCourseId, $toCourseId, $status);
        $stmt->execute();
        return $stmt->get_result();
    }
    function getTransferRequestsForUser()
    {
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
    function getTransferRequests()
    {
        $stmt = $this->conn->prepare("  SELECT * 
                                        FROM    `transfer_requests`
                                        JOIN    `courses`
                                            ON  `transfer_requests`.`transfer_to_course_id` = `courses`.`course_id`
                                        JOIN    `students`
                                            ON  `transfer_requests`.`transfer_student_id` = `students`.`student_id`
                                        WHERE   `status` = 'Pending'");

        $stmt->execute();
        return $stmt->get_result();
    }
    function getTradesRequests()
    {
        $stmt = $this->conn->prepare("  SELECT  `trades`.`trade_id` AS `trade_id`,
                                                `c1`.`course_id`    AS `donor_course_id`, 
                                                `c1`.`name`         AS `donor_course_name`,
                                                `c2`.`course_id`    AS `receiver_course_id`, 
                                                `c2`.`name`         AS `receiver_course_name`,
                                                `s1`.`student_id`   AS `donor_student_id`, 
                                                `s1`.`username`     AS `donor_student_username`,
                                                `s2`.`student_id`   AS `receiver_student_id`, 
                                                `s2`.`username`     AS `receiver_student_username`
                                        FROM    `trades`
                                        JOIN    `courses` AS `c1`
                                            ON  `trades`.`donor_course_id`      = `c1`.`course_id`
                                        JOIN    `courses` AS `c2`
                                            ON  `trades`.`receiver_course_id`   = `c2`.`course_id`
                                        JOIN	`students` AS `s1`
                                            ON  `trades`.`donor_student_id`     = `s1`.`student_id`
                                        JOIN	`students` AS `s2`
                                            ON  `trades`.`receiver_student_id`  = `s2`.`student_id`
                                        WHERE   `trades`.`status`               = 'Secretary'");

        $stmt->execute();
        return $stmt->get_result();
    }
    function cancelTransferRequest($transferId)
    {
        $stmt = $this->conn->prepare("  UPDATE  `transfer_requests` 
                                        SET     `status` = 'Canceled'
                                        WHERE   `transfer_id` = ?");
        $stmt->bind_param("s", $transferId);
        $stmt->execute();
        return $stmt->get_result();
    }
    function acceptTransferRequest($transferId)
    {

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
    function acceptTradeRequest($trade_id)
    {
        //Selecteaza datele despre donor student/course
        $stmt = $this->conn->prepare("  SELECT  `donor_student_id`,
                                                `donor_course_id`,
                                                `receiver_student_id`,
                                                `receiver_course_id`
                                        FROM    `trades`
                                        WHERE   `trade_id` = ?");

        $stmt->bind_param("s", $trade_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $result = $result->fetch_assoc();

        $donorCourseId  = $result["donor_course_id"];
        $donorStudentId = $result["donor_student_id"];
        $receiverCourseId = $result["receiver_course_id"];
        $receiverStudentId = $result["receiver_student_id"];

        $stmt->close();

        //Schimba cursurile pentru Donor Student
        $stmt = $this->conn->prepare("  UPDATE  `assigned_courses`
                                        SET     `course_id`  = ?, 
                                                `status`     = 'Traded'
                                        WHERE   `student_id` = ?
                                        AND     `course_id`  = ?");

        $stmt->bind_param("sss", $receiverCourseId, $donorStudentId, $donorCourseId);
        $stmt->execute();
        $stmt->close();

        //Schimba cursurile pentru Receiver Student
        $stmt = $this->conn->prepare("  UPDATE  `assigned_courses`
                                        SET     `course_id`  = ?, 
                                                `status`     = 'Traded'
                                        WHERE   `student_id` = ?
                                        AND     `course_id`  = ?");

        $stmt->bind_param("sss", $donorCourseId, $receiverStudentId, $receiverCourseId);
        $stmt->execute();
        $stmt->close();

        //Seteaza status Completed
        $stmt = $this->conn->prepare("  UPDATE  `trades`
                                        SET     `status` = 'Completed'
                                        WHERE   `trade_id` = ?");

        $stmt->bind_param("s", $trade_id);
        $stmt->execute();
        $stmt->close();
    }
    function declineTransferRequest($transferId)
    {
        $stmt = $this->conn->prepare("  UPDATE  `transfer_requests` 
                                        SET     `status` = 'Declined'
                                        WHERE   `transfer_id` = ?");
        $stmt->bind_param("s", $transferId);
        $stmt->execute();
        return $stmt->get_result();
    }
    function declineTradeRequest($trade_id)
    {
        $stmt = $this->conn->prepare("  DELETE FROM `trades` 
                                        WHERE       `trade_id` = ?");
        $stmt->bind_param("s", $trade_id);
        $stmt->execute();
        return $stmt->get_result();
    }
    function getTransferRequestsForCourse($courseId)
    {
        $stmt = $this->conn->prepare("  SELECT    COUNT(*) 
                                        FROM    `transfer_requests` 
                                        WHERE   `transfer_student_id` = ?
                                        AND     `transfer_from_course_id` = ?
                                        AND     `status` != 'Canceled'");
        $stmt->bind_param("ss", $_SESSION["login_usr"], $courseId);
        $stmt->execute();
        return $stmt->get_result();
    }
    function getTradeById($trade_id)
    {
        $stmt = $this->conn->prepare("  SELECT *
                                        FROM `trades`
                                        WHERE `trade_id` = ?");
        $stmt->bind_param("s", $trade_id);
        $stmt->execute();
        return $stmt->get_result();
    }
}
