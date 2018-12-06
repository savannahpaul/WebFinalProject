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
          <li><a href="settings.php">Settings</a></li>
		  <li><a href="logout.php">Logout</a></li>
        </ul>
      </div>
      <div id="profileDiv">
        <br>
      </div>

    <footer>

    </footer>
    </body>
</html>
