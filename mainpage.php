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
<html>
<head>
<title>TRAVEL PLANNER</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script>
$(document).on('click','.jump',function(data){
var id=$(this).attr('id');
var departure=$('#dep').val();

var destination=$('#des').val();
console.log(departure);
if (id=='traffic'){
window.location.href='chooseTraffic.php?destination='+destination+'&departure='+departure;
}
if (id=='food'){
window.location.href='chooseFood.php?destination='+destination+'&departure='+departure;
}
if (id=='activity'){
window.location.href='chooseActivity.php?destination='+destination+'&departure='+departure;
}
if (id=='hotel'){
window.location.href='chooseHotel.php?destination='+destination+'&departure='+departure;
}
	/**/
});	
</script>
</head>

<body style="background-color: #FDF2E9">
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

<a href="collection.php">
<img src="http://www.shejiye.com/uploadfile/icon/2017/0202/shejiyeiconjnwrgmasw3o.png" align="right" width="50" height="50"> 
</a>

<a href="message.php">
<img src="https://img.icons8.com/doodle/50/000000/alarm.png" align="right" width="50" height="50"> 
</a>

<a href="">
<img src="https://cdn.iconscout.com/icon/free/png-256/profile-277-453818.png" align="right" width="50" height="50"> 
</a>

<br>
<h1 align="center"; style="font-family: verdana">Let's start your journey!</h1>
<br><br>
<hr/>

<div id="Departure" style="text-align: center;">
<b style="text-align: center;">Departure</b>
<select id="dep" style="text-align:center; width: 250px; height: 50px">
  <option value="Hong Kong">Hong Kong</option>
  <option value="Macau">Macau</option>
  <option value="Zhuhai">Zhuhai</option>
  <option value="Shenzhen">Shenzhen</option>
</select></div>
<br>
<div id="Destination" style="text-align: center;">
<b style="text-align: center;">Destination</b>
<select id="des" style="text-align: center; width: 250px; height: 50px">
  <option value="Hong Kong">Hong Kong</option>
  <option value="Macau">Macau</option>
  <option value="Zhuhai">Zhuhai</option>
  <option value="Shenzhen">Shenzhen</option>
</select></div>
<br>
<hr/>
<div id="food" class="jump" style="float: left;">
<img src="https://www.ledscs.com/img/L2EhYJygLJqyYz15pzIwnKOypl5wo20ip2y0MKZiMTIzLKIfqP9znJkypl9mqUyfMKZioJIxnKIgKmW4Y3O1LzkcLl8kAGNlBGt3AGD3Y0qyqUE5FJ1uM2ImYGH0AGV4AwZ4BP5dpTp%2FnKEin1k1ZQNmMSu6FHudJGuW/what-you-need-to-start-cooking-more-chinese-food-at-home-myrecipes.jpg" title="Food&Drink" alt="Food&Drink" width="600" height="400"> 
<a href="#"><font size="100px">Food & Drink</font></a>
</div>
<br>

<div id="hotel" class="jump" style="float: right;">
<a href="#"><font size="100px">Hotel</font></a>
<img src="https://pix10.agoda.net/hotelImages/10584/-1/1abdd52ca38253ec7eaac80ccc70e821.jpg" title="Hotel" alt="Hotel" width="600" height="400"> 
</div>
<br>

<div id="activity" class="jump" style="float: left;">
<img src="http://cms.exmoo.com/uploads/nXJs0lGIYIKZAYfLghsB.jpg" align="middle" title="Scenic" alt="Scenic" width="600" height="400"> 
<a href="#"><font size="100px">Activity</font></a>
</div>
<br>

<div id="traffic" class="jump" style="float: right;">
<a href="#"><font size="100px">Traffic</font></a>
<img src="https://4.bp.blogspot.com/-WNldFF_TFtM/WX1pH5YEYoI/AAAAAAAAJhk/ZBtJrEF8RL491KOAFnYzLE20dSTh5kV9wCLcBGAs/s1600/DSC05020.jpg" title="Trafic" alt="Trafic" width="600" height="400">
</div>
<?php endif ?>
</body>
</html>