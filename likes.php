<?php
	session_start();

	//do not allow people to steal our post data if they are not logged in
	if($_SESSION["uname"] == ""){
		exit;
	}

	$servername = "localhost";
	$dbusername = "qwinter";
	$dbpassword = "EMGAYIIS";
	$dbname = "f18_qwinter";

	$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

	$updateQuery = $conn->prepare("UPDATE posts SET likes = likes + 1 WHERE username=?");
  $updateQuery->bind_param('s', $_SESSION["uname"]);
  $updateQuery->execute();
  $updateQuery->close();

  $conn->close();
?>
