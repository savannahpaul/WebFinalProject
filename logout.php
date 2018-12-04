<?php
	session_start();
	$_SESSION["uname"] = "";
	header("Location: login.php");
?>