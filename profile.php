<?php
    include 'dbConn.php';
    include 'navibar.php';
    $username = $_SESSION['Username'];
    $password = $_SESSION['Password'];
    $email = $_SESSION['Email'];
    if (isset($_POST['submit'])) {
        $oldusername = $_SESSION['Username'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $query = "SELECT * FROM `user table` WHERE Username='$username' AND Email='$email'";
        $result = mysqli_query($connection, $query);
        if(mysqli_num_rows($result) == 1) {
            $alert = "grid";
        }
        else {
            $query = "UPDATE `review table` SET `Username`='$username' WHERE Username ='$oldusername'";
            $result = mysqli_query($connection, $query);
            $query = "UPDATE `user table` SET `Username`='$username',`Password`='$password',`Email`='$email' WHERE Username ='$oldusername'";
            $result = mysqli_query($connection, $query);
            $_SESSION['Username'] = $username;
            $_SESSION['Password'] = $password;
            $_SESSION['Email'] = $email;
            $complete = "grid";
        }
        

    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: black;
            background-image: url(picture/profileBackground.jpg);
            background-size:100vw;
            background-repeat: no-repeat;
        }

        #box {
            background-color: #E7ECEF;
            width: 40dvw;
            height: 90dvh;
            margin: auto;
            border-radius: 4vw;
            display: grid;
            grid-template:
            'topic'
            'input-field'
            'input-field'
            'input-field';
            grid-gap: 10px;
        }

        #header{
            height: 5dvh;
        }

        #topic {
            margin: auto;
            font-size: 4vw;
            grid-area: topic;
            margin-top: 5%;
        }

        #input-field{
            grid-area: input-field;
            display: block;
        }

        #username, #password, #re-password, #email{
            border: none;
            background-color: transparent;
            border: 0.15vw solid black;
            border-radius:0.5vw;
            height: 6dvh;
            position: relative;
            width: 100%;
            display: block;
            font-size: 1.7vw;
            outline: none; 
            font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        }

        label{   
            font-size: 1.5vw;
            transform: translateY(-280%);
            position: absolute;
            transition: all 0.2s ease;
        }

        form{
            margin: auto;
            width: 60%;
        }
        
        
        #submit {
            cursor: pointer;
            float:right;
            margin-top: 7dvh;
            display: block;
            background-color:black;
            color:white;
            font-size:2vw;
            width: 10dvw;
            height: 10dvh;
            display: grid;
            border-radius: 1vw;
            font-weight:bold;
        }

        #submit:enabled:hover{
            background-color:darkgrey;
        }     
        
        form span{
            color: red;
            font-size: 0.9vw;
            opacity: 0;
        }

        #submit:disabled{
            cursor: not-allowed;
            background-color:lightgrey;

        }
        
        #myVideo {
        position: fixed;
        left: 0;
        width:100vw;
        }

        #alert-box, #complete-box{
            width: 40dvw;
            position: fixed;
            height: 30dvh;
            background-color: #E7ECEF;
            top: 0;
            left: 30dvw;
            margin: auto;
            display: none;
            opacity: 1;
            border-radius: 2vw;
            grid-template: 
            'cross'
            'word'
            'word';
        }

        

        #cross{
            grid-area: cross;
            margin-right: 3%;
            justify-self: end;
            align-self: start;
            font-size: 2vw;
            font-weight: bold;
            font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            cursor: pointer;
        }

        #word{
            grid-area: word;
            text-align: center;
            font-size: 2.5vw;
            font-weight: bold;
        }

        #input-field span {
            position: relative;
            font-size:1vw;
        }

        #logout {
            cursor: pointer;
            float:left;
            margin-top: 4dvh;
            display: flex; 
            justify-content: center;
            align-items: center; 
            background-color:black;
            color:white;
            font-size:2vw;
            width: 10dvw;
            height: 10dvh;
            border-radius: 1vw;
            font-weight:bold;
            text-align:center;
            text-justify:center;
        }

        #logout a{
            text-decoration:none;
            color:white;
        }

        #logout:hover {
            background-color:darkgrey;
        }

        @media screen and (max-width: 600px) {
            label{   
            transform: translateY(-580%);
        }
        }

        
    </style>
