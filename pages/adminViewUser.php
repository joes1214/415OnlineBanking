<?php 
include('../database/dbConnectUser.php');
include('verifySession.php');

if(isset($_POST['adminLogoutBtn'])){
   session_destroy();
   header('location: adminLogin.php');
}

if($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['home'])){
    $accNum = $_POST['accNum'];
    $query = "SELECT * FROM customer_info WHERE accNum = '$accNum'";
    
	$result = mysqli_query($dbConnect, $query);
	
    if (!$result->num_rows > 0) {
        $userNotFound = "ERROR";
    } else {
        $row = $result->fetch_assoc();
        $query = "SELECT username, approved FROM user_table WHERE accNum LIKE ".($row['accNum']);
        $UsrnameResult = mysqli_query($dbConnect, $query);
        $customerUT = $UsrnameResult->fetch_assoc();
    }

    switch($customerUT['approved']){
        case 0:
            $approvedText = "Awaiting Approval";
            break;
        case 1:
            $approvedText = "Account Approved";
            break;
        case 2:
            $approvedText = "Account Disabled";
            break;
        default:
            $approvedText = "ERROR";
    }
    
}

if(($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['approved']))){
    $approveQuery = "UPDATE user_table SET approved ='1' WHERE accNum = $accNum";
    $approveResult = mysqli_query($dbConnect, $approveQuery);

    //echo $approveResult -> error;

    $query = "SELECT * FROM customer_info WHERE accNum = '$accNum'";
	$result = mysqli_query($dbConnect, $query);

    if (!$result->num_rows > 0) {
        $userNotFound = "ERROR";
    } else {
        $row = $result->fetch_assoc();
        $query = "SELECT username, approved FROM user_table WHERE accNum LIKE ".($row['accNum']);
        $UsrnameResult = mysqli_query($dbConnect, $query);
        $customerUT = $UsrnameResult->fetch_assoc();
        $approvedText = "Account Approved";
    }

} else if(($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reject']))){
    $approveQuery = "UPDATE user_table SET approved ='2' WHERE accNum = $accNum";
    $approveResult = mysqli_query($dbConnect, $approveQuery);

    $query = "SELECT * FROM customer_info WHERE accNum = '$accNum'";
	$result = mysqli_query($dbConnect, $query);
    
    if (!$result->num_rows > 0) {
        $userNotFound = "ERROR";
    } else {
        $row = $result->fetch_assoc();
        $query = "SELECT username, approved FROM user_table WHERE accNum LIKE ".($row['accNum']);
        $UsrnameResult = mysqli_query($dbConnect, $query);
        $customerUT = $UsrnameResult->fetch_assoc();
        $approvedText = "Account Disabled";
    }
}




/*
To be done here is layout the text properly and create two buttons to create/reject the user.
If Reject is pressed, nothing is done. There is no value in the database to do be used.
  such as, if rejected, it no longer shows up on the adminPortal homepage and has to be manually searched

if approved the query used should update ONLY the 'approved' column in 'user_table' from 0 to 1
thats it, when pressed can send the user back to the adminPortal.php page
*/
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
				height: 40vw;
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
                overflow: scroll;
                overflow: hidden;
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
                overflow: hidden;
                left: 10%;
                right: 10%;
                padding: 0px 0px 0px 10px;
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
			 	bottom: 5;
				left: 80%;
			}
			#adminLogoutBtn:hover{
				opacity: 0.6;
			}
            #userTransaction{
                height: 45%;
                width: wrap;
                margin: 0px 5px 0px 5px; 
                background: white;
				color: black;
				margin: 0 auto;
				text-align: center;
				border: 2px solid black;
			    border-radius: 5px;
			    overflow: scroll;
                overflow-x: hidden;
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
                        <h1><?php echo $approvedText; ?></h1>
                        <h2>Account Number: <?php echo $accNum; ?></h2>
                        <h3><?php echo $row['FName'].", ".$row['LName']; ?></h3>
                        <p><?php 
                            if($row['add_aptNum'] == NULL){
                                echo $row['add_street'].", ".$row['add_town'].", ".$row['add_state']." ".$row['add_zip'];
                            } else {
                                echo $row['add_street']." APT: ".$row['add_aptNum'].", ".$row['add_town'].", ".$row['add_state']." ".$row['add_zip'];
                            }?></p>
                                
                            <?php
                                if($customerUT['approved'] == 0)
                                {
									/*
									*	If the user is NOT Approved the following options will show up:
									*	Approve | Reject | Update
									*	Reject will set the account to Disabled
									*/
							?>
                                    <form action="adminViewUser.php" method="post">
                                        <?php echo "<input type='hidden' name=accNum value='$accNum'/>" ?>
                                        <?php echo "<button type= 'submit' name='approved' value='1'>Approve</button>";?>
                                        <?php echo "<button type= 'submit' name='reject' value='1'>Reject</button>";?>
                                        <?php echo "<button type= 'submit' name='update' value='NULL'>Update</button>";?>
                                    </form>


                                <?php } else if($customerUT['approved'] == 1){ 
								/*
								*	If the user is Approved the following options will show up:
								*	Disable | Update | Password
								*/
								?>
								<div style="display:table;padding-bottom: 5px">
                                    <form action="adminViewUser.php" method="post" style="display: table-cell">
                                        <?php echo "<input type='hidden' name=accNum value='$accNum'/>" ?>
                                        <?php echo "<button type= 'submit' name='reject' value='1'>Disable</button>";?>
                                        <?php echo "<button type= 'submit' name='update' value='NULL'>Update</button>";?>
                                    </form>
									
									<form action="adminChangePass.php" method="post" style="display: table-cell">
										<?php echo "<input type='hidden' name=accNum value='$accNum'/>" ?>
										<?php echo "<button type= 'submit' name='changePass' value='NULL'>Password</button>";?>
                                    </form>
								</div>

                                <?php } else if($customerUT['approved'] == 2){
									/*
									*	If the user is Disabled the following options will show up:
									*	Enable | Update
									*/
									?>
                                    <form action="adminViewUser.php" method="post" style="display: table-cell">
                                        <?php echo "<input type='hidden' name=accNum value='$accNum'/>" ?>
                                        <?php echo "<button type= 'submit' name='approved' value='1'>Enable</button>";?>
                                        <?php echo "<button type= 'submit' name='update' value='NULL'>Update</button>";?>
                                    </form>

                                <?php } else {?>
                                    <form action="adminViewUser.php" method="post">
                                        <?php echo "<input type='hidden' name=accNum value='$accNum'/>" ?>
                                        <?php echo "<button type= 'submit' name='approved' value='1'>Approve</button>";?>
                                        <?php echo "<button type= 'submit' name='reject' value='1'>Reject</button>";?>
                                        <?php echo "<button type= 'submit' name='update' value='NULL'>Update</button>";?>
                                    </form>
                                <?php }
                                ?>

                        <div id="userTransaction">
                            <?php
                                if($customerUT['approved'] >= 1){
                                    echo "<table style = 'border: none; width: 100%;'>";
                                    echo "<tr><th>Date</th> <th>Withdrawal</th> <th>Deposit</th> <th>Balance</th> <th>Transaction Name</th></tr>";
                                    $query = "SELECT date, withdrawl, deposit, balance, transaction_name, transaction_id FROM transaction_table WHERE accNum = '$accNum'";
                                    $result = mysqli_query($dbConnect, $query);
                                    if ($result->num_rows > 0){
                                    while ($row = mysqli_fetch_assoc($result)){
                                        echo "<tr><td>" . $row['date'] . "</td><td>" . $row['withdrawl'] . "</td><td>" . $row['deposit'] . "</td><td>" . $row['balance'] . "</td><td>" . $row['transaction_name']  . "</td></tr>";
                                        }
                                    }
                                    echo "</table>";
                                    //code directly taken from customerTransaction.php
                                }
                            ?>
                        </div>
	                </div>
			</div>
			<form id = "signoutForm" method = "post">
                <input id = "adminLogoutBtn" type = "submit" name = "signout" value = "Log out"/>
            </form>  
		</div>

	</body>
</html>