<?php
require_once "../shared/db_conn.php";
require_once "../libs/fpdf/fpdf.php";
require_once "../models/m_users.php";
require_once "../models/m_assignations.php";

class PDF extends FPDF {
    var $widths;
    var $aligns;
    function Header() {
        // Arial bold 15
        $this->SetFont('Arial','B',15);
        // Move to the right
        $this->Cell(70);
        // Title
        $this->Cell(30,10,'Optionale anul 3','C');
        // Line break
        $this->Ln(20);
    } 
    function SetWidths($w) {
        //Set the array of column widths
        $this->widths=$w;
    }

    function SetAligns($a) {
        //Set the array of column alignments
        $this->aligns=$a;
    }

    function Row($data) {
        //Calculate the height of the row
        $nb=0;
        $i = 0;
        foreach ($data as $data_line) {
            $nb=max($nb,$this->NbLines($this->widths[$i],$data_line));
            $i++;
        }
        $h=5*$nb;
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        //Draw the cells of the row
        $i = 0;
        foreach ($data as $data_line) {
            $w=$this->widths[$i];
            $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            //Save the current position
            $x=$this->GetX();
            $y=$this->GetY();
            //Draw the border
            $this->Rect($x,$y,$w,$h);
            //Print the text
            $this->MultiCell($w,5,$data_line,0,$a);
            //Put the position to the right of the cell
            $this->SetXY($x+$w,$y);
            $i++;
        }
        //Go to the next line
        $this->Ln($h);
    }

    function CheckPageBreak($h) {
        //If the height h would cause an overflow, add a new page immediately
        if($this->GetY()+$h>$this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }

    function NbLines($w,$txt) {
        //Computes the number of lines a MultiCell of width w will take
        $cw=&$this->CurrentFont['cw'];
        if($w==0)
            $w=$this->w-$this->rMargin-$this->x;
        $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
        $s=str_replace("\r",'',$txt);
        $nb=strlen($s);
        if($nb>0 and $s[$nb-1]=="\n")
            $nb--;
        $sep=-1;
        $i=0;
        $j=0;
        $l=0;
        $nl=1;
        while($i<$nb)
        {
            $c=$s[$i];
            if($c=="\n")
            {
                $i++;
                $sep=-1;
                $j=$i;
                $l=0;
                $nl++;
                continue;
            }
            if($c==' ')
                $sep=$i;
            $l+=$cw[$c];
            if($l>$wmax)
            {
                if($sep==-1)
                {
                    if($i==$j)
                        $i++;
                }
                else
                    $i=$sep+1;
                $sep=-1;
                $j=$i;
                $l=0;
                $nl++;
            }
            else
                $i++;
        }
        return $nl;
    }

    function download_pdf() {
        if(!isset($db)) {
            //Create db connection
            $db = new database_conn;
            $db->connect();
        }
        if (!isset($assignations)) {
            //Create model instance
            $assignations = new m_assignations($db->conn);
        }
        if (!isset($students)) {
            //Create model instance
            $students = new m_users($db->conn);
        }
        $result = $students->getAllStudentsIds();
        while($row = $result->fetch_assoc()) {
            foreach($row as $key => $value) {
                //Encode each value of the row in utf8
                $row[$key] = utf8_encode($value);
            }
            //Add rows to Array
            $students_ids[] = $row;
        }
        $display_line = array();
        foreach ($students_ids as $students_id) {
            $result = $students->getById($students_id["student_id"]);
            $username = $result->fetch_assoc()["username"];
            
            $userCompleteName = explode('.', $username);
            $userCompleteName = ucfirst($userCompleteName[0]) . " " . ucfirst($userCompleteName[1]);
            $display_line["studentName"] = $userCompleteName;

            $result = $assignations->getAssignationsForStudent($students_id["student_id"]);
            $i = 0;
            while($row = $result->fetch_assoc()) {
                $i++;
                foreach($row as $key => $value) {
                    //Encode each value of the row in utf8
                    $row[$key] = utf8_encode($value);
                }
                //Add rows to Array
                $display_line["course_" . $i] = $row["name"];
            }
            $display[] = $display_line;
        }
        
        $display_heading = array(   'studentName'=>'Student', 
                                    'course_1'=> 'Optional 1', 
                                    'course_2'=> 'Optional 2',
                                    'course_3'=> 'Optional 3',
                                    'course_4'=> 'Optional 4',
                                    'course_5'=> 'Optional 5',
                                );

        
                                
        $this->AddPage();
        $this->AliasNbPages();
        $this->SetFont('Times','',10);
        $this->SetTitle("Optionale");
        $this->SetWidths(array(30,30,30,30,30,30));
        $this->SetAligns(array("C","C","C","C","C","C"));
        $this->Row($display_heading);
        foreach ($display as $display_line) {
            $this->Row($display_line);
        }
        $this->Output();
    }
}