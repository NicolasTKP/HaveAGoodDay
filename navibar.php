<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="navibar.js"></script>
    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        navibar extend-bar span{
            width: 4dvh;
            height: 0.65dvh;
            margin: 0.5dvh 1dvw;
            background-color: azure;
            display: block;
            border-radius:0.2vw;
            transition: transform 0.3s ease-in-out, display 0.3s ease-in-out;
        }
        navibar extend-bar {
            cursor: pointer;
            margin: 10px 0px 0px 5px;
            display: block;
            z-index: 10;
            position: absolute;
        }
        navibar{
            position: fixed;
            z-index: 100;
        }
        navibar background{
            background-color: #0D2F57;
            width: 25dvw;
            height: 100dvh;
            margin: 0dvh 0dvw 0 0;
            position: absolute;
            display: grid;
            grid-template:
            'div'
            'span'
            'span'
            'span'
            'span'
            'span'
            'empty';
            opacity: 1;
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out, border-radius 0.45s ease-in-out;
        }

        navibar background span{
            display: flex;
            color: white;
            padding: 3.7dvh;
            font-size: 1.6vw;
            flex-direction:column;
            justify-content: center;
            align-items:center;
            font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        }

        navibar background span div a{
            color: white;
            align-self: flex-end;
            text-decoration:none;
        }

        navibar background span div:hover a{
            /* color: #B0B0B0; */
            /* font-size: 1.85vw; */
            
        }
        

        navibar background div{
            display: flex;
            flex-direction: column;
            justify-items: center;
            align-items: center;
            margin-top: 2dvh;
        }

        navibar background span div{
            display: inline-flex;
            justify-items: center;
            align-items: center;
            flex-direction:row;
            margin: 0;
            height: auto;
            cursor:pointer;
        }

        #logo{
            cursor:pointer;
        }

        #login{
            color: white;
            font-size: 1vw;
            text-decoration: none;
            display: flex;

            align-self:end;
            grid-area:empty;
        }

        #login a{
            color: white;
            font-size: 1vw;
            text-decoration: none;
            padding-bottom:5%;
        }
        
        #login:hover {
            text-decoration: underline;
        }

        navibar background line {
            height: 0.5vh;
            width: 0;
            background-color:white;
            display: block;
            transition:all 0.5s ease;
            margin-top: 0.7vh;
            border-radius:1.5vw;
        }

        @media screen and (max-width: 600px) {
        navibar background span {
            font-size: 17px;
        }
        navibar background span:hover {
            font-size: 18px;
        }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <navibar>
        <extend-bar onclick="extend()">
            <span></span>
            <span></span>
            <span></span>
        </extend-bar>
        <background>
            <div id="logo"><img src="picture/logo.png" width="75dvw" height="75dvh" onclick="redirect('Mainpage.php')"></div>
            <span>
                <div onmouseover="showLine('line1', '47.5%')" onmouseout="hideLine('line1')">
                    <img src="picture/naviWeather.png" width="50dvw" height="50dvh" onclick="redirect('newPrediction.php')">
                    <a href="newPrediction.php">Prediction</a>
                </div>
                <line id="line1"></line>
            </span>
            <span>
                <div onmouseover="showLine('line2', '45%')" onmouseout="hideLine('line2')">
                    <img src="picture/history.png" width="50dvw" height="50dvh" onclick="redirect('historical.php')">
                    <a href="historical.php">Historical</a>
                </div>
                <line id="line2"></line>
            </span>
            <span>
                <div onmouseover="showLine('line3', '37%')" onmouseout="hideLine('line3')">
                    <img src="picture/review.png" width="50dvw" height="50dvh" onclick="redirect('comments.php')">
                    <a href="comments.php">Review</a>
                </div>
                <line id="line3"></line>
            </span>
            <?php
                if (isset($_SESSION['Username'])) {
            ?>
                <span>
                    <div onmouseover="showLine('line4', '48%')" onmouseout="hideLine('line4')">
                        <img src="picture/naviAdvanced.png" width="50dvw" height="50dvh" onclick="redirect('newAadvancedUser.php')">
                        <a href="newAdvancedUser.php">Advanced</a>
                    </div>
                    <line id="line4"></line>

                </span>
            <?php
                }
            ?>
            <?php
                if (isset($_SESSION['Username'])&&$_SESSION['Role']=="DataAnalyst") {
            ?>
                <span>
                    <div onmouseover="showLine('line5', '38%')" onmouseout="hideLine('line5')">
                        <img src="picture/naviReport.png" width="50dvw" height="50dvh" onclick="redirect('dataAnalyst.php')">
                        <a href="dataAnalyst.php">Report</a>
                    </div>
                    <line id="line5"></line>
                </span>
            <?php
                }
            ?>
            <?php
                if (isset($_SESSION['Username'])) {
            ?>
                <div id="login"><a href="profile.php">Welcome Back <?php echo $_SESSION['Username'] ?></a></div>
            <?php
                }
                else{
            ?>
                <div id="login"><a style="color:white; text-decoration:none;" href="login.php">Login</a></div>
            <?php
                }
            ?>
        </background>
    </navibar>
</body>
</html>