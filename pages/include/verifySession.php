<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// session start for admin & if logout is pressed, redirection ton admin login
if(isset($_POST['signout'])){
    session_destroy();
    header('location: ../login.php');
}  
if(empty($_SESSION['accNum'])){
    exit("<p style = 'text-align: center;'> <h1> Please login first:  <br>
            <a href = 'login.php'> Sign In here</a></h1>");
}

?>