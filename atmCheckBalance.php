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
            font-size: 50px;
            color: lightyellow;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <?php 
        require_once "database.php";
        $currAccID = $_SESSION["accountID"];
        echo "<h1>The balance on account $currAccID is</h1>";
        $sql = "SELECT money FROM account_info WHERE uniqueID = $currAccID";
        $result = $connection->query($sql);
        if ($result) {
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $money = $row['money'];

                echo "<b class='moneyText'>$$money</b>";
            } else {
                echo "No records found";
            }
        } else {
            echo "Error in the query: " . $connection->error;
        }

        $connection->close();
        ?>
        <br>
        <button type="button" onclick = "window.location.href = 'atmMenu.php'" class="backButton">Go Back</button>
    </div>
</body>
</html>
