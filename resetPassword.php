<?php
$email = $returnLogin = "";
$emailErr = "";

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
                $emailErr = "There is not an account associated with that email.";
            }
        }
    }
    
    if($emailErr == "") {
        //Generate a random 6 digit password
        $tempPass = rand(100000, 999999);
        
        $message = "Your temporary password is {$tempPass}.";
        
        $passQuery = "UPDATE users SET password = ? WHERE email = ?";
        $pstmt = $conn->prepare($passQuery);
        $pstmt->bind_param("ss", $tempPass, $email);
        $pstmt->execute();
        $pstmt->close();
        
        mail($email, 'Social Network Password Reset', $message);
        
        $emailErr = "<p>An email has been sent to {$email} to change your password.</p>";
        $returnLogin = "<p>Return to the <a href='login.php'>login page</a></p>";
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
            <h2>Reset Password</h2>
            <div id="form">
                <form method="post">
                    <label>Email:</label> <input type="text" name="email" value="<?php echo $email;?>">
                    <br>
                    <span class="error"><?php echo $emailErr;?></span>
                    <span><?php echo $returnLogin;?></span>
                    <br>
                    
                    <input type="submit" name="submit" value="Submit">
                </form>
            </div>
            <br>
            <a href="createuser.php">Create an Account</a><br>
        </div>
        
        
        <footer>
        </footer>
        
    </body>
    
</html>
