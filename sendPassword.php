<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
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
        img{
            height: 200px; 
            width: 400px; 
        }
    </style>
</head>
<body>
    <div class="container">
    <h1 style= "color:white; text-align: center"><b>Reset Password</b></h1>
        <form action="sendPassword.php" method="post">
            <?php
            if(isset($_POST["submit"])){
                $password = $_POST["password"];
                $repeatPassword = $_POST["repeatPassword"];
                session_start(); 
                $email = $_SESSION["username"]; 
                
                //encrypt password 
                $hash_password = password_hash($password, PASSWORD_BCRYPT); 

                $errors = array(); 
                if(empty($password)OR empty($repeatPassword)){
                    array_push($errors, "Required to fill out all fields!");
                } 
                if(strlen($password) < 8){
                    array_push($errors, "Password length must be greater than 8 characters!");
                }
                if($password != $repeatPassword){
                    array_push($errors, "Passwords entered don't match!");
                }
                if(count($errors) > 0){
                    foreach($errors as $error){
                        echo "<div class= 'alert alert-danger'>$error</div>";
                    }
                }else{
                    require_once "database.php"; 
                    $sql = "UPDATE user_info SET password = '$hash_password' WHERE email = '$email'";
                    $results = mysqli_query($connection, $sql); 
                    if($results){
                        header("Location: login.php");

                    }else{
                        echo "<div class= 'alert alert-danger'>Unable to Reset Password</div>";
                        echo mysqli_error($connection); 
                    }
                }
            }
             ?>
            <div class = "form-group">
                <input type = "password" name = "password" placeholder = "Password: ">
            </div>
            <div class = "form-group">
                <input type = "password" name = "repeatPassword" placeholder = "Repeat Password: ">
            </div>
            <div class = "form-group">
                <input type = "submit" value = "Reset Password" name = "submit" >
            </div>
        </form>
        <div>
            
        </div>
        <button onclick="window.location.href = 'login.php'" id="buttonSize">Return to Login</button>
    </div>
</body>
</html>
