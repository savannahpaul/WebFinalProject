<?php
$contentErr = "";
$content = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {
    //Connect to server
    $servername = "localhost";
    $dbusername = "qwinter";
    $dbpassword = "EMGAYIIS";
    $dbname = "f18_qwinter";
    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

    $content = $_POST["content"];
  }
?>

<script>
  function clic(element){
    var x = document.getElementById("postcontent");
    var y = document.getElementById("postlocation");
    var append = "<div class='post'><div class='postheader'><span class='poster'>Username</span> - 11/28/18 - Post #1 - Likes: 0</div><div class='postcontent'>" + x.value +"</div><div class='postlikes'><button class='likebutton' value='1'>&#128077; Like</button></div></div>";
    y.innerHTML += append;
  }
</script>

<!DOCTYPE html>
<html lang = "en-US">
  <head>
    <link rel="stylesheet" type="text/css" href="global.css">
    <link rel="stylesheet" type="text/css" href="home.css">
    <meta charset = "UTF-8">
  </head>
  <body>
    <header>
      <h1>Social Network</h1>
    </header>
      <div id="nav">
        <ul>
          <li><a href="">My Page</a></li>
          <li><a href="">My Feed</a></li>
          <li><a href="">Settings</a></li>
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
