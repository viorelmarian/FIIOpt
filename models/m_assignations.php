<?php
class m_assignations {
    private $conn;
    function m_assignations($conn) {
        $this->conn = $conn;
    }
    function insert($course) {
        $stmt = $this->conn->prepare("INSERT    INTO    `assigned_courses`(`student_id`, `course_id`, `status`) 
                                                VALUES  ((SELECT `student_id` FROM `students` WHERE `username` = ?), ?, ?)");        
        $status = "Pending";
        $stmt->bind_param("sis", $_SESSION["login_usr"], $course, $status);
        $stmt->execute();
        return $stmt->get_result(); 
    }
    function validateChoice($courseId) {
        $stmt = $this->conn->prepare("SELECT COUNT(*)   FROM    `assigned_courses` 
                                                        JOIN    `courses`           ON `assigned_courses`.`course_id` = `courses`.`course_id` 
                                                        WHERE   `courses`.`package`     IN (
                                                                                            SELECT  `package` 
                                                                                            FROM    `courses` 
                                                                                            WHERE   `course_id` = ?)
                                                        AND     `courses`.`year`        IN (
                                                                                            SELECT  `year` 
                                                                                            FROM    `courses` 
                                                                                            WHERE   `course_id` = ?)
                                                        AND     `assigned_courses`.`student_id`  IN (
                                                                                            SELECT  `student_id`
                                                                                            FROM    `students`
                                                                                            WHERE   `username` = ?)");
        $user = $_SESSION["login_usr"];
        $stmt->bind_param("iis", $courseId, $courseId, $user);
        $stmt->execute();
        return $stmt->get_result();
    }
    function getAssignations() {
        $stmt = $this->conn->prepare("  SELECT  `name`, 
                                                `package`, 
                                                `status`,
                                                `username`,
                                                `co`.`year`, 
                                                `co`.`course_id`    
                                        FROM    `assigned_courses`   AS  `ch` 
                                        JOIN    `courses`   AS  `co`    ON `ch`.`course_id`  = `co`.`course_id` 
                                        JOIN    `students`  AS  `st`    ON `ch`.`student_id` = `st`.`student_id`
                                        WHERE   `username`  = ?");
        $user = $_SESSION["login_usr"];
        $stmt->bind_param("s", $user);
        $stmt->execute();
        return $stmt->get_result();
    }
    function getTradeCourses() {
        $stmt = $this->conn->prepare("  SELECT  `name`, 
                                                `package`, 
                                                `status`,
                                                `username`,
                                                `co`.`year`, 
                                                `co`.`course_id`    
                                        FROM    `assigned_courses`  AS  `ch` 
                                        JOIN    `courses`           AS  `co`    ON `ch`.`course_id`  = `co`.`course_id` 
                                        JOIN    `students`          AS  `st`    ON `ch`.`student_id` = `st`.`student_id`
                                        WHERE   `username`  = ?
                                        AND     `status` = 'Pending'");
        $user = $_SESSION["login_usr"];
        $stmt->bind_param("s", $user);
        $stmt->execute();
        return $stmt->get_result();
    }
}