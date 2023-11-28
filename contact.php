<?php
$messageSent = false;
session_start(); 
if(!isset($_SESSION["authenticate"])){
    header("location: login.php"); 
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $messageSent = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="regStyling.css">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <title>Contact Us</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 500px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        textarea {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
            resize: vertical;
        }
        button {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            background-color: #0056b3;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #003d7a;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php 
             use PHPMailer\PHPMailer\PHPMailer; 
             use PHPMailer\PHPMailer\SMTP; 
             use PHPMailer\PHPMailer\Exception; 
 
            require '../vendor/autoload.php'; 
            if($_SERVER["REQUEST_METHOD"] == "POST"){
                echo  
                $concern = $_POST["concern"];
                $mail = new PHPMailer(true); 
                // session_start(); 
                $email = $_SESSION["username"]; 
                $firstName = $_SESSION["firstName"]; 
                try{
                    $mail->SMTPDebug = 0; 
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com'; 
                    $mail->SMTPAuth = true; 
                    $mail->Username = 'bankmusa6@gmail.com'; 
                    $mail->Password = 'pmbwfjyjrcrkjnfm'; 
                    $mail->SMTPSecure = "tls"; 
                    $mail->Port = 587; 
                    $mail->setFrom('pravee.samy@gmail.com', $firstName, 0); 
                    $mail->addAddress('bankmusa6@gmail.com', 'Bank of Musa'); 
                    // $mail->addAddress($email, $firstName); 
                    $mail->isHTML(true); 
                    $mail->Subject = 'Customer Support Needed'; 
                    $mail->Body = $concern; 
                    $mail->send(); 
                }catch(Exception $e){
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }  
            }
        ?>
            <form action="contact.php" method="post">
                <label for="concern">Your Concern:</label>
                <textarea id="concern" name="concern" rows="6" placeholder="Please enter your concerns here..."></textarea>
                <input type="submit" value = "submit" name = "submit">
            </form>
    </div>
</body>
</html>