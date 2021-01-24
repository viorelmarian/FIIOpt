<?php
require_once "../shared/db_conn.php";
require_once "../libs/fpdf/fpdf.php";
require_once "../models/m_users.php";
require_once "../models/m_assignations.php";

class PDF extends FPDF
{
    var $widths;
    var $aligns;
    function Header($course_name, $title, $header, $widths, $align)
    {
        $this->SetFont('Times', '', 12);
        $this->Cell(190, 10, 'Facultatea de Informatica Iasi', 0, 0, 'L');
        $this->Ln(5);
        if (date('n') >= 1 && date('n') <= 3) {
            $y2 = date('Y');
            $y1 = $y2 - 1;
        } else {
            $y1 = date('Y');
            $y2 = $y1 + 1;
        }
        $this->Cell(190, 10, 'Anul Universitar ' . $y1 . '-' . $y2, 0, 0, 'L');

        $this->SetFont('Times', 'B', 15);
        $this->Ln(10);
        if ($this->CurOrientation == 'L') {
            $this->Cell(400, 10, $title, 0, 0, 'C');
        } else {
            $this->Cell(190, 10, $title, 0, 0, 'C');
        }
        $this->Ln(10);
        $this->Cell(190, 10, $course_name, 0, 0, 'C');
        // Line break
        $this->Ln(20);


        $this->SetFont('Times', 'B', 12);

        $this->SetWidths($widths);
        $this->SetAligns($align);
        $this->Row($header);
    }
    function SetWidths($w)
    {
        //Set the array of column widths
        $this->widths = $w;
    }

    function SetAligns($a)
    {
        //Set the array of column alignments
        $this->aligns = $a;
    }

    function Row($data)
    {
        //Calculate the height of the row
        $nb = 0;
        $i = 0;
        // var_dump($data);
        // die();
        foreach ($data as $data_line) {
            $nb = max($nb, $this->NbLines($this->widths[$i], $data_line));
            $i++;
        }
        $h = 5 * $nb;
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        //Draw the cells of the row
        $i = 0;
        foreach ($data as $data_line) {
            $w = $this->widths[$i];
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            $this->Rect($x, $y, $w, $h);
            //Print the text
            $this->MultiCell($w, 5, $data_line, 0, $a);
            //Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
            $i++;
        }
        //Go to the next line
        $this->Ln($h);
    }

    function CheckPageBreak($h)
    {
        //If the height h would cause an overflow, add a new page immediately
        if ($this->GetY() + $h > $this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }

    function NbLines($w, $txt)
    {
        //Computes the number of lines a MultiCell of width w will take
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 and $s[$nb - 1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ')
                $sep = $i;
            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                } else
                    $i = $sep + 1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else
                $i++;
        }
        return $nl;
    }

    function downloadPDFforCourse($course_id)
    {
        if (!isset($db)) {
            //Create db connection
            $db = new database_conn;
            $db->connect();
        }
        if (!isset($assignations)) {
            //Create model instance
            $assignations = new m_assignations($db->conn);
        }
        if (!isset($courses)) {
            //Create model instance
            $courses = new m_courses($db->conn);
        }
        $result = $assignations->getAssigneesForCourse($course_id);
        $lines = array();
        while ($row = $result->fetch_assoc()) {
            foreach ($row as $key => $value) {
                //Encode each value of the row in utf8
                $row[$key] = utf8_encode($value);
            }
            //Add rows to Array
            $lines[] = $row;
        }
        $display_line = array();
        $i = 0;
        $display = array();
        foreach ($lines as $line) {
            $i++;
            $display_line["nr_crt"] = $i;
            $display_line["student_name"] = $line['last_name'] . ' ' . $line['father_init'] . '. ' . $line['first_name'];
            $display_line["year"] = $line['year'];
            $display_line["class"] = $line['class'];
            $display[] = $display_line;
        }

        $course_info = $courses->getById($course_id);
        $course_name = $course_info->fetch_assoc()["name"];
        $title = 'Lista studentilor repartizati la disciplina optionala';
        $display_heading = array(
            'nr_crt' => 'Nr. Crt.',
            'student_name' => 'Nume si Prenume',
            'year' => 'An studii',
            'class' => 'Grupa'
        );
        $widths = array(20, 100, 35, 35);
        $align = array("C", "L", "C", "C");
        $this->AddPage('P', 'A4', 0, '"' . $course_name . '"', $title, $display_heading, $widths, $align);
        $this->AliasNbPages();
        $this->SetFont('Times', '', 12);
        $this->SetTitle("Optionale");
        $this->SetWidths(array(20, 100, 35, 35));
        $this->SetAligns(array("C", "L", "C", "C"));
        foreach ($display as $display_line) {
            $this->Row($display_line);
        }
        $this->Output();
    }

    function downloadPDFassignedCourses($year)
    {
        if (!isset($db)) {
            //Create db connection
            $db = new database_conn;
            $db->connect();
        }
        if (!isset($assignations)) {
            //Create model instance
            $assignations = new m_assignations($db->conn);
        }
        $result = $assignations->getAllAssignationsByYear($year);
        $data = array();
        foreach ($result as $line) {
            $data_line['nr_crt'] = $line['nr_crt'];
            $data_line['student_name'] = $line['student_name'];
            $data_line['year'] = $line['year'];
            $data_line['class'] = $line['class'];
            if (array_key_exists('course_1', $line)) {
                $data_line['course_1'] = $line['course_1'];
            } else {
                $data_line['course_1'] = '';
            }
            if (array_key_exists('course_2', $line)) {
                $data_line['course_2'] = $line['course_2'];
            } else {
                $data_line['course_2'] = '';
            }
            if (array_key_exists('course_3', $line)) {
                $data_line['course_3'] = $line['course_3'];
            } else {
                $data_line['course_3'] = '';
            }
            if (array_key_exists('course_4', $line)) {
                $data_line['course_4'] = $line['course_4'];
            } else {
                $data_line['course_4'] = '';
            }
            if (array_key_exists('course_5', $line)) {
                $data_line['course_5'] = $line['course_5'];
            } else {
                $data_line['course_5'] = '';
            }
            $data[] = $data_line;
        }
        $display_heading = array(
            'nr_crt' => 'Nr. Crt.',
            'student_name' => 'Nume si Prenume',
            'year' => 'An studii',
            'class' => 'Grupa',
            'course_1' => 'Optional 1',
            'course_2' => 'Optional 2',
            'course_3' => 'Optional 3',
            'course_4' => 'Optional 4',
            'course_5' => 'Optional 5'
        );

        $title = 'Lista studentilor repartizati la disciplinele optionale';
        $widths = array(20, 50, 20, 20, 58, 58, 58, 58, 58);
        $align = array("C", "C", "C", "C", "L", "L", "L", "L", "L");
        $this->AddPage('L', 'A3', 0, '', $title, $display_heading, $widths, $align);
        $this->AliasNbPages();
        $this->SetFont('Times', '', 12);
        $this->SetTitle("Optionale");
        $this->SetWidths(array(20, 50, 20, 20, 58, 58, 58, 58, 58));
        $this->SetAligns(array("C", "L", "C", "C", "L", "L", "L", "L", "L"));
        foreach ($data as $display_line) {
            $this->Row($display_line);
        }
        $this->Output();
    }
}
