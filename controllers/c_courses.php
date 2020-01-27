<?php
require_once "../shared/db_conn.php";
require_once "../models/m_courses.php";

class courses {    
    function display() {
        //If logged in
        if (isset($_SESSION["logged"])) {
            //Allow access
            require_once "../views/v_courses.php";            
        } else {
            //Redirect accordingly
            require_once "../views/v_login.php";
        }
    }
    function getAllCourses() {
        //If logged in
        if (isset($_SESSION["logged"]) || isset($_SESSION["logged_adm"])) {
            //If db connection does not exist
            if(!isset($db)) {
                //Create db connection
                $db = new database_conn;
                $db->connect();
            }
            //If model instance does not exist
            if (!isset($courses)) {
                //Create model instance
                $courses = new m_courses($db->conn);
            }
            //Get data
            $result = $courses->get();    
            $rows = array();        
            //Fetch data in assoc array
            while($row = $result->fetch_assoc()) {
                foreach($row as $key => $value) {
                    //Encode each value of the row in utf8
                    $row[$key] = utf8_encode($value);
                }
            //Add rows in Array
            $rows[] = $row;
            }
            //Encode in JSON Format and return
            echo json_encode($rows);
        } else {
            //Redirect accordingly
            require_once "../views/v_login.php";
        }
    }
    function getStudyCycles() {
        //If logged in
        if (isset($_SESSION["logged"]) || isset($_SESSION["logged_adm"])) {
            //If db connection does not exist
            if(!isset($db)) {
                //Create db connection
                $db = new database_conn;
                $db->connect();
            }
            //If model instance does not exist
            if (!isset($courses)) {
                //Create model instance
                $courses = new m_courses($db->conn);
            }
            //Get data
            $result = $courses->getStudyCycles();    
            $rows = array();        
            //Fetch data in assoc array
            while($row = $result->fetch_assoc()) {
                foreach($row as $key => $value) {
                    //Encode each value of the row in utf8
                    $row[$key] = utf8_encode($value);
                }
            //Add rows in Array
            $rows[] = $row;
            }
            //Encode in JSON Format and return
            echo json_encode($rows);
        } else {
            //Redirect accordingly
            require_once "../views/v_login.php";
        }
    }
    function getProfessors() {
        //If logged in
        if (isset($_SESSION["logged"]) || isset($_SESSION["logged_adm"])) {
            //If db connection does not exist
            if(!isset($db)) {
                //Create db connection
                $db = new database_conn;
                $db->connect();
            }
            //If model instance does not exist
            if (!isset($courses)) {
                //Create model instance
                $courses = new m_courses($db->conn);
            }
            //Get data
            $result = $courses->getProfessors();    
            $rows = array();        
            //Fetch data in assoc array
            while($row = $result->fetch_assoc()) {
                foreach($row as $key => $value) {
                    //Encode each value of the row in utf8
                    $row[$key] = utf8_encode($value);
                }
            //Add rows in Array
            $rows[] = $row;
            }
            //Encode in JSON Format and return
            echo json_encode($rows);
        } else {
            //Redirect accordingly
            require_once "../views/v_login.php";
        }
    }
    function getById($courseId) {
        //If logged in
        if (isset($_SESSION["logged"]) || isset($_SESSION["logged_adm"])) {
            //If db connection does not exist
            if(!isset($db)) {
                //Create db connection
                $db = new database_conn;
                $db->connect();
            }
            //If model instance does not exist
            if (!isset($courses)) {
                //Create model instance
                $courses = new m_courses($db->conn);
            }
            //Get data
            $result = $courses->getById($courseId);    
            $rows = array();    
            while( $row = $result->fetch_assoc()) {
                //For each Course get the corresponding professors
                $professors = $courses->getProfessorsByCourse($courseId);
                //Add professors to the rest of the data
                $i = 1;
                while($professor = $professors->fetch_assoc()) {
                    $row["professor_" . $i++] = $professor["professor_id"];
                }
                foreach($row as $key => $value) {
                    //Encode each value of the row in utf8
                    $row[$key] = utf8_encode($value);
                }
            //Add rows to Array
            $rows[] = $row;
            }
            //Encode in JSON Format and return
            echo json_encode($rows);
        } else {
            //Redirect accordingly
            require_once "../views/v_login.php";
        }
    }
    function get($src) {        
        //If logged in
        if (isset($_SESSION["logged"]) || isset($_SESSION["logged_adm"])) {
            //If db connection does not exist
            if(!isset($db)) {
                //Create db connection
                $db = new database_conn;
                $db->connect();
            }
            //If model instance does not exist
            if (!isset($courses)) {
                //Create model instance
                $courses = new m_courses($db->conn);
            }
            //Get data
            $result = $courses->getCourses($src);    
            $rows = array();        
            //Fetch data in assoc array
            while($row = $result->fetch_assoc()) {                
                //For each Course get the corresponding professors
                $professors = $courses->getProfessorsByCourse($row["course_id"]);
                //Add professors to the rest of the data
                $i = 1;
                while($professor = $professors->fetch_assoc()) {
                    $row["professor_" . $i++] = $professor["title"] . " " . $professor["l_name"] . " " . $professor["f_name"];
                }
                foreach($row as $key => $value) {
                    //Encode each value of the row in utf8
                    $row[$key] = utf8_encode($value);
                }
            //Add rows in Array
            $rows[] = $row;
            }
            //Encode in JSON Format and return
            echo json_encode($rows);
        } else {
            //Redirect accordingly
            require_once "../views/v_login.php";
        }
    }

