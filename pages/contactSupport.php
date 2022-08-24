<?php
if(isset($_POST['sendBtn'])){
    $email = $_POST['email'];
    $message = $email . " said: " . $_POST['message'];
    $to = "burgosd2@montclair.edu";
    $subject = "An Important Email From " .$email;
    $headers = "From: ". $email;
    mail($to,$subject,$message,$headers);

    mail($to, $subject, $message, $headers);
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Online Banking</title>
    <link href="style.css" rel="stylesheet" type="text/css" />
		<style>
		
			#headerMessage{
				font-family: sans-serif;
				color: black;
				border-bottom: 1px solid #000000;
				text-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
				text-align: center;
				font-size: 30px;
				padding-top: 10px;
			}
			#container1{
				margin: 0 auto;
				width: 500px;
				height: 500px;
				background: rgba(235,23,23);
				box-shadow: 10px 10px 5px grey;
				border-radius: 10px; 
				border-color: black;
				border-width: 5px;
			}
			.container2{
				margin: 0 auto;
				background: white;
				width: 430px;
				height: 400px;
				color: black;
				font-family: sans-serif;
				border-radius: 10px;
			}
			#userLoginForm{
			    padding-top: 10px;
			    padding-left: 15px;
			}
			#linksNavi{
				text-align: center;
				margin-top: 50px
			}
			#userLogin{
			   border-radius: 5px;
			   border: 2px black solid;
			   width: 100px; 
			   height: 30px; 
			   float: right; 
			   margin-right: 80px; 
			   text-align: center;
		   	   font-family: sans-serif; 
		       color: red;
			   background: white;
			}
			#userLogin:hover{
				opacity: 0.6;
			}
			input, textarea{
			   border-radius: 5px;
			   border: 2px black solid;
			}
		</style>
	</head>
  <body>

		<div id = "container1">
			<h3 id= "headerMessage"> Contact Support </h3>
			<div class = "container2">
					<form id ="userLoginForm"  method="post">
					<h4><label for="username">Email:</label><br>
						<input type="email" style= "width: 250px;" name="email" autocomplete = "off" required /><br>

						<label for="password">Message:</label>
						<br>
						<textarea style= "width: 250px; height: 150px;"  name = "message" required></textarea>
						<br>
					<br>
					<br>
                   
					<input id ="userLogin" name ="sendBtn" type="submit" value="Send">
					</form>

					<!-- <button id = "customerLoginBtn" onclick= "window.location.href = 'pages/customerHomePage.html'">Log In</button> i-->
					<div id = "linksNavi"> 
					    <a href="login.php">Customer Login</a>
					    |
						<a href="adminLogin.php">Admin Login</a>
						|
						<a href="registerUser.php">Register</a>
						|
						<a href="ERROR.php">Forgot Password</a>
					</div> </h4>
			</div> 
		</div>
  </body>