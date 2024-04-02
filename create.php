<?php
require("datacon.php");

// User Creation

if(isset($_GET['createuser'])){


    date_default_timezone_set('Asia/Manila');

    $fname = $_POST["firstname"];
    $lname = $_POST["lastname"];
    $uname = $_POST["username"];
    $ualvl = $_POST["acclevel"];
    $passwords = $_POST["password"];
    $passwordsHash = sha1("digi".$passwords."digi");
    $addby = $_POST["creator"];
    $date = date("Y-m-d h:i:s a");


    $authid = $_POST["authid"];
    


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


    $stnt = $pdo->prepare("INSERT INTO users(username,password,access_level,addedby,addedon,fname,lname,pic) VALUES (?,?,?,?,?,?,?,?)");
    $params = array($uname,$passwordsHash,$ualvl,$addby,$date,$fname,$lname,$newpath);
    $stnt -> execute($params);

    if($stnt){
        $result =  true;
    } else{

        $result = false;
    }

    echo json_encode($result);

} 


// Scholar Creation

if (isset($_GET['createScholar'])){

    //Personal Information



    /*$scholardata = json_decode($_POST['scholarinfo'], true);*/

    $creator = $_POST["usercreator"];
    $spasLvl = $_POST["spasLvl"];
    $spasYear = $_POST["spasYear"];
    $spasRegion = $_POST["spasRegion"];
    $spasAy = $_POST["spasAy"];
    $lname = $_POST["lastname"];
    $strlname = strtoupper($lname);
    $fname = $_POST["firstname"];
    $strfname = strtoupper($fname);
    $mname = $_POST["midname"];
    $strmname = strtoupper($mname);
    $sname = $_POST["suffixname"];
    $strsname = strtoupper($sname);
    $emailAdd = $_POST["emailadd"];
    $alternateEmail = $_POST["alternateEmail"];
    $birth = $_POST["birthdate"];

    $contactnum = $_POST["contact"];
    $cleanNumber = preg_replace('/[^0-9]/', '', $contactnum);

    $sex = $_POST["gender"];
    $date = date("Y-m-d h:i:s a");

    // Address Info

    $provid = $_POST["province"]; 
    $house = $_POST["housenum"];
    $strt = $_POST["street"];  
    $mun = $_POST["municipality"]; 
    $brgy = $_POST["barangay"];
    $subd = $_POST["subdivision"];
    $provcity = $_POST["provincecity"]; 
    $districts = $_POST["district"]; 
    $region = $_POST["region"]; 

    //School Info

    $courses = $_POST["course"];
    $schools = $_POST["school"];
    $units = $_POST["units"];
    $scprog = $_POST["scprog"];
      
    
    // Scholar Information

    $scholarprog = $_POST["scholarprog"];
    $entries = $_POST["entry"];
    $ay = $_POST["ay"];
    $batch = $_POST["batch"];
    $grant = $_POST["grant"];
    $level = $_POST["level"];
    $comp = $_POST["comp"];
    

    //School

    $council = $_POST["council"];
    $gradschool = $_POST["gradschool"];
    $schoolregion = $_POST["schoolregion"];
    $gradcourse = $_POST["gradcourse"];
    $field = $_POST["field"];
    $duration = $_POST["duration"];

    $fsemone = $_POST["fsemone"];
    $fstartdate = $_POST["fstartdate"];
    $fsemtwo = $_POST["fsemtwo"];
    $fenddate = $_POST["fenddate"];

    $lsemone = $_POST["lsemone"];
    $lstartdate = $_POST["lstartdate"];
    $lsemtwo = $_POST["lsemtwo"];
    $lenddate = $_POST["lenddate"];


    // Other Scholarship Program

    $availment = $_POST["availment"];
    $stats = $_POST["status"];
    $sem = $_POST["sem"];
    $yraward = $_POST["yraward"];
    $remarks = $_POST["remarks"];
    $obligation = $_POST["obligation"];
    $title = $_POST["title"];
    $scholartype = $_POST["scholartype"];
    $graddate = $_POST["graddate"];
    $honors = $_POST["honors"];
    $dissemination = $_POST["dissemination"];
    $research = $_POST["research"];

    // Modified Variables

    $spas = $spasLvl.$spasYear.$spasRegion.$spasAy;
    // $mail = $emailAdd.'; '.$alternateEmail;
    $mail = $emailAdd;


    // Conditional Statement


    if ($fstartdate == "" && $fenddate == "") {
        
        $fstartdate = NUll;
        $fenddate = NUll;
    } else {
        
        $fstartdate = $_POST["fstartdate"];
        $fenddate = $_POST["fenddate"];

    }



    $pdo->beginTransaction();
    $stnt = $pdo->prepare("INSERT INTO g_scholar_profile(spas_id,lname,fname,mname,suffix,email,birthday,sex,contact,added_by,added_on,school_grad_status,alternate_email) VALUES (?,?,?,?,?,?,?,?,?,?,?,'Ongoing',?) RETURNING id");

    $stntAdress = $pdo->prepare("INSERT INTO g_scholar_address(scholar_id,street,town,province,zipcode,district,h_region,house_number,subdivision,barangay) VALUES (?,?,?,?,?,?,?,?,?,?)");

    $stntSchool = $pdo->prepare("INSERT INTO g_scholar_current_school(scholar_id,current_school,current_course,entry_level,year,batch,scholarship_program,units) VALUES (?,?,?,?,?,?,?,?)");

    $stntthesis = $pdo->prepare("INSERT INTO g_scholar_thesis(scholar_id,duration,remarks,field,servob,school_type,title,grad_date,dissemination,research,honors,added_on) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");

    $stntgradshool = $pdo->prepare("INSERT INTO g_scholar_grad_school(scholar_id,grad_course,grad_school,council,grad_program,region,grant_test,level,comp,award_year,availment,sem,status) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,'NEW')");

    $stntdates = $pdo->prepare("INSERT INTO g_scholar_dates(scholar_id,foreign_sem,foreign_startdate,foreign_endsem,foreign_enddate,local_sem,local_startdate,local_endsem,local_enddate,added_on) VALUES (?,?,?,?,?,?,?,?,?,?)");


    $params = array($spas,$strlname,$strfname,$strmname,$strsname,$mail,$birth,$sex,$cleanNumber,$creator,$date,$alternateEmail);
    $stnt -> execute($params);

    if($stnt){
        $errors[] =  true;
    } else{

        $errors[] = false;
    }

    $sid = "";
    try{

        $result = $stnt->fetch();
        $sid = $result["id"];
    }catch(Exception $e){
        echo $e;
    }


    $aparams = array($sid,$strt,$mun,$provcity,$provid,$districts,$region,$house,$subd,$brgy);
    $stntAdress -> execute($aparams);
    if($stntAdress){
        $errors[] =  true;
    } else{

        $errors[] = false;
    }


    $scparams = array($sid,$schools,$courses,$entries,$yraward,$batch,$scprog,$units);
    $stntSchool -> execute($scparams);
    if($stntSchool){
        $errors[] =  true;
    } else{

        $errors[] = false;
    }

     $thparams = array($sid,$duration,$remarks,$field,$obligation,$scholartype,$title,$graddate,$dissemination,$research,$honors,$date);
    $stntthesis -> execute($thparams);
    if($stntthesis){
        $errors[] =  true;
    } else{

        $errors[] = false;
    }


    $gparams = array($sid,$gradcourse,$gradschool,$council,$scholarprog,$schoolregion,$grant,$level,$comp,$ay,$availment,$sem);
    $stntgradshool -> execute($gparams);
    if($stntgradshool){
        $errors[] =  true;
    } else{

        $errors[] = false;
    }



    $dateparams = array($sid,$fsemone,$fstartdate,$fsemtwo,$fenddate,$lsemone,$lstartdate,$lsemtwo,$lenddate,$date);
    $stntdates -> execute($dateparams);
    if($stntdates){
        $errors[] = true;
    }else{
        $errors[] = false;
    }


    if(in_array(false, $errors)){
     echo "false";
     $pdo->rollback();
 } else{
    echo "true";
    $pdo->commit();
}

}


