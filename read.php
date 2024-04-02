<?php

require("datacon.php");

// Read Users on Dashboard

if(isset($_GET['readuser'])){
$data = array();
try
{

    $stnt = $pdo->prepare("SELECT 
    id,
    username,
    addedby,
    fname,
    lname,
    to_char(addedon, 'MONTH DD, YYYY on HH12:MI AM') as addedon,
    CASE 
        WHEN access_level = 1 THEN 'Administrator Account'
        WHEN access_level = 2 THEN 'Coordinator'
        WHEN access_level = 3 THEN 'Project Leader'
        ELSE 'User Account'
    END AS access_level 
FROM 
    users 
WHERE 
    status = 'active' 
ORDER BY 
    id;
");
    $stnt->execute();

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch(PDO::FETCH_ASSOC)){
    $data[] = $row;
}

echo json_encode($data);

$stnt = null;
$pdo = null;

}

// Read Users Access Level

if(isset($_GET['readuserAccess'])){
$data = array();
try
{

    $stnt = $pdo->prepare("SELECT * FROM users");
    $stnt->execute();

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch(PDO::FETCH_ASSOC)){
    $data[] = $row;
}

echo json_encode($data);

$stnt = null;
$pdo = null;

}

// Count Ongoing Scholars


if(isset($_GET['OnScholars'])){
$data = array();
$frstyr = $_POST["frstYearSelect"];
$scndyr = $_POST["yearselect"];
try
{

    $stnt = $pdo->prepare("SELECT COUNT(id) as ongoingscholar FROM g_scholar_profile WHERE school_grad_status = 'Ongoing'
AND status = 'active' AND EXTRACT(YEAR FROM added_on) BETWEEN ? AND ?");
    $stnt->execute([$frstyr,$scndyr]);

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch(PDO::FETCH_ASSOC)){
    $data = $row;
}

echo json_encode($data);

$stnt = null;
$pdo = null;

}

// Count Graduated Scholars


if(isset($_GET['GradScholars'])){
$data = array();
$frstyr = $_POST["frstYearSelect"];
$scndyr = $_POST["yearselect"];
try
{

    $stnt = $pdo->prepare("SELECT COUNT(id) as gradscholar FROM g_scholar_profile WHERE school_grad_status = 'Graduated' 
        AND status = 'active' AND EXTRACT(YEAR FROM added_on) BETWEEN ? AND ?");
    $stnt->execute([$frstyr,$scndyr]);

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch(PDO::FETCH_ASSOC)){
    $data = $row;
}

echo json_encode($data);

$stnt = null;
$pdo = null;

}


// Count Graduated Scholars


if(isset($_GET['TermScholars'])){
$data = array();
$frstyr = $_POST["frstYearSelect"];
$scndyr = $_POST["yearselect"];
try
{

    $stnt = $pdo->prepare("SELECT COUNT(id) as termscholar FROM g_scholar_profile WHERE school_grad_status = 'Terminated' 
        AND status = 'active' AND EXTRACT(YEAR FROM added_on) BETWEEN ? AND ?");
    $stnt->execute([$frstyr,$scndyr]);

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch(PDO::FETCH_ASSOC)){
    $data = $row;
}

echo json_encode($data);

$stnt = null;
$pdo = null;

}




// Authentication

if(isset($_GET['usnames'])){


$out = array('error' => false);

$usernames = $_POST['usernames'];
$password = $_POST['password'];
$passwordsHash = sha1("digi".$password."digi");


    $stnt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $params = array($usernames,$passwordsHash);
    $stnt->execute($params);
    $row=$stnt->fetch();



    if ($row){
        $_SESSION['loggedInUser']=$row;
                $out['message'] = "Login Successful";
    }else{
        $out['error'] = true;
        $out['message'] = "Login Failed";
    }


echo json_encode($out);
die();
}


if (isset($_GET['authLog'])){
    // session_destroy();
    if(isset($_SESSION['loggedInUser'])){
       echo json_encode($_SESSION["loggedInUser"]);
    }else{
        echo json_encode(false);
    }
}



// Authentication Logout

if(isset($_GET['authlogout'])){
    session_destroy();
    // $_SESSION['variable']
    echo "Log out";
}


// Read Address

if(isset($_GET['address'])){
$data = array();
try
{

    $stnt = $pdo->prepare("SELECT * FROM esch_shared.zipi ORDER BY zpro");
    $stnt->execute();

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch()){
    $data[] = array(

            "label" => $row['zzip'] . " - " . $row['zbar'] . " - " . $row['zmun'] . " - " . $row['zpro'] . " - " . $row["zreg"],

            "value" => $row['zzid']

        );
}

echo json_encode($data);

$stnt = null;
$pdo = null;

}

// Read Address Id

if(isset($_GET['addressid'])){

$data = array();
$addressids = $_POST["province"];

try
{

    $stnt = $pdo->prepare("SELECT e.zzid, e.zreg, e.zpro, e.zmun, e.zbar, e.zzip, e.zdis, e.zpov, CASE WHEN e.zreg = 'NCR' THEN '16' ELSE e.zreg end as new_region FROM esch_shared.zipi e WHERE zzid = ?");
    $stnt->execute([$addressids]);



}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch(PDO::FETCH_ASSOC)){
    $data = $row;
}

echo json_encode($data);

$stnt = null;
$pdo = null;

}


//Read OnGoing Scholars

if(isset($_GET['readscholar'])){
$data = array();
try
{

    $stnt = $pdo->prepare("SELECT * FROM g_scholar_profile WHERE school_grad_status = 'Ongoing' AND status = 'active' ORDER by id DESC");
    $stnt->execute();

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch(PDO::FETCH_ASSOC)){
    $data[] = $row;
}

echo json_encode($data);

$stnt = null;
$pdo = null;

}



//Read Graduated Scholars

if(isset($_GET['readgraduatescholar'])){
$data = array();
try
{

    $stnt = $pdo->prepare("SELECT * FROM g_scholar_profile WHERE school_grad_status = 'Graduated'  AND status = 'active' ORDER BY id DESC");
    $stnt->execute();

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch(PDO::FETCH_ASSOC)){
    $data[] = $row;
}

echo json_encode($data);

$stnt = null;
$pdo = null;

}



//Read Terminated Scholars

if(isset($_GET['readtermscholar'])){
$data = array();
try
{

    $stnt = $pdo->prepare("SELECT * FROM g_scholar_profile WHERE school_grad_status = 'Terminated'  AND status = 'active' ORDER BY id DESC");
    $stnt->execute();

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch(PDO::FETCH_ASSOC)){
    $data[] = $row;
}

echo json_encode($data);

$stnt = null;
$pdo = null;

}





// Count Scholar Male


if(isset($_GET['countmale'])){


$data = array();
$frstyr = $_POST["frstYearSelect"];
$scndyr = $_POST["yearselect"];


try
{

    $stnt = $pdo->prepare("SELECT COUNT(sex) AS malecount 
FROM g_scholar_profile 
WHERE sex LIKE '%M%' 
AND EXTRACT(YEAR FROM added_on) BETWEEN ? AND ?");
    $stnt->execute([$frstyr,$scndyr]);

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch(PDO::FETCH_ASSOC)){
    $data = $row;
}

echo json_encode($data);

$stnt = null;
$pdo = null;

}

// Count Scholar Female


if(isset($_GET['countfemale'])){
$data = array();
$frstyr = $_POST["frstYearSelect"];
$scndyr = $_POST["yearselect"];
try
{

    $stnt = $pdo->prepare("SELECT COUNT(sex)as femalecount 
FROM g_scholar_profile 
WHERE sex 
like '%F%' AND EXTRACT(YEAR FROM added_on) BETWEEN ? AND ?");
    $stnt->execute([$frstyr,$scndyr]);

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch(PDO::FETCH_ASSOC)){
    $data = $row;
}

echo json_encode($data);

$stnt = null;
$pdo = null;

}


// Read Scholar Personal Info

if(isset($_GET['readScholarPersonalInfo'])){

$data = array();
$saddressids = $_POST["saddressid"];

try
{

    $stnt = $pdo->prepare("SELECT * FROM g_scholar_profile WHERE scholar_id = ?");
    $stnt->execute([$saddressids]);

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch(PDO::FETCH_ASSOC)){
    $data = $row;
}

echo json_encode($data);

$stnt = null;
$pdo = null;

}


// Read Scholar Address Id For Edit

if(isset($_GET['scholarAddressid'])){

$data = array();
$saddressids = $_POST["saddressid"];

try
{

    $stnt = $pdo->prepare("SELECT * FROM g_scholar_address WHERE scholar_id = ?");
    $stnt->execute([$saddressids]);

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch(PDO::FETCH_ASSOC)){
    $data = $row;
}

echo json_encode($data);

$stnt = null;
$pdo = null;

}




// Read Schools For Edit

if(isset($_GET['shoolsID'])){

$data = array();
$schoolids = $_POST["saddressid"];

try
{

    $stnt = $pdo->prepare("SELECT * FROM g_scholar_current_school WHERE scholar_id = ?");
    $stnt->execute([$schoolids]);

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch(PDO::FETCH_ASSOC)){
    $data = $row;
}

echo json_encode($data);

$stnt = null;
$pdo = null;

}



// Read Year for Statistics

if(isset($_GET['years'])){
$data = array();
try
{

    $stnt = $pdo->prepare("SELECT DISTINCT EXTRACT(YEAR FROM added_on) AS year
FROM g_scholar_profile WHERE added_on IS NOT NULL");
    $stnt->execute();

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch()){
    $data[] = array(

            "label" => $row['year'],

            "value" => $row['year']

        );
}

echo json_encode($data);

$stnt = null;
$pdo = null;

}

// Read Year Selected

if(isset($_GET['yrselect'])){

$yr = $_POST["years"];
$data = array();
$data2 = array();

try
{

    $stnt = $pdo->prepare("SELECT EXTRACT(YEAR FROM added_on) AS year,
       EXTRACT(MONTH FROM added_on) AS month,
       COUNT(*) AS count_between_months
FROM g_scholar_profile
WHERE EXTRACT(YEAR FROM added_on) = ?
GROUP BY EXTRACT(YEAR FROM added_on), EXTRACT(MONTH FROM added_on)
ORDER BY year, month");
$stnt->execute([$yr]);




}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch(PDO::FETCH_ASSOC)){
    $data[$row["month"]] = $row;
}

for ($i=1; $i <= 12 ; $i++) { 
    $data2[$monthName = date('F', mktime(0, 0, 0, $i, 1))] = isset($data[$i]) ? $data[$i]["count_between_months"] : 0;  
}

echo json_encode($data2);

$stnt = null;
$pdo = null;

}

// Popultae File Description


if(isset($_GET['populatefiles'])){
$data = array();
try
{

    $stnt = $pdo->prepare("SELECT * FROM filetypes ORDER BY id");
    $stnt->execute();

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch()){
    $data[] = array("label" => $row['name'],"value" => $row["name"],"val_id" => $row["id"]
        );
}

echo json_encode($data);

$stnt = null;
$pdo = null;

}



// Read Documents

if(isset($_GET['docuID'])){

$data = array();
$scholar_id = $_POST["ids"];

try
{

    $stnt = $pdo->prepare("SELECT * FROM g_scholar_documents WHERE spas_id = ?");
    $stnt->execute([$scholar_id]);

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch(PDO::FETCH_ASSOC)){
    $data[] = $row;
}

echo json_encode($data);

$stnt = null;
$pdo = null;

}



// Read User Logs 



if(isset($_GET['ReadLogs'])){

$data = array();
date_default_timezone_set('Asia/Manila');
$date = date('
Y-m-d h:i:s a
');

try
{

    $stnt = $pdo->prepare("SELECT id,action_title,action_type,added_on,added_by,status,spas_id,read_stats, CASE
                WHEN EXTRACT(EPOCH FROM '$date' - added_on) / 60 < 60 THEN
                    CONCAT(ROUND(EXTRACT(EPOCH FROM '$date' - added_on) / 60)::numeric,
                        ' min',
                        CASE WHEN ROUND(EXTRACT(EPOCH FROM '$date' - added_on) / 60)::numeric > 1 THEN 's' ELSE '' END)
                WHEN EXTRACT(EPOCH FROM '$date' - added_on) / 3600 < 24 THEN
                    CONCAT(ROUND(EXTRACT(EPOCH FROM '$date' - added_on) / 3600)::numeric,
                        ' hour',
                        CASE WHEN ROUND(EXTRACT(EPOCH FROM '$date' - added_on) / 3600)::numeric > 1 THEN 's' ELSE '' END)
                WHEN EXTRACT(EPOCH FROM '$date' - added_on) / 86400 < 365 THEN
                    CONCAT(ROUND(EXTRACT(EPOCH FROM '$date' - added_on) / 86400)::numeric,
                        ' day',
                        CASE WHEN ROUND(EXTRACT(EPOCH FROM '$date' - added_on) / 86400)::numeric > 1 THEN 's' ELSE '' END)
                ELSE
                    CONCAT(ROUND(EXTRACT(EPOCH FROM '$date' - added_on) / 86400 / 365)::numeric,
                        ' year',
                        CASE WHEN ROUND(EXTRACT(EPOCH FROM '$date' - added_on) / 86400 / 365)::numeric > 1 THEN 's' ELSE '' END)
            END as prevtime
            
            FROM g_scholar_console_log WHERE status = 'active' ORDER BY id DESC");
    $stnt->execute();

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch(PDO::FETCH_ASSOC)){
    $data[] = $row;
}

echo json_encode($data);

$stnt = null;
$pdo = null;

}


// Read Notif numbers

if(isset($_GET['readNotifCount'])){
$data = array();
try
{

    $stnt = $pdo->prepare("SELECT COUNT(*)
FROM g_scholar_console_log
WHERE read_stats = 'unread' AND status = 'active'");
    $stnt->execute();

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch(PDO::FETCH_ASSOC)){
    $data = $row;
}

echo json_encode($data);

$stnt = null;
$pdo = null;

}


// Read Courses

if(isset($_GET['readStats'])){
$data = array();
$id = $_POST["logids"];
try
{

    $stnt = $pdo->prepare("SELECT * FROM g_scholar_console_log WHERE id = ?");
    $stnt->execute([$id]);

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch(PDO::FETCH_ASSOC)){
    $data[] = $row;
}

echo json_encode($data);

$stnt = null;
$pdo = null;

}














// Read Schools

if(isset($_GET['readschool'])){
$data = array();
try
{

    $stnt = $pdo->prepare("SELECT * FROM grad_school ORDER BY id");
    $stnt->execute();

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch(PDO::FETCH_ASSOC)){
    $data[] = $row;
}

echo json_encode($data);

$stnt = null;
$pdo = null;

}


// Read Courses

if(isset($_GET['readcourse'])){
$data = array();
$id = $_POST["ids"];
try
{

    $stnt = $pdo->prepare("SELECT * FROM grad_courses WHERE school_id = ?");
    $stnt->execute([$id]);

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch(PDO::FETCH_ASSOC)){
    $data[] = $row;
}

echo json_encode($data);

$stnt = null;
$pdo = null;

}



// Populate Grad School

if(isset($_GET['gradSchool'])){
$data = array();
try
{

    $stnt = $pdo->prepare("SELECT * FROM grad_school ORDER BY id");
    $stnt->execute();

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch()){
    $data[] = array(

            "label" => $row['school_name'],

            "value" => $row['id']

        );
}

echo json_encode($data);

$stnt = null;
$pdo = null;

}



// Populate Grad School

// if(isset($_GET['gradSchool'])){
// $data = array();
// try
// {

//     $stnt = $pdo->prepare("SELECT * FROM grad_school ORDER BY id");
//     $stnt->execute();

// }catch (Exception $ex){
//     die("Failed to run query". $ex);

// }

// http_response_code(200);

// while ($row = $stnt->fetch()){

//     $data[] = array(
//         "label" => $row["school_name"],
//         "value" => $row["id"],
        
//         );
// }

// echo json_encode($data);

// $stnt = null;
// $pdo = null;

// }


// Read Gradschool

if(isset($_GET['gradSchoolFilter'])){

$data = array();
$schoolId = $_POST["schoolId"];

try
{

    $stnt = $pdo->prepare("SELECT 
    school_name, 
    school_region, 
    CASE 
        WHEN LOWER(school_name) = 'upou' THEN '0' 
        ELSE '' 
    END as duration 
FROM 
    grad_school 
 WHERE id= ? ORDER BY id");
    $stnt->execute([$schoolId]);



}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch(PDO::FETCH_ASSOC)){
    $data = $row;
}

echo json_encode($data);

$stnt = null;
$pdo = null;

}






// Read Grad School Region

if(isset($_GET['schoolIDS'])){


$data = array();
$schoolids = $_POST["colleges"];

try
{

    $stnt = $pdo->prepare("SELECT * FROM grad_school WHERE id = ?");
    $stnt->execute([$schoolids]);

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch(PDO::FETCH_ASSOC)){
    $data = $row;
}

echo json_encode($data);

$stnt = null;
$pdo = null;

}



// Populate Grad School Courses

if(isset($_GET['gradSchoolCourses'])){
$data = array();
try
{

    $stnt = $pdo->prepare("SELECT * FROM grad_courses ORDER by id");
    $stnt->execute();

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch()){

    $data[] = array(
        "label" => $row["courses"],
        "value" => $row["courses"],
        "field" => $row["field"],
        );
}

echo json_encode($data);

$stnt = null;
$pdo = null;

}





// GRANT

if(isset($_GET['grant'])){
$data = array();
try
{

    $stnt = $pdo->prepare("SELECT * FROM grad_grant ORDER BY id");
    $stnt->execute();

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch()){

    $data[] = array("label" => $row["grants"],
        "value" => $row["grants"]
        );
}

echo json_encode($data);

$stnt = null;
$pdo = null;

}

// Council


if(isset($_GET['council'])){
$data = array();
try
{

    $stnt = $pdo->prepare("SELECT * FROM grad_council ORDER BY id");
    $stnt->execute();

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch()){

    $data[] = array("label" => $row["council"],
        "value" => $row["council"]
        );
}

echo json_encode($data);

$stnt = null;
$pdo = null;

}

// Grad School

if(isset($_GET['editGradschool'])){

    $scholarid = $_POST["saddressid"];
    $data = array();


    try
{

    $stnt = $pdo->prepare("SELECT * FROM g_scholar_grad_school WHERE scholar_id = ?");
    $stnt->execute([$scholarid]);

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch(PDO::FETCH_ASSOC)){
    $data = $row;
}

echo json_encode($data);

$stnt = null;
$pdo = null;



}




// Thesis


if(isset($_GET['editThesis'])){

    $scholarid = $_POST["saddressid"];
    $data = array();


    try
{

    $stnt = $pdo->prepare("SELECT * FROM g_scholar_thesis WHERE scholar_id = ?");
    $stnt->execute([$scholarid]);

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch(PDO::FETCH_ASSOC)){
    $data = $row;
}

echo json_encode($data);

$stnt = null;
$pdo = null;


}

// Scholarships View

if(isset($_GET['editScholarship'])){

    $scholarid = $_POST["saddressid"];
    $data = array();


    try
{

    $stnt = $pdo->prepare("SELECT * FROM g_scholar_grad_school WHERE scholar_id = ?");
    $stnt->execute([$scholarid]);

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch(PDO::FETCH_ASSOC)){
    $data = $row;
}

echo json_encode($data);

$stnt = null;
$pdo = null;


}

// Stats


if(isset($_GET['stats'])){
$data = array();
try
{

    $stnt = $pdo->prepare("SELECT DISTINCT status FROM g_scholar_grad_school");
    $stnt->execute();

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch()){

    $data[] = array("label" => $row["status"],
        "value" => $row["status"]
        );
}

echo json_encode($data);

$stnt = null;
$pdo = null;

}


// Edit Date & Sem


if(isset($_GET['editDateSem'])){

    $scholarid = $_POST["saddressid"];
    $data = array();


    try
{

    $stnt = $pdo->prepare("SELECT * FROM g_scholar_dates WHERE scholar_id = ?");
    $stnt->execute([$scholarid]);

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch(PDO::FETCH_ASSOC)){
    $data = $row;
}

echo json_encode($data);

$stnt = null;
$pdo = null;


}


//Read Scholar Lists

if(isset($_GET['readScholarLists'])){
$data = array();
try
{

    $stnt = $pdo->prepare("SELECT * FROM g_scholar_thesis");
    $stnt->execute();

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch(PDO::FETCH_ASSOC)){
    $data[] = $row;
}

echo json_encode($data);

$stnt = null;
$pdo = null;

}


// Populate UnderGrad Courses

if(isset($_GET['underGradCourses'])){
$data = array();
try
{

    $stnt = $pdo->prepare("SELECT * FROM g_undergrad_courses ORDER by id");
    $stnt->execute();

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch()){

    $data[] = array(
        "label" => $row["course_fullname"],
        "value" => $row["course_fullname"],
        
        );
}

echo json_encode($data);

$stnt = null;
$pdo = null;

}

// Populate UnderGrad School

if(isset($_GET['underGradSchool'])){
$data = array();
try
{

    $stnt = $pdo->prepare("SELECT * FROM g_undergrad_schools ORDER by sch_id");
    $stnt->execute();

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch()){

    $data[] = array(
        "label" => $row["school_name"],
        "value" => $row["school_name"],
        
        );
}

echo json_encode($data);

$stnt = null;
$pdo = null;

}







?>