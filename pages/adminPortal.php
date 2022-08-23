<?php 
include('../database/dbConnectUser.php');
include('verifySession.php');

if(isset($_POST['adminLogoutBtn'])){
   session_destroy();
   header('location: adminLogin.php');
}
$resultNotApproved;
$userNotFound = "";
if($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['home'])){
	/*
	*	If the server has a POST request, it will try and pull up an existing user
	*/
    $accNum = $_POST['accNum'];

    $query = "SELECT * FROM customer_info WHERE accNum = '$accNum'";
    
	$result = mysqli_query($dbConnect, $query);

    if (!$result->num_rows > 0) {
        $userNotFound = "This account was not found";
    }

}else{
	//If not, it will display a list of users who have not been approved yet
	$query = "SELECT accNum FROM user_table WHERE approved LIKE 0";
	$resultNotApproved = mysqli_query($dbConnect, $query);
	
	//As well as list regular users
	$query = "SELECT accNum, username FROM user_table WHERE approved LIKE 1 LIMIT 100";
	$result = mysqli_query($dbConnect, $query);

}
?> 


<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Admin Portal</title>
    <link rel="stylesheet" type="text/css" >
    <link rel="stylesheet" type="text/css" href="../scripts/generalStyle.css" media="screen" />
    <!-- <link href="style.css" rel="stylesheet" type="text/css" /> -->
		<style>
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
			.adminContainer1{
				margin: 0 auto;
				position: relative;
				height: 85vh;
				width: 85vw;
				background: rgb(223,23,23);
				box-shadow: inset 0px 4px 4px rgba(0, 0, 0, 0.25);
				border-radius: 12px;	
			}
			.adminContainer2{	
				margin-left: 100px;
				position: absolute;
				top: 8%;
				background: white;
				font-family: sans-serif;
				height: 85%;
				width: 85%;
				border-radius: 12px; 
			}
			.center{
				padding: 15px 0;
				margin-left: auto;
				margin-right: auto;
			}
			#adminSearch{
				margin-top: 10px;
				width: 60%;
				margin-left: 150px;
				font-family: sans-serif;
				font-size: 17px;
				border-width: 3px;
				border-radius: 8px;
				border-color: rgb(223,23,23);;
				color: black;
			}
			#adminSearchBtn{
				background: rgb(223,23,23);
				color: white;
				font-size: 17px;
				border-width: 3px;
				border-radius: 8px;
				border-color: white;
			}
			#adminSearchBtn:hover{
				opacity: 0.6;
			}
			#adminResults{
			    /*Might need help making this look nicer*/
                overflow: scroll;
                left: 10%;
                right: 10%;
			}
			#adminLogoutBtn{ 
				width: 150px; 
				height: 30px; 
				text-align: center;
				font-family: sans-serif; 
				color: white;
				background: black;
				font-size: 17px;
				border-width: 3px;
				border-radius: 8px;
				border-color: black;
				position: absolute;
			 	bottom: 10;
				left: 80%;
			}
			#adminLogoutBtn:hover{
				opacity: 0.6;
			}
			td{
				text-align: right;
			}
			table{
			    
				border-collapse: collapse;
				table-layout: fixed;
				width: 200px;
			}

			th,
			td{
			    width: 100px;
				text-align: center;
				border: 1px solid black;
				overflow: hidden;
				font-family: Arial;
			}
			#viewBtn{
			    border-radius: 20px;
                background: white;
                color: rgb(223,23,23);
                font-size: 14px;
                border: 2px solid black;
			    
			}

	
		</style>
	</head>
	
	<body>
		<!--Page container --> 

			<h3 style= "border-bottom: 2px solid rgb(223,23,23); font-size: 25px; font-family: sans-serif; padding-left: 25px; color: black;"> Welcome Admin! </h3>
			<div class = "navBar">
    			<a id= "link" href = "adminPortal.php">Home</a>
            </div>
			<!-- Container 1 --> 	
			<div class = "adminContainer1">
				<!-- Container 2-->  
				<div class = "adminContainer2">
				<form action= "adminPortal.php" method="post">
					<!-- Admin Search Bar -->
					<input id = "adminSearch" type="text" placeholder="Search users by account number.." name="accNum">
      			    <input id = "adminSearchBtn" type="submit" name = "adminSearchBtn" value ="Search"/>
				</form>		
					<div id = "adminResults">
						<table class='center'>
							<tr>
								<th>Account Number</th>
								<th>Username</th>
								<th>Last Name</th>
								<th>First Name</th>
								<th>Street</th>
								<th>City</th>
								<th>State</th>
								<th>Zip</th>
								<th>Apt #</th>
								<th></th>
							</tr>
							<?php
							if(!empty($resultNotApproved)){
								foreach ($resultNotApproved as $userInfo):
									$query = "SELECT * FROM customer_info WHERE accNum LIKE ".$userInfo['accNum'];
									$custInfoResults = mysqli_query($dbConnect, $query);
									$customerInformation = $custInfoResults->fetch_assoc();
									
									$query = "SELECT * FROM user_table WHERE accNum LIKE ".$userInfo['accNum'];
									$UsrnameResult = mysqli_query($dbConnect, $query);
									$customerUsername = $UsrnameResult->fetch_assoc();

									
							
									echo "<tr>"; // starts row
									echo "<td> <h5><span>".$customerInformation['accNum']."</span></h5> </td>";
									
									echo "<td> <h5><span>".$customerUsername['username']."</span></h5> </td>";
									
									echo "<td> <h5><span>".$customerInformation['LName']."</span></h5> </td>";
									
									echo "<td> <h5><span>".$customerInformation['FName']."</span></h5> </td>";
									
									echo "<td> <h5><span>".$customerInformation['add_street']."</span></h5> </td>";
									
									echo "<td> <h5><span>".$customerInformation['add_town']."</span></h5> </td>";

									echo "<td> <h5><span>".$customerInformation['add_state']."</span></h5> </td>";
									
									echo "<td> <h5><span>".$customerInformation['add_zip']."</span></h5> </td>";
									
									echo "<td> <h5><span>".$customerInformation['add_aptNum']."</span></h5> </td>";
									
									echo "<td>";
									?>
									<form action="adminViewUser.php" method="post">
										<?php echo "<button type='submit' name=accNum value=".$customerInformation['accNum'].">View</button>"; ?>
									</form>
									<?php
									echo "</td>";
									
									echo "</tr>";
								endforeach;
							}
							?>

							<?php
							if(!empty($result)){
								foreach($result as $row):
								    $query = "SELECT * FROM customer_info WHERE accNum LIKE ".($row['accNum']);
									$UsrnameResult = mysqli_query($dbConnect, $query);
									$customerUsername = $UsrnameResult->fetch_assoc();
									
									echo "<tr>"; // starts row
									echo "<td> <h5><span>".$row['accNum']."</span></h5> </td>";
                                    echo "<td> <h5><span>".$row['username']."</span></h5> </td>";
									echo "<td> <h5><span>".$customerUsername['LName']."</span></h5> </td>";
									echo "<td> <h5><span>".$customerUsername['FName']."</span></h5> </td>";
									echo "<td> <h5><span>".$customerUsername['add_street']."</span></h5> </td>";
									echo "<td> <h5><span>".$customerUsername['add_town']."</span></h5> </td>";
									echo "<td> <h5><span>".$customerUsername['add_state']."</span></h5> </td>";
									echo "<td> <h5><span>".$customerUsername['add_zip']."</span></h5> </td>";
									echo "<td> <h5><span>".$customerUsername['add_aptNum']."</span></h5> </td>";
    
                                    
									echo "<td>";
									echo"
										<form action='adminViewUser.php' method='post'>
											<input type='submit' id = 'viewBtn' value = 'View'/>
											<input type='hidden' name= 'accNum' value='".$row['accNum']."' value = 'View'/>
										</form>";
									echo "</td></tr>";
								endforeach;
							}
							?>
						</table>
						
						<?php
							echo "<h3 style = 'text-align: center'>$userNotFound</h3>";
						?>

	                </div>
	                
	                
					
				</div> 
					<!--Log out button --> 
					<form method = "post">
					    <input id = "adminLogoutBtn" type = "submit" name = "signout" value = "Log out"/>
					</form>

			</div> 	

	</body>
</html>