// Documents Insert


if(isset($_GET['insertDocx'])){

    date_default_timezone_set('Asia/Manila');
    $spasid = $_POST["spasid"];
    $scholarLname = $_POST["scholarLastname"];
    $filedesc = $_POST["filedesc"];
    $file_type = $_POST["filetype"];
    $filetypeid = $_POST["filetypeid"];
    $authname = $_POST["username"];
    $scholarids = $_POST["scholarids"];
    $date = date("Y-m-d h:i:s a");

    $targetDir = 'upload/';
    $filename = $_FILES['files']['name'];

// File upload

    $data = array();
    try
    {

        $count=$pdo->prepare("SELECT (count(*)+1)  as counted from g_scholar_documents WHERE spas_id = '$scholarids'");
        $count->execute();


    }catch (Exception $ex){
        die("Failed to run query". $ex);

    }

    http_response_code(200);

    while ($row = $count->fetch(PDO::FETCH_ASSOC)){
        $data = $row;
    }


    $extexplode=explode('.',$filename);
    $extend=end($extexplode);
    $ext=strtolower($extend);


    $newFilename = $scholarids."_".$filetypeid."_".$data["counted"].".".$ext;
    $targetFile = $targetDir . $newFilename;

    move_uploaded_file($_FILES['files']['tmp_name'], $targetFile);

    $file = fopen($targetFile, 'rb');

    $content = fread($file, filesize($targetFile));

    fclose($file);

    // Search for the /Count key in the PDF structure

    $countPosition = strpos($content, '/Count');



    $countStartPosition = $countPosition + 7;

    $countEndPosition = strpos($content, ' ', $countStartPosition);

    $pageCount = (int) substr($content, $countStartPosition, $countEndPosition - $countStartPosition);


    $stnt = $pdo->prepare("INSERT INTO g_scholar_documents(file_name,file_description,added_on,added_by,file_type,spas_id,descriptions) VALUES (?,?,?,?,?,?,?)");
    $params = array($newFilename,$file_type,$date,$authname,$filetypeid,$scholarids,$filedesc);
    $stnt->execute($params);


    if($stnt){
        $result =  true;
    } else{

        $result = false;
    }

    echo json_encode($result);


}


