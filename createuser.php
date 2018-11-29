<?php
$fnameErr = $lnameErr = $userErr = $emailErr = $passErr = "";
$fname = $lname = $uname = $email = $pass = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {
    //Connect to server
    $servername = "localhost";
    $dbusername = "qwinter";
    $dbpassword = "EMGAYIIS";
    $dbname = "f18_qwinter";
    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
    
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
            $lnameErr = "Only letters, spaces, and apostrophes allowed.";
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
                $userErr = "That email is already being used.";
            }
        }
    }
    
    //Check password
    if(empty($_POST["password"])) {
        $passErr = "Password is required.";
    }
    else {
        $pass = $_POST["password"];
        if(!preg_match("/^[a-zA-Z0-9]*$/", $uname)) {
            $lnameErr = "Only letters and numbers allowed.";
        }
    }
    
    
    //No errors, go to homepage
    if($fnameErr == "" && $lnameErr == "" && $userErr == "" && $emailErr == "" && $passErr == "") {
        //Add user to database
        $createQuery = "INSERT INTO users (username, password, fname, lname, email) VALUES (?, ?, ?, ?, ?)";
        $cstmt = $conn->prepare($createQuery);
        $cstmt->bind_param("sssss", $uname, $pass, $fname, $lname, $email);
        $cstmt->execute();
        $cstmt->close();
        
        //Go to Homepage        
        include 'home.html';
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
      I am an empty footer
    </footer>
    
  </body>
</html>
