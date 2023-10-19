<?php 
session_start(); 
if(isset($_SESSION["user"])){
    header("location: index.php");
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
    <style>
        /* Custom CSS for a black, gray, and white theme */
        body {
            background-color: #000;
            color: #fff;
            font-family: Arial, sans-serif;
            text-align: center;
        }

        .container {
            background-color: #333;
            border: 1px solid #888;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }

        .form-control {
            background-color: #fff;
            color: #000;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin: 5px 0;
            padding: 10px;
        }

        .btn-primary {
            background-color: #000;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
        }

        .btn-primary:hover {
            background-color: #333;
        }

        .alert {
            background-color: #fff;
            color: #000;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin: 10px 0;
            padding: 10px;
        }

        .logo {
            max-width: 150px;
            display: block;
            margin: 0 auto 20px;
        }
        .buttonSize {
            width: 300px; 
            margin: 12px; 
            height: 100px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>User Login</h1>
        <?php 
        if(isset($_POST["login"])){
            $email = $_POST["email"]; 
            $password = $_POST["password"]; 
            require_once "database.php"; 
            $query = "SELECT * FROM user_info WHERE email = '$email'"; 
            $result = mysqli_query($connection, $query); 
            if(!$result){
                die("Query failed: ". mysqli_error($connection)); 
            }
            if(mysqli_num_rows($result) == 1){
                $row = mysqli_fetch_assoc($result); 
                if(password_verify($password, $row["password"])){
                    session_start();
                    $_SESSION["user"] = "yes"; 
                    header("Location: index.php");
                    echo "<div class='alert alert-success'>Login Successful!</div>";
                } else {
                    echo "<div class='alert alert-danger'>Incorrect Login!</div>";
                }
            } else {
                echo "User not found."; 
            }
            mysqli_close($connection); 
        }
        ?>
        <form action="login.php" method="post">
            <div class="form-group">
                <input type="email" placeholder="Enter Email:" name="email" class="form-control">
            </div>
            <div class="form-group">
                <input type="password" placeholder="Enter Password:" name="password" class="form-control">
            </div>
            <div class="form-btn">
                <input type="submit" value="Login" name="login" class="btn btn-primary">
            </div>
        </form>
        <div>
            <p>Haven't registered yet?</p><button onclick = "window.location.href = 'registration.php'" id = "buttonSize">Register Here</button>
        </div>
    </div>
</body>
</html>