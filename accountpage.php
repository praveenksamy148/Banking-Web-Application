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
<?php
    require_once "database.php"; 
    $sql = "SELECT money, accID, accType, uniqueID FROM account_info";
    $result = mysqli_query($connection, $sql); 
    
    $accountInfoMatrix = array (
        "accID" => array(),
        "money" => array(),
        "accType" => array(),
        "userID" => array()
    ); 

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)){
            if($_SESSION["userID"] == $row["accID"]){
                $accountInfoMatrix["accID"][] = $row["uniqueID"];
                $accountInfoMatrix["money"][] = $row["money"];
                $accountInfoMatrix["userID"][] = $row["accID"];
                if ($row["accType"] == "Checkings") {
                    $accountInfoMatrix["accType"][] = "Checkings";
                } else {
                    $accountInfoMatrix["accType"][] = "Savings";
                }
            }
        }
    }
    $numOfAccounts = 0; 
    for ($i = 0; $i < count($accountInfoMatrix["userID"]); $i++) {
        if ($_SESSION["userID"] == $accountInfoMatrix["userID"][$i]) {
            $numOfAccounts++;
        }
    }

    if (!$accountInfoMatrix["accType"][0]) {
        header("Location: /NewAccConfirm.php");
        exit();
    }
    


?>


<html lang="en">
    <head>
        <title>Account Balances - Bank of MUSA</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="styles.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="BANK Checking and Savings Accounts viewpage">
    </head>
   
   <!--Alt shadows for the accounts box-shadow: rgba(80, 23, 23, 0.4) 5px 5px, rgba(80, 23, 23, 0.3) 10px 10px, rgba(80, 23, 23, 0.2) 15px 15px, rgba(80, 23, 23, 0.1) 20px 20px, rgba(80, 23, 23, 0.05) 25px 25px;-->
   
    <header style="display: flex; align-items: center;">
        <a href="Home.html"><img id='logo' width='200' height='50' src="logo.png"></a>
        <div class="navbar"><a href='logMusaHome.php'>Home</a></div>
        <div class="navbar"><a href='withdraw.php'>Withdraw Funds</a></div>       
        <div class="navbar"><a href='checkdeposit.php'>Make a Deposit</a></div>      
        <div class="navbar"><a href='fundsTransfer.php' style='flex-grow: 1;'>Transfer Funds</a></div>
        <div class="navbar"><a href='logout.php'>Log Out</a></div>
        <div class="navbar"><a href = "NewAccConfirm.php">Create Account</a></div>
        <div class="navbar"><a href = "accountDeletion.php">Delete Account</a></div>
        <div class="navbar"><a href = "atmAccountPicker.php">ATM</a></div>
        <div class = "navbar">
        <script>
            var countdown = <?php echo json_encode($remaining_time);?>; 
            var minutes = Math.floor(countdown / 60); 
            var seconds = countdown % 60; 
            document.getElementById('time').textContent = countdown; 

        </script>
        <h3><b>Session Countdown: </b></h3>
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
    <body>
        <!-- <h1>Welcome Back <?php echo $_SESSION["firstName"]?><h1> -->
        <div class="first-account-container">
            <div class="account-container-top">
                <h1>Ignore this.</h1>
            </div>
            <h1><?php echo $accountInfoMatrix["accType"][0]?> <?php echo " #". $accountInfoMatrix["accID"][0] ?></h1>
            <h3>Available balance</h3>
            <h2><?php echo " $" .$accountInfoMatrix["money"][0]?></h2>
            <div class="account-options-container">
                <p class="account-options"><a href='accountdetails.html'>Account Details</a></p>
                <p class="account-options"><a href='carddetails.html'>Delete Account</a></p>
                <p class="account-options"><a href='bills.html'>Pay Bills</a></p>
                <p class="account-options"><a href='transfers.html'>Transfer</a></p>
                <p class="account-options"><a href='check.html'>Deposit</a></p>
            </div>
        </div>
        <?php
            // Class attributes constants 
            $accountContainerClass = "account-container";
            $accountContainerTopClass = "account-container-top";
            $accountOptionsContainerClass = "account-options-container";
            $accountOptionsClass = "account-options";

            for ($i = 1; $i < $numOfAccounts; $i++) {
                echo "<div class=\"$accountContainerClass\">
                    <div class=\"$accountContainerTopClass\">
                        <h1>Ignore this.</h1>
                    </div>
                    <h1>" . $accountInfoMatrix["accType"][$i] . " #" . $accountInfoMatrix["accID"][$i] . "</h1>
                    <h3>Available balance</h3>
                    <h2>$" . $accountInfoMatrix["money"][$i] . "</h2>
                    <div class=\"$accountOptionsContainerClass\">
                        <p class=\"$accountOptionsClass\"><a href='accountdetails.html'>Account Details</a></p>
                        <p class=\"$accountOptionsClass\"><a href='carddetails.html'>My Card</a></p>
                        <p class=\"$accountOptionsClass\"><a href='bills.html'>Pay Bills</a></p>
                        <p class=\"$accountOptionsClass\"><a href='transfers.html'>Transfer</a></p>
                        <p class=\"$accountOptionsClass\"><a href='deposits.html'>Deposit</a></p>
                    </div>
                </div>";


            }

        ?>
        <button id="showTableBtn">Show Transactions</button>
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
<table id="transactionsTable" style="display: none;">
    <?php 
        require_once "database.php"; 
        // session_start();  // Ensure session is started
        $accID = $_SESSION["userID"]; 
        $query = "SELECT transaction, time FROM user_transactions WHERE accID = '$accID' ORDER BY time DESC"; 
        $result = mysqli_query($connection, $query); 
        if(!$result){
            die("Query failed: " . mysqli_error($connection)); 
        }
    ?>
     <tr>
        <th style="border: 1px solid black; padding: 8px; background-color: #f2f2f2;">Account Transaction</th>
        <th style="border: 1px solid black; padding: 8px; background-color: #f2f2f2;">Transaction Date & Time</th>
    </tr>
    <?php 
        while($row = $result->fetch_assoc()) {
            $accTransactions = $row["transaction"];
            $time = $row["time"];
            echo "<tr><td>$accTransactions</td><td>$time</td></tr>";
        }
    ?>
</table>
    <script>
        document.getElementById('showTableBtn').addEventListener('click', function() {
            var table = document.getElementById('transactionsTable');
            if (table.style.display === 'none') {
                table.style.display = 'block';
            } else {
                table.style.display = 'none';
            }
        });
    </script>
    
    </body>
</html>