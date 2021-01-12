<?php
require_once "../shared/db_conn.php";
require_once "../models/m_courses.php";

class courses
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
            require_once "../views/v_courses.php";
        } else {
            //Redirect accordingly
            require_once "../views/v_login.php";
        }
    }

    function getAllCourses()
    {
        if ($this->isAjax()) {
            //If logged in
            if (isset($_SESSION["logged"]) || isset($_SESSION["logged_adm"])) {
                //If db connection does not exist
                if (!isset($db)) {
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
                while ($row = $result->fetch_assoc()) {
                    foreach ($row as $key => $value) {
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
        } else {
            die(header("HTTP/1.1 404 Not Found"));
        }
    }

    function getStudyCycles()
    {
        if ($this->isAjax()) {
            //If logged in
            if (isset($_SESSION["logged"]) || isset($_SESSION["logged_adm"])) {
                //If db connection does not exist
                if (!isset($db)) {
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
                while ($row = $result->fetch_assoc()) {
                    foreach ($row as $key => $value) {
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
        } else {
            die(header("HTTP/1.1 404 Not Found"));
        }
    }

    function getProfessors()
    {
        if ($this->isAjax()) {
            //If logged in
            if (isset($_SESSION["logged"]) || isset($_SESSION["logged_adm"])) {
                //If db connection does not exist
                if (!isset($db)) {
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
                while ($row = $result->fetch_assoc()) {
                    foreach ($row as $key => $value) {
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
        } else {
            die(header("HTTP/1.1 404 Not Found"));
        }
    }

    function getById($courseId)
    {
        if ($this->isAjax()) {
            //If logged in
            if (isset($_SESSION["logged"]) || isset($_SESSION["logged_adm"])) {
                //If db connection does not exist
                if (!isset($db)) {
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
                while ($row = $result->fetch_assoc()) {
                    //For each Course get the corresponding professors
                    $professors = $courses->getProfessorsByCourse($courseId);
                    //Add professors to the rest of the data
                    $i = 1;
                    while ($professor = $professors->fetch_assoc()) {
                        $row["professor_" . $i++] = $professor["professor_id"];
                    }
                    foreach ($row as $key => $value) {
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
        } else {
            die(header("HTTP/1.1 404 Not Found"));
        }
    }

    function get()
    {
        if ($this->isAjax()) {
            //If logged in
            if (isset($_SESSION["logged"]) || isset($_SESSION["logged_adm"])) {
                //If db connection does not exist
                if (!isset($db)) {
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
                $result = $courses->getCourses();
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
                    //Add rows in Array
                    $rows[] = $row;
                }
                //Encode in JSON Format and return
                if ($rows) {
                    echo json_encode($rows);
                } else {
                    header('HTTP/1.1 218 Internal Server Error');
                    header('Content-Type: application/json; charset=UTF-8');
                    die();
                }
            } else {
                //Redirect accordingly
                require_once "../views/v_login.php";
            }
        } else {
            die(header("HTTP/1.1 404 Not Found"));
        }
    }

    function saveCourse($data)
    {
        if ($this->isAjax()) {
            //If logged in
            if (isset($_SESSION["logged_adm"])) {
                //If db connection does not exist
                if (!isset($db)) {
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
                if ($id == "Choose a course") {
                    $result = $courses->insert($name, $year, $package, $cycle, $link, $professor1, $professor2, $no_studs);
                    if ($result) {
                        $response = array(
                            "status" => "Success",
                            "msg" => "Course inserted successfully!",
                            "course_id" => $result
                        );
                    } else {
                        $response = array(
                            "status" => "Error",
                            "msg" => "Course already exists!"
                        );
                    }
                } else {
                    $result = $courses->update($id, $name, $year, $package, $cycle, $link, $professor1, $professor2, $no_studs);
                    if ($result) {
                        $response = array(
                            "status" => "Success",
                            "msg" => "Course updated successfully!",
                            "course_id" => $result
                        );
                    } else {
                        $response = array(
                            "status" => "Error",
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
        } else {
            die(header("HTTP/1.1 404 Not Found"));
        }
    }

    function saveProfessor($data)
    {
        if ($this->isAjax()) {
            //If logged in
            if (isset($_SESSION["logged_adm"])) {
                //If db connection does not exist
                if (!isset($db)) {
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
                if ($id == "Choose a professor") {
                    $result = $courses->insertProfessor($title, $l_name, $f_name);
                    if ($result) {
                        $response = array(
                            "status" => "Success",
                            "msg" => "Professor inserted successfully!"
                        );
                    } else {
                        $response = array(
                            "status" => "Error",
                            "msg" => "Professor already exists!"
                        );
                    }
                } else {
                    $result = $courses->updateProfessor($id, $title, $l_name, $f_name);
                    if ($result) {
                        $response = array(
                            "status" => "Success",
                            "msg" => "Professor updated successfully!"
                        );
                    } else {
                        $response = array(
                            "status" => "Error",
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
        } else {
            die(header("HTTP/1.1 404 Not Found"));
        }
    }

    function deleteCourse($courseId)
    {
        if ($this->isAjax()) {
            //If logged in
            if (isset($_SESSION["logged_adm"])) {
                //If db connection does not exist
                if (!isset($db)) {
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

                $response = array(
                    "status" => "Success",
                    "msg" => "Course deleted successfully!"
                );
                //Encode in JSON Format and return
                echo json_encode($response);
            } else {
                //Redirect accordingly
                require_once "../views/v_login.php";
            }
        } else {
            die(header("HTTP/1.1 404 Not Found"));
        }
    }

    function deleteProfessor($professorId)
    {
        if ($this->isAjax()) {
            //If logged in
            if (isset($_SESSION["logged_adm"])) {
                //If db connection does not exist
                if (!isset($db)) {
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

                $response = array(
                    "status" => "Success",
                    "msg" => "Professor deleted successfully!"
                );
                //Encode in JSON Format and return
                echo json_encode($response);
            } else {
                //Redirect accordingly
                require_once "../views/v_login.php";
            }
        } else {
            die(header("HTTP/1.1 404 Not Found"));
        }
    }

    function getProfessorById($professorId)
    {
        if ($this->isAjax()) {
            //If logged in
            if (isset($_SESSION["logged_adm"])) {
                //If db connection does not exist
                if (!isset($db)) {
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
                while ($row = $result->fetch_assoc()) {
                    foreach ($row as $key => $value) {
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
        } else {
            die(header("HTTP/1.1 404 Not Found"));
        }
    }

    function assignCourses()
    {
        if ($this->isAjax()) {
            //If logged in
            if (isset($_SESSION["logged_adm"])) {
                //If db connection does not exist
                if (!isset($db)) {
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
                $result = $courses->getAssignationList();
                while ($row = $result->fetch_assoc()) {
                    //Get the gredes of the past courses relevant for this option assignation
                    $result2 = $courses->getGradesOfRelevantPastCourses($row['course_id'], $row['student_id']);
                    //Init with 0
                    $option_grade = 0.00;
                    $sum_of_grades = 0.00;
                    $no_of_grades = 0;
                    //Calculate average
                    while ($row2 = $result2->fetch_assoc()) {
                        $no_of_grades += 1;
                        $sum_of_grades += $row2['grade'];
                    }
                    if ($no_of_grades <> 0) {
                        $option_grade = $sum_of_grades / $no_of_grades;
                    }
                    //Add to array
                    $row['option_grade'] = $option_grade;
                    //Encode each value of the row in utf8
                    foreach ($row as $key => $value) {
                        $row[$key] = utf8_encode($value);
                    }
                    //Add rows to Array
                    $rows[] = $row;
                }

                array_multisort(
                    array_column($rows, 'year'),
                    SORT_ASC,
                    array_column($rows, 'package'),
                    SORT_ASC,
                    array_column($rows, 'priority'),
                    SORT_ASC,
                    array_column($rows, 'option_grade'),
                    SORT_DESC,
                    array_column($rows, 'anual_grade'),
                    SORT_DESC,
                    $rows
                );

                foreach ($rows as $row) {
                    $result = $assignations->validateChoice($row["course_id"], $row["student_id"]);
                    if ($result->fetch_assoc()["COUNT(*)"] == 0) {
                        $places_available = $courses->getAvailablePlaces($row["course_id"]);
                        if ($places_available > 0) {
                            $assignations->insert($row["course_id"], $row["student_id"]);
                        }
                    }
                }
            } else {
                //Redirect accordingly
                require_once "../views/v_login.php";
            }
        } else {
            die(header("HTTP/1.1 404 Not Found"));
        }
    }

    function getPastCourses()
    {
        // if ($this->isAjax()) {
        //If logged in
        if (isset($_SESSION["logged"]) || isset($_SESSION["logged_adm"])) {
            //If db connection does not exist
            if (!isset($db)) {
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
            $result = $courses->getPastCourses();
            $rows = array();
            //Fetch data in assoc array
            while ($row = $result->fetch_assoc()) {
                foreach ($row as $key => $value) {
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
        // } else {
        //     die(header("HTTP/1.1 404 Not Found"));
        // }
    }

    function getPastCoursesForCourse($course)
    {
        if ($this->isAjax()) {
            //If logged in
            if (isset($_SESSION["logged"]) || isset($_SESSION["logged_adm"])) {
                //If db connection does not exist
                if (!isset($db)) {
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
                $result = $courses->getPastCoursesForCourse($course);
                $rows = array();
                //Fetch data in assoc array
                while ($row = $result->fetch_assoc()) {
                    foreach ($row as $key => $value) {
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
        } else {
            die(header("HTTP/1.1 404 Not Found"));
        }
    }

    function insertAssignationDependency()
    {
        if ($this->isAjax()) {
            //If logged in
            if (isset($_SESSION["logged"]) || isset($_SESSION["logged_adm"])) {
                //If db connection does not exist
                if (!isset($db)) {
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
                $options = $_POST['data'];
                $courses->insertAssignationDependency($options[0]['course'], $options[0]['past_course']);
            } else {
                //Redirect accordingly
                require_once "../views/v_login.php";
            }
        } else {
            die(header("HTTP/1.1 404 Not Found"));
        }
    }

    function deleteAssignationDependencies()
    {
        if ($this->isAjax()) {
            //If logged in
            if (isset($_SESSION["logged"]) || isset($_SESSION["logged_adm"])) {
                //If db connection does not exist
                if (!isset($db)) {
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
                $options = $_POST['data'];
                $courses->deleteAssignationDependencies($options[0]['course']);

                $response = array(
                    "status" => "Success",
                    "msg" => "Dependencies deleted successfully!"
                );
                //Encode in JSON Format and return
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
