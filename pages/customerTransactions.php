<?php 
include('../database/dbConnectUser.php');
include('verifySession.php');


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
			a:active{
				background: black;
				color: white;
			}
			a:hover{
				background: black;
				color: white;
			}
			#content{
				margin: 0 auto;
				background: rgb(223,23,23);
				border-radius: 20px;
				height: 60vh;
				width: 85vw;
				box-shadow: 0px 6px 6px rgba(0, 0, 0, 0.25);
				font-family: sans-serif;
				font-size: 20px;
				color: white;
				border: 2px solid black;
			    border-radius: 5px;
			}
			#transactionContainer{
				background: white;
				color: black;
				height: 40vh;
				width: 80vw;
				margin: 0 auto;
				text-align: center;
				border: 2px solid black;
			    border-radius: 5px;
			    overflow: scroll;
			}
			span{
			    padding: 2px;
			    border-bottom: 2px solid black;
			}
			#signout{
			  height: 50px;
			  width: 100px;
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
            tr:nth-child(even) {
              background: lightgrey;
            }
			
		</style>
	</head>
	
	<body>
		<h2 style= "border-bottom: 2px solid rgb(223,23,23); font-size: 30px; font-family: sans-serif; padding-left: 25px; color: black;"> Transaction History </h2>
		
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
			<p style = "text-align: center;">Recent Transactions: </p>
			<div id ="transactionContainer">
			 <?php
			 $accNum =  $_SESSION['accNum'];
			  echo "<table style = 'border: none; width: 100%;'>";
    		  echo "<tr><th>Date</th> <th>Withdrawal</th> <th>Deposit</th> <th>Balance</th> <th>Transaction Name</th></tr>";
                $query = "SELECT date, withdrawl, deposit, balance, transaction_name, transaction_id FROM transaction_table WHERE accNum = '$accNum'";
                $result = mysqli_query($dbConnect, $query);
                if ($result->num_rows > 0){
    		    while ($row = mysqli_fetch_assoc($result)){
    		        echo "<tr><td>" . $row['date'] . "</td><td>" . $row['withdrawl'] . "</td><td>" . $row['deposit'] . "</td><td>" . $row['balance'] . "</td><td>" . $row['transaction_name']  . "</td></tr>";
    		        }
                }
                echo "</table>"
    		    ?>
			</div>
		</div>
	</body>

</html>