<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banking Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="regStyling.css">
</head>
<body>
    <div class = "container">
        <?php 
        if(isset($_POST["login"])){
            $email = $_POST["email"]; 
            $password = $_POST["password"]; 
            require_once "database.php"; 
            $query = "SELECT * FROM user_info Where email = '$email'"; 
            $result = mysqli_query($connection, $query); 
            if(!$result){
                die("Query failed: ". mysqli_error($connection)); 
            }
            if(Mysqli_num_rows($result) == 1){
                $row = mysqli_fetch_assoc($result); 
                if(password_verify($password, $row["password"])){
                    header("Location: index.php");
                    echo "<div class = 'alert alert-success'>Login Successful!</div>";
                }else{
                    // header("Location: index.php")
                    echo "<div class = 'alert alert-danger>Incorrect Login!</div>";
                }
            }else{
                echo "User not found."; 
            }
            mysqli_close($connection); 

        }
        ?>
    <form action = "login.php" method = "post">
        <div class = "form-group">
            <input type = "email" placeholder = "Enter Email:" name="email" class = "form-control">
        </div>
        <div class = "form-group">
            <input type = "password" placeholder = "Enter Password:" name="password" class = "form-control">
        </div>
        <div class="form-btn">
            <input type = "submit" value ="Login" name = "login" class="btn btn-primary">
        </div>
    </form>
    </div>
        
        
    
</body>
</html>