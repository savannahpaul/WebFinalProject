<?php
$contentErr = "";
$content = "";

session_start();

//do not allow people to access home.php if they are not logged in
if($_SESSION["uname"] == ""){
	header("Location: login.php");
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    //Connect to server
    $servername = "localhost";
	$dbusername = "qwinter";
	$dbpassword = "EMGAYIIS";
	$dbname = "f18_qwinter";
    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

    //$content = $_POST["content"];
  }
?>

<!DOCTYPE html>
<html lang = "en-US">
  <head>
    <link rel="stylesheet" type="text/css" href="global.css">
    <link rel="stylesheet" type="text/css" href="home.css">
	<script src="home.js"></script>
    <meta charset = "UTF-8">
  </head>
  <body>
    <header>
      <h1>Social Network</h1>
    </header>
      <div id="nav">
        <ul>
          <li><a href="profile.php">My Page</a></li>
          <li><a href="settings.php">Settings</a></li>
		  <li><a href="logout.php">Logout</a></li>
        </ul>
      </div>
      <div id="postheader">
      <br>
      <div>
        <input class="draft" id="postcontent" type="text" name="content" value="Create a post...">
        <input type="submit" onClick="clic(this);" value="Submit">
      </div>
    </div>
	  <div id="postlocation"></div>
    </body>
    <footer>
      I am an empty footer
    </footer>
    </body>
</html>