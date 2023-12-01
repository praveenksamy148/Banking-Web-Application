
<?php
    // Create a connection
    $conn = mysqli_connect("localhost", "root", "", "bank users");
        
    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // double check your column/table names here 
    $sql = "SELECT uniqueID, transDescription, transTime, transValue, fromAccount, fromAccountType, toAccount, toAccountType, newAccount, newAccountType, deletedAccount, deletedAccountType  FROM transaction_info";
    $result = mysqli_query($conn, $sql);

    $transactionInfo = array (
        "description" => array(),
        "time" => array(),
        "value" => array(),
        "updatedBalance" => array()
    );

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            if ($_SESSION["userID"] == $row["uniqueID"]) {
                $transactionInfo["time"] = $row["transTime"];
                $transactionInfo["value"] = $row["transValue"];
                $transactionInfo["updatedBalance"] = $row["updatedBalance"];
                switch($row["transDescription"]) {
                    // Create
                    case 1: $transactionInfo["description"] = "Created account: " . $row["newAccountType"] . " " . $row["newAccount"] . ". Deposited $" . $row["transValue"];
                    // Delete
                    case 2: $transactionInfo["description"] = "Deleted account: " . $row["deletedAccountType"] . " " . $row["deletedAccount"];
                    // Deposit
                    case 3: $transactionInfo["description"] = "Deposited $" . $row["transValue"] . " to " . $row["toAccountType"] . " " . $row["toAccount"];
                    // Withdraw
                    case 4: $transactionInfo["description"] = "Withdrew $" . $row["transValue"] . " from " . $row["fromAccountType"] . " " . $row["fromAccount"];
                    // Transfer
                    case 5: $transactionInfo["description"] = "Transferred $" . $row["transValue"] . " from " . $row["fromAccountType"] . " " . $row["fromAccount"] . " to " . $row["toAccountType"] . " " . $row["toAccount"];
                    default: $transactionInfo["description"] = "No Transaction recorded here. Please let us know if you see this, because you shouldn't.";
                } 
            }
        }
    }
    $numOfTransactions = count($transactionInfo["description"]);

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Account Details - Bank of MUSA</title>
        <link rel='stylesheet' href='accDetailsStyles.css'>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Account Details page">
        <meta charset='UTF-8'>
        <script src="https://kit.fontawesome.com/803675de36.js"></script>
    </head>
    <body>
        <!-- Navbar -->
        <header>
            <a href="MusaHome.html"><img id='logo' width='300' height='50' src="homeImages/logo.png" title='Bank of MUSA logo'></a>
                <div class="navbar"><a href='MusaHome.html'>Home</a></div>
                <div class="navbar"><a href='NewAccConfirm.php'>Checking & Savings</a></div>       
                <div class="navbar"><a href='deposits.html'>Make a Deposit</a></div>      
                <div class="navbar"><a href='transfers.html'>Transfer Funds</a></div>
                <div class="navbar"><a href='logout.php'>Log Out</a></div>
                <div class="navbar"><a href = "NewAccConfirm.php">Create Account</a></div>            
        </header>
        <main>
            <div class="border">This is empty</div>
            <!-- Quick nav options -->
            <div class='container-quick-nav'>
                <div class='account-quick-nav' id='myAccount'><button class="open-account-modal" onclick="openAccModal()">My Account</button></div>
                <div class='account-quick-nav' id='myCard'><button class="open-card-modal" onclick="openCardModal()">My Card</button></div>
                <div class='account-quick-nav' id='deposit'><a class='quick-nav-link' href='checkDeposit.php'>Deposits</a></div>
                <div class='account-quick-nav' id='transfer'><a class='quick-nav-link' href='fundsTransfer.php'><p>Transfer</p></a></div>
                <div class='account-quick-nav' id='onlineATM'><p>Online ATM</p></div>
            </div>
            <!-- Modals -->
            <dialog class='modal' id='account-modal'>
                <div class='modal-container'>    
                    <h2>My Account</h2>
                    <p>Account Type: Checking</p>
                    <p>Account Id: 012345678</p>
                    <p>Account Address: 123 White Rd, San Jose, CA 95126</p>
                    <button class="modal-btn" onclick="closeAccModal()">Close Modal</button>
                </div>
            </dialog>
            <dialog class='modal' id='card-modal'>
                <div class="modal-container">
                    <h2>My Card</h2>
                    <div class="card">
                        <div class="stripe"></div>
                        <div class="inner-card-container">
                            <p class='card-number'>XXXX-XXXX-XXXX-1234</p>
                            <p class='card-type'>DEBIT</p>
                        </div>
                        <div class="inner-card-container">
                            <p class='card-name'>Insert Name Here</p>    
                            <p class='expiration'>Valid Thru 12/24/28</p>
                        </div>
                        <img class='card-logo' src="homeImages/logo.png" title='Bank of MUSA logo'>
                    </div>
                    <p>Account Type: Savings</p>
                    <p>Account Address: 123 White Rd, San Jose, CA 95126</p>
                    <button class="modal-btn" onclick="closeCardModal()">Close Modal</button>                    
                </div>
            </dialog>
            <script src="accountdetails.js"></script>            
            <!-- Transactions -->
            <div class="transaction-title">Transactions for "accountType" "accoundId"</div>
            <div class='container-transaction'>
                <?php
                    $transactionBoxOuter = "transaction-box-outer";
                    $transactionBoxInner = "transaction-box-inner";
                    $transactionDescription = "transaction-description";
                    $transactionTimestamp = "transaction-timestamp";
                    $right = "right";
                    $transferredValue = "transferred-value";
                    $updatedBalance = "updated-balance";

                    for ($i = 0; $numOfTransactions > $i; $i--) {
                        echo "<div class=\"$transactionBoxOuter\">
                                <div class=\"$transactionBoxInner\">
                                    <p class=\"$transactionDescription\">" . $transactionInfo["description"][$i] . "</p>
                                    <p class=\"$transactionTimestamp\">" . $transactionInfo["time"][$i] . "</p>
                                </div>  
                                <div class=\"$transactionBoxInner" . " " . "$right\">
                                    <p class=\"$transferredValue\">" . $transactionInfo["value"][$i] . "</p>
                                    <p class=\"$updatedBalance\">" . $transactionInfo["updatedBalance"][$i] . "</p>
                                </div>    
                            </div>";
                    }
                ?>
            </div>
        </main> 
        <!-- Footer --> 
        <footer>
            <div class="row">
                <div class="col" id='footer-description'>
                <a href="MusaHome.html"><img id='logo' width='300' height='50' src="homeImages/logo.png" title='Bank of MUSA logo'></a>
                    <p>When we talk about financial wellness, we're talking about trading debt and 
                        worry for security and financial well-being. It's about knowing where you stand 
                        and having a plan to get where you're going. Less about skipping lattes, more 
                        about understanding how saving for tomorrow can fit into your life today. Here at Bank of Musa we pride ourselves in our customer service, honesty, and dedication.</p>
                </div>
                <div class="col" id='location'>
                    <h3>Location</h3>
                    <p>1234 Bank Rd</p>
                    <p>San Jose, CA, USA</p>
                    <p>95127-4864</p>
                    <p>(408) 123-4567
                </div>
                <div class="col" id='footer-links-col'>
                    <h3>Links</h3>
                    <ul>
                        <li><a href="NewAccConfirm.php" class="footer-links">Open an account</a></li>
                        <li><a href="checkDeposit.php" class="footer-links">Deposit</a></li>
                        <li><a href="fundsTransfer.php" class="footer-links">Transfer</a></li>
                        <li><a href="onlineATM.html" class="footer-links">Online ATM</a></li>                                                                        
                    </ul>
                </div>
                <div class="col" id='footer-contacts'>
                    <h3>Newsletter</h3>
                    <form>
                        <i class="fa-regular fa-envelope"></i>
                        <input type="email" placeholder="Enter your email address" required>
                        <button type="submit"><i class="fa-solid fa-arrow-right"></i></button>
                    </form>
                    <div class="social-icons">
                        <i class="fa-brands fa-facebook-f"></i>
                        <i class="fa-brands fa-twitter"></i>
                        <i class="fa-brands fa-instagram"></i>
                        <i class="fa-brands fa-pinterest"></i>
                    </div>
                </div>
            </div>
            <hr>
            <p class="copyright">Bank of MUSA © 2023 - All Rights Reserved</p>
        </footer>
    </body>
</html>
