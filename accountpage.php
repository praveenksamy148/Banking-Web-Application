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
    // Create a connection
    $conn = mysqli_connect("localhost", "root", "", "bank_users");
    
    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $sql = "SELECT money, accID, accType, uniqueID FROM account_info";
    $result = mysqli_query($conn, $sql); 
    
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
                if ($row["accType"] == 1) {
                    $accountInfoMatrix["accType"][] = "Checking";
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
   
    <header>
        <a href="Home.html"><img id='logo' width='300' height='50' src="logo.png"></a>
        <div class="navbar"><a href='MusaHome.html'>Home</a></div>
        <div class="navbar"><a href='withdraw.php'>Withdraw Funds</a></div>       
        <div class="navbar"><a href='deposits.html'>Make a Deposit</a></div>      
        <div class="navbar"><a href='transfers.html' style='flex-grow: 1;'>Transfer Funds</a></div>
        <div class="navbar"><a href='logout.php'>Log Out</a></div>
        <div class="navbar"><a href = "NewAccConfirm.php">Create Account</a></div>
        <div class="navbar"><a href = "accountDeletion.php">Delete Account</a></div>
        <div class = "navbar">
        <script>
            var countdown = <?php echo json_encode($remaining_time);?>; 
            var minutes = Math.floor(countdown / 60); 
            var seconds = countdown % 60; 
            document.getElementById('time').textContent = countdown; 

        </script>
        <h3>Live Session   </h2>
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
            <h1><?php echo $accountInfoMatrix["accType"][0]?> ...<?php echo $accountInfoMatrix["accID"][0] ?></h1>
            <h3>Available balance</h3>
            <h2><?php echo $accountInfoMatrix["money"][0]?></h2>
            <div class="account-options-container">
                <p class="account-options"><a href='accountdetails.html'>Account Details</a></p>
                <p class="account-options"><a href='carddetails.html'>Delete Account</a></p>
                <p class="account-options"><a href='bills.html'>Pay Bills</a></p>
                <p class="account-options"><a href='transfers.html'>Transfer</a></p>
                <p class="account-options"><a href='deposits.html'>Deposit</a></p>
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
                    <h1>" . $accountInfoMatrix["accType"][$i] . " ..." . $accountInfoMatrix["accID"][$i] . "</h1>
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
        
    </body>
</html>