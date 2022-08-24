<?php 
    include("../include.php");

// session start for admin & if logout is pressed, redirection ton admin login


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
				width: 85vw;
				box-shadow: 0px 6px 6px rgba(0, 0, 0, 0.25);
				font-family: sans-serif;
				font-size: 20px;
				color: white;
				display: flex;
				justify-content: center;
				align-items: center;
				border: 2px solid black;
			    border-radius: 5px;
			}
			#statements{
				background: white;
				color: black;
				height: 50vh;
				width: 75vw;
				margin: 0 auto;
				resize: both;
				overflow: scroll;
				border: 2px solid black;
			    border-radius: 5px;
			}
			button{
			  height: 50px;
			  width:  150px;
			  border-radius: 5px;
			  background: white;
			  color: rgb(223,23,23);
			  font-size: 14px;
		      border: 2px solid black;
		      margin-left: 50%;
		      margin-right: 50%;
		      margin-top: 20px;
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
			
		</style>
	</head>
	
	<body>
		<h2 style= "border-bottom: 2px solid rgb(223,23,23); font-size: 30px; font-family: sans-serif; padding-left: 25px; color: black;"> View/Print Your Statements </h2>
		
		<?php include("customerNavBar.php");?>

		<div id = "content">
    		<div id ="statements">
    			<p><h1><b>Digital Banking</b></h1>
    			<b></b><h3>© 2022-<?php echo date("Y");?></h3></b>
    			<b><h3>Date: <?php echo date("d-m-Y");?></h3></b>
    			<b><h3>Time: <?php date_default_timezone_set("America/New_York"); echo date("h:i");?> </h3></b>
    			</p>
    			<br><br><br>
    		    <?php
    		    $accNum =  $_SESSION['accNum'];
    		    echo "</b><h3>Account Number: " . $accNum ."<br><p style = 'border-bottom: 2px solid black; width: 100%;'>Transactions:</u><h3><br>";
                echo "<table style = 'border: none; width: 100%;'>";
    		    echo "<tr><th>Date</th> <th>Withdrawal</th> <th>Deposit</th> <th>Balance</th> <th>Transaction Name</th> <th>Transaction ID</th></tr>";
                $query = "SELECT date, withdrawl, deposit, balance, transaction_name, transaction_id FROM transaction_table WHERE accNum = '$accNum'";
                $result = mysqli_query($dbConnect, $query);
                if ($result->num_rows > 0){
    		    while ($row = mysqli_fetch_assoc($result)){
    		        echo "<tr><td>" . $row['date'] . "</td><td>" . $row['withdrawl'] . "</td><td>" . $row['deposit'] . "</td><td>" . $row['balance'] . "</td><td>" . $row['transaction_name']  . "</td><td>" . $row['transaction_id'] . "</td></tr>";
    		        }
                }
                echo "</table>"
    		    ?>
    		</div>
		</div> 
		<button onclick="printStatement('statements')">Print this page</button>
		


		<script>
			function printStatement(statements){
				var statement = document.getElementById(statements).innerHTML;
				var originalStatement = document.body.innerHTML;
				document.body.innerHTML = statement;
				window.print();
				document.body.innerHTML = originalStatement;
			}	
		</script>

	
	</body>

</html>