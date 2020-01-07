<?php
class m_choices {
    private $conn;
    function m_choices($conn) {
        $this->conn = $conn;
    }
    function get() {
        $stmt = $this->conn->prepare("SELECT *  FROM    `choices` 
                                                JOIN    `students`      ON `choices`.`student_id` = `students`.`student_id` 
                                                WHERE   `username` = ?");
        $user = $_SESSION["login_usr"];
        $stmt->bind_param("s", $user);
        $stmt->execute();
        return $stmt->get_result();    
    }    
    function insert($course) {
        $stmt = $this->conn->prepare("INSERT    INTO    `choices`(`student_id`, `course_id`, `status`) 
                                                VALUES  ((SELECT `student_id` FROM `students` WHERE `username` = ?), ?, ?)");        
        $status = "Pending";
        $stmt->bind_param("sis", $_SESSION["login_usr"], $course, $status);
        $stmt->execute();
        return $stmt->get_result(); 
    }
    function validateChoice($courseId) {
        $stmt = $this->conn->prepare("SELECT COUNT(*)   FROM    `choices` 
                                                        JOIN    `courses`           ON `choices`.`course_id` = `courses`.`course_id` 
                                                        WHERE   `courses`.`package`     IN (
                                                                                            SELECT  `package` 
                                                                                            FROM    `courses` 
                                                                                            WHERE   `course_id` = ?)
                                                        AND     `courses`.`year`        IN (
                                                                                            SELECT  `year` 
                                                                                            FROM    `courses` 
                                                                                            WHERE   `course_id` = ?)
                                                        AND     `choices`.`student_id`  IN (
                                                                                            SELECT  `student_id`
                                                                                            FROM    `students`
                                                                                            WHERE   `username` = ?)");
        $user = $_SESSION["login_usr"];
        $stmt->bind_param("iis", $courseId, $courseId, $user);
        $stmt->execute();
        return $stmt->get_result();
    }
    function getChoices() {
        $stmt = $this->conn->prepare("SELECT    `name`, 
                                                `package`, 
                                                `status`,
                                                `username`,
                                                `co`.`year`, 
                                                `co`.`course_id`   FROM    `choices`   AS  `ch` 
                                                                    JOIN    `courses`   AS  `co`    ON `ch`.`course_id`  = `co`.`course_id` 
                                                                    JOIN    `students`  AS  `st`    ON `ch`.`student_id` = `st`.`student_id`
                                                                    WHERE   `username` = ?");
        $user = $_SESSION["login_usr"];
        $stmt->bind_param("s", $user);
        $stmt->execute();
        return $stmt->get_result();
    }
}