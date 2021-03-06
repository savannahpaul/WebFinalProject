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

    if(empty($_POST["email"])) {
        $emailErr = "Email is required.";
    }
    else {
        $email = $_POST["email"];
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email = "Invalid email address.";
        }
        else {
            //Check is email is active
            $emailQuery = "SELECT COUNT(*) FROM users WHERE email = ?";
            $estmt = $conn->prepare($emailQuery);
            $estmt->bind_param("s", $email);
            $estmt->execute();
            $estmt->bind_result($numEmail);
            $estmt->fetch();
            $estmt->close();

            if($numEmail <= 0) {
                $emailErr = "Invalid email.";
            }
        }
    }

    //Check old password
    if(empty($_POST["password"])) {
        $oldPassErr = "Password is required.";
    }
    else {
        $oldPass = $_POST["password"];
        if(!preg_match("/^[a-zA-Z0-9]*$/", $oldPass)) {
            $oldPassErr = "Only letters and numbers allowed.";
        }
    }

    //Check new password
    if(empty($_POST["newPassword"])) {
        $newPassErr = "Password is required.";
    }
    else {
        $newPass = $_POST["newPassword"];
        if(!preg_match("/^[a-zA-Z0-9]*$/", $newPass)) {
            $newPassErr = "Only letters and numbers allowed.";
        }
    }

    //Check current email and password
    if($emailErr == "" && $oldPassErr == "" && $newPassErr == "") {
        $checkQuery = "SELECT COUNT(*) FROM users WHERE email = ? AND password = ?";
        $cstmt = $conn->prepare($checkQuery);
        $cstmt->bind_param("ss", $email, $oldPass);
        $cstmt->execute();
        $cstmt->bind_result($validEmailPass);
        $cstmt->fetch();
        $cstmt->close();

        if($validEmailPass != 1) {
            $emailErr = $oldPassErr = "Invalid email or password.";
        }
        else {
            $updateQuery = "UPDATE users SET password = ? WHERE email = ? AND password = ?";
            $ustmt = $conn->prepare($updateQuery);
            $ustmt->bind_param("sss", $newPass, $email, $oldPass);
            $ustmt->execute();
            $ustmt->close();

            $newPassErr = "<p>Password has been reset for email {$email}</p>";
        }
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
            <h2>Change Password</h2>
            <div id="form">
                <form method="post">
                    <label>Email:</label> <input type="text" name="email" value="<?php echo $email;?>">*
                    <br>
                    <span class="error"><?php echo $emailErr;?></span>
                    <br>

                    <label>Current Password:</label> <input type="password" name="password" value="<?php echo $oldPass;?>">*
                    <br>
                    <span class="error"><?php echo $oldPassErr;?></span>
                    <br>

                    <label>New Password:</label> <input type="password" name="newPassword" value="<?php echo $newPass;?>">*

                    <br>
                    <span class="error"><?php echo $newPassErr;?></span>
                    <br>

                    <span class="error">* Required Field</span>
                    <br><br>

                    <input type="submit" name="submit" value="Submit">
                </form>
            </div>
            <br>
            <a href="home.php">Home</a>
            <br>
        </div>


        <footer>
          <img style="padding-left:230px;float:left;height:30px;width:60px;" src="images/css.png" alt="css">
          <img style="float:left;height:30px;width:60px;" src="images/html5.png" alt="css">
          <img style="float:left;height:30px;width:60px;" src="images/wcag2AA.png" alt="css">
        </footer>

    </body>

</html>
