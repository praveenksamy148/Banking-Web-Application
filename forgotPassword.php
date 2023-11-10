<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
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
    <div class = "container"> 
    <h1 style="color:white"><b>Password Reset</b></h1>
        <?php 
        use PHPMailer\PHPMailer\PHPMailer; 
        use PHPMailer\PHPMailer\SMTP; 
        use PHPMailer\PHPMailer\Exception; 

        require '../vendor/autoload.php'; 
        if(isset($_POST["submit"])){
            $email = $_POST["email"]; 
            $token = bin2hex(random_bytes(16)); 
            $token_hash = hash("sha256", $token); 
            $expiry = date("Y-m-d H:i:s", time() + 60 * 30); 
            require_once("database.php");
            if (!$connection) {
                die("Database connection failed");
            }

            $sql = "UPDATE user_info SET reset_token_hash = ?, reset_token_expires_at = ? WHERE email = ?"; 
            $result = $connection->prepare($sql); 
            if(!$result){
                die("Failed to prepare the statement: " . $connection->error); 
            }
            $result->bind_param("sss", $token_hash, $expiry, $email); 
            $result->execute(); 

            // Check if the UPDATE was successful
            if($result->affected_rows === 0) {
                echo "<div class='alert alert-warning'>No account found with that email.</div>";
            } else {
                // Send email logic goes here
                echo "<div class='alert alert-success'>If an account with that email exists, a password reset link has been sent.</div>";
            }
            $mail = new PHPMailer(true); 
            $mail->SMTPDebug = 0; 
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; 
            $mail->SMTPAuth = true; 
            $mail->Username = 'bankmusa6@gmail.com'; 
            $mail->Password = 'pmbwfjyjrcrkjnfm'; 
            $mail->SMTPSecure = "tls"; 
            $mail->Port = 587; 
            $mail->setFrom('bankmusa6@gmail.com', 'Bank of Musa', 0); 
            $mail->addAddress($email); 
            $mail->isHTML(true); 
            $mail->Subject = "Password Reset"; 
            $mail->Body = <<<END
                Click <a href = "http://localhost/Banking_App/Banking-Web-Application/sendPassword.php">here</a> to reset password.
            END; 
            try{
                $mail->send(); 
                echo "Message sent. Check inbox"; 
            }catch(Exception $e){
                echo "Message can't be sent. Mailer error: {$mail->ErrorInfo}"; 
            }
        }
        ?>
        <form action="forgotPassword.php" method="post">
            <div class="form-group">
                <input type="email" placeholder="Enter Email:" name="email" class="form-control">
            </div>
            <div class="form-btn">
                <input type="submit" value="Send Password" name="submit" class="btn btn-primary">
            </div>
        </form>
    </div>
    
</body>
</html>