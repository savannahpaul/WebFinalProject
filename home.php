<?php
$contentErr = "";

session_start();

//do not allow people to access home.php if they are not logged in
if(isset($_SESSION["uname"]) && $_SESSION["uname"] == ""){
	header("Location: login.php");
}

if(isset($_COOKIE["userCookie"]) && ($_COOKIE["userCookie"] == $_SESSION["uname"]) && ($_SESSION["lastActive"] < $_SESSION["expire"])) {
    //Cookie is set, reset timer
    $_SESSION["lastActive"] = time();
    $_SESSION["expire"] = time() + (60* 10);
    setcookie("userCookie", $_SESSION["uname"], $_SESSION["expire"], "/");
}
else {
    session_destroy();
    header("Location: logout.php");
}


if($_SERVER["REQUEST_METHOD"] == "POST") {
    //Connect to server
		$servername = "localhost";
	  $dbusername = "qwinter";
	  $dbpassword = "EMGAYIIS";
	  $dbname = "f18_qwinter";

    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
    if($conn ->connect_error){
		die("Cannot connect to database");
	}

    //Add a post to the database everytime the user clicks submit
    $username = $_SESSION["uname"];
    $content = $_POST["content"];
    $time = time();
    $zerovar = 0;

    //Check if account has been activated, and don't allow
    // user to post if it has not been activated
    $astmt = $conn->prepare("SELECT activated FROM users WHERE username = ?");
    $astmt->bind_param("s", $username);
    $astmt->execute();
    $astmt->bind_result($act);
    $astmt->fetch();
    $astmt->close();

    //Create post
    if($act >= 1) {
        $stmt = $conn->prepare("INSERT INTO posts (username, content, likes, time) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssii", $username, $content, $zerovar, $time);

        $stmt->execute();
        $stmt->close();
        $conn->close();
    }
    else {
        echo "<script type='text/javascript'>alert('Account must be activated in order to post');</script>";
    }


  }
?>

<!DOCTYPE html>
<html lang = "en-US">
  <head>
    <link rel="stylesheet" type="text/css" href="global.css">
    <link rel="stylesheet" type="text/css" href="home.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="home.js"></script>
    <meta charset = "UTF-8">
  </head>

  <body>
    <header id="hdr">
      <h1>Social Network</h1>
    </header>
      <div id="nav">
        <ul>
          <li><a href="profile.php">Search Users</a></li>
          <li><a href="activate.php">Activate</a></li>
          <li><a href="settings.php">Change password</a></li>
          <li><a href="setbio.php">Change your bio</a></li>
		  <li><a href="logout.php">Logout</a></li>
        </ul>
      </div>
      <div id="postcreate">
      <br>
      <form method="POST">
        <input class="draft" id="postcontent" type="text" name="content" value="Create a post...">
        <input type="submit" value="Submit">
      </form>
    </div>
	<div id="postcheck"></div>
	<div id="postlocation"></div>


    <footer>

    </footer>
    </body>
</html>
