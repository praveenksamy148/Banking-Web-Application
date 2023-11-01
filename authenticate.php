<?php 
session_start(); 
if(isset($_SESSION["authenticate"])){
    header("location: accountpage.php");
}
?>

<form action="authenticate.php" method="post">
            <div class="form-group">
                <input type="text" placeholder="Enter Authentication Code:" name="authenticate" class="form-control">
            </div>
            <div class="form-btn">
                <input type="submit" value="Submit" name="submit" class="btn btn-primary">
            </div>
</form>
 


