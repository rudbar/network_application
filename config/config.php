<?php
ob_start(); // включает выходную буферизацию

$timezone = date_default_timezone_set("Asia/Yekaterinburg");

$con = mysqli_connect("localhost", "root", "", "social");

if(!isset($_SESSION)) 
{ 
    session_start(); 
}
if(mysqli_connect_errno()) 
{
    echo "Failed to connect: " . mysqli_connect_errno();
}

?>