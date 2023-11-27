<?php 
session_start(); 
if(!isset($_SESSION["authenticate"])){
    header("location: MusaHome.html");
    exit(); 
}else{
    $timeout_duration = 900; 
    $time_lastLogin = time() - $_SESSION['last_login_timestamp']; 
    if($time_lastLogin > $timeout_duration){
        header('Location: login.php'); 
        exit(); 
    }else{
        $remaining_time = $timeout_duration - $time_lastLogin; 
        $_SESSION['last_login_timestamp'] = time(); 
        
    }
    
}

?>
<!DOCTYPE html>
<head>
    <title>Bank of Musa - Home</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="home.css">
</head>
<header>
    <nav>
        <div class="logo">
            <img src="logo.png" alt="Bank of Musa" width="300" height="50">
        </div>
        <ul class="nav-links">
            <li class="spacer"></li>
            <li><a href="accountpage.php">Account Dashboard</a></li>
            <li><a href="logout.php">Log Out</a></li>
            <li><a href="about.php">About Us</a></li>
        </ul>
           
    </nav>
</header>
<body>
    <div class = "flex-container">
        <section class="main-content">
            <div class="left-panel">
                <h1><b>What is Financial Wellness?</b></h1>
                <p class="info-content">It starts with knowing where you stand. It encompasses the education and understanding of various financial products, services, and concepts, enabling individuals to make informed decisions and achieve their financial goals. In today's dynamic economic environment, financial awareness is essential for effective money management, prudent investment, and safeguarding oneself from potential financial pitfalls.
                    <strong>Register today and get a free $100 signing bonus!</strong>
                </p>
                <a href="registration.php" class="oval-button">Register Here</a>
            </div>
            <div class="right-panel">
                <img src="homeImages/Egypt.jpeg" alt="Financial Wellness Image">
            </div>
        </section>
        <section class="main-content2">
            <div class="right-panel">
                <img src="homeImages/Musa.jpg" alt="Financial Wellness Image">
            </div>
            <div class="bottom-panel">
                <h1>Why Bank of Musa? </h1>
                <p class="info-content">
                    When we talk about financial wellness, we're talking about trading debt and 
                    worry for security and financial well-being. It's about knowing where you stand 
                    and having a plan to get where you're going. Less about skipping lattes, more 
                    about understanding how saving for tomorrow can fit into your life today. Here at Bank of Musa we pride ourselves in our customer service, honesty, and dedication. 
                    <strong>Learn How Easy It Is to Get Started Today!</strong>
                </p>
            </div>
        </section>
    </div>
</body>
</html>