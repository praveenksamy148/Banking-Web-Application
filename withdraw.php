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

<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Banking Account Creation</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="regStyling.css">
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="withdraw.css">
</head>

<header>
        <a href="Home.html"><img id='logo' width='350' height='50' src="logo.png"></a>
        <div class="navbar"><a href='MusaHome.html'>Home</a></div>
        <div class="navbar"><a href='withdraw.php'>Withdraw Funds</a></div>       
        <div class="navbar"><a href='checkDeposit.php'>Make a Deposit</a></div>      
        <div class="navbar"><a href='fundsTransfer.php' style='flex-grow: 1;'>Transfer Funds</a></div>
        <div class="navbar"><a href = "NewAccConfirm.php">Create Account</a></div>
        <div class="navbar"><a href = "accountDeletion.php">Delete Account</a></div>
        <div class="navbar"><a href='logout.php'>Log Out</a></div>
        <div class = "navbar">
        <script>
            var countdown = <?php echo json_encode($remaining_time);?>; 
            var minutes = Math.floor(countdown / 60); 
            var seconds = countdown % 60; 
            document.getElementById('time').textContent = countdown; 

        </script>
        <h4>Session Countdown: </h4>
        <h4 id = "time"> &nbsp Minutes:
            <script type="text/javascript">
            document.write(minutes)
            </script>
            Seconds:
            <script type="text/javascript">
            document.write(seconds)
            </script>
      </h4>
        </div>

</header>

    <div class = "container">
        <h3>Please follow the steps below to withdraw from an account:</h3>

    <!-- FORM HTML + PHP -->
    <div class="form-group">
        <form action="withdraw.php" method="post">
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
            <br>
            <br>
            <div class = "amount_input">
                <input type="number" name="amount" placeholder="Amount: ">
            </div>
            <br>
            <div>
                <input type="submit" value="Enter" name="submit">
            </div>
    <!-- AFTER SUBMIT PROCESSING -->
        <?php
        if(isset($_POST["submit"])) 
        {
            $selectedAcc = $_POST["selectedAcc"];
            $amount = $_POST["amount"];
            $userID = $_SESSION["userID"];

            if (!empty($selectedAcc)) //Check if selected account is empty
            {
                $sql = "SELECT money FROM account_info WHERE uniqueID = $selectedAcc"; //Find money
                $result = $connection->query($sql);
                
                if ($result) 
                {
                    $row = $result->fetch_assoc();
                    $currentMoney = $row["money"];
                    $newMoney = $currentMoney - $amount;
                    if($newMoney >= 0)
                    {
                        $updateSql = "UPDATE account_info SET money = $newMoney WHERE accID = $selectedAcc"; //Update money
                        $updateResult = $connection->query($updateSql);
                        if ($updateResult) 
                        {
                            echo "<div class='alert alert-success'>Successfully Withdrew From Account!</div>";
                            require_once "database.php"; 
                            $userID = $_SESSION["userID"]; 
                            $transaction = "Withdrew $". $amount ." from Account Number: " . $selectedAcc; 
                            $transactions = "INSERT INTO user_transactions (accID, transaction) VALUES ('$userID', '$transaction')"; 
                            $document = $connection->query($transactions); 
                            if(!$document){
                                die("Failed to upload documentation"); 
                            }
                        } 
                        else 
                        {
                            echo "<div class='alert alert-danger'>Failed to update money in the account!</div>";
                        }
                    } 
                    else 
                    {
                        echo "<div class='alert alert-danger'>Cannot have a negative balance!</div>";
                    }
                } 
                else 
                {
                    echo "<div class='alert alert-danger'>Cannot find account ID!</div>";
                }
            }
            else 
            {
               echo "ERROR: Selected account is empty.";       
            }
        }
        
        ?>
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