<?php
class m_admin {
    private $conn;
    function m_admin($conn) {
        $this->conn = $conn;
    }
    function get() {
        $stmt = $this->conn->prepare("SELECT * FROM `admins`");
        $stmt->execute();
        return $stmt->get_result();
    }
    function getById($id) {    
        $stmt = $this->conn->prepare("SELECT * FROM `admins` WHERE `admin_id` = ?");        
        $stmt->bind_param("s", $id);
        $stmt->execute();
        return $stmt->get_result();        
    }
    function getByUser($username) {    
        $stmt = $this->conn->prepare("SELECT * FROM `admins` WHERE `admin_username` = ?");        
        $stmt->bind_param("s", $username);
        $stmt->execute();
        return $stmt->get_result();        
    }
    function insert($username, $password) {
        $stmt = $this->conn->prepare("INSERT INTO `admins`(`admin_username`, `admin_password`) VALUES (?, ?)");        
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        return $stmt->get_result(); 
    }   
    function update($id, $username, $password) {
        $stmt = $this->conn->prepare("UPDATE `admins` SET `admin_username` = ?,`admin_password` = ? WHERE `admin_id` = ?");        
        $stmt->bind_param("sss", $username, $password, $id);
        $stmt->execute();
        return $stmt->get_result(); 
    }
    function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM `admins` WHERE `admin_id` = ?");        
        $stmt->bind_param("s", $id);
        $stmt->execute();
        return $stmt->get_result(); 
    }
}
?>