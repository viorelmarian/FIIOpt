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
    function getProfessorsByCourse($course_id) {
        $stmt = $this->conn->prepare("  SELECT  * 
                                        FROM    `professors` 
                                        JOIN    `course_professors` ON `professors`.`professor_id` = `course_professors`.`professor_id` 
                                        WHERE   `course_id` = ?");
        $stmt->bind_param("i", $course_id);
        $stmt->execute();
        return $stmt->get_result();  
    }
    function getProfessors() {
        $stmt = $this->conn->prepare("  SELECT  *
                                        FROM    `professors`");
        $stmt->execute();
        return $stmt->get_result();  
    }
    function getById($id) {    
        $stmt = $this->conn->prepare("SELECT * FROM `courses` WHERE `course_id` = ?");        
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result();        
    }
    function insert($name, $year, $package, $cycle, $link, $professor1, $professor2) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM `courses` WHERE `name` = ?");        
        $stmt->bind_param("s", $name);
        $stmt->execute();

        $result = $stmt->get_result();
        if (array_values($result->fetch_assoc())[0] == 0) { 
            $stmt = $this->conn->prepare("INSERT INTO `courses`(`name`, `year`, `package`, `study_cycle_id`, `link`) VALUES (?, ?, ?, ?, ?)");        
            $stmt->bind_param("siiis", $name, $year, $package, $cycle, $link);
            $stmt->execute();

            $stmt = $this->conn->prepare("SELECT `course_id` FROM `courses` WHERE `name` = ? AND `year` = ? AND `package` = ?");        
            $stmt->bind_param("sii", $name, $year, $package);
            $stmt->execute();

            $result = $stmt->get_result();
            $result = $result->fetch_assoc();
            
            $courseId = $result["course_id"];    
            
            $stmt = $this->conn->prepare("INSERT INTO `course_professors`(`course_id`, `professor_id`) VALUES (?, ?)");        
            $stmt->bind_param("si", $courseId, $professor1);
            $stmt->execute();

            $stmt = $this->conn->prepare("INSERT INTO `course_professors`(`course_id`, `professor_id`) VALUES (?, ?)");        
            $stmt->bind_param("si", $courseId, $professor2);
            $stmt->execute();

            return true;
        } else {
            return false;
        }        
    }   
    function insertProfessor($title,$l_name,$f_name) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM `professors` WHERE `f_name` = ? AND `l_name` = ?");        
        $stmt->bind_param("ss", $l_name, $f_name);
        $stmt->execute();

        $result = $stmt->get_result();
        if (array_values($result->fetch_assoc())[0] == 0) { 
            $stmt = $this->conn->prepare("INSERT INTO `professors`(`f_name`, `l_name`, `title`) VALUES (?, ?, ?)");        
            $stmt->bind_param("sss", $f_name, $l_name, $title);
            $stmt->execute();

            return true;
        } else {
            return false;
        }        
    }   
    function update($id, $name, $year, $package, $cycle, $link, $professor1, $professor2) {
        $stmt = $this->conn->prepare("UPDATE `courses` SET `name` = ?, `year` = ?, `package` = ?, `study_cycle_id` = ?, `link` = ? WHERE `course_id` = ?");    
        $stmt->bind_param("siiisi", $name, $year, $package, $cycle, $link, $id);
        $stmt->execute();

        $stmt = $this->conn->prepare("  SELECT `id` FROM    `course_professors` 
                                                    JOIN    `courses` 
                                                    ON      `course_professors`.`course_id` = `courses`.`course_id`
                                                    WHERE   `name` = ?");    
        $stmt->bind_param("s", $name);
        $stmt->execute();

        $result = $stmt->get_result();
        $professor_id_1 = $result->fetch_assoc();
        $professor_id_2 = $result->fetch_assoc();

        $stmt = $this->conn->prepare("UPDATE `course_professors` SET `professor_id` = ? WHERE `course_professors`.`id` = ?");    
        $stmt->bind_param("ii", $professor1, $professor_id_1["id"]);
        $stmt->execute();
        
        $stmt = $this->conn->prepare("UPDATE `course_professors` SET `professor_id` = ? WHERE `course_professors`.`id` = ?"); 
        $stmt->bind_param("ii", intval($professor2), $professor_id_2["id"]);
        $stmt->execute();

        return true;
    }
    function updateProfessor($id, $title,$l_name,$f_name) {
        $stmt = $this->conn->prepare("UPDATE `professors` SET `f_name` = ?, `l_name` = ?, `title` = ? WHERE `professor_id` = ?");    
        $stmt->bind_param("sssi", $f_name, $l_name, $title, $id);
        $stmt->execute();

        return true;
    }
    function deleteProfessor($id) {
        $stmt = $this->conn->prepare("DELETE FROM `professors` WHERE `professor_id` = ?");        
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result(); 
    }
    function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM `courses` WHERE `course_id` = ?");        
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result(); 
    }
    function getProfessorById($professorId) {
        $stmt = $this->conn->prepare("SELECT * FROM `professors` WHERE `professor_id` = ?");        
        $stmt->bind_param("i", $professorId);
        $stmt->execute();
        return $stmt->get_result();
    }
}
?>