<?php
$email = $newPass = $oldPass = $backToHome = "";
$emailErr = $oldPassErr = $newPassErr = "";

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
            $backToHome = "<p>Go to <a href='home.php'>home page</a></p>";
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
                    
                    <span><?php echo $backToHome;?></span>
                    <br>
                    
                    <span class="error">* Required Field</span>
                    <br><br>
                    
                    <input type="submit" name="submit" value="Submit">
                </form>
            </div>
        </div>
        
        
        <footer>
        </footer>
        
    </body>
    
</html>
