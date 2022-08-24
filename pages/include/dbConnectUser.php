<?php // connects to database 
	$dbConnect = mysqli_connect("localhost","root","","online_banking");
    if (mysqli_connect_errno()){
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	die();
    }
?>