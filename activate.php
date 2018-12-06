<?php
$code = $active = "";
$codeErr = "";

session_start();
if(isset($_SESSION["uname"]) && $_SESSION["uname"] == ""){
	header("Location: login.php");
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

    //Get username
    $username = $_SESSION["uname"];

    //Check is account is active
    $astmt = $conn->prepare("SELECT activated FROM users WHERE username = ?");
    $astmt->bind_param("s", $username);
    $astmt->execute();
    $astmt->bind_result($act);
    $astmt->fetch();
    $astmt->close();

    if($act >= 1) {
        $active = "Account has already been activated.";
    }
    else {
        //Check code
        if(empty($_POST["code"])) {
            $codeErr = "Activation code required";
        }
        else {
            $code = $_POST["code"];
        }
        //Activate account
        if($codeErr == "") {
            echo $username . " " . $code;
            $checkQuery = "SELECT COUNT(*) FROM users WHERE username = ? AND activecode = ?";
            $cstmt = $conn->prepare($checkQuery);
            $cstmt->bind_param("ss", $username, $code);
            $cstmt->execute();
            $cstmt->bind_result($correctCode);
            $cstmt->fetch();
            $cstmt->close();

            if($correctCode < 1) {
                $codeErr = "Invalid activation code";
            }
            else {
                $activeQuery = "UPDATE users SET activated = 1 WHERE username = ? AND activecode = ?";
                $astmt = $conn->prepare($activeQuery);
                $astmt->bind_param("ss", $username, $code);
                $astmt->execute();
                $astmt->fetch();
                $astmt->close();

                $active = "Your account has been activated";
            }
        }
    }
}

?>

<!DOCTYPE html>
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

    <div id="loginbox">
    <h2>Activate your Account</h2>
        <div id="form">
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <label>Activation Code:</label> <input type="text" name="code" value="<?php echo $code;?>">

                <br>
                <span class="error"><?php echo $codeErr;?></span>
                <br>
                <span><?php echo $active;?></span>
                <br><br>

                <input type="submit" name="submit" value="Submit">
            </form>

        </div>
        <br><br>
        <a href="home.php"> Home </a>
        <br>
    </div>

    <footer>
    </footer>

  </body>
</html>
