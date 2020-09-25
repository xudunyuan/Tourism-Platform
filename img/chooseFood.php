<?php 
session_start();
if(isset($_POST['search'])){
$want = $_POST['search']; }
$db = mysqli_connect('mysql.comp.polyu.edu.hk', '17083297d','imxwdidj','17083297d');
if(!$db){
    echo "<br>Error: Unable to connect to MySQL." . PHP_EOL;
    echo "<br>Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "<br>Debugging error: " . mysqli_connect_error() . PHP_EOL;
}
else{
}
$destination=$_GET['destination'];
$departure=$_GET['departure'];

$query = "SELECT hotelID,Name,Type,Price,Contact,Address FROM hotel WHERE Name LIKE '%$want%' ORDER BY Price; ";
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
	$.post('addDataToBooking.php?type=food&id='+$(this).attr('data-id'),function(data){
		alert('Added');
	});
});
</script>
<body>
<!--<script type="text/javascript" src="hotel.php?action=text"></script>-->
<form method="post" action='"chooseHotel.php?destination="+$destination+"&departure="+$departure'>
            <input type="text" name="search" placeholder="input something here...">
            <input type="submit" value="GO">
</form>
</body>
</html>