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

    <!-- Title -->
    <head>
        <title>Bank of Musa - Account Deletion</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <link rel="stylesheet" href="accountDeletionStyles.css">
    </head>

    <!-- Top Bar -->
    <header>
        <img src = "logo.png" alt="Bank of Musa" width = 300 height = 50>
        <div class="topRight">
        <a href='MusaHome.html'>Home</a>
        <a href='withdraw.php'>Withdraw Funds</a>    
        <a href='checkDeposit.php'>Make a Deposit</a>    
        <a href='accountpage.php' style='flex-grow: 1;'>User Dashboard</a>
        <a href='logout.php'>Log Out</a>
        <a href = "NewAccConfirm.php">Create Account</a>
        <a href = "accountDeletion.php">Delete Account</a>
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
        </div>
    </header>

    <div class="grayBar"></div>

    <!-- Everything Else -->
    <body>
        <!-- Center Box -->
        <div class = "centerBox">
            <?php 
            if(isset($_POST["confirm"])){
                $dropdown = $_POST["dropdown"]; 
                require_once "database.php"; 
                $query = "DELETE FROM account_info WHERE uniqueID = '$dropdown'";
                $result = mysqli_query($connection, $query);
                
                if(!$result){
                    die("Query failed: ". mysqli_error($connection));
                }
                
                if(mysqli_affected_rows($connection) > 0){
                    echo "<div class='alert alert-success'>Successfully Deleted Account!</div>";
                    require_once "database.php"; 
                    $userID = $_SESSION["userID"]; 
                    $transaction = "Deleted Account Number: " . $dropdown;  
                    $transactions = "INSERT INTO user_transactions (accID, transaction) VALUES ('$userID', '$transaction')"; 
                    $document = $connection->query($transactions); 
                    if(!$document){
                        die("Failed to upload documentation"); 
                    }else{
                        echo "<div class='alert alert-success'>Uploaded!</div>";
                    }
                } else {
                    echo "No account found to delete.";
                }
                // mysqli_close($connection); 
            }
            ?>
            <form action = "accountDeletion.php" method = "post">
                <?php
                $userID = $_SESSION["userID"];
                require_once "database.php";
                $sql = "SELECT uniqueID FROM account_info WHERE accID = $userID";
                $result = $connection->query($sql);
                ?>
                <p>Which account would you like to delete?</p>
                <select name = "dropdown">
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
                    mysqli_close($connection);
                    ?>
                </select>
                <br></br>
                <button class="confirm-button" type = "Confirm" name = "confirm">Confirm</button>
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
    </body>

</html>