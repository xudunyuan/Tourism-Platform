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

<!DOCTYPE html>
<html lang = "en">
<head>
	<title>My favorite</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>
<?php if (isset($_SESSION['success'])) : ?>
      <div class="error success" >
      	<h3>
          <?php 
          	echo $_SESSION['success']; 
          	unset($_SESSION['success']);
          ?>
      	</h3>
      </div>
  	<?php endif ?>
	<?php  if (isset($_SESSION['username'])&&$_SESSION['username']!=="Administrator") : ?>



<div class="w3-container">
  <h1>Welcome! <?php echo $_SESSION['username']?></h1>
</div>
	
	<div class="w3-container">
  <img src="tomold.jpg" class="w3-round-xxlarge" alt="Back" style="width:100%">
</div>		
			
			
		
		
			
	<div class="w3-container">
		         
		<table class="w3-table-all w3-xxlarge">
		
		
		<tr>
						  <th>Finished Plan</th>
						  </tr>
			<?php
				$username = $_SESSION['username'] ;
				$sqlID = mysqli_query($_SESSION['db'], "select FoodID, TrafficID, HotelID, ActivityID from Booking where BookingID in (select BookingID from collection where status = 'fin' and username = '$username' ");
				
				
				if($sqlID){
				$datarow = mysqli_num_rows($sqlID);
				
					for($i = 0; $i < $datarow; $i++) {
						$sql_arr = mysqli_fetch_assoc($sqlID);
						$foodID = $sql_arr['FoodID'];
						$TrafficID = $sql_arr['TrafficID'];
						$HotelID = $sql_arr['HotelID'];
						$activityID = $sql_arr['ActivityID'];
						$sqlFood = mysqli_query($_SESSION['db'], "select name, city, minprice, maxprice, address from food where FoodID = '$FoodID'");
						$sqlTraffic = mysqli_query($_SESSION['db'], "select StartCity, EndCity, Transportation, Time, Price from traffic where TrafficID = '$TrafficID'");
						$sqlHotel = mysqli_query($_SESSION['db'], "select Name, City, Price, Type, Address from hotel where HotelID = '$HotelID'");
						$sqlActivity = mysqli_query($_SESSION['db'], "select TYPE, city, price, starttime, endtime, address from activity where ActivityID = '$ActivityID'");
						
						$sql_arr2 = mysqli_fetch_assoc($sqlFood);
						$foodname = $sql_arr2['name'];
						$foodcity = $sql_arr2['city'];
						$foodminprice = $sql_arr2['minprice'];
						$foodmaxprice = $sql_arr2['maxprice'];
						$foodaddress = $sql_arr2['address'];
						
						$sql_arr3 = mysqli_fetch_assoc($sqlTraffic);
						$trafficstart = $sql_arr3['StartCity'];
						$trafficend = $sql_arr3['EndCity'];
						$transportation = $sql_arr3['Transportation'];
						$traffictime = $sql_arr3['Time'];
						$trafficprice = $sql_arr3['Price'];
						
						$sql_arr4 = mysqli_fetch_assoc($sqlHotel);
						$hotelname = $sql_arr4['Name'];
						$hotelcity = $sql_arr4['City'];
						$hotelprice = $sql_arr4['Price'];
						$hoteladdress = $sql_arr4['Address'];
						$hoteltype = $sql_arr4['Type'];
						
						$sql_arr5 = mysqli_fetch_assoc($sqlActivity);
						$activitytype = $sql_arr5['TYPE'];
						$activitycity = $sql_arr5['city'];
						$activityprice = $sql_arr5['price'];
						$activitystart = $sql_arr5['starttime'];
						$activityend = $sql_arr5['endtime'];
						$activityaddress = $sql_arr5['address'];
						
						echo
						  "
						  <tr>
						  <td>Plan $datarow</td>
						  </tr>
						  <tr>
							<td>FoodName</td>
							<td>FoodCity</td>
							<td>FoodminPrice</td>
							<td>FoodmaxPrice</td>
							<td>FoodAddress</td>
						  </tr>						  
						  <tr>
							<td>$foodname</td>
							<td>$foodcity</td>
							<td>$foodminprice</td>
							<td>$foodmaxprice</td>
							<td>$foodaddress</td>
						  </tr>
						  <tr>
							<td>TrafficStart</td>
							<td>TrafficEnd</td>
							<td>Transportation</td>
							<td>TrafficTime</td>
							<td>TrafficPrice</td>
						  </tr>							  
						  <tr>
							<td>$trafficstart</td>
							<td>$trafficend</td>
							<td>$transportation</td>
							<td>$traffictime</td>
							<td>$trafficprice</td>
						  </tr>
						  <tr>
							<td>HotelName</td>
							<td>HotelCity</td>
							<td>HotelPrice</td>
							<td>HotelAddress</td>
							<td>HotelType</td>
						  </tr>							  
						  <tr>
							<td>$hotelname</td>
							<td>$hotelcity</td>
							<td>$hotelprice</td>
							<td>$hoteladdress</td>
							<td>$hoteltype</td>
						  </tr>";
						
					}
				}
				
			?>
			
		</table>
		
		
		
		
		
		
		<table class="w3-table-all w3-xxlarge">
			<?php echo "<br/>";
				echo "<br/>"; 
				echo "<br/>";
				echo "<br/>";  
			?>
			<tr>
			</tr>
			<tr>
				<th>Unfinished Plan</th>
			</tr>
			
			
	
			<?php
				$username = $_SESSION['username'] ;
				$sqlID = mysqli_query($_SESSION['db'], "select FoodID, TrafficID, Hotel, ActivityID from Booking where BookingID in (select BookingID from collection where status = 'cart' and username = '$username' )");
				
				 
				$datarow = mysqli_num_rows($sqlID);
				
					for($i = 0; $i < $datarow; $i++) {
						$sql_arr = mysqli_fetch_assoc($sqlID);
						$foodID = $sql_arr['FoodID'];
						$TrafficID = $sql_arr['TrafficID'];
						$HotelID = $sql_arr['Hotel'];
						$activityID = $sql_arr['ActivityID'];
						$sqlFood = mysqli_query($_SESSION['db'], "select name, city, minprice, maxprice, address from food where foodID = '$foodID'");
						$sqlTraffic = mysqli_query($_SESSION['db'], "select StartCity, EndCity, Transportation, Time, Price from traffic where TrafficID = '$TrafficID'");
						$sqlHotel = mysqli_query($_SESSION['db'], "select Name, City, Price, Type, Address from hotel where HotelID = '$HotelID'");
						$sqlActivity = mysqli_query($_SESSION['db'], "select TYPE, city, price, starttime, endtime, address from activity where activityID = '$activityID'");
						
						$sql_arr2 = mysqli_fetch_assoc($sqlFood);
						$foodname = $sql_arr2['name'];
						$foodcity = $sql_arr2['city'];
						$foodminprice = $sql_arr2['minprice'];
						$foodmaxprice = $sql_arr2['maxprice'];
						$foodaddress = $sql_arr2['address'];
						
						$sql_arr3 = mysqli_fetch_assoc($sqlTraffic);
						$trafficstart = $sql_arr3['StartCity'];
						$trafficend = $sql_arr3['EndCity'];
						$transportation = $sql_arr3['Transportation'];
						$traffictime = $sql_arr3['Time'];
						$trafficprice = $sql_arr3['Price'];
						
						$sql_arr4 = mysqli_fetch_assoc($sqlHotel);
						$hotelname = $sql_arr4['Name'];
						$hotelcity = $sql_arr4['City'];
						$hotelprice = $sql_arr4['Price'];
						$hoteladdress = $sql_arr4['Address'];
						$hoteltype = $sql_arr4['Type'];
						
						$sql_arr5 = mysqli_fetch_assoc($sqlActivity);
						$activitytype = $sql_arr5['TYPE'];
						$activitycity = $sql_arr5['city'];
						$activityprice = $sql_arr5['price'];
						$activitystart = $sql_arr5['starttime'];
						$activityend = $sql_arr5['endtime'];
						$activityaddress = $sql_arr5['address'];
						
						echo
						  "<tr>
							<td>FoodName</td>
							<td>FoodCity</td>
							<td>FoodminPrice</td>
							<td>FoodmaxPrice</td>
							<td>FoodAddress</td>
						  </tr>						  
						  <tr>
							<td>$foodname</td>
							<td>$foodcity</td>
							<td>$foodminprice</td>
							<td>$foodmaxprice</td>
							<td>$foodaddress</td>
						  </tr>
						  <tr>
							<td>TrafficStart</td>
							<td>TrafficEnd</td>
							<td>Transportation</td>
							<td>TrafficTime</td>
							<td>TrafficPrice</td>
						  </tr>						  
						  <tr>
							<td>$trafficstart</td>
							<td>$trafficend</td>
							<td>$transportation</td>
							<td>$traffictime</td>
							<td>$trafficprice</td>
						  </tr>
						  <tr>
							<td>HotelName</td>
							<td>HotelCity</td>
							<td>HotelPrice</td>
							<td>HotelAddress</td>
							<td>HotelType</td>
						  </tr>						  
						  <tr>
							<td>$hotelname</td>
							<td>$hotelcity</td>
							<td>$hotelprice</td>
							<td>$hoteladdress</td>
							<td>$hoteltype</td>
						  </tr>";
						
					}
				
			?>
		</table>
		
	</div>
	
	<div class="w3-bar">
		<button class="w3-button w3-right w3-green">Confirm </button>
	</div>
			
				
			

<?php endif ?>

</body>


</html>
	