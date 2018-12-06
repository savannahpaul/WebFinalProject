<?php
	session_start();

	//do not allow people to steal our post data if they are not logged in
	if(isset($_SESSION["uname"]) && $_SESSION["uname"] == ""){
		exit;
	}
    if(isset($_COOKIE["userCookie"]) && ($_COOKIE["userCookie"] == $_SESSION["uname"]) && ($_SESSION["lastActive"] < $_SESSION["expire"])) {
        //Cookie is set, reset timer
        $_SESSION["lastActive"] = time();
        $_SESSION["expire"] = time() + (60 * 10);
        setcookie("userCookie", $_SESSION["uname"], $_SESSION["expire"], "/");
    }
    else {
        exit();
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
		echo "There are no posts to display.";
		exit;
	}
	$_SESSION["postsDisplayed"] = $postResult->num_rows;
	while($row = $postResult->fetch_assoc()){
		echo "<div class='post'><div class='postheader'><span class='poster'>" . $row["username"] . "</span> - " . date("m/d/Y H:i:s", $row["time"]) . " - Post #" . $row["id"] . " - Likes: " . $row["likes"] . "<button id='like' class='like' onClick='deletePost()' value='".$row["id"]."' style='float:right;'> [Delete] </button></div><div class='postcontent'>" . $row["content"] . "</div><div class='postlikes'><button class='like' onClick='updateLike()' value='" . $row["id"] . "'>&#128077; Like</button></div></div>";
	}
	$conn->close();
?>
