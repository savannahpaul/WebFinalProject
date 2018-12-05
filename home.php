<?php
$contentErr = "";

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

		//Add a post to the database everytime the user clicks submit
		$username = $_SESSION["uname"];
		$content = $_POST["content"];
		$time = date("h:i:s");

		$stmt = $conn->prepare("INSERT INTO posts (username, content, likes, time) VALUES (?, ?, ?, ?)");
		$stmt->bind_param("ssii", $username, $content, 0, $time);

		$stmt->execute();
		$stmt->close();
		$conn->close();
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
      <form method="POST">
        <input class="draft" id="postcontent" type="text" name="content" value="Create a post...">
        <input type="submit" value="Submit">
      </form>
    </div>
	  <div id="postlocation"></div>
    <footer>
      I am an empty footer
    </footer>
    </body>
</html>
