<?php 
session_start(); 
if(!isset($_SESSION["authenticate"])){
    header("location: login.php");
    exit(); 
}else{
    $timeout_duration = 900; 
    $time_lastLogin = time() - $_SESSION['last_login_timestamp']; 
    if($time_lastLogin > $timeout_duration){
        header('Location: login.php'); 
        exit(); 
    }else{
        $remaining_time = $timeout_duration - $time_lastLogin; 
        $_SESSION['last_login_timestamp'] = time(); 
        
    }
    
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funds Transfer</title>
    <style>
        /* Custom CSS for your design */
        html, body {
            min-height: 100%;
        }

        body {
            background-color: #800000;
            color: gold;
            font-family: 'Open Sans', sans-serif;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        .container {
            background-color: maroon;
            border: 1px solid gold;
            border-radius: 10px;
            padding: 20px;
            margin-top: 40px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-control {
            background-color: white;
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

        #buttonSize {
            background-color: white;
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

    </style>
</head>
    <header>
        <a href="MusaHome.html"><img id='logo' width='300' height='50' src="logo.png"></a>
        <div class="navbar"><a href='MusaHome.html'>Home</a></div>
        <div class="navbar"><a href='NewAccConfirm.php'>Checking & Savings</a></div>       
        <div class="navbar"><a href='deposits.html'>Make a Deposit</a></div>      
        <div class="navbar"><a href='fundsTransfer.php' style='flex-grow: 1;'>Transfer Funds</a></div>
        <div class="navbar"><a href='logout.php'>Log Out</a></div>
        <div class="navbar"><a href = "NewAccConfirm.php">Create Account</a></div>
    </header>
<body>
<div class="container">
        <h1>Funds Transfer</h1>
        <form action="fundsTransfer.php" method="post">
        <input type="hidden" name="transferFunds" value="1">
        <div class="form-group">
        <?php
            $userID = $_SESSION["userID"];
            require_once "database.php";
            $sql = "SELECT uniqueID FROM account_info WHERE accID = $userID";
            $result = $connection->query($sql);
        ?>
                        <div class = "select_style">
                            <h5> Select an account: </h5>
                            <select name="selectedAcc">
                                <?php
                                if ($result->num_rows > 0) 
                                {
                                    while ($row = $result->fetch_assoc()) {
                                        $accountId = $row["uniqueID"];
                                        echo "<option value='$accountId'>$accountId</option>";
                                    }
                                }
                                else
                                {
                                    echo "There are no accounts associated with this user ID.";
                                }
                                ?>
                            </select>
            </div>
            </div>
            <div class="form-group">
                <input type="text" name="toAccount" placeholder="To Balance Account:" class="form-control" required>
            </div>
            <div class="form-group">
                <input type="number" step="0.01" name="amount" placeholder="Amount:" class="form-control" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Transfer Funds" class="btn-primary">
            </div>
        </form>
    <?php

    require_once 'database.php';

    if (isset($_POST["transferFunds"])) {
        // $stmt = mysqli_prepare($connection, $uniqueID);
        // mysqli_stmt_bind_param($stmt, "s", $_POST["fromAccount"]);
        // mysqli_stmt_execute($stmt);
        // $result = mysqli_stmt_get_result($stmt);
        $fromUniqueID = $_POST["selectedAcc"];
        // $fromAccountID = $_POST["fromAccount"];
        $toUniqueID = $_POST["toAccount"];
        // $toAccountID = $_POST["toAccount"];
        $fromAccountType = $_POST["accountType"];
        /* if ($_POST["accountType"] == "Checking") {
            $fromAccountType = 1;
        } else {
            $fromAccountType = 2;
        }
        */
        $amount = $_POST["amount"];

        // Perform funds transfer
        $queryFrom = "SELECT money FROM `account_info` WHERE uniqueID = '$fromUniqueID'";
        $resultFrom = mysqli_query($connection, $queryFrom);

        if ($resultFrom && mysqli_num_rows($resultFrom) == 1) {
            $rowFrom = mysqli_fetch_assoc($resultFrom);
            $balanceFrom = $rowFrom['money'];

            if ($balanceFrom >= $amount && $amount > 0) {
                // Deduct funds from the "from" account
                $newBalanceFrom = $balanceFrom - $amount;
                $updateQueryFrom = "UPDATE `account_info` SET money = '$newBalanceFrom' WHERE uniqueID = '$fromUniqueID'";
                mysqli_query($connection, $updateQueryFrom);

                // Add funds to the "to" account
                $queryTo = "SELECT money FROM `account_info` WHERE uniqueID = '$toUniqueID'";
                $resultTo = mysqli_query($connection, $queryTo);

                if ($resultTo && mysqli_num_rows($resultTo) == 1) {
                    $rowTo = mysqli_fetch_assoc($resultTo);
                    $balanceTo = $rowTo['money'];
                    $newBalanceTo = $balanceTo + $amount;
                    $updateQueryTo = "UPDATE `account_info` SET money = '$newBalanceTo' WHERE uniqueID = '$toUniqueID'";
                    mysqli_query($connection, $updateQueryTo);

                    // Funds transfer successful
                    echo "Funds transfer successful.";
                } else {
                    echo "Invalid 'To Account' ID or insufficient balance.";
                }
            } else {
                echo "Insufficient balance in the '$fromAccountType' account OR negative value.";
            }
        } else {
            echo "Invalid 'From Account' ID.";
        }
    }
?>
