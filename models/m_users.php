<?php
class m_users {
    private $conn;
    function m_users($conn) {
        $this->conn = $conn;
    }
    function get() {
        $stmt = $this->conn->prepare("SELECT * FROM `users`");
        $stmt->execute();
        return $stmt->get_result();
    }
    function getById($id) {    
        $stmt = $this->conn->prepare("SELECT * FROM `users` WHERE `id` = ?");        
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result();        
    }
    function getByUser($username) {    
        $stmt = $this->conn->prepare("SELECT * FROM `users` WHERE `username` = ?");        
        $stmt->bind_param("s", $username);
        $stmt->execute();
        return $stmt->get_result();        
    }
    function insert($username, $password) {
        $stmt = $this->conn->prepare("INSERT INTO `users`(`username`, `password`) VALUES (?, ?)");        
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        return $stmt->get_result(); 
    }   
    function update($id, $username, $password) {
        $stmt = $this->conn->prepare("UPDATE `users` SET `username` = ?,`password` = ? WHERE `id` = ?");        
        $stmt->bind_param("ssi", $username, $password, $id);
        $stmt->execute();
        return $stmt->get_result(); 
    }
    function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM `users` WHERE `id` = ?");        
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result(); 
    }
}
?>