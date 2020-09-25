<?php include('server.php') ?>
<?php 
  if (!isset($_SESSION['username'])) {
  	$_SESSION['msg'] = "You must log in first";
  	header('location: login.php');
  }
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['username']);
  	header("location: login.php");
  }
?>

<?php
if(isset($_SESSION['username'])){
	$sql=mysqli_query($_SESSION['db'],'select BookingID from collection where username = "'.$_SESSION['username'].'" and status="cart"');
}else{
	$sql=mysqli_query($_SESSION['db'],'select BookingID from collection where status="cart"');
}


$temp=mysqli_fetch_array($sql)['BookingID'];
echo $temp;
$bookingid="";
if ($temp!=""){
	$bookingid=$temp;
}else{
	$sql=mysqli_query($_SESSION['db'],'insert into collection (username,status,BookingID) values ("'.$_SESSION['username'].'","cart","'.$bookingid.'")');
	$bookingid=mysqli_insert_id($_SESSION['db']);
	$sql=mysqli_query($_SESSION['db'],'insert into Booking (BookingID) value ("'.$bookingid.'")');
}

$id=$_GET['id'];
if ($_GET['type']=='hotel'){
	$sql=mysqli_query($_SESSION['db'],'update Booking set Hotel="'.$id.'" where BookingID="'.$bookingid.'"');
}else if ($_GET['type']=='food'){
	$sql=mysqli_query($_SESSION['db'],'update Booking set FoodID="'.$id.'" where BookingID="'.$bookingid.'"');
}else if ($_GET['type']=='activity'){
	$sql=mysqli_query($_SESSION['db'],'update Booking set ActivityID="'.$id.'" where BookingID="'.$bookingid.'"');
}else if ($_GET['type']=='traffic'){
	$sql=mysqli_query($_SESSION['db'],'update Booking set TrafficID="'.$id.'" where BookingID="'.$bookingid.'"');
}else if ($_GET['type']=='change'){
	$sql=mysqli_query($_SESSION['db'],'update collection set status="fin" where BookingID="'.$bookingid.'"');
}

?>