<?php
    include 'dbConn.php';
    include 'navibar.php';
    if (isset($_POST['submit'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $date = date('Y-m-d');
        $query = "SELECT * FROM `user table` WHERE Username='$username' OR Email='$email'";
        $result = mysqli_query($connection, $query);
        if(mysqli_num_rows($result) == 1) {
            $alert = "grid";
        }
        else {
            $query = "INSERT INTO `user table`(`Username`, `Password`, `Email`, `RegisteredDate`, `Role`) VALUES ('$username','$password','$email','$date','AdvancedUser')";
            mysqli_query($connection, $query);
            echo "<script>alert('Register Successfully'); window.location.href = 'login.php';</script>";
            exit;
        }
        

    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
            'input-field'
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
        }

        #input-field{
            grid-area: input-field;
            display: block;
        }

        #username, #password, #re-password, #email{
            border: none;
            background-color: transparent;
            border: 0.15vw solid black;
            border-radius:0.3vw;
            position: relative;
            width: 100%;
            height: 6dvh;
            display: block;
            font-size: 1.7vw;
            outline: none; 
            font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        }

        label{   
            font-size: 1.5vw;
            transform: translateY(-130%) translateX(7%);
            position: absolute;
            transition: all 0.2s ease;
        }

        form{
            margin: auto;
            width: 60%;
        }


        #username:focus + label, #password:focus + label, #username:valid + label, #password:valid + label, #re-password:focus + label, #re-password:valid + label, #email:focus + label, #email:valid + label {
            font-size: 1vw;
            transform: translateY(-300%) translateX(7%);
            background-color:#E7ECEF
        }

        
        
        #submit {
            cursor: pointer;
            margin: auto;
            display: block;
            background-image: url(picture/arrow.png);
            background-size: 5dvw;
            background-repeat: no-repeat;
            background-position: center;
            width: 7dvw;
            height: 13dvh;
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
            width: 65dvw;
            position: fixed;
            height: 30dvh;
            background-color: #E7ECEF;
            top: 0;
            left: 17.5dvw;
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

        #payment-topic {
            font-size: 1.5vw;
            display: flex;
            font:black;
            text-decoration: underline;
            font-weight: bold;
            justify-content: center;
            align-items: center;
        }

        #payment-options{
            width: 100%;
            display: flex;
        }

        .option{
            background-color: transparent;
            border: .15vw solid black;
            border-radius: 1vw;
            height: 70%;
            width: 33%;
            margin: 5% .5%;
            cursor: pointer;
            display: block;
        }

        .option:hover{
            background-color: gray;
        }
        .option img{
            margin-left: 1.5dvh;
        }
        .option div{
            font-size: 1vw;
            text-align: center;
        }

        .option.highlighted {
            background-color: white;
            border-color: white;
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
        <span id="topic">Register</span>
        
        <div id="input-field">
            <form method="post">
                <input type="text" id="username" name="username" required minlength="1" oninput="checkForm(), checkUser()">
                <label for="username">Username</label>
                <span id="username-alert">*Please Insert A Valid Username With Minimum Length: 4</span>
                <br>
                <input type="password" id="password" name="password" required minlength="1" style="margin-top: 1dvh;" oninput="checkForm(), checkPassword()">
                <label for="password">Password</label>
                <span id="password-alert">*Please Insert A Valid Password With Minimum Length: 7</span>
                <br>
                <input type="password" id="re-password" name="re-password" required minlength="1" style="margin-top: 1dvh;" oninput="checkForm(), checkPassword()">
                <label for="re-password">Confirm Password</label>
                <span id="re-password-alert">*Please Insert Password That Match Password Field</span>
                <br>
                <input type="text" id="email" name="email" required minlength="1" style="margin-top: 1dvh;" oninput="checkForm(), checkEmail()">
                <label for="email">Email</label>
                <span id="email-alert">*Please Insert A Valid Email</span>
                <div id="payment-topic">Payment Option</div>
                <div id="payment-options">
                    <div class="option highlighted" onclick="highlighted(this)">
                        <img src="picture/banking.png" width="80%" height="80%">
                        <div>Bank Transfer</div>
                    </div>
                    <div class="option" onclick="highlighted(this)">
                        <img src="picture/wallet.png" width="80%" height="80%">
                        <div>E-Wallet</div>
                    </div>
                    <div class="option" onclick="highlighted(this)">
                        <img src="picture/card.png" width="80%" height="80%">
                        <div>Card</div>
                    </div>
                </div>
                <input type="submit" value="" id="submit" name="submit" disabled >
            </form>

        </div>
    </div>

    <div id="alert-box" style="display:<?php echo $alert?>;">
        <span id="cross" onclick="closeAlert()">x</span>
        <span id="word">Username or Email Already Registered, Please Try Again</span>
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

    function highlighted(element) {
        let options = document.getElementsByClassName('option');
        Array.from(options).forEach(function(option){
            option.classList.remove('highlighted');
        });
        element.classList.add('highlighted');
    }

    document.getElementById('myVideo').playbackRate = .7;
    document.getElementById('myAudio').volume = 1;

</script>