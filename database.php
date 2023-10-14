<?php
$hostName = "localhost"; 
$dbUser = "root"; 
$dbPassword = "";
$dbName = "";
$connection = mysqli_connect($hostName, $dbUser, $dbPassword, "bank users");
if(!$connection){
    die("Database not connecting"); 
}
 ?>