<?php
ob_start();
session_start();
include_once 'connect.php';
$error = false;
if (isset($_POST['signup']) ) {
	
	    // clean user inputs to prevent sql injections
		$username = trim($_POST['username']);
		$username = strip_tags($username);
		$username = htmlspecialchars($username);
		
		$email = trim($_POST['email']);
		$email = strip_tags($email);
		$email = htmlspecialchars($email);
		
		$password = trim($_POST['password']);
		$password = strip_tags($password);
		$password = htmlspecialchars($password);
		// basic name validation
		if (empty($username)) {
			$error = true;
			$nameError = "Please enter your full name.";
		} else if (strlen($username) < 3) {
			$error = true;
			$nameError = "Name must have atleat 3 characters.";
		} else if (!preg_match("/^[a-zA-Z ]+$/",$username)) {
			$error = true;
			$nameError = "Name must contain alphabets and space.";
		}
		//basic email validation
		if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
			$error = true;
			$emailError = "Please enter valid email address.";
		} 
		// check email exist or not
			$query = "SELECT email FROM users WHERE email='$email'";
			$result = mysql_query($query);
			$count = mysql_num_rows($result);
			if($count!=0){
				$error = true;
				$emailError = "Provided Email is already in use.";
			}
		// password validation
		if (empty($password)){
			$error = true;
			$passError = "Please enter password.";
		} else if(strlen($password) < 6) {
			$error = true;
			$passError = "Password must have atleast 6 characters.";
		}
		// password encrypt using SHA256();
		$password = hash('sha256', $password);
		// if there's no error, continue to signup
		if( !$error ) {
		
			$query = "INSERT INTO users(username,email,password) VALUES('$username','$email','$password')";
			$res = mysql_query($query);
			
			if ($res) {
				$errTyp = "success";
				$errMSG = "Registration Successfully,";
				unset($username);
				unset($email);
				unset($password);
			} else {
				$errTyp = "danger";
				$errMSG = "Something went wrong, try again later...";	
			}	
				
		}
	}
	if( isset($_POST['login']) ) {	
		
		// prevent sql injections/ clear user invalid inputs
		$email = trim($_POST['email']);
		$email = strip_tags($email);
		$email = htmlspecialchars($email);
		
		$password = trim($_POST['password']);
		$password = strip_tags($password);
		$password = htmlspecialchars($password);
		// prevent sql injections / clear user invalid inputs
		if(empty($email)){
			$error = true;
			$loemailError = "Please enter your email address.";
		} else if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
			$error = true;
			$loemailError = "Please enter valid email address.";
		}
		if(empty($password)){
			$error = true;
			$lopassError = "Please enter your password.";
		}
		// if there's no error, continue to login
		if (!$error) {
			
			$password = hash('sha256', $password); // password hashing using SHA256
		
			$res=mysql_query("SELECT * FROM users WHERE email='$email'");
			$row=mysql_fetch_array($res);
			$count = mysql_num_rows($res); // if uname/pass correct it returns must be 1 row
			
			if( $count == 1 && $row['password']==$password ) {
				$_SESSION['user'] = $row['id'];
				$tm=date("Y-m-d H:i:s");
				header("Location: profile.php");
			}
			else {
				$loerrMSG = "Incorrect Credentials, Try again...";
			}
				
		}
		
	}
?>
<html>
<head>
<title>Sample</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
<div class="row">
  <div class="col-md-2"></div>
  <div class="col-md-4">
     <h2>Register</h2>
   <form action="index.php" method = "POST" >
     <?php
			if ( isset($errMSG) ) {
				
				?>
				<div class="form-group">
            	<div class="alert alert-<?php echo ($errTyp=="success") ? "success" : $errTyp; ?>">
				<span class="glyphicon glyphicon-info-sign"></span> <?php echo $errMSG; ?>
                </div>
            	</div>
                <?php
			}
			?>
  <div class="form-group">
    <label for="exampleInputEmail1">Username</label>
    <input name='username' type="text" class="form-control" id="exampleInputEmail1" placeholder="Username">
	<span class="text-danger"><?php echo $nameError; ?></span>
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Email address</label>
    <input name='email' type="email" class="form-control" id="exampleInputEmail1" placeholder="Email">
	<span class="text-danger"><?php echo $emailError; ?></span>
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Password</label>
    <input name='password' type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
	<span class="text-danger"><?php echo $passError; ?></span>
  </div>
  
  <button name='signup' type="submit" class="btn btn-default">Submit</button>
</form>
  </div>
  <div class="col-md-4">
    <h2>Login</h2>
   <form action="index.php" method='post'>
    <?php
			if ( isset($loerrMSG) ) {
				
				?>
				<div class="form-group">
            	<div class="alert alert-danger">
				<span class="glyphicon glyphicon-info-sign"></span> <?php echo $loerrMSG; ?>
                </div>
            	</div>
                <?php
			}
			?>
  <div class="form-group">
    <label for="exampleInputEmail1">Email address</label>
    <input name="email" type="email" class="form-control" id="exampleInputEmail1" placeholder="Email">
	<span class="text-danger"><?php echo $loemailError; ?></span>
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Password</label>
    <input name="password" type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
	<span class="text-danger"><?php echo $lopassError; ?></span>
  </div>
  
  <button name="login" type="submit" class="btn btn-default">Submit</button>
</form>
  </div>
  <div class="col-md-2"></div>
</div>
</body>
</html>