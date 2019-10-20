<?php
class m_trades {
    private $conn;
    function m_trades($conn) {
        $this->conn = $conn;
    }
    function get() {
        $stmt = $this->conn->prepare("SELECT * FROM `trades`    JOIN `students` ON `students`.`student_id`  = `trades`.`donor_student_id` 
                                                                JOIN `courses`  ON `courses`.`course_id`    = `trades`.`donor_course_id` 
                                                                ORDER BY `trade_id` DESC");
        $stmt->execute();
        return $stmt->get_result();
    }
    function getAcceptedCourses($trade_id) {
        $stmt = $this->conn->prepare("SELECT * FROM `trade_options` WHERE `trade_id` = ?");
            $stmt->bind_param("i", $trade_id);
            $stmt->execute();
            return $stmt->get_result();
    }
    function insertTrade($course) {
        $stmt = $this->conn->prepare("SELECT    COUNT(*) 
                                                FROM    `trades` 
                                                WHERE   `donor_student_id` 
                                                IN (
                                                        SELECT  `student_id` 
                                                        FROM    `students` 
                                                        WHERE   `username` = ?
                                                    ) 
                                                AND     `donor_course_id` = ?");
        $stmt->bind_param("si", $_SESSION["login_usr"], $course);
        $stmt->execute();
        if (array_values($stmt->get_result()->fetch_assoc())[0] == 0) {
            $stmt = $this->conn->prepare("INSERT    INTO    `trades`(`donor_student_id`, `donor_course_id`) 
                                                    VALUES  (
                                                                (
                                                                    SELECT  `student_id` 
                                                                    FROM    `students` 
                                                                    WHERE   `username` = ?
                                                                ), 
                                                                ?
                                                            )"
                                        );
            $stmt->bind_param("si", $_SESSION["login_usr"], $course);
            $stmt->execute();
            return True;
        } else {
            return False;
        }        
    }
    function insertOption($donorCourseId, $receiverCourseId) {
        $stmt = $this->conn->prepare("INSERT    INTO    `trade_options`(`trade_id`, `receiver_course_id`, `receiver_student_id`, `trade_status`) 
                                                VALUES  (
                                                            (   SELECT  `trade_id`  FROM    `trades` 
                                                                                    WHERE   `donor_student_id` 
                                                                                    IN (
                                                                                        SELECT  `student_id` 
                                                                                        FROM    `students` 
                                                                                        WHERE   `username` = ? 
                                                                                    )
                                                                                    AND `donor_course_id` = ?
                                                            ),
                                                            ?,
                                                            NULL,
                                                            ?
                                                        )"
                                    );
        $status = "Pending";
        $stmt->bind_param("siis", $_SESSION["login_usr"], $donorCourseId, $receiverCourseId, $status);
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
        $stmt->bind_param("iii", $courseId, $courseId, $courseId);
        $stmt->execute();
        return $stmt->get_result(); 
    }
}
?>