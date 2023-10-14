<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banking Registration</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="regStyling.css">
</head>
<body>
    <div class = "container">
        <h1>Register</h1>
        <form action = "registration.php" method = "post">
            <?php
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
                
                //encrypt password 
                $hash_password = password_hash($password, PASSWORD_BCRYPT); 
                echo $hash_password;

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
                if(count($errors) > 0){
                    foreach($errors as $error){
                        echo "<div class= 'alert alert-danger'>$error</div>";
                    }
                }else{
                    require_once "database.php"; 
                    $sql = "INSERT INTO user_info (first_name, last_name, email, date_of_birth, ssn, address, zipcode, password) VALUES ('$firstName', '$lastName', '$email', '$dateOfBirth', '$ssn', '$address', '$zipcode', '$hash_password')";
                    $results = mysqli_query($connection, $sql); 
                    if($results){
                        echo "<div class= 'alert alert-success'>Successfully Registered</div>";
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
                <input type = "date" name = "dateOfBirth" placeholder = "Date of Birth: ">
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
    
</body>
</html>