<?php 
session_start(); 
if(!isset($_SESSION["user"])){
    header("location: login.php");
}else{
    $email = $_SESSION["username"];
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Banking Login</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="regStyling.css">
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
        <style>
            /* Custom CSS for a maroon and gold theme with improved fonts */
            body {
                background-color: maroon;
                color: gold;
                font-family: 'Open Sans', sans-serif;
                text-align: center;
            }

            .container {
                background-color: maroon;
                border: 1px solid gold;
                border-radius: 10px;
                padding: 20px;
                margin-top: 20px;
            }

            .form-group {
                margin-bottom: 20px;
            }

            .form-control {
                background-color: gold;
                color: maroon;
                border: 1px solid maroon;
                border-radius: 5px;
                padding: 10px;
                font-family: 'Open Sans', sans-serif;
            }

            .btn-primary {
                background-color: gold;
                color: maroon;
                border: none;
                border-radius: 5px;
                padding: 10px 20px;
                cursor: pointer;
                font-family: 'Playfair Display', serif;
            }

            .btn-primary:hover {
                background-color: #d4af37;
            }

            .alert {
                background-color: gold;
                color: maroon;
                border: 1px solid maroon;
                border-radius: 5px;
                margin: 10px 0;
                padding: 10px;
                font-family: 'Open Sans', sans-serif;
            }

            .logo {
                max-width: 150px;
                display: block;
                margin: 0 auto 20px;
            }

            #buttonSize {
                background-color: gold;
                color: maroon;
                border: none;
                border-radius: 5px;
                padding: 5px 10px;
                cursor: pointer;
                font-family: 'Playfair Display', serif;
            }

            #buttonSize:hover {
                background-color: #d4af37;
            }
        </style>
    </head>
    <body>
        <div class = "container">
        <?php
            if(isset($_POST["submit"])){
                $authenticate = $_POST["authenticate"]; 
                require_once "database.php"; 
                $email = $_SESSION["username"]; 
                $query = "SELECT * FROM user_info WHERE email = '$email'"; 
                $result = mysqli_query($connection, $query); 
                if(!$result){
                    die("Query failed: ". mysqli_error($connection)); 
                }
                if(mysqli_num_rows($result) == 1){
                    $row = mysqli_fetch_assoc($result);
                    if($authenticate == $row["authenticate"]){
                        $_SESSION["authenticate"] = "yes"; 
                        unset($_SESSION["user"]);
                        $_SESSION["firstName"] = $row["first_name"]; 
                        $_SESSION["lastName"] = $row["last_name"]; 
                        $_SESSION["start"] = time(); 
                        $_SESSION["expire"] = $_SESSION['start'] + (30 * 60); 
                        $_SESSION['last_login_timestamp'] = time(); 
                        header("Location: accountpage.php"); 
                    }else{
                        echo "<div class= 'alert alert-danger'>Incorrect Code!</div>";
                    }
                }
            }
        ?>
        <form action="authenticate.php" method="post">
            <div class="form-group">
                <input type="text" placeholder="Enter Authentication Code:" name="authenticate" class="form-control">
            </div>
            <div class="form-btn">
                <input type="submit" value="Submit" name="submit" class="btn btn-primary">
            </div>
        </form>
        </div>
       
    </body>

    
</html>


 


