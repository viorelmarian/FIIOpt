<?php
require_once "../shared/db_conn.php";
require_once "../libs/fpdf/fpdf.php";
require_once "../models/m_users.php";
require_once "../models/m_assignations.php";

class PDF extends FPDF
{
    var $widths;
    var $aligns;
    function Header($course_name)
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
        $this->Cell(190, 10, 'Lista studentilor repartizati la disciplina optionala', 0, 0, 'C');
        $this->Ln(10);
        $this->Cell(190, 10, $course_name, 0, 0, 'C');
        // Line break
        $this->Ln(20);


        $this->SetFont('Times', 'B', 12);
        $display_heading = array(
            'nr_crt' => 'Nr. Crt.',
            'student_name' => 'Nume si Prenume',
            'year' => 'An studii',
            'class' => 'Grupa'
        );
        $this->SetWidths(array(20, 100, 35, 35));
        $this->SetAligns(array("C", "L", "C", "C"));
        $this->Row($display_heading);
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
        foreach ($lines as $line) {
            $i++;
            $display_line["nr_crt"] = $i;
            $display_line["student_name"] = $line['last_name'] . ' ' . $line['father_init'] . '. ' . $line['first_name'];
            $display_line["year"] = $line['year'];
            $display_line["class"] = $line['class'];
            $display[] = $display_line;
        }
        // $a = 1;
        // while ($a <= 100) {
        //     $a++;
        //     $display[] = $display_line;
        // }

        $course_info = $courses->getById($course_id);
        $course_name = $course_info->fetch_assoc()["name"];
        $this->AddPage('','',0, '"'.$course_name.'"');
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
}
