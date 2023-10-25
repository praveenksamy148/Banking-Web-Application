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
        <form action="" method="post">
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

    <script src = "script.js"></script>
  </body>
</html>
