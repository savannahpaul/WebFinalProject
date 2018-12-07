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