    function saveCourse($data) {
        //If logged in
        if (isset($_SESSION["logged_adm"])) {
            //If db connection does not exist
            if(!isset($db)) {
                //Create db connection
                $db = new database_conn;
                $db->connect();
            }
            //If model instance does not exist
            if (!isset($courses)) {
                //Create model instance
                $courses = new m_courses($db->conn);
            }
            $parameters = explode('&', $data);
            foreach ($parameters as $parameter) {
                $key_value = explode('=', $parameter);
                ${$key_value[0]} = urldecode($key_value[1]);
            }
            //Get data
            if($id == "Choose a course") {
                $result = $courses->insert($name,$year,$package,$cycle,$link,$professor1,$professor2,$no_studs); 
                if ($result) {
                    $response = array(  "status"=>"Success",
                                        "msg" => "Course inserted successfully!"
                                    );
                } else {
                    $response = array(  "status"=>"Error",
                                        "msg" => "Course already exists!"
                                    );
                }
            } else {
                $result = $courses->update($id,$name,$year,$package,$cycle,$link,$professor1,$professor2,$no_studs); 
                if ($result) {
                    $response = array(  "status"=>"Success",
                                        "msg" => "Course updated successfully!"
                                    );
                } else {
                    $response = array(  "status"=>"Error",
                                        "msg" => "Course could not be updated!"
                                    );
                }
            }
            //Encode in JSON Format and return
            echo json_encode($response);
        } else {
            //Redirect accordingly
            require_once "../views/v_login.php";
        }
    }

    function saveProfessor($data) {
        //If logged in
        if (isset($_SESSION["logged_adm"])) {
            //If db connection does not exist
            if(!isset($db)) {
                //Create db connection
                $db = new database_conn;
                $db->connect();
            }
            //If model instance does not exist
            if (!isset($courses)) {
                //Create model instance
                $courses = new m_courses($db->conn);
            }
            $parameters = explode('&', $data);
            foreach ($parameters as $parameter) {
                $key_value = explode('=', $parameter);
                ${$key_value[0]} = urldecode($key_value[1]);
            }
            //Get data
            if($id == "Choose a professor") {
                $result = $courses->insertProfessor($title,$l_name,$f_name); 
                if ($result) {
                    $response = array(  "status"=>"Success",
                                        "msg" => "Professor inserted successfully!"
                                    );
                } else {
                    $response = array(  "status"=>"Error",
                                        "msg" => "Professor already exists!"
                                    );
                }
            } else {
                $result = $courses->updateProfessor($id,$title,$l_name,$f_name); 
                if ($result) {
                    $response = array(  "status"=>"Success",
                                        "msg" => "Professor updated successfully!"
                                    );
                } else {
                    $response = array(  "status"=>"Error",
                                        "msg" => "Professor could not be updated!"
                                    );
                }
            }
            //Encode in JSON Format and return
            echo json_encode($response);
        } else {
            //Redirect accordingly
            require_once "../views/v_login.php";
        }
    }

