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
                    require_once "database.php";
                    $currentUserID = $_SESSION["userID"]; 
                    echo "'$currentUserID'";
                ?>
                <select name = "dropdown">
                    <?php
                    require_once "database.php";
                    
                    $currentUserID = $_SESSION["userID"];
                    $query = "SELECT * FROM account_info WHERE userID = '$currentUserID'";
                    $result = mysqli_query($connection, $query);

                    if($result){
                        while($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='" . $row['uniqueID'] . "'>" . $row['uniqueID'] . "</option>";
                        }
                    }

                    mysqli_close($connection);
                    ?>
                </select>
                <br></br>
                <button class="confirm-button" type = "Confirm" name = "confirm">Confirm</button>
            </form>
        </div>
    </main>

</html>