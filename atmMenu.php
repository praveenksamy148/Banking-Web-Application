<?php 
    session_start(); 
    if(!isset($_SESSION["authenticate"])){
        header("location: login.php");
        exit(); 
    }else{
        $timeout_duration = 900; 
        $time_lastLogin = time() - $_SESSION['last_login_timestamp']; 
        if($time_lastLogin > $timeout_duration){
            session_unset();
            session_destroy();
            header('Location: login.php'); 
            exit(); 
        }else{
            $remaining_time = $timeout_duration - $time_lastLogin; 
            $_SESSION['last_login_timestamp'] = time(); 
            
        }

        if (!isset($_SESSION["accountID"])){
            header("location: atmAccountPicker.php");
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank of Musa - ATM</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            flex-wrap: wrap;
            height: 100vh;
            background-color: #1e3859;
            color: white;
            font-family: Arial, sans-serif;
        }

        .flex-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            border-radius: 10px;
        }

        .flex-container a {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            text-decoration: none;
            color: white;
            transition: color 0.3s; 
        }

        .flex-container:hover a {
            color: #87CEEB; 
        }

        .flex-container:nth-child(odd) {
            background-color: #2c4d75;
        }

        .flex-container:nth-child(even) {
            background-color: #2c4d75;
        }

        .content {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="flex-container">
        <a href="atmWithdraw.php">
            <h2>Withdraw</h2>
        </a>
    </div>

    <div class="flex-container">
        <a href="atmCheckBalance.php">
            <h2>Check Balance</h2>
        </a>
    </div>

    <div class="flex-container">
        <a href="atmAccountPicker.php">
            <h2>Exit</h2>
        </a>
    </div>
</body>
</html>
