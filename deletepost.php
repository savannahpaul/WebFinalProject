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

  $id = $_GET['id'];

	$updateQuery = $conn->prepare("DELETE FROM posts WHERE id=?");
  $updateQuery->bind_param('i', $id);
  $updateQuery->execute();
  $updateQuery->close();

  $conn->close();

?>
