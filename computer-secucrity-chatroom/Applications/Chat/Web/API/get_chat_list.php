<?php
require "../config.php";
$conn = new PDO($dsn_v2, $user, $password);
$sql="Select uid from registry.users where uid=:uid";
$stmt=$conn->prepare($sql);
$stmt->execute(array("uid"=>$_SESSION['userID']));
$result=$stmt->fetch(PDO::FETCH_ASSOC);
$user_id=$result['uid'];
echo json_encode($user_id);