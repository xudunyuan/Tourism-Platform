<?php
session_start();
// grab recaptcha library
require_once "recaptchalib.php";

$secret = "6LdfxcUUAAAAACD9z2A3KiXjTglPYCkbb_IIGU25";
$response = null;
$reCaptcha = new ReCaptcha($secret);

// initializing variables
$username = "";
$email    = "";
$errors = array(); 

// connect to the database
require "../config.php";
$db = mysqli_connect($host,$user,$password,$db_name);
$_SESSION['db'] = $db;

// REGISTER USER
if (isset($_POST['reg_user'])) {
  // receive all input values from the form
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
  $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

  // form validation: ensure that the form is correctly filled ...
  // by adding (array_push()) corresponding error unto $errors array
  if (empty($username)) { array_push($errors, "Username is required"); }
  if (empty($email)) { array_push($errors, "Email is required"); }
  if (empty($password_1)) { array_push($errors, "Password is required"); }
  if ($password_1 != $password_2) {
	array_push($errors, "The two passwords do not match");
  }

  // first check the database to make sure 
  // a user does not already exist with the same username and/or email
  $user_check_query = "SELECT * FROM users WHERE uname='$username' OR email='$email' LIMIT 1";
  $statement = $db->prepare($user_check_query);
    $statement->execute();
   $result = $statement -> get_result();
  $user = mysqli_fetch_assoc($result);
  
  if ($user) { // if user exists
    if ($user['uname'] === $username) {
      array_push($errors, "Username already exists");
    }
    if ($user['email'] === $email) {
      array_push($errors, "email already exists");
    }

  }
  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
  	$password = md5($password_1);//encrypt the password before saving in the database

  	$query = "INSERT INTO users (uname, email, passwd) 
  			  VALUES('$username', '$email','$password')";
  	$statement = $db->prepare($query);
    	$statement->execute();
  	$_SESSION['username'] = $username;
  	$_SESSION['success'] = "You are now logged in";
  	
  	
	$queryGetID = "SELECT users.uid FROM users where users.uname = '$username'";
	$res = mysqli_fetch_array($queryGetID);
	foreach($res as $row){
	$_SESSION['userID'] = $row['uid'];}

  	header('location: index.php');
  }
}

// LOGIN USER
if (isset($_POST['login_user'])) {
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $password = mysqli_real_escape_string($db, $_POST['password']);

  if (empty($username)) {
  	array_push($errors, "Username is required");
  }
  if (empty($password)) {
  	array_push($errors, "Password is required");
  }
  if ($_POST["g-recaptcha-response"]) {
    $response = $reCaptcha->verifyResponse(
        $_SERVER["REMOTE_ADDR"],
        $_POST["g-recaptcha-response"]
    );
  }

  if (count($errors) == 0 && $response != null && $response->success) {
  	$password = md5($password);
  	$query = "SELECT * FROM users WHERE uname='$username' AND passwd='$password'";
  	$statement = $db->prepare($query);
    $statement->execute();
   $results = $statement -> get_result();
  	if (mysqli_num_rows($results) == 1) {
	  $query = "SELECT * FROM users where uname='$username' AND permission=1";
	  $statement = $db->prepare($query);
    $statement->execute();
   $results = $statement -> get_result();
	$queryGetID = "SELECT users.uid FROM users where users.uname = '$username'";
	$res = mysqli_fetch_array($queryGetID);
	foreach($res as $row){
	$_SESSION['userID'] = $row['uid'];}
 
	if (mysqli_num_rows($results) == 1){
		$_SESSION['username'] = "Administrator";
		$_SESSION['success'] = "You are now logged in!";
		header('location: summary.php');
	  }
	  else{
				  $_SESSION['username'] = $username;
  	      $_SESSION['success'] = "You are now logged in!\nPlease wait for 2 seconds...";
	   
					header('location: index.php');
	  }  
	  
  	  
  	}else {
  		array_push($errors, "Wrong username/password combination");
  	}
  }
}
?>
