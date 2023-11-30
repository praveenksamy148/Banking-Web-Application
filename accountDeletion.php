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
    <title>Banking Account Deletion</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="regStyling.css">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="accountDeletionStyles.css">
    </head>

    <!-- Top Bar -->
    <header>
        <a href="Home.html"><img id='logo' width='300' height='50' src="logo.png"></a>
        <div class="navbar"><a href='logMusaHome.php'>Home</a></div>   
        <div class="navbar"><a href = "atmAccountPicker.php">ATM</a></div>   
        <div class="navbar"><a href='checkdeposit.php'>Make a Deposit</a></div>      
        <div class="navbar"><a href='fundsTransfer.php' style='flex-grow: 1;'>Transfer Funds</a></div>
        <div class="navbar"><a href = "NewAccConfirm.php">Create Account</a></div>
        <div class="navbar"><a href = "accountpage.php">User Dashboard</a></div>
        <div class="navbar"><a href='logout.php'>Log Out</a></div>
        <div class = "navbar">
        <script>
            var countdown = <?php echo json_encode($remaining_time);?>; 
            var minutes = Math.floor(countdown / 60); 
            var seconds = countdown % 60; 
            document.getElementById('time').textContent = countdown; 

        </script>
        <h>Live Session:</h>
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
            if(isset($_POST["submit"])){
                $dropdown = $_POST["dropdown"]; 
                require_once "database.php"; 
                $select = "SELECT * FROM account_info WHERE uniqueID = '$dropdown'"; 
                $result = mysqli_query($connection, $select);
                
                if(!$result){
                    die("Query failed: ". mysqli_error($connection));
                }
                if(mysqli_num_rows($result) == 1){
                    $row = mysqli_fetch_assoc($result); 
                    if($row["money"] == 0){
                        require_once "database.php"; 
                        $query = "DELETE FROM account_info WHERE uniqueID = '$dropdown'";
                        $delete = mysqli_query($connection, $query);
                        if(mysqli_affected_rows($connection) > 0){
                            echo "<div class= 'alert alert-success'>Account Successfully Deleted!</div>";
                        } else {
                            echo "<div class= 'alert alert-danger'>No account found to delete.</div>";
                        } 
                    }else{
                        echo "<div class= 'alert alert-danger'>Make Sure Account Has $0 Balance. Transfer or Withdraw Funds is Needed!</div>";
                    }
                }
            }
            ?>
            <form action = "accountDeletion.php" method = "post" >

                <h5>Which account would you like to delete?</h5>
                <div class = "select_style">
                <select name = "dropdown">
                    <?php
                    $userID = $_SESSION["userID"];
                    require_once "database.php";
                    $sql = "SELECT uniqueID FROM account_info WHERE accID = $userID";
                    $result = $connection->query($sql);

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
                <br></br>
                <div class = "form-group">
                <input type = "submit" value = "Submit" name = "submit" >
            </div>
            </form>
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
        </div>
    </main>
</html>