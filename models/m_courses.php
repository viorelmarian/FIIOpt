<?php
class m_courses
{
    private $conn;
    function m_courses($conn)
    {
        $this->conn = $conn;
    }
    function get()
    {
        $stmt = $this->conn->prepare("  SELECT *
                                        FROM        `courses` 
                                        ORDER BY    `year`, `package`, `name` ASC");

        $stmt->execute();
        return $stmt->get_result();
    }
    function getCourses()
    {
        $stmt = $this->conn->prepare("  SELECT      `username`, `course_id`, `name`, `courses`.`year`, `package`, `link` 
                                        FROM        `courses` 
                                        JOIN        `students_info`  ON `courses`.`year` = `students_info`.`year`
                                        JOIN        `students`       ON `students`.`student_id` = `students_info`.`student_id`
                                        WHERE       `courses`.`course_id` NOT IN ( SELECT `course_id` FROM `choices` WHERE `student_id` = ? )
                                        AND         `students`.`student_id` = ?
                                        ORDER BY    `year`, `package`, `name` ASC");

        $stmt->bind_param("ss", $_SESSION["login_usr"], $_SESSION["login_usr"]);
        $stmt->execute();
        return $stmt->get_result();
    }
    function getAvailablePlaces($course_id)
    {
        $stmt = $this->conn->prepare("  SELECT  `no_of_students`
                                        FROM    `courses` 
                                        WHERE   `course_id` = ?");

        $stmt->bind_param("s", $course_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $total_no_of_places = $result->fetch_assoc()["no_of_students"];

        $stmt = $this->conn->prepare("  SELECT  COUNT(*)
                                        FROM    `assigned_courses` 
                                        WHERE   `course_id` = ?");
        $stmt->bind_param("s", $course_id);
        $stmt->execute();
        $used_places = $stmt->get_result()->fetch_assoc()["COUNT(*)"];


        return $total_no_of_places - $used_places;
    }
    function getStudyCycles()
    {
        $stmt = $this->conn->prepare("  SELECT *
                                        FROM `study_cycles`");

        $stmt->execute();
        return $stmt->get_result();
    }
    function getProfessorsByCourse($course_id)
    {
        $stmt = $this->conn->prepare("  SELECT  * 
                                        FROM    `professors` 
                                        JOIN    `course_professors` ON `professors`.`professor_id` = `course_professors`.`professor_id` 
                                        WHERE   `course_id` = ?");
        $stmt->bind_param("s", $course_id);
        $stmt->execute();
        return $stmt->get_result();
    }
    function getProfessors()
    {
        $stmt = $this->conn->prepare("  SELECT  *
                                        FROM    `professors`
                                        ORDER BY `l_name`, `f_name` ASC");
        $stmt->execute();
        return $stmt->get_result();
    }
    function getById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM `courses` WHERE `course_id` = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        return $stmt->get_result();
    }
    function insert($name, $year, $package, $cycle, $link, $professor1, $professor2, $no_of_students)
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM `courses` WHERE `name` = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();

        $result = $stmt->get_result();
        if (array_values($result->fetch_assoc())[0] == 0) {
            $stmt = $this->conn->prepare("INSERT INTO `courses`(`course_id`, `name`, `year`, `package`, `study_cycle_id`, `link`, `no_of_students`) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $course_id = sha1(microtime(true) . mt_rand(10000, 90000));
            $stmt->bind_param("ssiissi", $course_id, $name, $year, $package, $cycle, $link, $no_of_students);
            $stmt->execute();

            $stmt = $this->conn->prepare("SELECT `course_id` FROM `courses` WHERE `name` = ? AND `year` = ? AND `package` = ?");
            $stmt->bind_param("sii", $name, $year, $package);
            $stmt->execute();

            $result = $stmt->get_result();
            $result = $result->fetch_assoc();

            $courseId = $result["course_id"];

            $stmt = $this->conn->prepare("INSERT INTO `course_professors`(`id`, `course_id`, `professor_id`) VALUES (?, ?, ?)");
            $id = sha1(microtime(true) . mt_rand(10000, 90000));
            $stmt->bind_param("sss", $id, $courseId, $professor1);
            $stmt->execute();

            $stmt = $this->conn->prepare("INSERT INTO `course_professors`(`id`, `course_id`, `professor_id`) VALUES (?, ?, ?)");
            $id = sha1(microtime(true) . mt_rand(10000, 90000));
            $stmt->bind_param("sss", $id, $courseId, $professor2);
            $stmt->execute();

            return $courseId;
        } else {
            return false;
        }
    }
    function insertProfessor($title, $l_name, $f_name)
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM `professors` WHERE `f_name` = ? AND `l_name` = ?");
        $stmt->bind_param("ss", $l_name, $f_name);
        $stmt->execute();

        $result = $stmt->get_result();
        if (array_values($result->fetch_assoc())[0] == 0) {
            $stmt = $this->conn->prepare("INSERT INTO `professors`(`f_name`, `l_name`, `title`) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $f_name, $l_name, $title);
            $stmt->execute();

            return true;
        } else {
            return false;
        }
    }
    function update($course_id, $name, $year, $package, $cycle, $link, $professor1, $professor2, $no_of_students)
    {
        $stmt = $this->conn->prepare("UPDATE `courses` SET `name` = ?, `year` = ?, `package` = ?, `study_cycle_id` = ?, `link` = ?, no_of_students = ? WHERE `course_id` = ?");
        $stmt->bind_param("siissis", $name, $year, $package, $cycle, $link, $no_of_students, $course_id);
        $stmt->execute();

        $stmt = $this->conn->prepare("  SELECT  `id` 
                                        FROM    `course_professors` 
                                        WHERE   `course_id` = ?");
        $stmt->bind_param("s", $course_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $professor_id_1 = $result->fetch_assoc();
        $professor_id_2 = $result->fetch_assoc();

        $stmt = $this->conn->prepare("UPDATE `course_professors` SET `professor_id` = ? WHERE `course_professors`.`id` = ?");
        $stmt->bind_param("ss", $professor1, $professor_id_1["id"]);
        $stmt->execute();

        if (!$professor_id_1["id"]) {
            $stmt = $this->conn->prepare("INSERT INTO `course_professors`(`id`, `course_id`, `professor_id`) VALUES (?, ?, ?)");
            $id = sha1(microtime(true) . mt_rand(10000, 90000));
            $stmt->bind_param("sss", $id, $course_id, $professor1);
            $stmt->execute();
        }

        $stmt = $this->conn->prepare("UPDATE `course_professors` SET `professor_id` = ? WHERE `course_professors`.`id` = ?");
        $stmt->bind_param("ss", $professor2, $professor_id_2["id"]);
        $stmt->execute();

        if (!$professor_id_2["id"]) {
            $stmt = $this->conn->prepare("INSERT INTO `course_professors`(`id`, `course_id`, `professor_id`) VALUES (?, ?, ?)");
            $id = sha1(microtime(true) . mt_rand(10000, 90000));
            $stmt->bind_param("sss", $id, $course_id, $professor2);
            $stmt->execute();
        }

        return $course_id;
    }
    function updateProfessor($id, $title, $l_name, $f_name)
    {
        $stmt = $this->conn->prepare("UPDATE `professors` SET `f_name` = ?, `l_name` = ?, `title` = ? WHERE `professor_id` = ?");
        $stmt->bind_param("sssi", $f_name, $l_name, $title, $id);
        $stmt->execute();

        return true;
    }
    function deleteProfessor($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM `professors` WHERE `professor_id` = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result();
    }
    function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM `courses` WHERE `course_id` = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result();
    }
    function getProfessorById($professorId)
    {
        $stmt = $this->conn->prepare("SELECT * FROM `professors` WHERE `professor_id` = ?");
        $stmt->bind_param("i", $professorId);
        $stmt->execute();
        return $stmt->get_result();
    }
    function studentHasCourseAssigned($course_id)
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM `assigned_courses` WHERE `student_id` = ? AND `course_id` = ? AND `status` = ?");
        $status = "Assigned";
        $stmt->bind_param("sss", $_SESSION["login_usr"], $course_id, $status);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc()["COUNT(*)"];
        if ($result > 0) {
            return true;
        } else {
            return false;
        }
    }
    function getPastCourses()
    {
        $stmt = $this->conn->prepare("SELECT * FROM `past_courses`");
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }
    function getPastCoursesForCourse($course)
    {
        $stmt = $this->conn->prepare("SELECT *  FROM    `assignation_dependencies` AS `a`
                                                JOIN    `past_courses` AS `p`
                                                ON      `a`.`past_course_id` = `p`.`past_course_id`
                                                WHERE   `a`.`course_id` = ?");
        $stmt->bind_param("s", $course);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }
    function getPastCourseGrade($course, $student)
    {
        $stmt = $this->conn->prepare("SELECT `grade` FROM `past_courses_grades` WHERE `past_course_id` = ? AND `student_id` = ?");
        $stmt->bind_param("ss", $course, $student);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }
    function insertAssignationDependency($course_id, $past_course_id)
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM `assignation_dependencies` WHERE `course_id` = ? AND `past_course_id` = ?");
        $stmt->bind_param("ss", $course_id, $past_course_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if (array_values($result->fetch_assoc())[0] == 0) {
            $stmt = $this->conn->prepare("INSERT    INTO `assignation_dependencies`(`dependency_id`,`course_id`,`past_course_id`)
            VALUES  (?, ?, ?)");
            $dependencyid = sha1(microtime(true) . mt_rand(10000, 90000));
            $stmt->bind_param("sss", $dependencyid, $course_id, $past_course_id);
            $stmt->execute();
            $result = $stmt->get_result();
        }

        return $result;
    }
    function deleteAssignationDependencies($course_id)
    {
        $stmt = $this->conn->prepare("DELETE FROM `assignation_dependencies` WHERE `course_id` = ?");
        $stmt->bind_param("s", $course_id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }
    function getAssignationList()
    {
        $stmt = $this->conn->prepare("  SELECT  `st`.`student_id`,   `ch`.`course_id`,  `co`.`year`, 
                                                `co`.`package`   ,   `ch`.`priority`,   `si`.`grade` AS `anual_grade`
                                        FROM    `students`      AS `st` 
                                        JOIN    `choices`       AS `ch`
                                        ON      `st`.`student_id`   = `ch`.`student_id`
                                        JOIN    `courses`       AS `co`
                                        ON      `co`.`course_id`    = `ch`.`course_id`
                                        JOIN    `students_info` AS `si` 
                                        ON      `si`.`student_id`   = `st`.`student_id`");
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }
    function getGradesOfRelevantPastCourses($course_id, $student_id) {
        $stmt = $this->conn->prepare("  SELECT  `grade` FROM `past_courses_grades` AS `pcg`
                                        JOIN    `assignation_dependencies` AS `asd`
                                        ON      `pcg`.`past_course_id` = `asd`.`past_course_id`
                                        WHERE   `pcg`.`student_id` = ?
                                        AND     `asd`.`course_id`  = ?");
        $stmt->bind_param("ss", $student_id, $course_id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }
}
