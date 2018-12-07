<?php
session_start();

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

$resultOfQuery = "";

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

	$name = $_POST["name"];
  //splits the name on spaces
  $names = explode(" ", $name);
  $username = "";
  $bio = "";

  $query = "SELECT username, bio FROM users WHERE fname=? AND lname=?";
  $result = $conn->prepare($query);
  $result->bind_param("ss", $names[0], $names[1]);
  $result->execute();
  $result->store_result();
  $result->bind_result($username, $bio);
  if($result->num_rows == 0){
		$resultOfQuery = "No users matched your search.";
		//echo "<script type='text/javascript'>alert('No users matched your search');</script>";
		//exit;
	}
	else{
		while($row = $result->fetch()){
			$resultOfQuery = $resultOfQuery . "<div class='post'><div class='postheader'><span class='poster'>" . $username . " - " . $names[0] . " " . $names[1] . "</span></div><div class='postcontent'>" . $bio . "</div>";
		}
	}

	$result->close();
	$conn->close();
	echo $resultOfQuery;
}

?>

<!DOCTYPE html>
<html lang = "en-US">
  <head>
    <link rel="stylesheet" type="text/css" href="global.css">
    <link rel="stylesheet" type="text/css" href="home.css">
    <meta charset = "UTF-8">
  </head>

  <body>
    <header id="hdr">
      <h1>Social Network</h1>
    </header>
      <div id="nav">
        <ul>
          <li><a href="home.php">Home</a></li>
          <li><a href="activate.php">Activate</a></li>
          <li><a href="settings.php">Change password</a></li>
          <li><a href="setbio.php">Change your bio</a></li>
		  <li><a href="logout.php">Logout</a></li>
        </ul>
      </div>
      <div id="profileDiv">
        <br>
      
      <div style="text-align:center;" id="search">
      <form method="POST">
        <input name="name" style="width:80%" type="text" placeholder="Search users...">
        <input type="submit" value="Submit">
      </form>
      </div>
    </div>

    <footer>

    </footer>
    </body>
</html>
