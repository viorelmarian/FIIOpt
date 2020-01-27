<?php
class m_assignations {
    private $conn;
    function m_assignations($conn) {
        $this->conn = $conn;
    }
    function insert($course, $student) {
        $stmt = $this->conn->prepare("INSERT    INTO    `assigned_courses`(`assignation_id`, `student_id`, `course_id`, `status`) 
                                                VALUES  (?, ?, ?, ?)");        
        $status = "Assigned";
        $assignation_id = sha1(microtime(true).mt_rand(10000,90000));
        $stmt->bind_param("ssss", $assignation_id, $student, $course, $status);
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
                                                        AND     `assigned_courses`.`student_id` = ?");
        $user = $_SESSION["login_usr"];
        $stmt->bind_param("iis", $courseId, $courseId, $user);
        $stmt->execute();
        return $stmt->get_result();
    }
    function getAssignations() {
        $stmt = $this->conn->prepare("  SELECT      `name`, 
                                                    `package`, 
                                                    `status`,
                                                    `username`,
                                                    `co`.`year`, 
                                                    `co`.`course_id`    
                                        FROM        `assigned_courses`   AS  `ch` 
                                        JOIN        `courses`   AS  `co`    ON `ch`.`course_id`  = `co`.`course_id` 
                                        JOIN        `students`  AS  `st`    ON `ch`.`student_id` = `st`.`student_id`
                                        WHERE       `st`.`student_id`  = ?
                                        ORDER BY    `year`, `package`, `name`");
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
                                        WHERE   `st`.`student_id`  = ?
                                        AND     `status` = 'Pending'");
        $user = $_SESSION["login_usr"];
        $stmt->bind_param("s", $user);
        $stmt->execute();
        return $stmt->get_result();
    }
}