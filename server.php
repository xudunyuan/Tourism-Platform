<?php
session_start();

// initializing variables
$username = "";
$email    = "";
$tel   	  = "";
$birthday	  = "";
$errors = array(); 

// connect to the database
$db = mysqli_connect('mysql.comp.polyu.edu.hk', '17083297d', 'imxwdidj', '17083297d');
$_SESSION['db'] = $db;

// REGISTER USER
if (isset($_POST['reg_user'])) {
  // receive all input values from the form
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $tel = mysqli_real_escape_string($db, $_POST['tel']);
  $birthday = mysqli_real_escape_string($db, $_POST['birthday']);
  $birthday = date('Y-m-d', strtotime(str_replace('-', '/', $birthday)));
  $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
  $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

  // form validation: ensure that the form is correctly filled ...
  // by adding (array_push()) corresponding error unto $errors array
  if (empty($username)) { array_push($errors, "Username is required"); }
  if (empty($email)) { array_push($errors, "Email is required"); }
  if (empty($birthday)) { array_push($errors, "Birthday is required"); }
  if (empty($tel)) { array_push($errors, "telephone number is required"); }
  if (empty($password_1)) { array_push($errors, "Password is required"); }
  if ($password_1 != $password_2) {
	array_push($errors, "The two passwords do not match");
  }

  // first check the database to make sure 
  // a user does not already exist with the same username and/or email
  $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' OR tel='$tel' LIMIT 1";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) { // if user exists
    if ($user['username'] === $username) {
      array_push($errors, "Username already exists");
    }
    if ($user['email'] === $email) {
      array_push($errors, "email already exists");
    }
	if ($user['tel'] === $tel) {
      array_push($errors, "tel already exists");
    }	
  }
  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
  	$password = md5($password_1);//encrypt the password before saving in the database

  	$query = "INSERT INTO users (username, email, tel, birthday, password) 
  			  VALUES('$username', '$email', '$tel', '$birthday', '$password')";
  	mysqli_query($db, $query);
	$time=time();
	$query = "INSERT INTO history(userid,datetime)
SELECT users.id, '$time' FROM users where users.username = '$username'";
	mysqli_query($db, $query);
  	$_SESSION['username'] = $username;
  	$_SESSION['success'] = "You are now logged in";
  	
  	
	$queryGetID = "SELECT users.id FROM users where users.username = '$username'";
	$res = mysqli_fetch_array($queryGetID);
	foreach($res as $row){
	$_SESSION['userID'] = $row['id'];}

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

  if (count($errors) == 0) {
  	$password = md5($password);
  	$query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
  	$results = mysqli_query($db, $query);
  	if (mysqli_num_rows($results) == 1) {
	  $stringtime1 = date('Y-m-d H:i:s');
	  $stringtime2 = date('m-d');
	  $query = "INSERT INTO history(userid,datetime)
SELECT users.id, '$stringtime1' FROM users where users.username = '$username'";
	  mysqli_query($db, $query);
	  $query = "SELECT * FROM users where username='$username' AND permission=1";
	  $results = mysqli_query($db, $query);
	$queryGetID = "SELECT users.id FROM users where users.username = '$username'";
	$res = mysqli_fetch_array($queryGetID);
	foreach($res as $row){
	$_SESSION['userID'] = $row['id'];}
 
	if (mysqli_num_rows($results) == 1){
		$_SESSION['username'] = "Administrator";
		$_SESSION['success'] = "You are now logged in!";
		header('location: summary.php');
	  }
	  else{
		$query = "SELECT * FROM users where username='$username' AND birthday LIKE '%$stringtime2'";
	    $results= mysqli_query($db, $query);	  
	    if (mysqli_num_rows($results) == 1){
		  $_SESSION['username'] = $username;
		  $_SESSION['success'] = "HAPPY BIRTHDAY!\nPlease wait for 2 seconds...";
	    }
	    else{
		  $_SESSION['username'] = $username;
  	      $_SESSION['success'] = "You are now logged in!\nPlease wait for 2 seconds...";
	    }
		header('location: index.php');
	  }  
	  
  	  
  	}else {
  		array_push($errors, "Wrong username/password combination");
  	}
  }
}
?>
