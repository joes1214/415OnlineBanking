<?php 
session_start();
include('../database/dbConnectUser.php');
include('verifySession.php');

$accNum =  $_SESSION['accNum'];
$depositMessage = "";
$withdrawlMessage = "";
$dates = date("Y-m-d");
$sql= "SELECT currentBal FROM customer_info WHERE accNum = '$accNum'";
$results = mysqli_query($dbConnect, $sql);
if ($results->num_rows > 0) {
    while($row = $results->fetch_assoc()) {
        $currentBal = $row['currentBal'];
    }
}
if(isset($_POST['submit_btn'])){
    	$counter = (rand(100,999));
    	$trasname = "#" . $counter;
    	
		if(empty($_POST["deposit1"])){
			$dep2 = 0;
		}else{
			$dep2 = $_POST["deposit1"];
			$withdrawl = 0;
			$balance= $currentBal + $dep2;
            //$query = "INSERT INTO transaction_table VALUES ('".$accNum."', '".$dates."', '".$widthrawal."', '".$dep2."', '".$balance."', '".$trasname.")";
			$query = "INSERT INTO transaction_table SET accNum=?, date=?, withdrawl=?, deposit=?, balance=?, transaction_name=?, transaction_id=NULL";
            $stmt = $dbConnect->prepare($query);
            $stmt->bind_param('ssssss', $accNum, $dates, $withdrawl, $dep2, $balance, $trasname);

			$result = $stmt->execute();
			
			$query2 = "UPDATE customer_info SET currentBal = $balance WHERE accNum = $accNum";
			//
			if($result && mysqli_query($dbConnect, $query2)){
				$depositMessage = "Successfully deposited $$dep2";
				$currentBal = $balance;
			}else{
				$depositMessage = "ERROR";
			}
		}
		
		if(empty($_POST["withdraw1"])){
			$withdrawl = 0;
		}else{
			$withdrawl = $_POST["withdraw1"];
			$deposit = 0;
			$balance= $currentBal - $withdrawl;
			if($balance > 0){
				//$query = "INSERT INTO transaction_table VALUES ('".$accNum."', '".$dates."', '".$widthrawal."', '".$dep2."', '".$balance."', '".$trasname.")";
				$query = "INSERT INTO transaction_table SET accNum=?, date=?, withdrawl=?, deposit=?, balance=?, transaction_name=?, transaction_id=NULL";
				$stmt = $dbConnect->prepare($query);
				$stmt->bind_param('ssssss', $accNum, $dates, $withdrawl, $dep2, $balance, $trasname);

				$result = $stmt->execute();
				
				$query2 = "UPDATE customer_info SET currentBal = $balance WHERE accNum = $accNum";
				//
				if($result && mysqli_query($dbConnect, $query2)){
					$withdrawlMessage = "Successfully withdrew $$withdrawl";
					$currentBal = $balance;
				}else{
					$withdrawlMessage = "ERROR";
				}
			} else {
				$withdrawlMessage = "Not enough funds!";
			}
            
		}
		/*
		//old code, don't know if it works or not, but it's there
		if(empty($_POST["withdraw1"])){
			$with2 = 0;
		}else{
			$with2= $_POST["withdraw1"];
			$deposit = 0;
			$balance= $currentBal - $with2;
			$query = "INSERT INTO transaction_table (accNum, date, withdrawl, deposit, balance, transaction_name, transaction_id) VALUES ('$accNum', '$dates', '$widthdrawal', '$dep2', '$dep2', '$trasname', '$id')";
		}
		if(empty($_POST["transfer1"])){
			$with2 = 0;
		}else{
			$with2= $_POST["transfer1"];
			//$query  = " ";
		}
	
        
        $transactionname1="TEST1";
        
    	//$query = "INSERT INTO transaction_table (accNum, date, withdrawl, deposit, balance, transaction_name) 
    	//VALUES ('.$accNum.', '.$date11.', '.$with2.', '.$dep2.', SUM(deposit-withdrawl), '.$transactionname1.')";
		
		$query = "INSERT INTO transaction_table set accNum=?, date=?, withdrawl=?, deposit=?, balance=?, transaction_name=?, transaction_id=NULL";
		
		
			this literally doesn't make any sense. The top query will work fine but the bottom one work
			why or how is beyond me. anyways, the query now inserts. add some logic and it'll be functional
		*/
		//$query = "INSERT INTO transaction_table SET accNum=$accNum, date=$date11, withdrawl=$with2, deposit=$dep2, balance=$balance, transaction_name=$transactionname1, transaction_id=NULL";
		//$query = "INSERT INTO transaction_table set accNum=?, date=?, withdrawl=?, deposit=?, balance=?, transaction_name=?, transaction_id=NULL";
    	//???? I dont get it

		//echo $query;
		/*
		$result = mysqli_query($dbConnect, $query);
        if ($result){
            $message = "sucess" ;
        }else{
			$message = $dbConnect -> error;
		}
    	mysqli_close($dbConnect);*/
}

