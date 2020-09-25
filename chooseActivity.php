<?php 
session_start();
$db = mysqli_connect('mysql.comp.polyu.edu.hk', '17083297d','imxwdidj','17083297d');
if(!$db){
    echo "<br>Error: Unable to connect to MySQL." . PHP_EOL;
    echo "<br>Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "<br>Debugging error: " . mysqli_connect_error() . PHP_EOL;
}
else{
}

if(isset($_POST['destination'])){
$destination = $_POST['destination']; }
else{
$destination=$_GET['destination'];}

if(isset($_POST['departure'])){
$departure = $_POST['departure']; }
else{
$departure=$_GET['departure'];}

if(isset($_POST['search'])){
$want = $_POST['search']; 
$query = "SELECT activityID,city,TYPE,starttime,endtime,address,price FROM activity WHERE city = '$destination' AND Name LIKE '%$want%' ORDER BY starttime; ";
}
else{
$query = "SELECT activityID,city,TYPE,starttime,endtime,address,price FROM activity WHERE city = '$destination' ORDER BY starttime; ";
}

//$_SESSION['hotel'] = $row
//header('location: kk.php');
//echo $username;
?>



<!DOCTYPE html>
<html>
<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<script>
$(document).on('click','.test',function(data){
	$.post('addDataToBooking.php?type=activity&id='+$(this).attr('data-id'),function(data){
		alert('Added');
	});
});	
</script>
<body>

<div class="container">
  <h2>Choose Activity</h2>
  <p>You can choose activities you want to book by clicking the button at the beginning of each lines.</p>            
  <table class="table">
    <tbody>
    
      <?php if ($stmt = mysqli_prepare($db, $query)) {

        /* execute statement */
        mysqli_stmt_execute($stmt);

        /* bind result variables */
        mysqli_stmt_bind_result($stmt, $activityID, $city, $TYPE, $starttime, $endtime, $address, $price);

        /* fetch values */
        $n = 0;

        echo "<tr><th>&#9</th><th>activityID&#9</th><th>city&#9</th><th>TYPE&#9</th><th>starttime&#9</th><th>endtime&#9</th><th>address</th><th>price</th></tr></input>";
        while (mysqli_stmt_fetch($stmt)) {
            $n++;
            echo  "<tr>
            <th><input type=\"radio\" class=\"test\" data-id=\"".$activityID."\" name=\"test\"></th>
            <th>$activityID&#9</th><th>$city&#9</th><th>$TYPE&#9</th><th>$starttime&#9</th><th>$endtime&#9</th><th>$address</th><th>$price</th></tr></input>";
        }
 
        /* close statement */
        mysqli_stmt_close($stmt);
    } 
    else {
        echo "<br>Error: " . mysqli_error($db);
    }
    ?>
    </tbody>
  </table>
</div>

<!--<script type="text/javascript" src="hotel.php?action=text"></script>-->
<form method="post" action="chooseActivity.php">
            <input type="text" name="search" placeholder="input something here...">
            <input type="hidden" name = "destination" value ="<?php echo $destination;?>">
            <input type="hidden" name = "departure" value ="<?php echo $departure;?>">
            <input type="submit" value="GO">
</form>
<a href = 'redirection.html'> confirm</a>
</body>
</html>