<?php
class m_choices {
    private $conn;
    function m_choices($conn) {
        $this->conn = $conn;
    }   
    function insert($course) {        
        $stmt = $this->conn->prepare("SELECT COUNT(*)   FROM    `choices` 
                                                        JOIN    `courses`               ON `choices`.`course_id` = `courses`.`course_id` 
                                                        WHERE   `courses`.`package`     IN (
                                                                                            SELECT  `package` 
                                                                                            FROM    `courses` 
                                                                                            WHERE   `course_id` = ?)
                                                        AND     `courses`.`year`        IN (
                                                                                            SELECT  `year` 
                                                                                            FROM    `courses` 
                                                                                            WHERE   `course_id` = ?)
                                                        AND     `choices`.`student_id` = ?");
        $user = $_SESSION["login_usr"];
        $stmt->bind_param("iis", $course, $course, $user);
        $stmt->execute();

        $priority = $stmt->get_result()->fetch_assoc()["COUNT(*)"] + 1;

        $stmt = $this->conn->prepare("INSERT    INTO    `choices`(`choice_id`, `student_id`, `course_id`, `priority`, `status`) 
                                                VALUES  (?, ?, ?, ? ,?)");        
        $status = "Pending";
        $choice_id = sha1(microtime(true).mt_rand(10000,90000));
        $stmt->bind_param("sssis", $choice_id, $_SESSION["login_usr"], $course, $priority, $status);
        $stmt->execute();
        return $stmt->get_result(); 
    }
    // function validateChoice($courseId) {
    //     $stmt = $this->conn->prepare("SELECT COUNT(*)   FROM    `choices` 
    //                                                     JOIN    `courses`               ON `choices`.`course_id` = `courses`.`course_id` 
    //                                                     WHERE   `courses`.`package`     IN (
    //                                                                                         SELECT  `package` 
    //                                                                                         FROM    `courses` 
    //                                                                                         WHERE   `course_id` = ?)
    //                                                     AND     `courses`.`year`        IN (
    //                                                                                         SELECT  `year` 
    //                                                                                         FROM    `courses` 
    //                                                                                         WHERE   `course_id` = ?)
    //                                                     AND     `choices`.`student_id` = ?");
    //     $user = $_SESSION["login_usr"];
    //     $stmt->bind_param("iis", $courseId, $courseId, $user);
    //     $stmt->execute();
    //     return $stmt->get_result();
    // }
    function getChoices($user) {
        $stmt = $this->conn->prepare("  SELECT      `name`,
                                                    `priority`, 
                                                    `package`, 
                                                    `status`,
                                                    `username`,
                                                    `co`.`year`, 
                                                    `co`.`course_id`    
                                        FROM        `choices`   AS  `ch` 
                                        JOIN        `courses`   AS  `co`    ON `ch`.`course_id`  = `co`.`course_id` 
                                        JOIN        `students`  AS  `st`    ON `ch`.`student_id` = `st`.`student_id`
                                        WHERE       `st`.`student_id`  = ?
                                        ORDER BY    `package`, `priority`, `year`, `name`");
        $stmt->bind_param("s", $user);
        $stmt->execute();
        return $stmt->get_result();
    }
}