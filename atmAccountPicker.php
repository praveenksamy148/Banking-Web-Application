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
    </style>
</head>
<body>
    <div class="form-container">
    <?php 
        if(isset($_POST["confirm"])){
            $_SESSION["accountID"] = $_POST["dropdown"];
            header("location: atmMenu.php");
        }
        ?>
        <form action = "atmAccountPicker.php" method = "post" enctype="multipart/form-data">
            <label for="dropdown">Select an account:</label>
            <select id="dropdown" name="dropdown">
                <?php
                    $userID = $_SESSION["userID"];
                    require_once "database.php";
                    $sql = "SELECT uniqueID FROM account_info WHERE accID = $userID";
                    $result = $connection->query($sql);
                    
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
                <br>
                <button type="submit" name="confirm">Confirm</button>
            </form>
            <button type="button" onclick = "window.location.href = 'accountpage.php'" class="backButton">Go Back</button>
        </div>
    </div>
</body>
</html>
