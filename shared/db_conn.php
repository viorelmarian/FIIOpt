<?php

class database_conn
{   
    public $conn;
    function connect() {
        if (!isset($servername) || !isset($username) || !isset($password) || !isset($database)) {
            require "db_config.php";
        }

        $this->conn = new mysqli($servername, $username, $password, $database);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    function disconnect() {
        mysqli_close($this->conn);        
    }
}
?>