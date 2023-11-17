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

    <!-- Title -->
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Bank of Musa - Account Deletion</title>
        <link rel="stylesheet" href="accountDeletionStyles.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    </head>

    <!-- Top Bar -->
    <header>
        <div class = "navbar"><a href="Home.html"><img id='logo' width='300' height='50' src="logo.png"></a></div>
        <div class="navbar"><a href='MusaHome.html'>Home</a></div>
        <div class="navbar"><a href='withdraw.php'>Withdraw Funds</a></div>       
        <div class="navbar"><a href='checkdeposit.php'>Make a Deposit</a></div>      
        <div class="navbar"><a href='fundsTransfer.php' style='flex-grow: 1;'>Transfer Funds</a></div>
        <div class="navbar"><a href='logout.php'>Log Out</a></div>
        <div class="navbar"><a href = "NewAccConfirm.php">Create Account</a></div>
        <div class="navbar"><a href = "accountDeletion.php">Delete Account</a></div>
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

    <div class="grayBar"></div>

    <!-- Everything Else -->
    <body>
        <!-- Center Box -->
        <div class = "centerBox">
            <?php 
            if(isset($_POST["confirm"]) && isset($_FILES['image'])){
                $dropdown = $_POST["dropdown"]; 
                $money = $_POST["moneyAmount"];
                //$check = $_POST["checkImage"];
                require_once "database.php"; 
                $query = "SELECT * FROM account_info WHERE uniqueID = '$dropdown'";
                $result = mysqli_query($connection, $query);

                if ($money <= 0) {
                    die("Invalid amount depositted");
                }

                if (mysqli_num_rows($result) == 1){
                    $row = mysqli_fetch_assoc($result);
                    $newMoney = $row["money"] + $money;
                    $query2 = "UPDATE account_info SET money = '$newMoney' WHERE uniqueID = '$dropdown'";
                    $result2 = mysqli_query($connection, $query2);
                    if(!$result2){
                        die("Query failed: ". mysqli_error($connection));
                    }

                    $targetDirectory = "checkUploads/";
                    $targetFile = $targetDirectory . time() . basename($_FILES["image"]["name"]);
                    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION)); 
                    if($fileType != "img"){
                        echo "<script>
                                alert('Only IMG files are accepted!');
                                window.location.href='checkDeposit.php';
                              </script>";
                        exit();
                    }
                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                        $imagePath = $targetFile;
                        
                        $query3 = "INSERT INTO check_deposit (amountDeposited, uniqueID, checkImagePath) VALUES ('$money', '$dropdown', '$imagePath')";

                        if ($connection->query($query3) === TRUE) {
                            echo "<div class='alert alert-success'>Successfully Uploaded Check!</div>";
                            require_once "database.php"; 
                            $userID = $_SESSION["userID"]; 
                            $transaction = "Successfully Deposited Check to Account: " . $dropdown . " Amount = $". $money; 
                            $transactions = "INSERT INTO user_transactions (accID, transaction) VALUES ('$userID', '$transaction')"; 
                            $document = $connection->query($transactions); 
                            if(!$document){
                                die("Failed to upload documentation"); 
                            }
                        } else {
                            echo "Error: " . $sql . "<br>" . $conn->error;
                        }
                    } else {
                        die("Error uploading the image.");
                    }

                }
                
                if(!$result){
                    die("Query failed: ". mysqli_error($connection));
                }
            }
            ?>
            <form action = "checkDeposit.php" method = "post" enctype="multipart/form-data">
                <p>How much money are you depositing?</p>
                <input type="moneyAmount" placeholder="Enter amount" name="moneyAmount">
                <p>Which account would you like to deposit into?</p>
                <?php
                    $userID = $_SESSION["userID"];
                    require_once "database.php";
                    $sql = "SELECT uniqueID FROM account_info WHERE accID = $userID";
                    $result = $connection->query($sql);
                ?>
                <div class = "select_style">
                    <h5> Select an account: </h5>
                    <select name="dropdown">
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
                <p>Please upload the photo</p>
                <input type="file" name="image">
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