<?php
	session_start();
	$_SESSION["uname"] = "";
	$_SESSION["postsDisplayed"] = 0;
	header("Location: login.php");
?>