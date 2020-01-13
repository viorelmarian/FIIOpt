<?php
require_once "../shared/db_conn.php";
require_once "../models/m_admin.php";

class admin
{    
    function display() {        
        require_once "../views/v_admin.php";
    }
    function login() {
        //Create Database Connection
        if (!isset($db)) {
            $db = new database_conn;
            $db->connect();            
        }
        //Create Model Instance
        if (!isset($admin)) {            
            $admin = new m_admin($db->conn);
        }
        //Reset Errors
        unset($_SESSION["error_usr"]);
        unset($_SESSION["error_pwd"]);
        
        //Preserve state of the username
        $_SESSION["login_usr"] = $_POST["login_usr"];

        //If username was inserted
        if ($_POST["login_usr"] != NULL) { 
            //Get user info
            $result = $admin->getByUser($_POST["login_usr"]);
            //Fetch data in assoc array
            $admin = $result->fetch_assoc();
            //If username exists in the database  
            if ($admin !== NULL) {
                //If password was inserted
                if ($_POST["login_pwd"] != NULL) { 
                    //If passwords match
                    if ($admin["admin_password"] === $_POST["login_pwd"]) { 
                        //Log In
                        $_SESSION["logged"] = true;
                    } else {
                        $_SESSION["error_pwd"] = "Wrong Password!"; 
                    }
                } else {
                    $_SESSION["error_pwd"] = "Enter Password!";
                }
            } else {
                $_SESSION["error_usr"] = "Username does not exist!";
            }
        //If username is empty
        } else { 
            $_SESSION["error_usr"] = "Enter Username!";
            //If password is empty
            if ($_POST["login_pwd"] == NULL) { 
                $_SESSION["error_pwd"] = "Enter Password!";
            }
        }
        //Redirect accordingly
        header("Location: ../administration/display");
        exit();      
    }
    function logout() {
        //Log Out
        unset($_SESSION["logged"]);  
        //Redirect accordingly
        header("Location: ../admin");
        exit();
    }
    function getLoggedUser() {
        echo json_encode($_SESSION["login_usr"]);
    }
}
?>