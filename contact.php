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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Of Musa: Contact Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="regStyling.css">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="contact.css">
    </head>

    <header style="display: flex; align-items: center;">
        <a href="Home.html"><img id='logo' width='200' height='50' src="logo.png"></a>
        <div class="navbar"><a href='logMusaHome.php'>Home</a></div>   
        <div class="navbar"><a href = "atmAccountPicker.php">ATM</a></div>   
        <div class="navbar"><a href='checkdeposit.php'>Make a Deposit</a></div>      
        <div class="navbar"><a href='fundsTransfer.php' style='flex-grow: 1;'>Transfer Funds</a></div>
        <div class="navbar"><a href = "NewAccConfirm.php">Create Account</a></div>
        <div class="navbar"><a href = "accountDeletion.php">Delete Account</a></div>
        <div class="navbar"><a href='accountpage.php'>User Dashboard</a></div>
        <div class="navbar"><a href='logout.php'>Log Out</a></div>

    </header>
<body>
    <div class="container">
        <?php 
              use PHPMailer\PHPMailer\PHPMailer; 
              use PHPMailer\PHPMailer\SMTP; 
              use PHPMailer\PHPMailer\Exception; 
      
              require '../vendor/autoload.php';
            if($_SERVER["REQUEST_METHOD"] == "POST"){
                echo "<div class= 'alert alert-success'>Message Sent! Admin Member will contact you shortly. </div>";
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
                    $mail->setFrom('pravee.samy@gmail.com', $_SESSION["username"], 0); 
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
        <h3>Please fill out the box to contact Bank Of Musa support: </h3>
        <br>
        <div class="form-group">
            <form action="contact.php" method="post">
                <textarea id="concern" name="concern" rows="6" placeholder="Please enter your questions or concerns here..."></textarea>
                <br>
                <br>
                <input type="submit" value = "Submit" name = "submit">
            </form>
        </div>
    </div>
</body>
</html>