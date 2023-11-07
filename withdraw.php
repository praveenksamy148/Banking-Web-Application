<?php 
session_start(); 
if(!isset($_SESSION["authenticate"])){
    header("location: login.php");
}

require_once "database.php"; // Include your database connection file at the beginning
?>
<html lang="en">
<head>
<link rel="stylesheet" href="withdraw.css">



</head>

<body>
    <div class="header">
        <h1 class="name">Welcome to Bank!</h1>
        <h3>Please follow the steps below to withdraw from an account:</h3>
    </div>

    <!-- FORM HTML + PHP -->
    <div class="Form">
        <form action="withdraw.php" method="post">
        <?php
            $userID = $_SESSION["userID"];
            require_once "database.php";
            $sql = "SELECT uniqueID FROM account_info WHERE accID = $userID";
            $result = $connection->query($sql);


        ?>
            <div class="form-group">
                <h5> Select an account: </h5>
                <select name="selectedAcc">
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
            <div class="form-group">
                <input type="number" name="amount" placeholder="Amount: ">
            </div>
            <div class="form-group">
                <input type="submit" value="Enter" name="submit">
            </div>
    <!-- AFTER SUBMIT PROCESSING -->
        <?php
        if(isset($_POST["submit"])) 
        {
            $selectedAcc = $_POST["selectedAcc"];
            $amount = $_POST["amount"];
            $userID = $_SESSION["userID"];

            if (!empty($selectedAcc)) //Check if selected account is empty
            {
                $sql = "SELECT money FROM account_info WHERE uniqueID = $selectedAcc"; //Find money
                $result = $connection->query($sql);
                
                if ($result) 
                {
                    $row = $result->fetch_assoc();
                    $currentMoney = $row["money"];
                    $newMoney = $currentMoney - $amount;
                    if($newMoney >= 0)
                    {
                        $updateSql = "UPDATE account_info SET money = $newMoney WHERE accID = $selectedAcc"; //Update money
                        $updateResult = $connection->query($updateSql);
                        if ($updateResult) 
                        {
                            echo "SUCCESS: Your new balance in that account is: $newMoney"; 
                        } 
                        else 
                        {
                            echo "ERROR: Failed to update money in the account.";
                        }
                    } 
                    else 
                    {
                        echo "ERROR: Cannot have a negative balance";
                    }
                } 
                else 
                {
                    echo "ERROR: Cannot find account ID.";
                }
            }
            else 
            {
               echo "ERROR: Selected account is empty.";       
            }
        }
        
        ?>
        </form>
        <?php
            $connection->close();
        ?>
    <div>
            <button onclick = "window.location.href = 'accountpage.php'" id = "buttonSize">Back to Home Page</button>
        </div>
    </div>
</body>
</html>
