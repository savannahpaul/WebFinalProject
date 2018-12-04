<?php
	//*****************************************************************************
	//EDIT THIS WHEN GRADING TO PROPERLY ACCESS YOUR DATABASE
	//*****************************************************************************
	$servername = "localhost";//127.0.0.1:3306";
	$username = "qwinter";
	$password = "EMGAYIIS";
	$dbname = "f18_qwinter";
	//*****************************************************************************
	//EDIT THIS WHEN GRADING TO PROPERLY ACCESS YOUR DATABASE
	//*****************************************************************************
	$conn = new mysqli($servername, $username, $password, $dbname);
	if($conn ->connect_error){
		die("Cannot connect to database");
	}
	
	$postQuery = "SELECT * FROM posts ORDER BY id DESC";
	$postResult = $conn->query($postQuery);
	if($postResult->num_rows == 0){
		echo "There are no posts to display.";
		exit();
	}
	while($row = $postResult->fetch_assoc()){
		echo "<div class='post'><div class='postheader'><span class='poster'>" . $row["username"] . "</span> - " . date("d/m/Y H:i:s", $row["time"]) . " - Post #" . $row["id"] . " - Likes: " . $row["likes"] . "</div><div class='postcontent'>" . $row["content"] . "</div><div class='postlikes'><button class='likebutton' value='" . $row["id"] . "'>&#128077; Like</button></div></div>";
	}
	$conn->close();
?>