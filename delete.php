<?php
require("datacon.php");


// Delete User

if(isset($_GET['deleteuser'])){


$usersid = $_POST["userid"];


$stnt = $pdo->prepare("UPDATE users SET status = 'Inactive' WHERE id=?");
$stnt -> execute([$usersid]);
 if($stnt){
        $result =  true;
    } else{
        
        $result = false;
    }

    echo json_encode($result);

}

// Delete Scholar



if(isset($_GET['deleteScholar'])){


$scholarid = $_POST["scholarid"];


$stnt = $pdo->prepare("UPDATE g_scholar_profile SET status = 'Inactive' WHERE id=?");
$stnt -> execute([$scholarid]);
 if($stnt){
        $result =  true;
    } else{
        
        $result = false;
    }

    echo json_encode($result);

}

// 

if(isset($_GET['deleteSchoolInfo'])){


$schoolid = $_POST["schoolid"];


$stnt = $pdo->prepare("DELETE FROM grad_courses WHERE id = ?");
$stnt -> execute([$schoolid]);
 if($stnt){
        $result =  true;
    } else{
        
        $result = false;
    }

    echo json_encode($result);

}


if(isset($_GET['deleteSchool'])){


$schoolid = $_POST["schoolid"];


$stnt = $pdo->prepare("DELETE FROM grad_school WHERE id = ?");
$stnt -> execute([$schoolid]);
 if($stnt){
        $result =  true;
    } else{
        
        $result = false;
    }

    echo json_encode($result);

}



?>