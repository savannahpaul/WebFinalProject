<?php
$fnameErr = $lnameErr = $userErr = $emailErr = $passErr = "";
$fname = $lname = $uname = $email = $pass = "";

session_start();

//go back to home if you try to go to login page when youre already logged in
if(isset($_SESSION["uname"]) && $_SESSION["uname"] != ""){
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

    //Check fname
    if(empty($_POST["firstname"])) {
        $fnameErr = "First Name is required.";
    }
    else {
        $fname = $_POST["firstname"];
        if(!preg_match("/^[a-zA-Z ']*$/", $fname)) {
            $fnameErr = "Only letters, spaces, and apostrophes allowed.";
        }
    }

    //Check last name
    if(empty($_POST["lastname"])) {
        $lnameErr = "Last name is required.";
    }
    else {
        $lname = $_POST["lastname"];
        if(!preg_match("/^[a-zA-Z ']*$/", $lname)) {
            $lnameErr = "Only letters, spaces, and apostrophes allowed.";
        }
    }

    //Check username
    if(empty($_POST["uname"])) {
        $userErr = "Username is required.";
    }
    else {
        $uname = $_POST["uname"];
        if(!preg_match("/^[a-zA-Z ']*$/", $uname)) {
            $userErr = "Only letters, spaces, and apostrophes allowed.";
        }
        else {
            //Check if username exists in database using prepared statement
            $userQuery = "SELECT COUNT(*) FROM users WHERE username = ?";
            $ustmt = $conn->prepare($userQuery);
            $ustmt->bind_param("s", $uname);
            $ustmt->execute();
            $ustmt->bind_result($numUsers);
            $ustmt->fetch();
            $ustmt->close();

            if($numUsers > 0) {
                $userErr = "That username is already being used.";
            }
        }
    }

    //Check email
    if(empty($_POST["email"])) {
        $emailErr = "Email is required.";
    }
    else {
        $email = $_POST["email"];
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email = "Invalid email address.";
        }
        else {
            //Check is email is already used
            $emailQuery = "SELECT COUNT(*) FROM users WHERE email = ?";
            $estmt = $conn->prepare($emailQuery);
            $estmt->bind_param("s", $email);
            $estmt->execute();
            $estmt->bind_result($numEmail);
            $estmt->fetch();
            $estmt->close();

            if($numEmail > 0) {
                $emailErr = "That email is already being used.";
            }
        }
    }

    //Check password
    if(empty($_POST["password"])) {
        $passErr = "Password is required.";
    }
    else {
        $pass = $_POST["password"];
        if(!preg_match("/^[a-zA-Z0-9]*$/", $pass)) {
            $passErr = "Only letters and numbers allowed.";
        }
    }


    //No errors, go to homepage
    if($fnameErr == "" && $lnameErr == "" && $userErr == "" && $emailErr == "" && $passErr == "") {
        $activationCode = rand(1000, 9999);
        $zeroVar = 0;
        //Add user to database
        $createQuery = "INSERT INTO users (username, password, fname, lname, email, activated, activecode) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $cstmt = $conn->prepare($createQuery);
        $cstmt->bind_param("sssssii", $uname, $pass, $fname, $lname, $email, $zeroVar, $activationCode);
        $cstmt->execute();
        $cstmt->close();

        //Send an activation code to the user
        $msg = "Your activation code is: {$activationCode}.";
        mail($email, 'Activation Code', $msg);

        //Go to Homepage
		$_SESSION["uname"] = $_POST["uname"];
        setcookie("userCookie", $_SESSION["uname"], time() + (60 * 10), "/");
		header("Location: home.php");
		//include 'home.html';
        exit;
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
    <h2>Create an Account</h2>
        <div id="form">
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <label>First Name:</label> <input type="text" name="firstname" value="<?php echo $fname;?>">*

                <br>
                <span class="error"><?php echo $fnameErr;?></span>
                <br>

                <label>Last Name:</label> <input type="text" name="lastname" value="<?php echo $lname;?>">*

                <br>
                <span class="error"><?php echo $lnameErr;?></span>
                <br>

                <label>User Name:</label> <input type="text" name="uname" value="<?php echo $uname;?>">*

                <br>
                <span class="error"><?php echo $userErr;?></span>
                <br>

                <label>Email:</label> <input type="text" name="email" value="<?php echo $email;?>">*

                <br>
                <span class="error"><?php echo $emailErr;?></span>
                <br>

                <label>Password:</label> <input type="password" name="password" value="<?php echo $pass;?>">*

                <br>
                <span class="error"><?php echo $passErr;?></span>
                <br><br>

                <span class="error">* Required Field</span>
                <br><br>

                <input type="submit" name="submit" value="Submit">
            </form>

        </div>
        <br><br>
        <a href="login.php"> Login </a>
        <br>
    </div>

    <footer>
    </footer>

  </body>
</html>
