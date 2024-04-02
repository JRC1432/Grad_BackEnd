<?php
require("datacon.php");
require('vendor/tecnickcom/tcpdf/tcpdf.php');

if(isset($_GET['printPDF'])){

// 
$scholarids = $_POST["editScholarID"];
// $scholarids = 5602;

    $stnt = $pdo->prepare("SELECT * FROM g_scholar_profile WHERE id = ?");
    $stnt->execute([$scholarids]);

    $Astnt = $pdo->prepare("SELECT * FROM g_scholar_address WHERE scholar_id = ?");
    $Astnt->execute([$scholarids]);
    $Sstnt = $pdo->prepare("SELECT * FROM g_scholar_current_school WHERE scholar_id = ?");
    $Sstnt->execute([$scholarids]);

   

    while ($row = $stnt->fetch(PDO::FETCH_ASSOC)){
         $spas = $row['spas_id'];
         $lname = $row['lname'];
         $fname = $row['fname'];
         $mname = $row['mname'];
         $sname = $row['suffix'];
         $mail = $row['email'];
         $gender = $row['sex'];
         $frststats = $row['school_grad_status'];
         $scndstats = $row['sub_status'];
         $contact = $row['contact'];
         $birth = $row['birthday'];
         
}

while ($row = $Astnt->fetch(PDO::FETCH_ASSOC)){
         
         $street = $row['street'];
         $town = $row['town'];
         $province = $row['province'];
         $region = $row['h_region'];
         $district = $row['district'];
         $zip = $row['zipcode'];
         $brgy = $row['barangay'];
         $subd = $row['subdivision'];
         $hnum = $row['house_number'];
               
}

while ($row = $Sstnt->fetch(PDO::FETCH_ASSOC)){
         
         $c_school = $row['current_school'];
         $c_course = $row['current_course'];
         $entry = $row['entry_level'];
         $yr = $row['year'];
         $batch = $row['batch'];
               
}





// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' ', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
$pdf->setFooterData(array(0,64,0), array(0,64,128));

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));


// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

$pdf->AddPage();
$pdf->SetFont('dejavusans', '', 20, '', true);
$pdf->Cell(0,22,'Scholar Profile Information',0,1,'C','',0,'',false,'M','M');



$pdf->SetFont('times', 'BI', 12);
$pdf->ln(1);
$pdf->Cell(180,15,'Date : '. date(" n / j / y"), 0, 1, 'R', 0, '', 0, false, 'M', 'M');

$pdf->ln(3);
$pdf->Cell(90,10,'SPAS - ID : '.$spas, 0, 0, 'L', 0, '', 0, false, 'M', 'M');
$pdf->Cell(90,10,'Status : '.$frststats.' - '.$scndstats, 0, 1, 'R', 0, '', 0, false, 'M', 'M');

$pdf->SetFont('dejavusans', '', 12, '', true);
$pdf->ln(6);
$pdf->Cell(90,10,'Last Name : '.$lname, 0, 1, 'L', 0, '', 0, false, 'M', 'M');
$pdf->Cell(90,10,'First Name : '.$lname, 0, 1, 'L', 0, '', 0, false, 'M', 'M');
$pdf->Cell(90,10,'Middle Name : '.$mname, 0, 1, 'L', 0, '', 0, false, 'M', 'M');
$pdf->Cell(90,10,'Suffix Name : '.$sname, 0, 1, 'L', 0, '', 0, false, 'M', 'M');
$pdf->Cell(90,10,'Birth Date : '.$birth, 0, 1, 'L', 0, '', 0, false, 'M', 'M');
$pdf->Cell(90,10,'Sex : '.$gender, 0, 1, 'L', 0, '', 0, false, 'M', 'M');



// Line Spacer
$pdf->Line(10,95,200,95);
$pdf->Line(10,97,200,97);


$pdf->SetFont('dejavusans', '', 15, '', true);
$pdf->ln(12);

