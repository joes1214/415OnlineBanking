<?php
    include("../include.php");
    

    $accNum = $_POST['accNum'];
	
    $errors = [];
    $username = $password = $confirm_password = "";
	
    if($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['changePass'])){
		echo "IN PASSWORD CHECK";
        if(empty(trim($_POST["password"]))){
            $errors['password'] = "Password Required";

        }elseif(strlen(trim($_POST["password"])) < 6){

            $errors["passwordlength"] = "Password must have atleast 6 characters.";
        }else{
            $password = $_POST["password"];
        }

        if(empty(trim($_POST["confirm_password"]))){
            $errors['confirm_password'] = "Please Confirm Password";
        }else{
            $confirm_password = $_POST["confirm_password"];
        }

        if(!empty(trim($_POST["password"])) && !empty(trim($_POST["confirm_password"]))){
            if($password != $confirm_password){
                $errors['notmatching'] = "Passwords do not match!";
            }
        }

        if(count($errors) == 0){
            $query = "SELECT * FROM user_table WHERE accNum='$accNum' LIMIT 1";
            $result = mysqli_query($dbConnect, $query);

            if(mysqli_num_rows($result) > 0){
                $query = "UPDATE user_table SET password=? WHERE user_table.accNum=?";
                $stmt = $dbConnect->prepare($query);
                $stmt->bind_param('ss', $hashed_password, $accNum);
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $result = $stmt->execute();

                if($result){
                    header("location: adminPortal.php");
                }else{
                    echo "Something went wrong.";
                }
            
            } else{
                echo "Username doesn't exist";
            }

        }
    } 
	$query = "SELECT username FROM user_table WHERE accNum='$accNum' LIMIT 1";
	$result = mysqli_query($dbConnect, $query);
	
	if (!$result->num_rows > 0) {
		echo "ERROR";
	} else {
		$row = $result->fetch_assoc();
	}
    
    mysqli_close($dbConnect);
?>

<html>
    <head>
    <title>Password Reset</title>
    <style>
        .layout{
			background: red;
			font: 14px sans-serif; 
			border: solid black 2px;
			border-radius: 10px;
            margin-left: auto;
            margin-right: auto;
			padding: 5px;
			color: black;
			height: 40vh;
			width: 30vw;
			text-align: center;
            /*display: inline-block; */
			resize: vertical;
  			overflow: auto;
              
		}
		#inputs{
		    margin: 5px;
    	    border: 1px solid black;
    		border-radius: 5px;
    		width: 75%;
		}
        #resetBtn{
            margin: 0 auto;
    	    border: 1px solid black;
    		border-radius: 5px;
    		width: 45%;
        }
        label{
            font-size: 20px;
        }
    </style>
    </head>
    <body>
		<div class = "navBar">
			<a id= "link" href = "adminPortal.php">Home</a>
		</div>

    <?php // will display any errors that are present
            if(count($errors) > 0): ?>
                <div>
                    <?php foreach ($errors as $error): ?>
                    <li>
                        <?php echo $error; ?>
                    </li>
                    <?php endforeach; ?>
                    </div>
        <?php endif; ?>

    <h1 style = "text-align: center;">Account Password Reset for: <?php echo $row['username']?>, ID: <?php echo $accNum?></h1>
        <table class="layout">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <tr>
                    <td><label>Password:</label></td>
                    <td colspan="2">
                        <input id = "inputs" type="password" name="password"/>
                    </td>
                </tr>
                <tr>
                    <td><label>Confirm Password:</label></td>
                    <td colspan="2">
                        <input  id = "inputs" type="password" name="confirm_password"/>
                    </td>
                </tr>
                <tr>
                    <td colspan = "2">
                        <input id ="resetBtn" type="submit" name="reset" value="Reset"/>
						<input type="hidden" name= "accNum" value="<?php echo $accNum;?>" value = "View"/>
                    </td>
                </tr>
            </form>
        </table>
    </body>
</html>