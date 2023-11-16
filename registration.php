<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banking Registration</title>
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
    <!-- <div class="image-section">
        <img src= "BankOfMusa.png" alt = "Company Logo" class= "logo">
    </div> -->
    <div class="container">
    <h1 style= "color:white; text-align: center"><b>Register</b></h1>
        <form action="registration.php" method="post">
            <?php
            use PHPMailer\PHPMailer\PHPMailer; 
            use PHPMailer\PHPMailer\SMTP; 
            use PHPMailer\PHPMailer\Exception; 
    
            require '../vendor/autoload.php'; 
            if(isset($_POST["submit"])){
                $firstName = $_POST["firstName"];
                $lastName = $_POST["lastName"];
                $email = $_POST["email"];
                $dateOfBirth = $_POST["dateOfBirth"];
                $address = $_POST["address"];
                $zipcode = $_POST["zipcode"];
                $ssn = $_POST["ssn"];
                $password = $_POST["password"];
                $repeatPassword = $_POST["repeatPassword"];
                $pinNumber = random_int(10000, 99999); 
                
                //encrypt password 
                $hash_password = password_hash($password, PASSWORD_BCRYPT); 
                // echo $hash_password;

                $errors = array(); 
                if(empty($firstName) OR empty($lastName) OR empty($email)OR empty($dateOfBirth)OR empty($address)OR empty($zipcode)OR empty($ssn)OR empty($password)OR empty($repeatPassword)){
                    array_push($errors, "Required to fill out all fields!");
                } 
                if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                    array_push($errors, "Email not valid");
                }
                if(strlen($password) < 8){
                    array_push($errors, "Password length must be greater than 8 characters!");
                }
                if($password != $repeatPassword){
                    array_push($errors, "Passwords entered don't match!");
                }
                require_once "database.php"; 
                $dupEmail = "SELECT * FROM user_info WHERE email = '$email'"; 
                $copied = mysqli_query($connection, $dupEmail); 
                if(!$copied || mysqli_num_rows($copied) > 0){
                    array_push($errors, "Username already exists"); 
                }
                if(count($errors) > 0){
                    foreach($errors as $error){
                        echo "<div class= 'alert alert-danger'>$error</div>";
                    }
                }else{
                    $sql = "INSERT INTO user_info (first_name, last_name, email, date_of_birth, ssn, address, zipcode, password, pinNumber) VALUES ('$firstName', '$lastName', '$email', '$dateOfBirth', '$ssn', '$address', '$zipcode', '$hash_password', '$pinNumber')";
                    $results = mysqli_query($connection, $sql); 
                    if($results){
                        $mail = new PHPMailer(true); 
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
                            $mail->Subject = 'Welcome to Bank of Musa! - Your Journey Begins Here'; 
                            $mail->Body = '
                            <div style="font-family: Arial, sans-serif; color: #333; line-height: 1.5; padding: 15px;">
                                <h1 style="color: #004a99;">Welcome to Bank of Musa!</h1>
                                <p>We are absolutely thrilled to have you join our banking family! As a valued member of our bank, you now have access to all of our professional services and resources. Now that you are a member, you have access to your very own user dashboard which reflects your different accounts, balances, and transactions. To access your ATM, we have created an unique PIN number for you: <b>' . $pinNumber . '</b></p>
                                <p>For any assistance, please feel free to contact our customer support team. Thank you again for choosing Bank of Musa for your financial needs. We look forward to a prosperous relationship!</p>
                                <p>Best Regards, <br> Kobe Bryant</p>
                            </div>';
                        $mail->send();
                        }catch(Exception $e){
                            echo "<div class='alert alert-danger'>Unable to Send Message!</div>";
                        }
                        header("Location: login.php");

                    }else{
                        echo "<div class= 'alert alert-danger'>Unable to Register</div>";
                        echo mysqli_error($connection); 
                    }
                }
            }
             ?>
            <div class = "form-group">
                <input type = "text" name = "firstName" placeholder = "First Name: ">
            </div>
            <div class = "form-group">
                <input type = "text" name = "lastName" placeholder = "Last Name: ">
            </div>
            <div class = "form-group">
                <input type = "email" name = "email" placeholder = "Email: ">
            </div>
            <div class = "form-group">
                <input type = "date" name = "dateOfBirth" placeholder = "Date of Birth: " onfocus= "(this.type='date')" onblur="(this.type='text')">
            </div>
            <div class = "form-group">
                <input type = "text" name = "address" placeholder = "Permanent Address: ">
            </div>
            <div class = "form-group">
                <input type = "text" name = "zipcode" placeholder = "Zipcode: ">
            </div>
            <div class = "form-group">
                <input type = "password" name = "ssn" placeholder = "Social Security Number: ">
            </div>
            <div class = "form-group">
                <input type = "password" name = "password" placeholder = "Password: ">
            </div>
            <div class = "form-group">
                <input type = "password" name = "repeatPassword" placeholder = "Repeat Password: ">
            </div>
            <div class = "form-group">
                <input type = "submit" value = "Register" name = "submit" >
            </div>
        </form>
        <div>
            <p>Already have an account?</p>
            
        </div>
        <button onclick="window.location.href = 'login.php'" id="buttonSize">Login Here</button>
    </div>
</body>
</html>
