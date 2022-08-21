<?php
	include('../database/dbConnectUser.php');
    session_start();
    //$_SESSION['username'] = $_POST["username"];
	$username = $password = "";
	$username_err = $password_err = "";
	$notApprovedError = "";

#echo "Before post check";
	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		if(empty(trim($_POST["usrname"])))
		{
			$username_err = "Please enter username.";
		} else{
			$username = trim($_POST["usrname"]);
		}
		
		if(empty(trim($_POST["usrpw"])))
		{
			$password_err = "Please enter your password.";
		} else{
			$password = trim($_POST["usrpw"]);
		}
#echo " ".$username." ".$password;
		if(empty($username_err) && empty($password_err))
		{
#echo "before creating the query";
			//$query = "SELECT username, password, acc_type, approved FROM user_table WHERE username like ?";
           $query = "SELECT username, password, accNum, acc_type, approved FROM user_table WHERE username like ?";

			if($stmt = mysqli_prepare($dbConnect, $query)){
				mysqli_stmt_bind_param($stmt, "s", $param_username);
				$param_username = $username;

				if(mysqli_stmt_execute($stmt))
				{
					mysqli_stmt_store_result($stmt);
					if(mysqli_stmt_num_rows($stmt) == 1)
					{    
					    mysqli_stmt_bind_result($stmt, $username, $hashed_password, $accNum, $acc_type, $approved);
						
						if(mysqli_stmt_fetch($stmt))
						{
							if(password_verify($password, $hashed_password))
							{
								if($acc_type == 0 && $approved == 1){
#									echo "yeehaw:)";
									/* if the account type is equal to 1 it will redirect to the admind portal.
									   if not, it will take you to customer landing page.
									*/
									$_SESSION['accNum'] = $accNum;
									echo '<META HTTP-EQUIV="refresh" content="0; URL=customerHomePage.php">';
								
 								}elseif($acc_type == 1){			
									//echo "Use admin login";			
								}else{
									$notApprovedError = "Your account has not been approved yet!";	
								}
							}else
							{
								$password_err = "The password you entered was not valid.";
							}
						}
					}else
					{
						$username_err = "No account found with that username.";
					}
				} else
				{
					echo "Error, something went wrong.";
				}
				mysqli_stmt_close($stmt);
			}
		}
		mysqli_close($dbConnect);
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
			    padding-left: 15px;
				padding-top: 30px;
			}
			#linksNavi{
				text-align: center;
				margin-top: 100px
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
			input{
			   border-radius: 5px;
			   border: 2px black solid;
			}
		</style>
	</head>
  <body>

		<div id = "container1">
			<h3 id= "headerMessage"> Online Banking </h3>
			<div class = "container2">
				<h4 style = "font-size: 25px; padding-top: 25px; text-align: center;"> Customer Login <h4>
					<form id ="userLoginForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
						<label for="username">Username:</label>
						<br>
						<input type="text" style= "width: 250px;" name="usrname" value="<?php echo $username; ?>">
						<br>
						<?php echo $username_err; ?>
						<br>

						<label for="password">Password:</label>
						<br>
						<input style= "width: 250px;" type="password" name="usrpw" value="<?php echo $password;?>">
						<br>
						<?php echo $password_err; ?>
					<br>
					<br>
                   
					<input id ="userLogin" type="submit" value="Login">
					</form>

					<!-- <button id = "customerLoginBtn" onclick= "window.location.href = 'pages/customerHomePage.html'">Log In</button> i-->
					<div id = "linksNavi"> 
						<a href="adminLogin.php">Admin Login</a>
						|
						<a href="registerUser.php">Register</a>
						|
						<a href="contactSupport.php">Contact Support </a>
						|
						<a href="secretPassReset.php">Forgot Password</a>
					</div> 
			</div> 
		</div>
		<p> <h2 style = "text-align: center; color: red"><?php echo $notApprovedError ?></h2></p>
  </body>