// Created new Scholar Log



if(isset($_GET['NewScholarLog'])){

    date_default_timezone_set('Asia/Manila');


    $date = date("Y-m-d h:i:s a");
    $authname = $_POST["usercreator"];
    $spasLvl = $_POST["spasLvl"];
    $spasYear = $_POST["spasYear"];
    $spasRegion = $_POST["spasRegion"];
    $spasAy = $_POST["spasAy"];
   
    $actType = 'Create';


     $spasid = $spasLvl.$spasYear.$spasRegion.$spasAy;





    $actTitle = 'The New SPAS ID:  '.$spasid. " is successfully created by:  ".$authname;

    $stnt = $pdo->prepare("INSERT INTO g_scholar_console_log(action_title,action_type,added_on,added_by,spas_id) VALUES (?,?,?,?,?)");
    $params = array($actTitle,$actType,$date,$authname,$spasid);
    $stnt -> execute($params);

    if($stnt){
        $result =  true;
    } else{

        $result = false;
    }

    echo json_encode($result);


}


// Edit Insert scholar Log


if(isset($_GET['InsertLog'])){

    date_default_timezone_set('Asia/Manila');


    $date = date("Y-m-d h:i:s a");
    $authname = $_POST["authname"];




    $spasid = $_POST["upspasid"];
    $stats = $_POST["upstats"];
    $substats = $_POST["upsubstats"];
    $lname = $_POST["uplastname"];
    $fname = $_POST["upfirstname"];
    $mname = $_POST["upmidname"];
    $sname = $_POST["upsuffixname"];
    $birthdate = $_POST["upbirthdate"];
    $sex = $_POST["upgender"];

    $actType = 'Update';





    $actTitle = 'The SCHOLAR INFORMATION of ' .$lname. ' ' .$fname. ' SPAS_ID:  ' .$spasid. ' has been updated by ' .$authname;

    $stnt = $pdo->prepare("INSERT INTO g_scholar_console_log(action_title,action_type,added_on,added_by,spas_id) VALUES (?,?,?,?,?)");
    $params = array($actTitle,$actType,$date,$authname,$spasid);
    $stnt -> execute($params);

    if($stnt){
        $result =  true;
    } else{

        $result = false;
    }

    echo json_encode($result);


}


// Document Upload Log


if(isset($_GET['DocxLog'])){

    date_default_timezone_set('Asia/Manila');


    $date = date("Y-m-d h:i:s a");

    $authname = $_POST["username"];
    $spasid = $_POST["spasid"];
    $actType = 'Upload';



    $actTitle = "Document uploaded by:  ".$authname. "   for SPAS:  ".$spasid;

    $stnt = $pdo->prepare("INSERT INTO g_scholar_console_log(action_title,action_type,added_on,added_by,spas_id) VALUES (?,?,?,?,?)");
    $params = array($actTitle,$actType,$date,$authname,$spasid);
    $stnt -> execute($params);

    if($stnt){
        $result =  true;
    } else{

        $result = false;
    }

    echo json_encode($result);


}



