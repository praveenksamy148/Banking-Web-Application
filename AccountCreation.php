<html class = "html">
  <head>
    <link rel="stylesheet" href="AccountManage_style.css">
    <title> Bank Name: Account Creation </title>
  </head>
  <body>
    <!-- Top Bar -->
    <header>
        <img src = "logo.png" alt="Bank of Musa" width = 300 height = 50>
        <div class="topRight">
            <a href = "">About Us</a>
            <a href = "registration.php">Register</a>
            <a href = "login.php">Login</a>
        </div>
    </header>
    <div class="grayBar"></div>
      <!-- Page Content -->

      <div class = "header">
        <h1 class = name> Welcome to Bank!</h1>
        <h3> Please follow the steps below to create a new account:  </h3>
      </div>
      <div class="Form">
        <form action="AccountCreation.php" method="post">
            <label for="acc_name">Account Name:</label>
            <input type="text" id="acc_name" name="acc_name" required>
            <br><br>

            <label for="account_type">Account Type:</label>
            <select id="account_type" name="account_type" required>
                <option value="savings">Savings</option>
                <option value="checking">Checking</option>
            </select>
            <label for "acc_type"><h6>For other account types please visit your local BANK OF MUSA bank</h6></label>
            <br><br>

            <label for="deposit">Initial Deposit:</label>
            <input type="number" id="deposit" name="deposit" required>
            <br><br>

            <input type = "submit" value = "Submit" name = "submit" >
        </form>
      </div>
          <?php
          if(isset($_POST["submit"]))
          {
              $accName = $_POST["acc_name"];
              $accID = $_POST["accID"];
              $balID1 = $_POST["balID"];
              $balType1 = $_POST["acc_type"];
              $balID1_Balance = $_POST["deposit"];


              $conn = mysqli_connect("localhost", "root", "", "users");
              if (!$conn)
              {
                die("Connection failed: " . mysqli_connect_error());
              }

              $errors = array();
              // if(empty($accName) OR empty($balType1) OR empty($balID1_Balance){
              
              //     array_push($errors, "Required to fill out all fields!");
              // }

              require_once "database.php";
              $dupAccName = "SELECT * FROM acc_info WHERE accName = '$accName'";
              $copied = mysqli_query($connection, $dupAccName);
              if(!$copied || mysqli_num_rows($copied) > 0)
              {
                array_push($errors, "Account name already exists");
              }
              if(count($errors) > 0)
              {
                  foreach($errors as $error)
                  {
                      echo "<div class= 'alert alert-danger'>$error</div>";
                  }
              }
              else
              {
                $check = false;
                while($check == false)
                {
                  $possibleID = rand(11111111111, 99999999999);
                  $accIDCheck = "SELECT * FROM acc_info WHERE accID = '$possibleID'";
                  $result = mysqli_query($connection, $accIDCheck);
                  if(!$result)
                  {
                    die("Query failed: ". mysqli_error($connection));
                  }
                  else
                  {
                    $accID = $accIDCheck;
                    $check = true;
                  }
                }

                $check2 = false;
                while($check2 == false)
                {
                  $possibleID = rand(11111111111, 99999999999);
                  $balIDCheck = "SELECT * FROM acc_info WHERE balID1 = '$possibleID'";
                  $result2 = mysqli_query($connection, $accIDCheck);
                  if(!$result2)
                  {
                    die("Query failed: ". mysqli_error($connection));
                  }
                  else
                  {
                    $balID1 = $accIDCheck;
                    $check2 = true;
                  }
                }
                  $sql = "INSERT INTO acc_info (accName, accID, balID1, balType1, balID1_Balance) VALUES ('$accName', '$accID', '$balID1', '$balType1', '$balID1_Balance')";
                  $results = mysqli_query($connection, $sql);
                  if($results)
                  {
                      header("Location: Home.html");
                  }
                  else
                  {
                      echo "<div class= 'alert alert-danger'>Unable to Register</div>";
                      echo mysqli_error($connection);
                  }
              }
          }
           ?>

    <script src = "script.js"></script>
  </body>
</html>
