<?php
$hostName = "localhost"; 
$dbUser = "root"; 
$dbPassword = "";
$connection = mysqli_connect($hostName, $dbUser, $dbPassword, "bank_users");
if(!$connection){
    die("Database not connecting"); 
}
 ?>