// Contact Infomation Log

if(isset($_GET['ContactLog'])){  


   date_default_timezone_set('Asia/Manila');


   $date = date("Y-m-d h:i:s a");


   $authname = $_POST["authname"];
   $spasid = $_POST["upspasid"];
   $actType = 'Update';
   $actTitle = 'The CONTACT INFORMATION of ' . $spasid . ' has been updated by ' . $authname;


   $stnt = $pdo->prepare("INSERT INTO g_scholar_console_log(action_title,action_type,added_on,added_by,spas_id) VALUES (?,?,?,?,?)");
   $params = array($actTitle,$actType,$date,$authname,$spasid);
   $stnt -> execute($params);

   if($stnt){
    $result =  true;
} else{

    $result = false;
}

echo json_encode($result);



}




// School Information Log


if(isset($_GET['SchoolInfoLog'])){  


   date_default_timezone_set('Asia/Manila');


   $date = date("Y-m-d h:i:s a");


   $authname = $_POST["authname"];
   $spasid = $_POST["upspasid"];
   $actType = 'Update';


   $actTitle = 'The SCHOOL INFORMATION LOG of ' . $spasid . ' has been updated by ' . $authname;


   $stnt = $pdo->prepare("INSERT INTO g_scholar_console_log(action_title,action_type,added_on,added_by,spas_id) VALUES (?,?,?,?,?)");
   $params = array($actTitle,$actType,$date,$authname,$spasid);
   $stnt -> execute($params);

   if($stnt){
    $result =  true;
} else{

    $result = false;
}

echo json_encode($result);



}


// Thesis Logs


if(isset($_GET['ThesisInfoLog'])){  


   date_default_timezone_set('Asia/Manila');


   $date = date("Y-m-d h:i:s a");


   $authname = $_POST["authname"];
   $spasid = $_POST["upspasid"];
   $actType = 'Update';


   $actTitle = 'The THESIS INFORMATION DETAILS of ' . $spasid . ' has been updated by ' . $authname;


   $stnt = $pdo->prepare("INSERT INTO g_scholar_console_log(action_title,action_type,added_on,added_by,spas_id) VALUES (?,?,?,?,?)");
   $params = array($actTitle,$actType,$date,$authname,$spasid);
   $stnt -> execute($params);

   if($stnt){
    $result =  true;
} else{

    $result = false;
}

echo json_encode($result);



}


// Scholar Details Logs


if(isset($_GET['ScholarInfoLog'])){  


   date_default_timezone_set('Asia/Manila');


   $date = date("Y-m-d h:i:s a");


   $authname = $_POST["authname"];
   $spasid = $_POST["upspasid"];
   $actType = 'Update';


   $actTitle = 'The SCHOLARSHIP INFORMATION DETAILS of ' . $spasid . ' has been updated by ' . $authname;


   $stnt = $pdo->prepare("INSERT INTO g_scholar_console_log(action_title,action_type,added_on,added_by,spas_id) VALUES (?,?,?,?,?)");
   $params = array($actTitle,$actType,$date,$authname,$spasid);
   $stnt -> execute($params);

   if($stnt){
    $result =  true;
} else{

    $result = false;
}

echo json_encode($result);



}

// New School



if(isset($_GET['newSchool'])){

    $schoolname = $_POST["addschool"];
    $schoolregion = $_POST["schoolregion"];
    $schoolcourse = $_POST["addcourse"];

    $pdo->beginTransaction();
    $stnt = $pdo->prepare("INSERT INTO grad_school(school_name,school_region) VALUES (?,?) RETURNING id");
    $stntcourse = $pdo->prepare("INSERT INTO grad_courses(school_id,courses) VALUES (?,?)");


    $params = array($schoolname,$schoolregion);
    $stnt -> execute($params);

    if($stnt){
        $errors[] =  true;
    } else{

        $errors[] = false;
    }


    $sid = "";
    try{

        $result = $stnt->fetch();
        $sid = $result["id"];
    }catch(Exception $e){
        echo $e;
    }

    foreach ($schoolcourse as $key => $value) {
        $cparams = array($sid,$value);
        $stntcourse -> execute($cparams);
    }



    if(in_array(false, $errors)){
        $pdo->rollback();
        echo false;
    }else{
        $pdo->commit();
        echo true;
    }


}


