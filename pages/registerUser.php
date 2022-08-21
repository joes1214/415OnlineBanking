<?php
// Include config file
include('../database/dbConnectUser.php');
 
// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$email = $add_street = $add_state = $add_town = $add_zip = $add_aptNum = "";
$LName = $FName = "";
// $inputError = $username_err = $password_err = $confirm_password_err = "";
$errors = [];
// $emptyError = "Field cannot be empty";
// $emailErr = "";
// $noErr = false;

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(empty(trim($_POST["username"]))){
        $errors["username"] = "Username Required";
        //create username validation
    }else{
        $username = $_POST["username"];
    }

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
    
    if(empty(trim($_POST["email"]))){
        $errors['email'] = "Email Required";
    }else{ //if not empty, check to see if valid email format
        if(!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
            $errors['emailInvalid'] = "Invalid email format";
        }else{//valid, proceed
            $email = $_POST["email"];
        }
    }

    if(empty(trim($_POST["add_street"]))){
        $errors['add_street'] = "Address Required";
    }else{
        $add_street = $_POST["add_street"];
    }

    if(empty(trim($_POST["add_zip"]))){
        $errors['add_zip'] = "Zip Code Required";
        //maybe add some validation for zip code
    }else{
        $add_zip = $_POST["add_zip"];
    }

    if(empty(trim($_POST["add_town"]))){
        $errors['add_town'] = "City Required";
    }else{
        $add_town = $_POST["add_town"];
    }

    if(empty(trim($_POST["lname"]))){
        $errors['lname'] = "Last Name Required";
    }else{
        $LName = $_POST["lname"];
    }

    if(empty(trim($_POST["fname"]))){
        $errors['fname'] = "First Name Required";
    }else{
        $FName = $_POST["fname"];
    }

    if(empty(trim($_POST["add_aptNum"]))){
        $add_aptNum = null;
    }else{
        $add_aptNum = $_POST["add_aptNum"];
    }

    if((trim($_POST["add_state"])) == "--"){
        $errors['add_state'] = "Select a state";
    }else{
        $add_state = $_POST["add_state"];
    }

    $add_state = trim($_POST["add_state"]);
    
    $query = "SELECT * FROM user_table WHERE username='$username' LIMIT 1";
    $result = mysqli_query($dbConnect, $query);
    if(mysqli_num_rows($result) > 0){
        $errors["username"] = "Username already in use";
    }

    //accNum ran generator
    $Accepted = false;
    $accNum = 0;
    while(!$Accepted){
        $accNum = rand(1000000000, 9999999999);
        $query = "SELECT * FROM user_table WHERE accNum='$accNum' LIMIT 1";
        $result = mysqli_query($dbConnect, $query);
        if(mysqli_num_rows($result) == 0){
            $Accepted = true;
        }
    }

    if(count($errors) == 0){
        //fix so only after the information gets valided it goes through
        $query = "INSERT INTO user_table set username=?, email=?, password=?, accNum=?";
        $queryCI = "INSERT INTO customer_info set accNum=?, LName=?, FName=?, add_street=?, add_town=?, add_state=?, add_zip=?, add_aptNum=?";
        
        $stmt = $dbConnect->prepare($query);
        $stmt->bind_param('ssss', $username, $email, $hashed_password, $accNum);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $result = $stmt->execute();

        $stmt = $dbConnect->prepare($queryCI);
        $stmt->bind_param('ssssssss', $accNum, $LName, $FName, $add_street, $add_town, $add_state, $add_zip, $add_aptNum);
        $resultCI = $stmt->execute();

        if($result && $resultCI){
            header("location: login.php");
        }else{
            echo "Something went wrong.";
        }
    }

    mysqli_close($dbConnect);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Registration</title>
        <!-- Insert css if needed -->
    <style>
        #registrationForm{
			background: red;
			font: 14px sans-serif; 
			border: solid black 2px;
			border-radius: 10px;
            margin-left: auto;
            margin-right: auto;
			padding: 5px;
			color: white;
			height: auto;
			width: 450px;
			text-align: center;
			resize: vertical;
  			overflow: auto;
              
		}
		table{
		    width: 100%;
		}
		input{
		    margin: 5px;
    	    border: 1px solid black;
    		border-radius: 5px;
    		width: 60%;
		}
        select{
		    margin: 5px;
    	    border: 1px solid black;
    		border-radius: 5px;
    		width: 60%;
        }
        label{
            font-family: sans-serif;
            font-size: 18px;
        }
        td:nth-child(even){
            width: 60%;
        }
        td:nth-child(odd){
            width: 40%;
            text-align: center;
        }
        .createBtn{
            height: 30px;
            width: 100%;
    	    border: 1px solid black;
    		border-radius: 5px;
    		width: 100%;
    		color: black;
    		background: white;
        }
        
    </style>
