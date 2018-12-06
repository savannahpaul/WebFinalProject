<?php
session_start();

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

  $query = "SELECT * FROM users WHERE fname='$names[0]' AND lname='$names[1]'";
  $result = $conn->query($query);

  if($result == false){
    echo "no users matched your search";
    //echo "<script type='text/javascript'>alert('No users matched your search');</script>";
    exit;
  }

  while($row = $result->fetch_assoc()){
    echo   "<div class='post'><div class='postheader'><span class='poster'>" . $row["username"] . "</span></div><div class='postcontent'>" . $rows["bio"] . "</div>";
  }
  $conn->close();
}

?>

<html lang = "en-US">
    <head>
        <meta charset = "UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="global.css">
    </head>

    <body>
        <header>
            <h1> Social Network </h1>
        </header>

        <br><br><br><br><br><br>

        <div style="text-align:center;" id="search">
        <form method="POST">
          <input name="name" style="width:80%" type="text" placeholder="Search users...">
          <input type="submit" value="Submit">
        </form>
        </div>


        <footer>
        </footer>

    </body>

</html>
