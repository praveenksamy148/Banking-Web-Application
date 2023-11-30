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
    <title>Bank of Musa - ATM</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #1e3859;
            color: white;
            font-family: Arial, sans-serif;
        }

        .form-container {
            text-align: center;
            background-color: #2c4d75; 
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        select, button {
            padding: 10px;
            margin: 10px;
            font-size: 16px;
        }

        button {
            background-color: #02A200;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .backButton {
            background-color: #A90000;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .moneyText {
            color: lightyellow;
        }
    </style>
</head>
<body>
<div class="form-container">
    <h1>How much would you like to withdraw?</h1>
    <?php 
        require_once "database.php";
        $currAccID = $_SESSION["accountID"];
        $sql = "SELECT money FROM account_info WHERE uniqueID = $currAccID";
        $result = $connection->query($sql);
        if ($result) {
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $money = $row['money'];

                echo "<b class='moneyText'>Balance: $$money</b>";
            } else {
                echo "No records found";
            }
        } else {
            echo "Error in the query: " . $connection->error;
        }
    ?>
    <br>
    <?php
        if(isset($_POST["confirm"])){
            require_once "database.php";
            $withdrawAmount = $_POST["moneyAmount"];
            $currAccID = $_SESSION["accountID"];
            $userID = $_SESSION["userID"];
            $sql = "SELECT money FROM account_info WHERE uniqueID = $currAccID";
            $sql2 = "SELECT pinNumber FROM user_info WHERE ID = $userID";
            $result = $connection->query($sql);
            $result2 = $connection->query($sql2);
            if ($result && $result2) {
                if ($result->num_rows > 0 && $result2->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $row2 = $result2->fetch_assoc();
                    $newMoney = $row['money'] - $withdrawAmount;
                    if ($newMoney < 0) {
                        echo "Withdraw ammount cannot exceed balance.";
                    } else if ($withdrawAmount < 0){
                        echo "Invalid withdrawl amount.";
                    } else if ($_POST["PIN"] != $row2["pinNumber"]) {
                        echo "Incorrect PIN number.";
                    } else {
                        $query2 = "UPDATE account_info SET money = '$newMoney' WHERE uniqueID = '$currAccID'";
                        $result2 = mysqli_query($connection, $query2);
                        if(!$result2){
                            die("Query failed: ". mysqli_error($connection));
                        }
                        echo "<script>
                                alert('You have successfully withdrawn $$withdrawAmount. Your new balance is $$newMoney.');
                                window.location.href='atmMenu.php';
                            </script>";
                    }
                } else {
                    echo "No records found";
                }
            } else {
                echo "Error in the query: " . $connection->error;
            }
        }
    ?>
    <form action = "atmWithdraw.php" method = "post" enctype="multipart/form-data">
        <br>
        <input type = "number" placeholder="Withdraw Amount" name="moneyAmount" step="0.01" required>
        <br>
        <input type="password" name="PIN" pattern="\d{5}" title="Please enter a 5-digit numeric password" placeholder="PIN" required>
        <br>
        <button type="submit" name="confirm">Confirm</button>
    </form>
    <button type="button" onclick = "window.location.href = 'atmMenu.php'" class="backButton">Go Back</button>
</div>
</body>
</html>
