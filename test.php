<?php


require("dbcon.php");



$db = new dbconn();





$sql = "SELECT id,birthday_string FROM public.g_scholar_profile";
$query = $db -> fetchAll($sql);



foreach ($query as $key => $value) {
	$courses = $value["birthday_string"];
	$ids = $value["id"];


if($courses == '0000-00-00' || $courses == '0000-07-17' ||  $courses == '-0001-11-30'){


    	   $timestamp = strtotime('1000-00-00');
    $formattedDate = date('Y-m-d', $timestamp);
}else{
   $timestamp = strtotime($courses);
    $formattedDate = date('Y-m-d', $timestamp);
}


	 $sql = "UPDATE public.g_scholar_profile SET birthday = '$formattedDate' WHERE id = '$ids'";
	 $db -> query($sql);
}

echo 'done';