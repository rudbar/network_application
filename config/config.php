<?php
ob_start(); // включает выходную буферизацию

$timezone = date_default_timezone_set("Asia/Yekaterinburg");


mb_internal_encoding('UTF-8');
$con = mysqli_connect("localhost", "root", "", "social");

if(mysqli_connect_errno()) 
{
    echo "Failed to connect: " . mysqli_connect_errno();
}

?>