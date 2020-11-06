<?php
require_once "../shared/db_conn.php";
require_once "../models/m_choices.php";

class choices
{
    function isAjax()
    {
        $headers = apache_request_headers();
        $is_ajax = (isset($headers['X-Requested-With']) && $headers['X-Requested-With'] == 'XMLHttpRequest');
        return $is_ajax;
    }

    function display()
    {
        //If logged in
        if (isset($_SESSION["logged"])) {
            //Allow access
            require_once "../views/v_choices.php";
        } else {
            //Redirect accordingly
            require_once "../views/v_login.php";
        }
    }
    function get()
    {
        if ($this->isAjax()) {
            //If logged in    
            if (isset($_SESSION["logged"])) {
                //If db connection does not exist
                if (!isset($db)) {
                    //Create db connection
                    $db = new database_conn;
                    $db->connect();
                }
                //If model instance does not exist
                if (!isset($choices)) {
                    //Create model instance
                    $choices = new m_choices($db->conn);
                }
                if (!isset($courses)) {
                    //Create model instance
                    $courses = new m_courses($db->conn);
                }
                //Get data
                $result = $choices->getPrioChoices($_SESSION["login_usr"]);
                $rows = array();
                //Fetch data in assoc array
                while ($row = $result->fetch_assoc()) {
                    //For each Course get the corresponding professors
                    $professors = $courses->getProfessorsByCourse($row["course_id"]);
                    //Add professors to the rest of the data
                    $i = 1;
                    while ($professor = $professors->fetch_assoc()) {
                        $row["professor_" . $i++] = $professor["title"] . " " . $professor["l_name"] . " " . $professor["f_name"];
                    }
                    foreach ($row as $key => $value) {
                        //Encode each value of the row in utf8
                        $row[$key] = utf8_encode($value);
                    }
                    //Add rows to Array
                    $rows[] = $row;
                }
                //Encode data in JSON Format and return
                echo json_encode($rows);
            } else {
                //Redirect accordingly
                require_once "../views/v_login.php";
            }
        } else {
            die(header("HTTP/1.1 404 Not Found"));
        }
    }
    function getAllChoices()
    {
        if ($this->isAjax()) {
            //If logged in    
            if (isset($_SESSION["logged"])) {
                //If db connection does not exist
                if (!isset($db)) {
                    //Create db connection
                    $db = new database_conn;
                    $db->connect();
                }
                //If model instance does not exist
                if (!isset($choices)) {
                    //Create model instance
                    $choices = new m_choices($db->conn);
                }
                if (!isset($courses)) {
                    //Create model instance
                    $courses = new m_courses($db->conn);
                }
                //Get data
                $result = $choices->getAllChoices($_SESSION["login_usr"]);
                $rows = array();
                //Fetch data in assoc array
                while ($row = $result->fetch_assoc()) {
                    //For each Course get the corresponding professors
                    $professors = $courses->getProfessorsByCourse($row["course_id"]);
                    //Add professors to the rest of the data
                    $i = 1;
                    while ($professor = $professors->fetch_assoc()) {
                        $row["professor_" . $i++] = $professor["title"] . " " . $professor["l_name"] . " " . $professor["f_name"];
                    }
                    foreach ($row as $key => $value) {
                        //Encode each value of the row in utf8
                        $row[$key] = utf8_encode($value);
                    }
                    //Add rows to Array
                    $rows[] = $row;
                }
                //Encode data in JSON Format and return
                echo json_encode($rows);
            } else {
                //Redirect accordingly
                require_once "../views/v_login.php";
            }
        } else {
            die(header("HTTP/1.1 404 Not Found"));
        }
    }
    function insert()
    {
        if ($this->isAjax()) {
            //If logged in
            if (isset($_SESSION["logged"])) {
                //If db connection does not exist 
                if (!isset($db)) {
                    //Create db connection
                    $db = new database_conn;
                    $db->connect();
                }
                //If model instance does not exist
                if (!isset($choices)) {
                    //Create model instance
                    $choices = new m_choices($db->conn);
                }
                //Validations
                $options = $_POST['data'];
                $result = $choices->validateChoices($options);

                if ($result == 0) {
                    //Insert choosen options
                    foreach ($options as $option) {
                        $choices->insert($option['id'], $option['priority']);
                    }
                    //Generate response
                    $response = array(
                        "status" => "Success",
                        "msg" => "Your choices have been successfully registered!"
                    );
                } else {
                    //Generate response
                    $response = array(
                        "status" => "Error",
                        "msg" => "Selected courses are misplaced!"
                    );
                }
                echo json_encode($response);
            } else {
                //Redirect accordingly
                require_once "../views/v_login.php";
            }
        } else {
            die(header("HTTP/1.1 404 Not Found"));
        }
    }
}
