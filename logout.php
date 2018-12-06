<?php
	session_start();
    setcookie("userCookie", $_SESSION["uname"], time() - 3600);
	$_SESSION["uname"] = "";
	$_SESSION["postsDisplayed"] = 0;
	header("Location: login.php");
?>