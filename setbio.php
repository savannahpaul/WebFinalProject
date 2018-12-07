<?php
$email = $newPass = $oldPass = "";
$emailErr = $oldPassErr = $newPassErr = "";

session_start();

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

    if(empty($_POST["biotext"])) {
        //we don't care if they don't edit their bio
    }
    else {
        $bio = $_POST["biotext"];

	    $bioQuery = "UPDATE users SET bio=? WHERE username=?";
		$bstmt = $conn->prepare($bioQuery);
		$bstmt->bind_param("ss", $bio, $_SESSION["uname"]);
		$bstmt->execute();
		$bstmt->close();
    }
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

        <br><br>

        <div id="resetBox">
            <h2>Change your bio</h2>
            <div id="form" style="margin:0px;text-align:center;">
                <form method="post" id="bioform" name="bioform">
                    <label>Write a new bio here:</label><br>
					<textarea id="textareabio" cols="50" rows="20" name="biotext" form="bioform"></textarea>
                    <br><br>

                    <input type="submit" name="submit" value="Submit">
                </form>
            </div>
            <br>
            <a href="home.php">Home</a>
            <br>
        </div>


        <footer>
        </footer>

    </body>

</html>
