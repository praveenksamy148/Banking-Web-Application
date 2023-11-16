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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banking Account Creation</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="regStyling.css">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="accountDeletionStyles.css">
    </head>

    <!-- Top Bar -->
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
        <h>Live Session</h>
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

    <!-- Everything Else -->
    <main>
        <!-- Center Box -->
        <div class = "container">
        <h2> Account Deletion </h2>
        <br>
        <h4>Please follow the steps below to delete an account:</h4>
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
                    echo "Account deleted successfully.";
                } else {
                    echo "No account found to delete.";
                }
                mysqli_close($connection); 
            }
            ?>
            <form action = "accountDeletion.php" method = "post">
                <?php
                $userID = $_SESSION["userID"];
                require_once "database.php";
                $sql = "SELECT uniqueID FROM account_info WHERE accID = $userID";
                $result = $connection->query($sql);
                ?>
                <h5>Which account would you like to delete?</h5>
                <div class = "select_style">
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
                </div>
                <br></br>
                <div class = "form-group">
                <input type = "submit" value = "Submit" name = "submit" >
            </div>
            </form>
        </div>
    </main>
</html>