    function deleteCourse($courseId) {
        //If logged in
        if (isset($_SESSION["logged_adm"])) {
            //If db connection does not exist
            if(!isset($db)) {
                //Create db connection
                $db = new database_conn;
                $db->connect();
            }
            //If model instance does not exist
            if (!isset($courses)) {
                //Create model instance
                $courses = new m_courses($db->conn);
            }
            $courses->delete($courseId);

            $response = array(  "status"=>"Success",
                                        "msg" => "Course deleted successfully!"
                        );
            //Encode in JSON Format and return
            echo json_encode($response);
        } else {
            //Redirect accordingly
            require_once "../views/v_login.php";
        }
    }

    function deleteProfessor($professorId) {
        //If logged in
        if (isset($_SESSION["logged_adm"])) {
            //If db connection does not exist
            if(!isset($db)) {
                //Create db connection
                $db = new database_conn;
                $db->connect();
            }
            //If model instance does not exist
            if (!isset($courses)) {
                //Create model instance
                $courses = new m_courses($db->conn);
            }
            $courses->deleteProfessor($professorId);

            $response = array(  "status"=>"Success",
                                        "msg" => "Professor deleted successfully!"
                        );
            //Encode in JSON Format and return
            echo json_encode($response);
        } else {
            //Redirect accordingly
            require_once "../views/v_login.php";
        }
    }

    function getProfessorById($professorId){
        //If logged in
        if (isset($_SESSION["logged_adm"])) {
            //If db connection does not exist
            if(!isset($db)) {
                //Create db connection
                $db = new database_conn;
                $db->connect();
            }
            //If model instance does not exist
            if (!isset($courses)) {
                //Create model instance
                $courses = new m_courses($db->conn);
            }
            $courses->getProfessorById($professorId);

            $result = $courses->getProfessorById($professorId);
            $rows = array();    
            while( $row = $result->fetch_assoc()) {
                foreach($row as $key => $value) {
                    //Encode each value of the row in utf8
                    $row[$key] = utf8_encode($value);
                }
                //Add rows to Array
                $rows[] = $row;
            }
            //Encode in JSON Format and return
            echo json_encode($rows);
        } else {
            //Redirect accordingly
            require_once "../views/v_login.php";
        }
    }

    function assignCourses() {
         //If logged in
         if (isset($_SESSION["logged_adm"])) {
            //If db connection does not exist
            if(!isset($db)) {
                //Create db connection
                $db = new database_conn;
                $db->connect();
            }
            //If model instance does not exist
            if (!isset($courses)) {
                //Create model instance
                $courses = new m_courses($db->conn);
            }
            //If model instance does not exist
            if (!isset($choices)) {
                //Create model instance
                $choices = new m_choices($db->conn);
            }
            //If model instance does not exist
            if (!isset($assignations)) {
                //Create model instance
                $assignations = new m_assignations($db->conn);
            }
            //If model instance does not exist
            if (!isset($users)) {
                //Create model instance
                $users = new m_users($db->conn);
            }
            $result = $users->getAllStudentsIds();
            while( $row = $result->fetch_assoc()) {
                foreach($row as $key => $value) {
                    //Encode each value of the row in utf8
                    $row[$key] = utf8_encode($value);
                }
                //Add rows to Array
                $students[] = $row;
            }
            $student_choices = array();
            foreach ($students as $student) {
                $student_choices = array();
                $result = $choices->getChoices($student["student_id"]);
                while($row = $result->fetch_assoc()) {
                    foreach($row as $key => $value) {
                        //Encode each value of the row in utf8
                        $row[$key] = utf8_encode($value);
                    }
                    //Add rows to Array
                    $student_choices[] = $row;
                }
                foreach ($student_choices as $choice) {
                    $result = $assignations->validateChoice($choice["course_id"], $student["student_id"]);
                    if ($result->fetch_assoc()["COUNT(*)"] == 0) {
                        $places_available = $courses->getAvailablePlaces($choice["course_id"]);
                        if ($places_available > 0) {
                            $assignations->insert($choice["course_id"], $student["student_id"]);
                        }
                    }
                    $result = $assignations->validateChoice($choice["course_id"], $student["student_id"]);
                }
                
            }            
        } else {
            //Redirect accordingly
            require_once "../views/v_login.php";
        }
    }
}
?>