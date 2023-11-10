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
                
                <p>Which account would you like to delete?</p>
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
                <br></br>
                <button class="confirm-button" type = "Confirm" name = "confirm">Confirm</button>
            </form>
        </div>
    </main>

</html>