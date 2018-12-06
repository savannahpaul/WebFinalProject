<?php
	session_start();

	//do not allow people to steal our post data if they are not logged in
	if(isset($_SESSION["uname"]) && $_SESSION["uname"] == ""){
		exit;
	}

  $servername = "localhost";
  $dbusername = "qwinter";
  $dbpassword = "EMGAYIIS";
  $dbname = "f18_qwinter";

	$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
	if($conn ->connect_error){
		die("Cannot connect to database");
	}

	$id = $_GET['id'];

	$updateQuery = $conn->prepare("UPDATE posts SET likes = likes + 1 WHERE id=?");
  $updateQuery->bind_param('i', $id);
  $updateQuery->execute();
  $updateQuery->close();

  $conn->close();
?>
