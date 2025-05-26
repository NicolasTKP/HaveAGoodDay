<?php
    include 'dbConn.php';
    include 'navibar.php';
    if (isset($_POST['submit'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $query = "SELECT * FROM `user table` WHERE Username='$username' AND Password='$password'";
        $result = mysqli_query($connection, $query);
        if(mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['Username'] = $row['Username'];
            $_SESSION['Password'] = $row['Password'];
            $_SESSION['Email'] = $row['Email'];
            $_SESSION['Role'] = $row['Role'];
            mysqli_close($connection);
            echo "<script>alert('Login Successfully'); window.location.href = 'Mainpage.php';</script>";
        }
        else {
            $alert = "grid";
        }
        

    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: black;
            background-image: url(picture/);
            background-size:100vw;
            background-repeat: no-repeat;
        }

        #box {
            background-color: #E7ECEF;
            width: 40dvw;
            height: 90dvh;
            margin: auto;
            border-radius: 4vw;
            opacity: 0.9;
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

        #username, #password{
            border: none;
            background-color: transparent;
            border-bottom: 0.15vw solid black;
            position: relative;
            width: 100%;
            display: block;
            font-size: 1.7vw;
            outline: none; 
            font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        }

        label{   
            font-size: 1.5vw;
            transform: translateY(-100%);
            position: absolute;
            transition: all 0.2s ease;
        }

        form{
            margin: auto;
            width: 60%;
        }


        #username:focus + label, #password:focus + label, #username:valid + label, #password:valid + label {
            font-size: 1vw;
            transform: translateY(-280%);
        }

        
        
        #submit {
            cursor: pointer;
            margin: auto;
            margin-top: 15dvh;
            display: block;
            background-image: url(picture/arrow.png);
            background-size: 5dvw;
            background-repeat: no-repeat;
            background-position: center;
            width: 8dvw;
            height: 15dvh;
            display: grid;
            background-color: transparent;
            border: 0.2vw solid black;
            border-radius: 1vw;
        }

        #submit:enabled:hover{
            background-size: 4.5dvw;
            
        }     
        
        form span{
            color: red;
            font-size: 0.9vw;
            opacity: 0;
        }

        #submit:disabled{
            cursor: not-allowed;
            border-color: gray;
            background-image: url(picture/grey\ arrow.png);
        }
        
        #myVideo {
        position: fixed;
        left: 0;
        width:100vw;
        }

        #alert-box{
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

        #input-field a{
            position: relative;
            text-decoration:none;
            color:black;
            left: 46%;
            top:1%;
            cursor: pointer;
        }

        #input-field a:hover {
            text-decoration:underline;
        }
        
        #eye-container{
        }
        .eye {
            position: absolute;
            display: flex;
            align-items:end;
            justify-content:end;
            width: 1.8vw;
            transform:translateY(-135%) translateX(-70%);
        }

        
    </style>
</head>
<body>
    <audio autoplay loop>
        <source src="picture/light-rain-109591.mp3" type="audio/mpeg">
        Your browser does not support the audio element.
    </audio>
    <video autoplay muted loop id="myVideo">
        <source src="picture/raining-video.mp4" type="video/mp4">
        Your browser does not support HTML5 video.
    </video>

    <div id="header"></div>

    <div id="box">
        <span id="topic">Login</span>
        
        <div id="input-field">
            <form method="post">
                <br>
                <br>
                <br>
                <br>
                <input type="text" id="username" name="username" required minlength="1" oninput="checkForm(), checkUser()">
                <label for="username">Username</label>
                <span id="username-alert">*Please Insert A Valid Username With Minimum Length: 4</span>
                <br>
                <input type="password" id="password" name="password" required minlength="1" style="margin-top: 6dvh; padding-right:8%;" oninput="checkForm(), checkPassword()">
                <label for="password">Password</label>
                <span id="password-alert">*Please Insert A Valid Password With Minimum Length: 7</span>
                <!-- password eye picture -->
                <label id="eye-container">
                    <div class="eye">
                        <img src="Picture/eyeopen.png" id="eyeopen" width='100%' style="display: none; cursor:pointer;" onclick="showpassword()">
                        <img src="Picture/eyeclose.png" id="eyeclose" width='100%' style="display: block;cursor:pointer;" onclick="showpassword()">
                    </div>
                </label>
                 
                 
                <input type="submit" value="" id="submit" name="submit" disabled >
            </form>
            <span><a href="register.php">Register</a></span>
        </div>
    </div>

    <div id="alert-box" style="display:<?php echo $alert?>;">
        <span id="cross" onclick="closeAlert()">x</span>
        <span id="word">Incorrect username or password<br>Please try again</span>
    </div>
</body>
</html>

<script>
    // Submit Bar Enable
    function checkForm() {
        let username = document.getElementById('username').value;
        let password = document.getElementById('password').value;
        let usercheck = username.length>=4;
        let passwordcheck = password.length>=7;
        document.getElementById("submit").disabled = !(usercheck&&passwordcheck);
    }
    
    function checkUser() {
        let username = document.getElementById('username').value;
        let usercheck = username.length>=4;
        if (usercheck){
            document.getElementById('username').style.borderBottomColor = "black";
            document.getElementById('username-alert').style.opacity = "0";
        }
        else{
            document.getElementById('username').style.borderBottomColor = "red";
            document.getElementById('username-alert').style.opacity = "1";
        }
    }

    function checkPassword() {
        let password = document.getElementById('password').value;
        let passwordcheck = password.length>=7;
        if (passwordcheck){
            document.getElementById('password').style.borderBottomColor = "black";
            document.getElementById('password-alert').style.opacity = "0";
        }
        else{
            document.getElementById('password').style.borderBottomColor = "red";
            document.getElementById('password-alert').style.opacity = "1";
        }
    }

    function closeAlert(){
        document.getElementById('alert-box').style.display = 'none';
    }

    function showpassword() {
        var passwordInput = document.getElementById('password');
        var eyeOpen = document.getElementById('eyeopen');
        var eyeClose = document.getElementById('eyeclose');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeOpen.style.display = 'block';
            eyeClose.style.display = 'none';
        } else {
            passwordInput.type = 'password';
            eyeOpen.style.display = 'none';
            eyeClose.style.display = 'block';
        }
    }

    document.getElementById('myVideo').playbackRate = .7;
    document.getElementById('myAudio').volume = 1;

</script>