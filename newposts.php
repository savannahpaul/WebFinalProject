<?php
	session_start();

	//do not allow people to steal our post data if they are not logged in
	if($_SESSION["uname"] == ""){
		exit;
	}

	//*****************************************************************************
	//EDIT THIS WHEN GRADING TO PROPERLY ACCESS YOUR DATABASE
	//*****************************************************************************
	$servername = "localhost";
	$dbusername = "qwinter";
	$dbpassword = "EMGAYIIS";
	$dbname = "f18_qwinter";
	//*****************************************************************************
	//EDIT THIS WHEN GRADING TO PROPERLY ACCESS YOUR DATABASE
	//*****************************************************************************
	$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
	if($conn ->connect_error){
		die("Cannot connect to database");
	}

	$postQuery = "SELECT * FROM posts ORDER BY id DESC";
	$postResult = $conn->query($postQuery);
	if($postResult->num_rows == 0){
		echo 0;
		exit;
	}
	echo $postResult->num_rows - $_SESSION["postsDisplayed"];
	$conn->close();
?>