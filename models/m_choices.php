<?php
class m_choices {
    private $conn;
    function m_choices($conn) {
        $this->conn = $conn;
    }
    function get() {
        $stmt = $this->conn->prepare("SELECT * FROM `choices` WHERE user = ?");
        $user = $_SESSION["login_usr"];
        $stmt->bind_param("s", $user);
        $stmt->execute();
        return $stmt->get_result();    
    }    
    function insert($course) {
        $stmt = $this->conn->prepare("INSERT INTO `choices`(`user`, `course`, `status`) VALUES (?, ?, ?)");        
        $status = "Pending";
        $stmt->bind_param("sis", $_SESSION["login_usr"], $course, $status);
        $stmt->execute();
        return $stmt->get_result(); 
    }
    function validateChoice($courseId) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM choices JOIN courses ON choices.course=courses.id 
                                        WHERE courses.package IN (SELECT package FROM courses WHERE id = ?)
                                        AND courses.year IN (SELECT year FROM courses WHERE id = ?)");
        $stmt->bind_param("ii", $courseId, $courseId);
        $stmt->execute();
        return $stmt->get_result();
    }
    function getChoices() {
        $stmt = $this->conn->prepare("SELECT * FROM `choices` join courses on choices.course=courses.id WHERE user = ?");
        $user = $_SESSION["login_usr"];
        $stmt->bind_param("s", $user);
        $stmt->execute();
        return $stmt->get_result();
    }
}