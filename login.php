
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

        #spacingBottom{
            margin-bottom: 3cm; 
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 style= "color:white"><b>User Login</b></h1>
    <?php 
        use PHPMailer\PHPMailer\PHPMailer; 
        use PHPMailer\PHPMailer\SMTP; 
        use PHPMailer\PHPMailer\Exception; 

        require '../vendor/autoload.php'; 

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
                    $firstName = $row["first_name"]; 
                    $_SESSION["username"] = $row["email"]; 
                    $_SESSION["userID"] = $row["ID"]; 
                    $mail = new PHPMailer(true); 

                    // comment try-catch statement out to avoid 2-factor authentication
                    try{
                        $mail->SMTPDebug = 0; 
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com'; 
                        $mail->SMTPAuth = true; 
                        $mail->Username = 'bankmusa6@gmail.com'; 
                        $mail->Password = 'pmbwfjyjrcrkjnfm'; 
                        $mail->SMTPSecure = "tls"; 
                        $mail->Port = 587; 
                        $mail->setFrom('bankmusa6@gmail.com', 'Bank of Musa', 0); 
                        $mail->addAddress($email, $firstName); 
                        $mail->isHTML(true); 
                        $code = random_int(10000,999999); 
                        require_once 'database.php'; 
                        $sql = "UPDATE user_info SET authenticate = '$code' WHERE email = '$email'";
                        mysqli_query($connection, $sql); 
                        $mail->Subject = 'User Authentication';
                        $mail->Body = '<p> Your verification code is: <b style = "font-size: 30px;">' . $code . '</b></p>'; 
                        $mail->send(); 
                        header("Location: authenticate.php"); 
                    }catch(Exception $e){
                        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    }  
                  
                }
            } else {
                echo "<div class='alert alert-danger'>Incorrect Login!</div>";
                // echo "User not found."; 
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
            <p class="spacingBottom">Haven't registered yet?</p><button onclick = "window.location.href = 'registration.php'" id = "buttonSize">Register Here</button>
        </div>
        <a href= "forgotPassword.php">Forgot Password?</a>
    </div>
</body>
</html>