if(isset($_GET['editnewCourse'])){

    $schoolid = $_POST["ids"];
    $courses = $_POST["editcourse"];

   
    $stntcourse = $pdo->prepare("INSERT INTO grad_courses(school_id,courses) VALUES (?,?)");


    foreach ($courses as $key => $value) {
        $params = array($schoolid,$value);
        $stntcourse -> execute($params);
    }

     if($stntcourse){
    $result =  true;
} else{

    $result = false;
}

echo json_encode($result);


    }





    // Bulk Upload



    if(isset($_GET['bulkUpload'])){


    date_default_timezone_set('Asia/Manila');

   
    $uname = $_POST["usercreator"];
 
    $date = date("Ymdhis");


    $authid = $_POST["authid"];



// Bulk Upload
    $bulkFile = $_FILES['bulkuploadScholars']['name'];
    $path = 'bulk/';
    $allowed_extensions = array('csv');
    $extension = pathinfo($bulkFile, PATHINFO_EXTENSION);
    if(in_array(strtolower($extension),$allowed_extensions) ) {

        if(!file_exists($path)){

            mkdir($path, 0775, true);
        }

        $temp_file = $_FILES['bulkuploadScholars']['tmp_name'];


            $newpath = $path.$authid.$uname.$date.".".$extension;

            if(move_uploaded_file($temp_file,$newpath)){
                echo "";
            } else{
                $newpath = "No_Files";
                    // echo "failed";
            }
        

    }else{
        $newpath = "No_Files";
        // echo "Failed";
    } 

$b = false;
    $file = fopen($newpath, "r");
    while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE)
    {
if(!$b) {       //edited for accuracy
       $b = true;
       continue;
    }

    

    $pdo->beginTransaction();
    $stnt = $pdo->prepare("INSERT INTO g_scholar_profile(spas_id,lname,fname,mname,suffix,email,alternate_email,birthday,sex,contact,school_grad_status,added_by,added_on) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?) RETURNING id");

    $stntAdress = $pdo->prepare("INSERT INTO g_scholar_address(scholar_id,street,town,province,zipcode,district,h_region,house_number,subdivision,barangay) VALUES (?,?,?,?,?,?,?,?,?,?)");

    $stntSchool = $pdo->prepare("INSERT INTO g_scholar_current_school(scholar_id,current_course,current_school,scholarship_program,units,entry_level,year,batch) VALUES (?,?,?,?,?,?,?,?)");

    $stntthesis = $pdo->prepare("INSERT INTO g_scholar_thesis(scholar_id,duration,remarks,field,servob,school_type,title,grad_date,dissemination,research,honors,added_on) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");

    $stntgradshool = $pdo->prepare("INSERT INTO g_scholar_grad_school(scholar_id,grad_course,grad_school,council,grad_program,region,grant_test,level,comp,award_year,availment,sem,status) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");

    $stntdates = $pdo->prepare("INSERT INTO g_scholar_dates(scholar_id,foreign_sem,foreign_startdate,foreign_endsem,foreign_enddate,local_sem,local_startdate,local_endsem,local_enddate,added_on) VALUES (?,?,?,?,?,?,?,?,?,?)");


    $params = array($emapData[0],$emapData[1],$emapData[2],$emapData[3],$emapData[4],$emapData[5],$emapData[6],$emapData[7],$emapData[8],$emapData[9],$emapData[10],$emapData[11],$emapData[12]);



    $stnt -> execute($params);

    if($stnt){
        $errors[] =  true;
    } else{

        $errors[] = false;
    }

    $sid = "";
    try{

        $result = $stnt->fetch();
        $sid = $result["id"];
    }catch(Exception $e){
        echo $e;
    }


    $aparams = array($sid,$emapData[13],$emapData[14],$emapData[15],$emapData[16],$emapData[17],$emapData[18],$emapData[19],$emapData[20],$emapData[21]);
    $stntAdress -> execute($aparams);
    if($stntAdress){
        $errors[] =  true;
    } else{

        $errors[] = false;
    }


    $scparams = array($sid,$emapData[22],$emapData[23],$emapData[24],$emapData[25],$emapData[26],$emapData[27],$emapData[28]);
    $stntSchool -> execute($scparams);
    if($stntSchool){
        $errors[] =  true;
    } else{

        $errors[] = false;
    }

     $thparams = array($sid,$emapData[29],$emapData[30],$emapData[31],$emapData[32],$emapData[33],$emapData[34],$emapData[35],$emapData[36],$emapData[37],$emapData[38],$emapData[39]);
    $stntthesis -> execute($thparams);
    if($stntthesis){
        $errors[] =  true;
    } else{

        $errors[] = false;
    }


    $gparams = array($sid,$emapData[40],$emapData[41],$emapData[42],$emapData[43],$emapData[44],$emapData[45],$emapData[46],$emapData[47],$emapData[48],$emapData[49],$emapData[50],$emapData[51]);
    $stntgradshool -> execute($gparams);
    if($stntgradshool){
        $errors[] =  true;
    } else{

        $errors[] = false;
    }



    $dateparams = array($sid,$emapData[52],$emapData[53],$emapData[54],$emapData[55],$emapData[56],$emapData[57],$emapData[58],$emapData[59],$emapData[60]);
    $stntdates -> execute($dateparams);
    if($stntdates){
        $errors[] = true;
    }else{
        $errors[] = false;
    }


 
   if(in_array(false, $errors)){
     $status = "false";
     $pdo->rollback();
    } else{
    $status = "true";
    $pdo->commit();
    }
    
    }
