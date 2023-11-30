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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Of Musa: Transfer</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="fundTransfer.css">
</head>
    <header>
        <a href="MusaHome.html"><img id='logo' width='300' height='50' src="logo.png"></a>
        <div class="navbar"><a href='logMusaHome.php'>Home</a></div>   
        <div class="navbar"><a href = "atmAccountPicker.php">ATM</a></div>   
        <div class="navbar"><a href='checkdeposit.php'>Make a Deposit</a></div>      
        <div class="navbar"><a href = "NewAccConfirm.php">Create Account</a></div>
        <div class="navbar"><a href = "accountDeletion.php">Delete Account</a></div>
        <div class="navbar"><a href = "accountpage.php">User Dashboard</a></div>
        <div class="navbar"><a href='logout.php'>Log Out</a></div>
        <div class = "navbar">
        <script>
            var countdown = <?php echo json_encode($remaining_time);?>; 
            var minutes = Math.floor(countdown / 60); 
            var seconds = countdown % 60; 
            document.getElementById('time').textContent = countdown; 

        </script>
        <h3 style = "color: white;"><b>Session Countdown: </b></h3>
        <h4 id = "time" style = "color: white;"> &nbsp Minutes:
            <script type="text/javascript" >
            document.write(minutes)
            </script>
            Seconds:
            <script type="text/javascript">
            document.write(seconds)
            </script>
      </h4>
        </div>
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
            $accounts = $result->fetch_all(MYSQLI_ASSOC); // Fetch all accounts once
            ?>
            <div class="select_style">
                <h5>Select an account:</h5>
                <select id="fromAccount" name="fromAccount" onchange="updateToAccountDropdown()">
                    <?php foreach ($accounts as $row): ?>
                        <option value="<?php echo $row['uniqueID']; ?>">
                            <?php echo $row['uniqueID']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <div class="select_style">
                <h5>Transfer to account:</h5>
                <select id="toAccount" name="toAccount">
                    <?php foreach ($accounts as $row): ?>
                        <option value="<?php echo $row['uniqueID']; ?>">
                            <?php echo $row['uniqueID']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <input type="number" step="0.01" name="amount" placeholder="Amount:" class="form-control" required>
        </div>
       <div class="form-group" style = "text-align: center;">
                <input type="submit" value="Transfer Funds" class="btn-primary">
        </div>
        <div style="text-align: center;">
            <button onclick="window.location.href = 'userFundsTransfer.php'" id="buttonSize">Transfer to Different User Here</button>
        </div>
    </form>
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
<script>
function updateToAccountDropdown() {
    var fromAccount = document.getElementById('fromAccount');
    var toAccount = document.getElementById('toAccount');
    var selectedFromAccount = fromAccount.value;

    // Clear current options in toAccount
    toAccount.innerHTML = '';

    // Add options to toAccount excluding the selected option in fromAccount
    <?php foreach ($accounts as $row): ?>
    if ('<?php echo $row['uniqueID']; ?>' !== selectedFromAccount) {
        var option = document.createElement('option');
        option.value = '<?php echo $row['uniqueID']; ?>';
        option.text = '<?php echo $row['uniqueID']; ?>';
        toAccount.appendChild(option);
    }
    <?php endforeach; ?>
}
// Initialize toAccount on page load
document.addEventListener('DOMContentLoaded', updateToAccountDropdown);
</script>
    <?php

    require_once 'database.php';

    if (isset($_POST["transferFunds"])) {
        // $stmt = mysqli_prepare($connection, $uniqueID);
        // mysqli_stmt_bind_param($stmt, "s", $_POST["fromAccount"]);
        // mysqli_stmt_execute($stmt);
        // $result = mysqli_stmt_get_result($stmt);
        $fromUniqueID = $_POST["fromAccount"];
        $toUniqueID = $_POST["toAccount"];
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
                    echo "<div class='alert alert-success'>Successfully Transfered!</div>";
                } else {
                    echo "<div class='alert alert-danger'>Invalid 'To Account' ID or insufficient balance.</div>";

                }
            } else {
                echo "<div class='alert alert-danger'>Insufficient balance inputted OR negative value.</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Invalid 'From Account' ID.</div>";
        }
    }
?>