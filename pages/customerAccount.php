<?php 
include('../database/dbConnectUser.php');
include('verifySession.php');

// session start for admin & if logout is pressed, redirection ton admin login

$accNum =  $_SESSION['accNum'];
$query = "SELECT currentBal, LName, FName, add_street, add_town, add_state, add_zip, add_aptNum FROM customer_info WHERE accNum = '$accNum' "; 
$result = mysqli_query($dbConnect, $query);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $currentBal = $row['currentBal'];
        $LName = $row['LName'];
        $FName = $row['FName'];
		$add_aptNum = '';
		if ($row['add_aptNum'] != NULL)
			$add_aptNum = $row['add_aptNum'];
        $add_street = $row['add_street'];
        $add_town = $row['add_town'];
        $add_state = $row['add_state'];
        $add_zip = $row['add_zip'];
    }
}

?>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Customer Account Page</title>
		<style>
			body{
				font-family: Arial, Helvetica, sans-serif;
				margin: 0;
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
			#link:active{
				background: black;
			}
			#link:hover{
				background: black;
				color: white;
			}
			#content{
				margin: 0 auto;
				left: 50%;
				background: rgb(223,23,23);
				border-radius: 20px;
				height: 60vh;
				width: 65vw;
				box-shadow: 0px 6px 6px rgba(0, 0, 0, 0.25);
				color: white;
				display: flex;
				text-align: center;
				justify-content: center;
				align-items: center;
				border: 2px solid black;
			    border-radius: 10px;
			}
			#accountInfo{
				background: white;
				color: black;
				height: 40vh;
				width: 45vw;
				margin: 0 auto;
				border: 2px solid black;
			    border-radius: 5px;
			}
			table{
			   border: none;
			   font-family: sans-serif;
			   font-size: 25px;
			   width: 100%;
			   margin-top: 10px;
			}
			#right{
			  float: right;
			}
			#signout{
			  height: 50px;
			  width: 100px;
			  float: right;
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
		<h2 style= "border-bottom: 2px solid rgb(223,23,23); font-size: 30px; font-family: sans-serif; padding-left: 25px; color: black;"> Account Information </h2>
		
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
		   <div id = "accountInfo">
			<table>
			   <tr>
			      <td> First name: </td>
			      <td id = "right"> <?php echo $FName; ?> </td>
			   </tr>
			   <tr>
			      <td> Last name: </td>
			      <td id = "right"> <?php echo $LName; ?> </td>
			   </tr>
			   <tr>
			      <td> Address: </td>
			      <td id = "right"> <?php echo $add_street . " " .  $add_state . " " . $add_zip . " "  . $add_aptNum; ?> </td>
			   </tr>
			   <tr>
			      <td> Account Number: </td>
			      <td id = "right"> <?php echo $accNum; ?> </td>
			   </tr>
			   <tr>
			       <td> Current Balance: </td>
			       <td id = "right"> <?php echo "$" . $currentBal; ?> </td>
			   </tr>
			</table>
			<br> 
			<a style = "text-align: center; font-size: 25px; color: black;" href = "secretPassReset.php">Click Here to Reset Password!</a>
			</div>
		</div> 
	
	</body>

</html>