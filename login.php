<?php
$userErr = $passErr = $loginErr = "";
$uname = $pass = "";

session_start();

//go back to home if you try to go to login page when youre already logged in
//if($_SESSION["uname"] != ""){
//	header("Location: home.php");
//}
if(isset($_COOKIE["userCookie"]) && $_COOKIE["userCookie"] == $_SESSION["uname"] && ($_SESSION["lastActive"] < $_SESSION["expire"])) {
    //Cookie is set, reset timer
    $_SESSION["lastActive"] = time();
    setcookie("userCookie", $_SESSION["uname"], $_SESSION["expire"], "/");
    header("Location: home.php");
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

    //Check for username
    if(empty($_POST["uname"])) {
        $userErr = "Username is required";
    }
    else {
        $uname = $_POST["uname"];
    }
    //Check for password
    if(empty($_POST["password"])) {
        $passErr = "Password is required";
    }
    else {
        $pass = $_POST["password"];
    }

    //Check is user exists
    if($userErr == "" && $passErr == "") {
        $loginQuery = "SELECT COUNT(*) FROM users WHERE username = ? AND password = ?";
        $lstmt = $conn->prepare($loginQuery);
        $lstmt->bind_param("ss", $uname, $pass);
        $lstmt->execute();
        $lstmt->bind_result($isUser);
        $lstmt->fetch();
        $lstmt->close();

        if($isUser <= 0) {
            $loginErr = "Invalid username or password.";
        }
        else {
            //include 'home.html';
			$_SESSION["uname"] = $_POST["uname"];
            $_SESSION["expire"] = time() + (60 * 10);
            setcookie("userCookie", $_SESSION["uname"], $_SESSION["expire"], "/");
            $_SESSION["lastActive"] = time();
			//echo $_SESSION["uname"];
			header("Location: home.php");
            exit;
        }
    }
}

?>

<!DOCTYPE html>
<html lang = "en-US">
  <head>
    <link rel="stylesheet" type="text/css" href="global.css">
    <meta charset = "UTF-8">
  </head>
  <body>
    <header>
      <h1> Social Network </h1>
    </header>

	<br><br>

    <div id="loginbox">
    <h2>Login</h2>
    <div id="form">
    <form method="post">
        <label>Username:</label> <input type="text" name="uname" value="<?php echo $uname;?>">
        <br>
        <span class="error"><?php echo $userErr;?></span>
        <br>

        <label>Password:</label> <input type="password" name="password" value="<?php echo $pass;?>">
        <br>
        <span class="error"><?php echo $passErr;?></span>
        <span class="error"><?php echo $loginErr;?></span>
        <br>

        <input type="submit" name="submit" value="Submit">
    </form>
    </div>
    <br>

    <br><br>
    <a href="createuser.php"> Create an Account </a><br>
    <a href="resetPassword.php"> Reset Password </a>
    </div>
  </body>
  <footer>
  </footer>
</html>
