<?php 
include('../database/dbConnectUser.php');
include('verifySession.php');

//session_start();
// session start for admin & if logout is pressed, redirection ton admin login

$accNum =  $_SESSION['accNum'];
$query = "SELECT currentBal, FName, LName, createdDate FROM customer_info WHERE accNum = '$accNum'";

$result = mysqli_query($dbConnect, $query);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $currentBal = $row['currentBal'];
        $FName = $row['FName'];
        $LName = $row['LName'];
        $createdDate = $row['createdDate'];
    }
}
?>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Customer Home Page</title>
		<style>
			body{
				font-family: Arial, Helvetica, sans-serif;
				margin: 0;
				box-sizing: border-box;
			}
			.navBar { 
				background: rgb(223,23,23);
				border: 2px solid black;
				margin-top: 5px;
				height: 5vh;
				width: 100vw;
				margin-bottom: 15px;
				overflow: auto;
				overflow-x: hidden;
				overflow-y: hidden;
				/*
				gonna eventually transfer this all to a css file so that we don't have to keep editing
				each page's css
				*/
			}
			.navBar a{
				float: left;
				text-decoration: none;
				text-align: center;
				color: white;
				font-size: 5vh;
				padding: 17px 15px;
				font-size: 18px;
				border-right: 2px solid white;
			}
			a#link:active{
				background: white;
				color: black;
			}
			#link:hover{
				background: black;
				color: white;
			}
			#content{
			    border: 2px solid black;
			    border-radius: 5px;
				margin: 0 auto;
				background: rgb(223,23,23);
				border-radius: 20px;
				height: 35vh;
				width: 65vw;
				box-shadow: 0px 6px 6px rgba(0, 0, 0, 0.25);
				font-family: sans-serif;
				font-size: 20px;
				color: white;
			}
			#accountInfo{
			    border-radius: 5px;
			    border: 2px solid black;
				background: white;
				color: black;
				height: 20vh;
				width: 55vw;
				margin: 0 auto;
			}
			#signout{
			  height: 100%;
			  width: 100px;
			  float: none;
			  border-radius: 5px;
			  background: white;
			  color: rgb(223,23,23);
			  font-size: 14px;
		      border: 2px solid black;
			}
			#signoutForm{
			   float: right;
			   margin-right: 4px;
			   padding: 2px;
			}
			
		</style>
	</head>
	
	<body>
		<h2 style= "border-bottom: 2px solid rgb(223,23,23); font-size: 30px; font-family: sans-serif; padding-left: 25px; color: black;"> <?php echo "Welcome Back " . $FName ." ". $LName . "!"; ?> </h2>
		
		<div class = "navBar">
			<a style = "font-size: 18px; color: black; background: white; overflow: auto; ">Digital Banking</a>
			<a id= "link" href = "customerHomePage.php">Home</a> 
			<a id= "link" href = "customerBalanceTransfer.php">Balance Transfer</a> 
			<a id= "link" href = "customerTransactions.php">Transaction History</a>
			<a id= "link" href = "customerStatements.php">Statements</a>
			<a id= "link" href = "customerAccount.php"> Account</a> 
			<form id = "signoutForm" method = "post">
			    <input id = "signout" name = "signout" value = "Sign Out" type = "submit"/>
            </form>
		</div>

		<div id = "content">
			<p style = "text-align: center;">Member Since: <?php echo $createdDate; ?></p>
			<div id ="accountInfo">
			<p style =  "margin-left: 5px;">  
    			<?php echo "<b>Welcome back " . $_SESSION['username'] . "</b><br><br>";?>
    			<?php echo "Available Balance: $" . $currentBal . "<br><br>";?>
    			<?php echo "Account Number: " . $accNum;?>
    	    </p>
			</div>
		</div>


	
	</body>

</html>