if(isset($_POST['transfer_btn'])){
	$transferAmnt = $_POST["transfer1"];
	$accToTransfer = $_POST["accNumber"];
	$transferMessage = "";

	if(empty($transferAmnt) && empty($accToTransfer)){
		$transferMessage = "Empty Field, please fill out";
	} else {
		$deposit = 0;
		$balance= $currentBal - $transferAmnt;
		
		$accToQuery = "SELECT * FROM customer_info WHERE accNum = $accToTransfer";
		$accToResult = mysqli_query($dbConnect, $accToQuery);

		if(!$accToResult->num_rows > 0){
			$transferMessage = "Account not found!";
		} else if($accToTransfer == $accNum){
			$transferMessage = "Can't send to self!";
		} else{
			$balance= $currentBal - $transferAmnt;
			if($balance > 0){
				$accToInfo = $accToResult->fetch_assoc();
				$trasname = "TRANSFER TO #$accToTransfer";
				
				$query = "INSERT INTO transaction_table SET accNum=?, date=?, withdrawl=?, deposit=?, balance=?, transaction_name=?, transaction_id=NULL";
				
				$stmt = $dbConnect->prepare($query);
				$stmt->bind_param('ssssss', $accNum, $dates, $transferAmnt, $deposit, $balance, $trasname);
				$result = $stmt->execute();
				$query2 = "UPDATE customer_info SET currentBal = $balance WHERE accNum = $accNum";
				//INSERTs and UPDATEs to the current user
				
				$accToBalance = $accToInfo['currentBal'];
				$accToCurrentBalanceUpdate = $accToBalance + $transferAmnt;
				
				$accToTransferName = "TRANSFER FROM #$accNum";
				
				$accToQueryInsert = "INSERT INTO transaction_table SET accNum=?, date=?, withdrawl=?, deposit=?, balance=?, transaction_name=?, transaction_id=NULL";
				$zeroValue = 0;
				$stmt = $dbConnect->prepare($accToQueryInsert);
				$stmt->bind_param('ssssss', $accToTransfer, $dates, $zeroValue, $transferAmnt, $accToCurrentBalanceUpdate, $accToTransferName);
				
				$accToResult = $stmt->execute();
				
				$accToUpdateTable = "UPDATE customer_info SET currentBal = $accToCurrentBalanceUpdate WHERE accNUm = $accToTransfer";
				
				if($result && mysqli_query($dbConnect, $query2) && $accToResult && mysqli_query($dbConnect, $accToUpdateTable)){
					$transferMessage = "Successfully sent $$transferAmnt to ".$accToInfo['FName']." ".$accToInfo['LName'];
					$currentBal = $balance;
				}else{
					$transferMessage = "ERROR";
				}
				//by god's will, I some how got this to all work nicely :)
			} else {
				$transferMessage = "Not enough funds!";
			}
		}
	}
}
?>

<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Customer Balance Transfer</title>
		<style>
			body{
			    font-family: Arial, Helvetica, sans-serif;
				margin: 0;
			}
			.center{
				padding: 10px 0;
				margin: 0 auto;
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
			#link.active{
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
				height: 50vh;
				width: 65vw;
				box-shadow: 0px 6px 6px rgba(0, 0, 0, 0.25);
				font-family: sans-serif;
				font-size: 20px;
				color: white;
				display: flex;
				text-align: center;
				justify-content: center;
				align-items: center;
				padding: 25px 0px 25px;
				
			}
			#formContainer{
			    margin: 0 auto;
			    height: wrap;
			    width: 45vw;
			    border-radius: 20px;
			    background: white;
			    color: black;
			}
			input[type=number]{
			   border-radius: 20px;
			   background: white;
			   color: rgb(223,23,23);
			   font-size: 14px;
			   border: 2px solid black;
			   width: 80%;
			}
			input[type=submit]{
			   border-radius: 20px;
			   background: white;
			   color: rgb(223,23,23);
			   font-size: 14px;
			   border: 2px solid black;
			}
			#transferForm{
			  width: 45vw;;
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

			input[type=number]::-webkit-inner-spin-button, 
			input[type=number]::-webkit-outer-spin-button { 
			-webkit-appearance: none; 
			}
			input[type=number] {
			-moz-appearance: textfield;
			}

		</style>
	</head>
	
	<body>
		<h2 style= "border-bottom: 2px solid rgb(223,23,23); font-size: 30px; font-family: sans-serif; padding-left: 25px; color: black;"> Deposit, Withdraw or Transfer </h2>
		
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
	   <div id = "formContainer">
        <?php //echo $message; ?>
		<h3>Make a Deposit</h3>
		<h4>Current Balance: $<?php echo $currentBal;?></h4>
    	<form id = "transferForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
		<table class='center'>
			<tr>
				<td><p>Deposit:</p></td>
				<td>$ <input type="number" min="0" step="0.01" name="deposit1" ></td>
				<?php if(!empty($depositMessage)){
					echo "<td>$depositMessage</td>";
				}
				?>
			</tr>
			<tr>
    	   		<td><p>Withdraw:</p></td>
				<td>$ <input type="number" min="0" step="0.01" name="withdraw1" ></td>
				<?php if(!empty($withdrawlMessage)){
					echo "<td>$withdrawlMessage</td>";
				}
				?>
			</tr>
    	</table>
		<input id="submit_btn" name="submit_btn" type="submit" value = "Submit"/>
    	</form>
    	<p style ="border-bottom: 2px solid black; width: 100%;"></p>
		<h3>Make a Transfer</h3>
		<?php if(!empty($transferMessage)){
					echo "<h4>$transferMessage</h4>";
				}
		?>
		<form id = "transferForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
			<table class='center'>
				<tr>
					<td><p>Transfer: $</p></td>
					<td> <input type="number" min="0.01" step ="0.01" name="transfer1"></td>
				</tr>
				<tr>
					<td><p>Account to:  </p></td>
					<td><p> </p><input type="number"  min="1000000000" max="9999999999" name="accNumber"></td>
				</tr>
			</table>
		
		<input name="transfer_btn" type="submit" value = "Transfer"></input>
		</form>
		</div>
	</div> 
	</body>
</html>