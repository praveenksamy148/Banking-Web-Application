<?php
$hostName = "localhost"; 
$dbUser = "root"; 
$dbPassword = "";
$connection = mysqli_connect($hostName, $dbUser, $dbPassword, "bank users");
if(!$connection){
    die("Database not connecting"); 
}
 ?>