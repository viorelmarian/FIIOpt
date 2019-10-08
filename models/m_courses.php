<?php
class m_courses {
    private $conn;
    function m_courses($conn) {
        $this->conn = $conn;
    }
    function get($src) {
        $stmt = $this->conn->prepare("SELECT * FROM `courses` WHERE name LIKE ?");
        $src = '%' . $src . '%';
        $stmt->bind_param("s", $src);
        $stmt->execute();
        return $stmt->get_result();    
    }    
    // function getById($id) {    
    //     $stmt = $this->conn->prepare("SELECT * FROM `courses` WHERE `id` = ?");        
    //     $stmt->bind_param("i", $id);
    //     $stmt->execute();
    //     return $stmt->get_result();        
    // }
    // function insert($username, $password) {
    //     $stmt = $this->conn->prepare("INSERT INTO `courses`(`username`, `password`) VALUES (?, ?)");        
    //     $stmt->bind_param("ss", $username, $password);
    //     $stmt->execute();
    //     return $stmt->get_result(); 
    // }   
    // function update($id, $username, $password) {
    //     $stmt = $this->conn->prepare("UPDATE `courses` SET `username` = ?,`password` = ? WHERE `id` = ?");        
    //     $stmt->bind_param("ssi", $username, $password, $id);
    //     $stmt->execute();
    //     return $stmt->get_result(); 
    // }
    // function delete($id) {
    //     $stmt = $this->conn->prepare("DELETE FROM `courses` WHERE `id` = ?");        
    //     $stmt->bind_param("i", $id);
    //     $stmt->execute();
    //     return $stmt->get_result(); 
    // }
}
?>