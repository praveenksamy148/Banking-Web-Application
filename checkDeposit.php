<?php 
session_start(); 
if(!isset($_SESSION["authenticate"])){
    header("location: login.php");
}
?>

<!DOCTYPE html>

    <!-- Title -->
    <head>
        <title>Bank of Musa - Account Deletion</title>
        <link rel="stylesheet" href="accountDeletionStyles.css">
    </head>

    <!-- Top Bar -->
    <header>
        <img src = "logo.png" alt="Bank of Musa" width = 300 height = 50>
        <div class="topRight">
            <a href = "#">Temp</a>
            <a href = "#">Temp</a>
            <a href = "#">Temp</a>
            <a href = "#">Temp</a>
            <a href = "#">Temp</a>
            <a href = "#">Temp</a>
        </div>
    </header>

    <div class="grayBar"></div>

    <!-- Everything Else -->
    <main>
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
                            }else{
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
    </main>

</html>