echo $status;

} 


// New User Log



if(isset($_GET['newUserLog'])){

    date_default_timezone_set('Asia/Manila');


    $date = date("Y-m-d h:i:s a");
    $authname = $_POST["creator"];

    $usname = $_POST["username"];

    $uslvl = $_POST["acclevel"];

    $newLvl = $uslvl;

    if ($newLvl == '0'){
        $newLvl = 'User';
    }elseif ($newLvl == '1') {
    $newLvl = 'Administrator';
    }elseif($newLvl == '2'){
    $newLvl = 'Coordinator';
    }else{
    $newLvl = 'Project Leader';
    }


   
    $actType = 'Create';



    $actTitle = 'The New User:  '.$usname. " with access level of " .$newLvl. " is successfully created by:  ".$authname;

    $stnt = $pdo->prepare("INSERT INTO g_scholar_console_log(action_title,action_type,added_on,added_by) VALUES (?,?,?,?)");
    $params = array($actTitle,$actType,$date,$authname);
    $stnt -> execute($params);

    if($stnt){
        $result =  true;
    } else{

        $result = false;
    }

    echo json_encode($result);


}

// Remove User Log



if(isset($_GET['delUserLog'])){

    date_default_timezone_set('Asia/Manila');


    $date = date("Y-m-d h:i:s a");
    $authname = $_POST["creator"];

    $usname = $_POST["username"];

    $uslvl = $_POST["acclevel"];

    $newLvl = $uslvl;

    if ($newLvl == '0'){
        $newLvl = 'User';
    }elseif ($newLvl == '1') {
    $newLvl = 'Administrator';
    }elseif($newLvl == '2'){
    $newLvl = 'Coordinator';
    }else{
    $newLvl = 'Project Leader';
    }


   
    $actType = 'Remove';



    $actTitle = 'The User:  '.$usname. " with access level of " .$newLvl. " was successfully removed by:  ".$authname;

    $stnt = $pdo->prepare("INSERT INTO g_scholar_console_log(action_title,action_type,added_on,added_by) VALUES (?,?,?,?)");
    $params = array($actTitle,$actType,$date,$authname);
    $stnt -> execute($params);

    if($stnt){
        $result =  true;
    } else{

        $result = false;
    }

    echo json_encode($result);


}


// Remove User Log



if(isset($_GET['editUserLog'])){

    date_default_timezone_set('Asia/Manila');


    $date = date("Y-m-d h:i:s a");
    $authname = $_POST["creator"];

    $usname = $_POST["upusername"];

    $uslvl = $_POST["upacclevel"];

    $newLvl = $uslvl;

    if ($newLvl == '0'){
        $newLvl = 'User';
    }elseif ($newLvl == '1') {
    $newLvl = 'Administrator';
    }elseif($newLvl == '2'){
    $newLvl = 'Coordinator';
    }else{
    $newLvl = 'Project Leader';
    }


   
    $actType = 'Remove';



    $actTitle = 'The User:  '.$usname. " with access level of " .$newLvl. " was successfully removed by:  ".$authname;

    $stnt = $pdo->prepare("INSERT INTO g_scholar_console_log(action_title,action_type,added_on,added_by) VALUES (?,?,?,?)");
    $params = array($actTitle,$actType,$date,$authname);
    $stnt -> execute($params);

    if($stnt){
        $result =  true;
    } else{

        $result = false;
    }

    echo json_encode($result);


}





?>