$pdf->Cell(0,22,'Contact Information',0,1,'C','',0,'',false,'M','M');
$pdf->SetFont('dejavusans', '', 12, '', true);

$pdf->Cell(90,10,'E-mail : '.$mail, 0, 1, 'L', 0, '', 0, false, 'M', 'M');
$pdf->Cell(90,10,'Contact No : '.$contact, 0, 1, 'L', 0, '', 0, false, 'M', 'M');
$pdf->Cell(90,10,'Block Lot/House No: '.$hnum, 0, 1, 'L', 0, '', 0, false, 'M', 'M');
$pdf->Cell(90,10,'Compound/Street/Phase/Purok: '.$street, 0, 1, 'L', 0, '', 0, false, 'M', 'M');
$pdf->Cell(90,10,'Subdivision/Village: '.$subd, 0, 1, 'L', 0, '', 0, false, 'M', 'M');
$pdf->Cell(90,10,'Barangay: '.$brgy, 0, 1, 'L', 0, '', 0, false, 'M', 'M');

$pdf->Cell(90,10,'Zip Code: '.$zip, 0, 1, 'L', 0, '', 0, false, 'M', 'M');
$pdf->Cell(90,10,'Distrcit: '.$district, 0, 1, 'L', 0, '', 0, false, 'M', 'M');
$pdf->Cell(90,10,'Region : '.$region, 0, 1, 'L', 0, '', 0, false, 'M', 'M');
$pdf->Cell(90,10,'Municipality: '.$town, 0, 1, 'L', 0, '', 0, false, 'M', 'M');
$pdf->Cell(90,10,'Province: '.$province, 0, 1, 'L', 0, '', 0, false, 'M', 'M');










// $pdf->Cell(90,10,'E-mail : '.$mail, 0, 1, 'L', 0, '', 0, false, 'M', 'M');

// $pdf->Cell(90,10,'Contact No : '.$contact, 0, 1, 'L', 0, '', 0, false, 'M', 'M');

// $pdf->Cell(90,10,'Block Lot/House No: '.$hnum, 0, 0, 'L', 0, '', 0, false, 'M', 'M');

// $pdf->Cell(90,10,'Compound/Street/Phase/Purok: '.$street, 0, 1, 'L', 0, '', 0, false, 'M', 'M');

// $pdf->Cell(90,10,'Subdivision/Village: '.$subd, 0, 0, 'L', 0, '', 0, false, 'M', 'M');

// $pdf->Cell(90,10,'Barangay: '.$brgy, 0, 1, 'L', 0, '', 0, false, 'M', 'M');

// $pdf->Cell(90,10,'Zip Code: '.$zip, 0, 0, 'L', 0, '', 0, false, 'M', 'M');
// $pdf->Cell(90,10,'Distrcit: '.$district, 0, 1, 'L', 0, '', 0, false, 'M', 'M');
// $pdf->Cell(90,10,'Region : '.$region, 0, 0, 'L', 0, '', 0, false, 'M', 'M');
// $pdf->Cell(90,10,'Municipality: '.$town, 0, 1, 'L', 0, '', 0, false, 'M', 'M');
// $pdf->Cell(90,10,'Province: '.$province, 0, 0, 'L', 0, '', 0, false, 'M', 'M');


// Line Spacer
$pdf->Line(10,170,200,170);
$pdf->Line(10,172,200,172);


$pdf->SetFont('dejavusans', '', 15, '', true);
$pdf->ln(16);
$pdf->Cell(0,22,'School Information',0,1,'C','',0,'',false,'M','M');
$pdf->SetFont('dejavusans', '', 12, '', true);
$pdf->Cell(90,10,'School : '.$c_school, 0, 1, 'L', 0, '', 0, false, 'M', 'M');
$pdf->Cell(90,10,'Course : '.$c_course, 0, 1, 'L', 0, '', 0, false, 'M', 'M');
$pdf->Cell(90,10,'Entry Type : '.$entry, 0, 1, 'L', 0, '', 0, false, 'M', 'M');
$pdf->Cell(90,10,'Year of Award : '.$yr, 0, 1, 'L', 0, '', 0, false, 'M', 'M');
$pdf->Cell(90,10,'Batch : '.$batch, 0, 1, 'L', 0, '', 0, false, 'M', 'M');


