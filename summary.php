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
<html>
<head>
    <title>Summary</title>
</head>
<body>
Show Summary Information
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
	<?php  if (isset($_SESSION['username'])&&$_SESSION['username']=="Administrator") : ?>
		<?php

		$con = mysqli_connect('mysql.comp.polyu.edu.hk','17081989d','hwznmgvv','17081989d');
		 
		echo "</br>Total users: </br>";
		$sql = mysqli_query($con, "SELECT count(id) AS users FROM users");
		$res = mysqli_fetch_array($sql);

		echo $res['users'];


			
		$stringtime1 = date('Y-m-d');

		echo "</br>Daily Active Users</br>";
		//total user number
		 $sql = mysqli_query($con,"select count(*) as cid, id, username from users, history where datetime like '$stringtime1%' and id = userid group by id");
		 $datarow = mysqli_num_rows($sql); 
			for($i=0;$i<$datarow;$i++){
				$sql_arr = mysqli_fetch_assoc($sql);
				$id = $sql_arr['id'];
				$name = $sql_arr['username'];
				$cid = $sql_arr['cid'];
				echo "<bd>$id $name $cid </bd></br>";

			}
			

		$stringtime2 = date('Y-m');	
		echo"</br>Monthly Active Users</br>";
		 $sql = mysqli_query($con,"select count(*) as cid, id, username from users, history where datetime like '$stringtime2%' and id = userid group by id");
		 $datarow = mysqli_num_rows($sql); 

			for($i=0;$i<$datarow;$i++){
				$sql_arr = mysqli_fetch_assoc($sql);
				$id = $sql_arr['id'];
				$name = $sql_arr['username'];
				$cid = $sql_arr['cid'];
				echo "<bd>$id $name $cid </bd></br>";

			}
			

			
		echo "</br>The Most Popular Hotel: </br>";
		$sql = mysqli_query($con, "SELECT hotelId, count(hotelId) AS hid 
		FROM booking
		GROUP BY hotelId
		ORDER BY hid DESC
		LIMIT 1 ");
		 $datarow = mysqli_num_rows($sql); 
			for($i=0;$i<$datarow;$i++){
				$sql_arr = mysqli_fetch_assoc($sql);
				$id = $sql_arr['hotelId'];
				$hid = $sql_arr['hid'];
				echo "<bd>$id $hid </bd></br>";

			}
			
		echo "</br>The Most Popular Food: </br>";
		$sql = mysqli_query($con, "SELECT foodId, count(foodId) AS fid 
		FROM booking
		GROUP BY foodId
		ORDER BY fid DESC
		LIMIT 1 ");
		 $datarow = mysqli_num_rows($sql); 
			for($i=0;$i<$datarow;$i++){
				$sql_arr = mysqli_fetch_assoc($sql);
				$id = $sql_arr['foodId'];
				$fid = $sql_arr['fid'];
				echo "<bd>$id $fid </bd></br>";

			}
			
		echo "</br>The Most Popular Activity: </br>";
		$sql = mysqli_query($con, "SELECT activityId, count(activityId) AS aid 
		FROM booking
		GROUP BY activityId
		ORDER BY aid DESC
		LIMIT 1 ");
		 $datarow = mysqli_num_rows($sql); 
			for($i=0;$i<$datarow;$i++){
				$sql_arr = mysqli_fetch_assoc($sql);
				$id = $sql_arr['activityId'];
				$aid = $sql_arr['aid'];
				echo "<bd>$id $aid </bd></br>";

			}

		?>

		<form method="get" action="UserManagement.php">
			<button type="submit">Continue</button>
		</form>
	<?php endif ?>
</body>
</html>