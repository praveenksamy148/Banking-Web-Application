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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banking Account Creation</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="regStyling.css">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="NewAccStyle.css">
</head>

<header>
        <a href="Home.html"><img id='logo' width='300' height='50' src="logo.png"></a>
        <div class="navbar"><a href='MusaHome.html'>Home</a></div>
        <div class="navbar"><a href='withdraw.php'>Withdraw Funds</a></div>       
        <div class="navbar"><a href='checkDeposit.php'>Make a Deposit</a></div>      
        <div class="navbar"><a href='fundsTransfer.php' style='flex-grow: 1;'>Transfer Funds</a></div>
        <div class="navbar"><a href = "accountpage.php">User Dashboard</a></div>
        <div class="navbar"><a href = "accountDeletion.php">Delete Account</a></div>
        <div class="navbar"><a href='logout.php'>Log Out</a></div>
        <div class = "navbar">
        <script>
            var countdown = <?php echo json_encode($remaining_time);?>; 
            var minutes = Math.floor(countdown / 60); 
            var seconds = countdown % 60; 
            document.getElementById('time').textContent = countdown; 

        </script>
        <h><b>Session Countdown:</b> </h>
        <h id = "time"> &nbsp Minutes:
            <script type="text/javascript">
            document.write(minutes)
            </script>
            Seconds:
            <script type="text/javascript">
            document.write(seconds)
            </script>
      </h>

        </div>

</header>

<body>
    <div class="container">
        <h2> Account Creation </h2>
        <br>
        <h4>Please follow the steps below to create a new account:</h4>
    <div class = "form-group">
        <form action="NewAccConfirm.php" method="post">
            <?php
            if(isset($_POST["submit"])){
                $accType = $_POST["accType"]; 
                $money = $_POST["money"]; 
                $accID = $_SESSION["userID"];
                $check = false;
                $errorcount = 0;

                if ($money > 0){
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
                    $sql = "INSERT INTO account_info (money, accType, accID, uniqueID) VALUES ('$money','$accType', '$accID', '$uniqueID')";
                    $results = mysqli_query($connection, $sql); 

                    if($results){
                        echo "<div class='alert alert-success'>Successfully Created Account!</div>";
                        require_once "database.php"; 
                        $userID = $_SESSION["userID"]; 
                        if($accID == "Checkings"){
                            $transaction = "Created New Checkings Account. Account Number = " . $uniqueID . " Initial Deposit = " . $money; 

                        }else{
                            $transaction = "Created New Savings Account. Account Number = " . $uniqueID . " Initial Deposit = " . $money; 
                        }
                        $transactions = "INSERT INTO user_transactions (accID, transaction) VALUES ('$userID', '$transaction')"; 
                        $document = $connection->query($transactions); 
                        if(!$document){
                            die("Failed to upload documentation"); 
                        }
                    }else{
                        echo "<div class= 'alert alert-danger'>Unable to Register</div>";
                        echo mysqli_error($connection); 
                    }
                } else {
                    echo "Cannot create an account with a negative balance.";
                }

            }
             ?>
            <br>
            <div class="select_style">
                <select name = "accType" required>
                    <option value = "" selected disabled>Account Type</option>
                    <option value="Savings">Savings</option>
                    <option value="Checkings">Checkings</option>
                </select>
            </div>
            <br>
            <br>
            <div class = "form-group">
                <input type = "number" name = "money" placeholder = "Initial Deposit: " step = 0.01 required>
            </div>
            <div class = "form-group">
                <input type = "submit" value = "Enter" name = "submit" >
            </div>
        </form>
    </div>
    </div>
    <script>
    var countdown = <?php echo json_encode($remaining_time); ?>; 
    var timer = setInterval(function() {
        countdown--;
        var minutes = Math.floor(countdown / 60); 
        var seconds = countdown % 60; 
        document.getElementById('time').textContent = minutes + " Minutes : " + seconds + " Seconds"; 
        if(countdown <= 0) clearInterval(timer);
    }, 1000);
    </script>
</body>
</html>