$pdf->Output(__DIR__ .'/test.pdf','I');
    

     }



// Update User

if(isset($_GET['updateuser'])){

$upfname = $_POST["upfirstname"];
$uplname = $_POST["uplastname"];
$upusername = $_POST["upusername"];
$uppassword = $_POST["uppassword"];
$passwordsHash = sha1("digi".$uppassword."digi");
$acclevel = $_POST["upacclevel"];
$upid = $_POST["userid"];


$stnt = $pdo->prepare("UPDATE users SET username = ?, password = ?, access_level = ?, fname = ?, lname = ?
WHERE id = ?");
$stnt -> execute([$upusername,$passwordsHash,$acclevel,$upfname,$uplname,$upid]);

 if($stnt){
        $result =  true;
    } else{
        
        $result = false;
    }

    echo json_encode($result);

}



// Update Scholar information

if(isset($_GET['updateScholarinfo'])){

    $scholarids = $_POST["editScholarID"];

    $upspasid = $_POST["upspasid"];
    $uplastname = $_POST["uplastname"];
    $upfirstname = $_POST["upfirstname"];
    $upmidname  = $_POST["upmidname"];

    $upsuffixname  = $_POST["upsuffixname"];
    $mail = $_POST["upemailadd"];
    $upgender = $_POST["upgender"];
    $upstats = $_POST["upstats"];
    $contact = $_POST["upcontact"];
    $upbirthdate = $_POST["upbirthdate"];
    $upsubstats = $_POST["upsubstats"];
    $alterEmail = $_POST["upAlternateEmail"];
   

    $stnt = $pdo->prepare("UPDATE g_scholar_profile SET spas_id = ?, lname = ?, fname = ?, mname = ?, suffix = ?, email = ?, sex = ?, school_grad_status = ?, contact = ?, birthday = ?, sub_status = ?, alternate_email = ? WHERE id = ?");
$stnt -> execute([$upspasid,$uplastname,$upfirstname,$upmidname,$upsuffixname,$mail,$upgender,$upstats,$contact,$upbirthdate,$upsubstats,$alterEmail,$scholarids]);


if($stnt){
        $result =  true;
    } else{
        
        $result = false;
    }

    echo json_encode($result);



}




// Update Scholar Address

if(isset($_GET['updatescholarAddress'])){

$scholarids = $_POST["editScholarID"];
$upemailadd = $_POST["upemailadd"];
$upcontact = $_POST["upcontact"];

$upprovince = $_POST["upprovince"];
$uphousenum = $_POST["uphousenum"];
$upstreet = $_POST["upstreet"];
$upsubdivision = $_POST["upsubdivision"];
$upbarangay = $_POST["upbarangay"];
$updistrict = $_POST["updistrict"];
$upregion = $_POST["upregion"];
$upmunicipality = $_POST["upmunicipality"];
$upprovincecity = $_POST["upprovincecity"];



$stnt = $pdo->prepare("UPDATE g_scholar_profile SET email = ?, contact = ? WHERE id = ?");
$stnt -> execute([$upemailadd,$upcontact,$scholarids]);

$Addstnt = $pdo->prepare("UPDATE g_scholar_address SET street = ?, town = ?, province = ?, zipcode = ?, district = ?, h_region = ?, house_number = ?, subdivision = ?, barangay = ?
WHERE scholar_id = ?");
$Addstnt -> execute([$upstreet,$upmunicipality,$upprovincecity,$upprovince,$updistrict,$upregion,$uphousenum,$upsubdivision,$upbarangay,$scholarids]);


 if($stnt && $Addstnt){
        $result =  true;
    } else{
        
        $result = false;
    }

    echo json_encode($result);

}



// Update Scholar School


if(isset($_GET['updateScholarschool'])){

    $scholarids = $_POST["editScholarID"];
    $upcourse = $_POST["upcourse"];
    $upschool = $_POST["upschool"];
    $scProgram = $_POST["upscprog"];
    $unit = $_POST["upunits"];
    

    $Prevstnt = $pdo->prepare("UPDATE g_scholar_current_school SET current_school = ?, current_course = ?, scholarship_program = ?, units = ?  WHERE scholar_id = ?");
    $Prevstnt -> execute([$upschool,$upcourse,$scProgram,$unit,$scholarids]);


if($Prevstnt){
        $result =  true;
    } else{
        
        $result = false;
    }

    echo json_encode($result);

}

// Graduate School


if(isset($_GET['updateGradScholarschool'])){

    $scholarids = $_POST["editScholarID"];
    $upgradcourse = $_POST["upgradcourse"];
    $upgradschool = $_POST["upgradschool"];
    $upschoolregion = $_POST["upschoolregion"];
    

    $Prevstnt = $pdo->prepare("UPDATE g_scholar_grad_school SET grad_course = ?, grad_school = ?, region = ? WHERE scholar_id = ?");
    $Prevstnt -> execute([$upgradcourse,$upgradschool,$upschoolregion,$scholarids]);


if($Prevstnt){
        $result =  true;
    } else{
        
        $result = false;
    }

    echo json_encode($result);

}





// Edit Scholarship 


if(isset($_GET['updateScholarshipInfo'])){

    $scholarids = $_POST["editScholarID"];
    $entry = $_POST["upentry"];
    $year = $_POST["upyraward"];
    $batch  = $_POST["upbatch"];


    $stnt = $pdo->prepare("UPDATE g_scholar_current_school SET entry_level = ?, year = ?, batch = ? WHERE scholar_id = ?");
    $stnt -> execute([$entry,$year,$batch,$scholarids]);


if($stnt){
        $result =  true;
    } else{
        
        $result = false;
    }

    echo json_encode($result);


}

if(isset($_GET['updateScholarshipInfo2'])){

    $scholarids = $_POST["editScholarID"];
    $grant = $_POST["upgrant"];
    $level = $_POST["uplevel"];
    $comp = $_POST["upcomp"];
    $scholarprog = $_POST["upscholarprog"];
    $council = $_POST["upcouncil"];
    $upavailment = $_POST["upavailment"];
    $sem = $_POST["upsem"];
    $ay = $_POST["upay"];
    $status = $_POST["upstatus"];



   $stnt = $pdo->prepare("UPDATE g_scholar_grad_school SET grant_test = ?, level = ?, comp = ?, grad_program = ?, council = ?, availment = ?, status = ?, sem = ?, award_year = ? WHERE scholar_id = ?");
     $stnt -> execute([$grant,$level,$comp,$scholarprog,$council,$upavailment,$status,$sem,$ay,$scholarids]);


if($stnt){
       $result =  true;
    } else{
        
        $result = false;
     }
    echo json_encode($result);


}



if(isset($_GET['updateScholarshipInfo3'])){

$scholarids = $_POST["editScholarID"];

$duration = $_POST["upduration"];
$remarks = $_POST["upremarks"];
$field = $_POST["upfield"];
$servob = $_POST["upobligation"];
$title = $_POST["uptitle"];
$scholartype = $_POST["upscholartype"];
$grad_date = $_POST["upgraddate"];
$honors = $_POST["uphonors"];
$dissemination = $_POST["updissemination"];
$research = $_POST["upresearch"];



    $stnt = $pdo->prepare("UPDATE g_scholar_thesis SET duration = ?, remarks = ?, field = ?, servob = ?, title = ?, school_type = ?, grad_date = ?, honors = ?, dissemination = ?, research = ?  WHERE scholar_id = ?");
    $stnt -> execute([$duration,$remarks,$field,$servob,$title,$scholartype,$grad_date,$honors,$dissemination,$research,$scholarids]);


if($stnt){
        $result =  true;
    } else{
        
        $result = false;
    }

    echo json_encode($result);

}




// Profile Pictures Update


if(isset($_GET['updatePic'])){


        $authid = $_POST["authid"];
         $uname = $_POST["username"];
    
// pic
$picture = $_FILES['pic']['name'];
$path = 'upload/'.$authid.$uname;
     $allowed_extensions = array('jpg','png','jpeg');
      $extension = pathinfo($picture, PATHINFO_EXTENSION);
       if(in_array(strtolower($extension),$allowed_extensions) ) {

        if(!file_exists($path)){

            mkdir($path, 0775, true);
            }

            $temp_file = $_FILES['pic']['tmp_name'];

            if($temp_file !=""){
                $newpath = $path."/pic.".$extension;

                if(move_uploaded_file($temp_file,$newpath)){
                    echo "";
                } else{
                    $newpath = "NO_IMAGE";
                    // echo "failed";
                }
            }

    }else{
        $newpath = "NO_IMAGE";
        // echo "Failed";
    } 



$Picstnt = $pdo->prepare("UPDATE users SET pic = ? WHERE id = ?");
$Picstnt->execute([$newpath,$authid]);




if($Picstnt){
        $result =  true;
    } else{
        
        $result = false;
    }

    echo json_encode($result);

}





// Update School Region 


if(isset($_GET['updateSchoolCourse'])){


    $schoolid = $_POST["ids"];
    $schoolname = $_POST["schoolname"];
    $upschoolregion = $_POST["upschoolregion"];
  


    $Prevstnt = $pdo->prepare("UPDATE grad_school SET school_name = ?, school_region = ? WHERE id = ?");
    $Prevstnt -> execute([$schoolname,$upschoolregion,$schoolid]);


if($Prevstnt){
        $result =  true;
    } else{
        
        $result = false;
    }

    echo json_encode($result);

}

// Update Date and Sem


if(isset($_GET['updateDateSem'])){


    $scholarids = $_POST["editScholarID"];
    $upfsemone = $_POST["upfsemone"];
    $upfstartdate = $_POST["upfstartdate"];
    $upfsemtwo = $_POST["upfsemtwo"];
    $upfenddate = $_POST["upfenddate"];

    $uplsemone = $_POST["uplsemone"];
    $uplstartdate = $_POST["uplstartdate"];
    $uplsemtwo = $_POST["uplsemtwo"];
    $uplenddate = $_POST["uplenddate"];

   


    if ($upfstartdate == "" && $upfenddate == "") {
        
        $upfstartdate = NUll;
        $upfenddate = NUll;
    } else {
        
        $upfstartdate = $_POST["upfstartdate"];
        $upfenddate = $_POST["upfenddate"];

    }




    $Prevstnt = $pdo->prepare("UPDATE g_scholar_dates SET foreign_sem = ?, foreign_startdate = ?, foreign_endsem = ?, foreign_enddate = ?, local_sem = ?, local_startdate = ?, local_endsem = ?, local_enddate = ? WHERE scholar_id = ?");
    $Prevstnt -> execute([$upfsemone,$upfstartdate,$upfsemtwo,$upfenddate,$uplsemone,$uplstartdate,$uplsemtwo,$uplenddate,$scholarids]);


if($Prevstnt){
        $result =  true;
    } else{
        
        $result = false;
    }

    echo json_encode($result);

}


// UPdate Log Stats

if(isset($_GET['logReadStats'])){

   $logids = $_POST["logids"];
   $newLogs = 'read';



   $stnt = $pdo->prepare("UPDATE g_scholar_console_log SET read_stats = ? WHERE id = ?");
     $stnt -> execute([$newLogs,$logids]);


if($stnt){
       $result =  true;
    } else{
        
        $result = false;
     }
    echo json_encode($result);


}



?>