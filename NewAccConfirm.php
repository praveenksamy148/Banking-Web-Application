<?php 
session_start(); 
if(!isset($_SESSION["authenticate"])){
    header("location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banking Account Creation</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="regStyling.css">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        /* Custom CSS for a maroon and gold theme with improved fonts */
        body {
            background-color: maroon;
            color: gold;
            font-family: 'Open Sans', sans-serif;
            text-align: center;
        }

        .container {
            background-color: maroon;
            border: 1px solid gold;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-control {
            background-color: gold;
            color: maroon;
            border: 1px solid maroon;
            border-radius: 5px;
            padding: 10px;
            font-family: 'Open Sans', sans-serif;
        }

        .btn-primary {
            background-color: gold;
            color: maroon;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            font-family: 'Playfair Display', serif;
        }

        .btn-primary:hover {
            background-color: #d4af37;
        }

        .alert {
            background-color: gold;
            color: maroon;
            border: 1px solid maroon;
            border-radius: 5px;
            margin: 10px 0;
            padding: 10px;
            font-family: 'Open Sans', sans-serif;
        }

        .logo {
            max-width: 150px;
            display: block;
            margin: 0 auto 20px;
        }

        #buttonSize {
            background-color: gold;
            color: maroon;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
            font-family: 'Playfair Display', serif;
        }

        #buttonSize:hover {
            background-color: #d4af37;
        }
        img{
            height: 200px; 
            width: 400px; 
        }
    </style>
</head>
<body>
    <!-- <div class="image-section">
        <img src= "BankOfMusa.png" alt = "Company Logo" class= "logo">
    </div> -->
    <div class="container">
    <div class="header">
        <h1 class="name">Welcome to Bank!</h1>
        <h3>Please follow the steps below to create a new account:</h3>
    </div>
    <h1 style= "color:white; text-align: center"><b>Account Creation</b></h1>
        <form action="NewAccConfirm.php" method="post">
            <?php
            if(isset($_POST["submit"])){
                $accType = $_POST["accType"]; 
                $money = $_POST["money"]; 
                $accID = $_SESSION["userID"];
                $check = false;
                $errorcount = 0;
                while (!$check) 
                {
                    $possibleID = rand(111111111, 999999999);
                    require_once "database.php"; 
                    $sql = "SELECT accID FROM account_info WHERE accID = $possibleID";
                    $accIDCheck = mysqli_query($connection, $sql);
                    $test = mysqli_num_rows($accIDCheck);
                    
                    if (mysqli_num_rows($accIDCheck) == 0) 
                    {
                        $uniqueID = $possibleID;
                        $check = true;
                    } 
                    else 
                    {
                        $errorcount++;
                        if($errorcount > 100);
                        {
                            echo "ERROR: UNABLE TO CREATE UNIQUE ID";
                            exit;
                        }
                    }
                }
                require_once "database.php"; 
                $sql = "INSERT INTO account_info (accID, money, accType, uniqueID) VALUES ('$accID','$money', '$accType', '$uniqueID')";
                $results = mysqli_query($connection, $sql); 

                  if($results){
                      header("Location: accountpage.php");

                  }else{
                      echo "<div class= 'alert alert-danger'>Unable to Register</div>";
                      echo mysqli_error($connection); 
                  }
              }
             ?>
            <div class="form-group">
              <select name="accType">
                  <option value = "TAccount">Account Type</option>
                  <option value="Savings">Savings</option>
                  <option value="Checkings">Checkings</option>
              </select>
            </div>
            
            <div class = "form-group">
                <input type = "number" name = "money" placeholder = "Initial Deposit: ">
            </div>
            <div class = "form-group">
                <input type = "submit" value = "Enter" name = "submit" >
            </div>
    </div>
</body>
</html>
