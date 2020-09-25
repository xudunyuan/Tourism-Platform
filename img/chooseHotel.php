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
	$query = "SELECT hotelID,Name,Type,Price,Contact,Address FROM hotel WHERE Name LIKE '%$want%' AND EndCity = '$destination' ORDER BY Price; ";
}
else{
	$query = "SELECT hotelID,Name,Type,Price,Contact,Address FROM hotel WHERE EndCity = '$destination' ORDER BY Price; ";
}

//$results = mysqli_query($db, $query);
if ($stmt = mysqli_prepare($db, $query)) {

        /* execute statement */
        mysqli_stmt_execute($stmt);

        /* bind result variables */
        mysqli_stmt_bind_result($stmt, $hotelID, $Name, $Type, $Price, $Contact, $Address);

        /* fetch values */
        $n = 0;
        echo '<br/>';
        while (mysqli_stmt_fetch($stmt)) {
            $n++;
            echo  "<input type=\"radio\" class=\"test\" data-id=\"".$hotelID."\" name=\"test\"><pre style=\"display:inline;\"><tr><td>$hotelID&#9</td><td>$Name&#9</td><td>$Type&#9</td><td>$Address&#9</td><td>$Contact&#9</td><td>$Price</td></tr></pre></input><br/>";
        }
 
        /* close statement */
        mysqli_stmt_close($stmt);
    } 
    else {
        echo "<br>Error: " . mysqli_error($db);
    }
//$_SESSION['hotel'] = $row
//header('location: kk.php');
//echo $username;
?>



<!DOCTYPE html>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<script>
$(document).on('click','.test',function(data){
	$.post('addDataToBooking.php?type=hotel&id='+$(this).attr('data-id'),function(data){
		alert('Added');
	});
});
</script>
<body>
<!--<script type="text/javascript" src="hotel.php?action=text"></script>-->
<form method="post" action="chooseHotel.php">
            <input type="text" name="search" placeholder="input something here...">
            <input type="hidden" name = "destination" value = $destination>
            <input type="hidden" name = "departure" value = $departure>
            <input type="submit" value="GO">
</form>
</body>
</html>