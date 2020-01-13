<?php
class m_courses {
    private $conn;
    function m_courses($conn) {
        $this->conn = $conn;
    }
    function get() {
        $stmt = $this->conn->prepare("  SELECT *
                                        FROM `courses`");
        
        $stmt->execute();
        return $stmt->get_result();  
    }
    function getCourses($src) {
        $stmt = $this->conn->prepare("  SELECT  `username`, `course_id`, `name`, `courses`.`year`, `package`, `link` 
                                        FROM    `courses` 
                                        JOIN    `students`  ON `courses`.`year` = `students`.`year` 
                                        WHERE   `name`      LIKE ? 
                                        AND     `username` = ?");
        $src = '%' . $src . '%';
        $stmt->bind_param("ss", $src, $_SESSION["login_usr"]);
        $stmt->execute();
        return $stmt->get_result();    
    }   
    function getStudyCycles() {
        $stmt = $this->conn->prepare("  SELECT *
                                        FROM `study_cycles`");
        
        $stmt->execute();
        return $stmt->get_result();
    } 
    function getProfessors($course_id) {
        $stmt = $this->conn->prepare("  SELECT  `title`, `f_name`, `l_name` 
                                        FROM    `professors` 
                                        JOIN    `course_professors` ON `professors`.`professor_id` = `course_professors`.`professor_id` 
                                        WHERE   `course_id` = ?");
        $stmt->bind_param("i", $course_id);
        $stmt->execute();
        return $stmt->get_result();  
    }
    function getById($id) {    
        $stmt = $this->conn->prepare("SELECT * FROM `courses` WHERE `course_id` = ?");        
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result();        
    }
    function insert($name, $year, $package, $cycle, $link) {
        $stmt = $this->conn->prepare("INSERT INTO `courses`(`name`, `year`, `package`, `study_cycle_id`, `link`) VALUES (?, ?, ?, ?, ?)");        
        $stmt->bind_param("siiis", $name, $year, $package, $cycle, $link);
        $stmt->execute();
        return $stmt->get_result(); 
    }   
    function update($id, $name, $year, $package, $cycle, $link) {
        $stmt = $this->conn->prepare("UPDATE `courses` SET `name` = ?, `year` = ?, `package` = ?, `study_cycle_id` = ?, `link` = ? WHERE `course_id` = ?");        
        var_dump( $name, $year, $package, $cycle, $link, $id);
        $stmt->bind_param("siiisi", $name, $year, $package, $cycle, $link, $id);
        $stmt->execute();
        return $stmt->get_result(); 
    }
    function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM `courses` WHERE `id` = ?");        
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result(); 
    }
}
?>