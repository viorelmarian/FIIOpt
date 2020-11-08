<?php
require_once "../shared/db_conn.php";
require_once "../models/m_users.php";

class users
{
    function isAjax()
    {
        $headers = apache_request_headers();
        $is_ajax = (isset($headers['X-Requested-With']) && $headers['X-Requested-With'] == 'XMLHttpRequest');
        return $is_ajax;
    }
    function display()
    {
        require_once "../views/v_login.php";
    }
    function login()
    {
        //Create Database Connection
        if (!isset($db)) {
            $db = new database_conn;
            $db->connect();
        }
        //Create Model Instance
        if (!isset($users)) {
            $users = new m_users($db->conn);
        }
        //Reset Errors
        unset($_SESSION["error_usr"]);
        unset($_SESSION["error_pwd"]);

        //If username was inserted
        if ($_POST["login_usr"] != NULL) {
            //Get user info
            $result = $users->getByUser($_POST["login_usr"]);
            //Fetch data in assoc array
            $user = $result->fetch_assoc();
            //Preserve state of the username
            $_SESSION["login_usr"] = $user["student_id"];
            //If username exists in the database  
            if ($user !== NULL) {
                //If password was inserted
                if ($_POST["login_pwd"] != NULL) {
                    //If passwords match
                    if ($user["password"] === $_POST["login_pwd"]) {
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
        header("Location: ../courses/display");
        exit();
    }
    function logout($from)
    {
        //Log Out
        unset($_SESSION["logged"]);
        //Redirect accordingly
        if ($from == 'adm') {
            header("Location: ../../admin");
        } else {
            header("Location: ../../");
        }

        exit();
    }
    function getLoggedUser()
    {
        if ($this->isAjax()) {
            //Create Database Connection
            if (!isset($db)) {
                $db = new database_conn;
                $db->connect();
            }
            //Create Model Instance
            if (!isset($users)) {
                $users = new m_users($db->conn);
            }

            $result = $users->getById($_SESSION["login_usr"]);
            echo json_encode($result->fetch_assoc()["username"]);
        } else {
            die(header("HTTP/1.1 404 Not Found"));
        }
    }
}
