<?php 
session_start(); 
if(isset($_SESSION["user"])){
    header("location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="NewAccStyle.css">
</head>
<body>
    <div class="header">
        <h1 class="name">Welcome to Bank!</h1>
        <h3>Please follow the steps below to create a new account:</h3>
    </div>
    <div class="Form">
        <form action="NewAccount.php" method="post">
        <?php
            if (isset($_POST["submit"])) {
                $accType = $_POST["accType"];
                $money = $_POST["money"];
                require_once "database.php";

                $check = false;
                $errorcount = 0;
                while (!$check) 
                {
                    $possibleID = rand(111111111, 999999999);
                    $sql = "SELECT accID FROM account_info WHERE accID = $possibleID";
                    $accIDCheck = mysqli_query($connection, $sql);
                    $test = mysqli_num_rows($accIDCheck);
                    
                    if (mysqli_num_rows($accIDCheck) == 0) 
                    {
                        $accID = $possibleID;
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
                $userID = $_SESSION("userID");
                $sql = "INSERT INTO account_info (accID, money, accType, userID) VALUES ('$accID', '$money', '$accType')";
                $results = mysqli_query($connection, $sql);
                if ($results) {
                    // header("Location: login.php");
                } else {
                    echo "<div class='alert alert-danger'>Unable to Register</div>";
                    echo mysqli_error($connection);
                }
            }
        ?>
            <div class="form-group">
                <input type="text" name="accType" placeholder="Account Type">
            </div>
            <div class="form-group">
                <input type="number" name="money" placeholder="Money">
            </div>
            <div class="form-group">
                <input type="submit" value="Enter" name="submit">
            </div>
        </form>
    </div>
</body>
</html>