</head>
<body>
    <div id="header"></div>

    <div id="box">
        <span id="topic">Profile</span>
        
        <div id="input-field">
            <form method="post">
                <br>

                <input type="text" id="username" name="username" required minlength="1" oninput="checkForm(), checkUser()" value="<?php echo $username ?>">
                <label for="username">Username</label>
                <span id="username-alert">*Please Insert A Valid Username With Minimum Length: 4</span>
                <br>
                <input type="password" id="password" name="password" required minlength="1" style="margin-top: 4dvh; padding-right:8%;" oninput="checkForm(), checkPassword()" value="<?php echo $password ?>">
                <label for="password">Password</label>
                <span id="password-alert">*Please Insert A Valid Password With Minimum Length: 7</span>
                <br>
                <input type="password" id="re-password" name="re-password" required minlength="1" style="margin-top: 4dvh;" oninput="checkForm(), checkPassword()" value="<?php echo $password ?>">
                <label for="re-password">Confirm Password</label>
                <span id="re-password-alert">*Please Insert Password That Match Password Field</span>
                <br>
                <input type="text" id="email" name="email" required minlength="1" style="margin-top: 4dvh;" oninput="checkForm(), checkEmail()" value="<?php echo $email ?>">
                <label for="email">Email</label>
                <span id="email-alert">*Please Insert A Valid Email</span>
                 
                 
                <input type="submit" value="Apply" id="submit" name="submit" disabled >

                <div id="logout"><a href="logout.php">Logout</a></div>
            </form>
            
        </div>
    </div>

    <div id="alert-box" style="display:<?php echo $alert?>;">
        <span id="cross" onclick="closeAlert()">x</span>
        <span id="word">Username or Email Address Already Exist<br>Please try again</span>
    </div>

    <div id="complete-box" style="display:<?php echo $complete?>;">
        <span id="cross" onclick="closeComplete()">x</span>
        <span id="word">Update Successfully</span>
    </div>
</body>
</html>

<script>
    // Submit Bar Enable
    function checkForm() {
        let username = document.getElementById('username').value;
        let password = document.getElementById('password').value;
        let rePassword = document.getElementById('re-password').value;
        let rePasswordCheck = rePassword == password;
        let usercheck = username.length>=4;
        let passwordcheck = password.length>=7;
        const emailPattern = /^[a-zA-Z0-9._%+-]+@(gmail|yahoo|hotmail|mail)\.com$/;
        let email = document.getElementById('email').value;
        let emailCheck = emailPattern.test(email);
        document.getElementById("submit").disabled = !(usercheck&&passwordcheck&&rePasswordCheck&&emailCheck);
    }
    
    function checkUser() {
        let username = document.getElementById('username').value;
        let usercheck = username.length>=4;
        if (usercheck){
            document.getElementById('username').style.borderColor = "black";
            document.getElementById('username-alert').style.opacity = "0";
        }
        else{
            document.getElementById('username').style.borderColor = "red";
            document.getElementById('username-alert').style.opacity = "1";
        }
    }

    function checkPassword() {
        let password = document.getElementById('password').value;
        let rePassword = document.getElementById('re-password').value;
        let passwordCheck = password.length>=7;
        let rePasswordCheck = rePassword == password;
        if (password.length>0){
            if (passwordCheck){
                document.getElementById('password').style.borderColor = "black";
                document.getElementById('password-alert').style.opacity = "0";
            }
            else{
                document.getElementById('password').style.borderColor = "red";
                document.getElementById('password-alert').style.opacity = "1";
            }
        }

        if(rePassword.length>0){
            if (rePasswordCheck){
                document.getElementById('re-password').style.borderColor = "black";
                document.getElementById('re-password-alert').style.opacity = "0";
            }
            else{
                document.getElementById('re-password').style.borderColor = "red";
                document.getElementById('re-password-alert').style.opacity = "1";
            }
        }
    }

    function checkEmail() {
        const emailPattern = /^[a-zA-Z0-9._%+-]+@(gmail|yahoo|hotmail|mail)\.com$/;
        let email = document.getElementById('email').value;
        let emailCheck = emailPattern.test(email);
        if (emailCheck){
                document.getElementById('email').style.borderColor = "black";
                document.getElementById('email-alert').style.opacity = "0";
            }
            else{
                document.getElementById('email').style.borderColor = "red";
                document.getElementById('email-alert').style.opacity = "1";
            }
    }

    function closeAlert(){
        document.getElementById('alert-box').style.display = 'none';
    }

    function closeComplete(){
        document.getElementById('complete-box').style.display = 'none';
    }


</script>