</head>

<body>
<h1 style= "text-align: center;"><u>Registration</u></h1>
<p style= "text-align: center;"> Please fill out the form to create an account</p>
<!-- For the time being, this doesnt center proprely and I cant figure it out,
     otherwise some of the issues have been fixed and everything is 
     displaying properly -->

    <form id = "registrationForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
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
            <table>
                <tr>
                    <td>
                <label>Username</label>
                    </td>
                    <td>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                    </td>
                </tr>

                <tr>
                    <td>
                        <label>Password</label>
                    </td>

                    <td>
                        <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                    </td>
                 </tr>
            
                <tr>
                    <td>
                <label>Confirm Password</label>
                    </td>
                    <td>
                        <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                    </td>
                </tr>
            
                <tr>
                    <td>
                        <label>Email</label>
                    </td>
                    <td>
                        <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
                    </td>
                </tr>
        
            <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>
                <tr>
                    <td>
                        <label>First Name</label>
                    </td>
                    <td>
                        <input type="text" name="fname" class="form-control" value="<?php echo $FName; ?>">
                    </td>
                </tr>

            <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>   
                <tr>
                    <td>
                        <label>Last Name</label>
                    </td>
                    <td>
                        <input type="text" name="lname" class="form-control" value="<?php echo $LName; ?>">
                    </td>
                </tr>

                <?php //(empty($_POST["add_street"])) ? echo "<p>".$emptyError."</p>" : '';?> 
                <tr>  
                    <td>
                        <label>Street</label>
                    </td>
                    <td>
                        <input type="text" name="add_street" class="form-control" value="<?php echo $add_street; ?>">
                    </td>
                </tr>

                <tr>
                    <td>
                        <label>City</label>
                    </td>
                    <td>
                        <input type="text" name="add_town" class="form-control" value="<?php echo $add_town; ?>">
                    </td>
                </tr>
            
                <tr>
                    <td>
                        <label>State</label> <!-- Could turn this into a dropdown -->
                    </td>
                    <td>
                        <select name="add_state">
                            <option value="--">--</option>
                            <option value="AL">AL</option>
                            <option value="AK">AK</option>
                            <option value="AZ">AZ</option>
                            <option value="AR">AR</option>
                            <option value="CA">CA</option>
                            <option value="CO">CO</option>
                            <option value="CT">CT</option>
                            <option value="DE">DE</option>
                            <option value="FL">FL</option>
                            <option value="GA">GA</option>
                            <option value="HI">HI</option>
                            <option value="ID">ID</option>
                            <option value="IL">IL</option>
                            <option value="IN">IN</option>
                            <option value="IA">IA</option>
                            <option value="KS">KS</option>
                            <option value="KY">KY</option>
                            <option value="LA">LA</option>
                            <option value="ME">ME</option>
                            <option value="MD">MD</option>
                            <option value="MA">MA</option>
                            <option value="MI">MI</option>
                            <option value="MN">MN</option>
                            <option value="MS">MS</option>
                            <option value="MO">MO</option>
                            <option value="MT">MT</option>
                            <option value="NE">NE</option>
                            <option value="NV">NV</option>
                            <option value="NH">NH</option>
                            <option value="NJ">NJ</option>
                            <option value="NM">NM</option>
                            <option value="NY">NY</option>
                            <option value="NC">NC</option>
                            <option value="ND">ND</option>
                            <option value="OH">OH</option>
                            <option value="OK">OK</option>
                            <option value="OR">OR</option>
                            <option value="PA">PA</option>
                            <option value="RI">RI</option>
                            <option value="SC">SC</option>
                            <option value="SD">SD</option>
                            <option value="TN">TN</option>
                            <option value="TX">TX</option>
                            <option value="UT">UT</option>
                            <option value="VT">VT</option>
                            <option value="VA">VA</option>
                            <option value="WA">WA</option>
                            <option value="WV">WV</option>
                            <option value="WI">WI</option>
                            <option value="WY">WY</option>
                        </select>
                    </td>
                </tr>
                
                <tr>
                    <td>
                        <label>Apartment #</label>
                    </td>
                    <td>
                        <input type="text" name="add_aptNum" class="form-control" value="<?php echo $add_aptNum; ?>">
                    </td>
                </tr>
            
                <tr>
                    <td>
                        <label>Zip Code</label>
                    </td>
                    <td>
                        <input type="text" name="add_zip" class="form-control" value="<?php echo $add_zip; ?>">
                    </td>
                </tr>
                <tr>
                   <td colspan = "2"><input class = "createBtn" name = "create" type = "submit" value = "Create Account"/></td>
                </tr>
            </table>
    </form>

</body>