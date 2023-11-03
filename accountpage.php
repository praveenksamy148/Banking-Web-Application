<?php 
session_start(); 
if(!isset($_SESSION["authenticate"])){
    header("location: login.php");
}

?>
<!DOCTYPE html>
<?php
    
    // Create a connection
    $conn = mysqli_connect("localhost", "root", "", "bank users");
    
    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $numOfAccounts = 0; 
    $sql = "SELECT * FROM acc_info";
    $result = mysqli_query($conn, $sql); 

    // $accountInfoMatrix = array (
    //     $accountIdArray => array();


    // )
    
    $accountIdArray = array();

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)){
            $accountIdArray[] = $row["account_id"];
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
        <div class="navbar"><a href='NewAccConfirm.php'>Checking & Savings</a></div>       
        <div class="navbar"><a href='deposits.html'>Make a Deposit</a></div>      
        <div class="navbar"><a href='transfers.html' style='flex-grow: 1;'>Transfer Funds</a></div>
        <div class="navbar"><a href='logout.php'>Log Out</a></div>
        <div class="navbar"><a href = "NewAccConfirm.php">Create Account</a></div>
        
    </header>
    <body>
        <!-- <h1>Welcome Back <?php echo $_SESSION["firstName"]?><h1> -->
        <div class="first-account-container">
            <div class="account-container-top">
                <h1>Ignore this.</h1>
            </div>
            <h1>Checking ...3151</h1>
            <h3>Available balance</h3>
            <h2>$20,000.00</h2>
            <div class="account-options-container">
                <p class="account-options"><a href='accountdetails.html'>Account Details</a></p>
                <p class="account-options"><a href='carddetails.html'>My Card</a></p>
                <p class="account-options"><a href='bills.html'>Pay Bills</a></p>
                <p class="account-options"><a href='transfers.html'>Transfer</a></p>
                <p class="account-options"><a href='deposits.html'>Deposit</a></p>
            </div>
        </div>
        <div class="account-container">
            <div class="account-container-top">
                <h1>Ignore this.</h1>
            </div>
            <h1>Savings ...3011</h1>
            <h3>Available balance</h3>
            <h2>$1,245,028.04</h2>
            <div class="account-options-container">
                <p class="account-options"><a href='accountdetails.html'>Account Details</a></p>
                <p class="account-options"><a href='carddetails.html'>My Card</a></p>
                <p class="account-options"><a href='bills.html'>Pay Bills</a></p>
                <p class="account-options"><a href='transfers.html'>Transfer</a></p>
                <p class="account-options"><a href='deposits.html'>Deposit</a></p>
            </div>
        </div>
        <div class="account-container">
            <div class="account-container-top">
                <h1>Ignore this.</h1>
            </div>
            <h1>Savings ...2987</h1>
            <h3>Available balance</h3>
            <h2>$134.46</h2>
            <div class="account-options-container">
                <p class="account-options"><a href='accountdetails.html'>Account Details</a></p>
                <p class="account-options"><a href='carddetails.html'>My Card</a></p>
                <p class="account-options"><a href='bills.html'>Pay Bills</a></p>
                <p class="account-options"><a href='transfers.html'>Transfer</a></p>
                <p class="account-options"><a href='deposits.html'>Deposit</a></p>
            </div>
        </div>
    </body>
</html>