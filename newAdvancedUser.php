<?php
    include 'dbConn.php';
    include 'navibar.php';

    if(isset($_POST['submit'])){
        $maxTemp = $_POST['max-temperature'];
        $minTemp = $_POST['min-temperature'];
        $humidity = $_POST['humidity'];
        $barometer = $_POST['barometer'];
        $windSpeed = $_POST['wind-speed'];
        $time = $_POST['time'];

        $output = shell_exec("python weatherModels/Predicting.py $maxTemp $minTemp $humidity $barometer $windSpeed $time");

        $output_array = explode("\n", trim($output));
        $fog = $output_array[0] ?? null;
        $haze = $output_array[1] ?? null;
        $lightRainCloud = $output_array[2] ?? null;
        $lightRainScattered = $output_array[3] ?? null;
        $cloud = $output_array[4] ?? null;
        $rain = $output_array[5] ?? null;
        $rainShower = $output_array[6] ?? null;
        $scatteredCloud = $output_array[7] ?? null;
        $thunderstorm = $output_array[8] ?? null;
        $thunderstormScatteredCloud = $output_array[9] ?? null;

        $_SESSION['display'] = true;
    }

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>new advanced</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        
        }
        body{
            background-color: black;
            display: flex;
        }

        #title {
            margin-top: 12%;
            color:white;
            width: auto;
            font-size: 5vw;
            margin-right: .2%;
            font-weight: bold;
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        }

        #myVideo {
        position: fixed;
        left: 0;
        width:100%;
        opacity: 0;
        transition: all 2.5s ease;
        z-index: -1;
        }
        
        #sub-title {
            font-size: 7vw;
            font-weight: bold;
            margin-right: 10px;
            color: #A3CEF1;
            opacity: 0;
            transition: opacity 1s ease;
            font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        }

        #button{
            margin-top: 2%;
            width: 10%;
            height: 8%;
            background-color: white;
            color: black;
            border-radius: 5vw;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 1.3vw;
            font-weight: bold;
            cursor:pointer;
            opacity: 0;
            transition: opacity 1s ease, background-color .5s ease;
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
        }

        #button:hover {
            background-color: rgb(131, 129, 129);
            /* color: white; */
        }

        #panelLeft, #panelRight{
            width: 100dvw;
            height: 100dvh;
            overflow: hidden;
            float: left;
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: all 1s ease;
        }

        #panelRight {
            width: 0;
            background-color: #E7ECEF;
        }

        #loader {
        border: 0.5vw solid white;
        border-radius: 50%;
        border-top: 0.5vw solid black;
        width: 6.5dvw;
        height: 6.5dvw;
        -webkit-animation: spin 2s linear infinite; /* Safari */
        animation: spin 2s linear infinite;
        background-color: #E7ECEF;
        position: relative;
        display: none;
        }

        @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
        }

        
        #input-box {
            background-color: #E7ECEF;
            width: 100%;
            height: 100%;
            display: none;
            opacity: 0;
            grid-template:
            'topic'
            'input-field'
            'input-field'
            'input-field'
            'input-field'
            'input-field'
            'input-field'
            'input-field'
            'input-field';
            grid-gap: 10px;
            transition:all 1s ease;
        }


        #input-topic {
            margin: auto;
            font-size: 2.5vw;
            grid-area: topic;
            margin-bottom: 5%;
            font-weight:bold;
            font-family:Verdana, sans-serif;
        }

        #input-field{
            grid-area: input-field;
            display: flex;
            top: 0;
            padding: 0;
        }

        #max-temperature, 
        #min-temperature, 
        #barometer, 
        #humidity, 
        #wind-speed, 
        #time{
            border: none;
            margin: auto;
            background-color: transparent; 
            position: relative;
            width: 100%;
            height: 6.5dvh;
            display: block;
            font-size: 1.7vw;
            outline: none; 
            border: 0.15vw solid black;
            border-radius: 0.5vw;
            font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            padding:0.5vw 0.75vw;
        }

        label{   
            font-size: 1.5vw;
            transform: translateY(-140%);
            position: absolute;
            transition:all .2s ease;
            margin-left: 1%;
            font-weight: bold;
            cursor:text;
            font-family:Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
        }

        form{
            margin: auto;
            margin-top: 0;
            padding: 0;
            width: 60%;
            display: block;
        }

        #max-temperature:focus + label, 
        #min-temperature:focus + label, 
        #max-temperature:valid + label, 
        #min-temperature:valid + label, 
        #humidity:focus + label, 
        #humidity:valid + label, 
        #barometer:focus + label, 
        #barometer:valid + label, 
        #wind-speed:focus + label, 
        #wind-speed:valid + label, 
        #time:focus + label, 
        #time + label {
            font-size: 1vw;
            transform: translateY(-300%);
            background-color: #E7ECEF;
            /* animation: up .15s linear; */
        }

        @keyframes up {
        0% { font-size:1vw;opacity:1;transform: translateY(-130%);}
        5% {font-size:0.6vw;opacity:0;transform: translateY(-130%);}
        95% {font-size:0.6vw;opacity:0;transform: translateY(-300%);}
        100% { font-size:1vw;opacity:1;transform: translateY(-300%);}
        }

        #time::-webkit-calendar-picker-indicator {
            cursor: pointer;
        }
        
        #submit {
            cursor: pointer;
            /* margin: auto; */
            background-image: url(picture/arrow.png);
            background-size: 5dvw;
            background-repeat: no-repeat;
            background-position: center;
            width: 100%;
            height: 100%;
            /* display: grid; */
            background-color: transparent;
            /* border: 0.2vw solid black;
            border-radius: 1vw; */
            border:none;
            display: block;
        }

        #submit-box{
            width: 8dvw;
            height: 15dvh;
            margin: auto;
            display: flex;
            justify-content:center;
            align-items:center;
            border: 0.2vw solid grey;
            border-radius: 1vw;
            transition: transform 1s ease, width .5s ease, height .5s ease;
            overflow:show;
            margin-top: 8%;
        }

        #submit-box.small{
            animation:turn .7s ease-in-out;
        }
        @keyframes turn {
        0% { transform: rotate(0deg); width:8dvw; height:15dvh;}
        50% {transform: rotate(180deg); width:6dvw; height:13dvh;}
        100% { transform: rotate(360deg); width:8dvw; height:15dvh;}
        }

        #submit:enabled:hover{
            background-size: 4.5dvw;
            
        }     
        
        form span{
            color: red;
            font-size: 0.9vw;
            opacity: 0;
            display: block;
            margin-top: 0;
            margin-bottom: 5.5%;
            margin-left: 2%;
            
        }

        #submit:disabled{
            cursor: not-allowed;
            /* border-color: gray; */
            background-image: url(picture/grey\ arrow.png);
        }

        #output-box{
            position: absolute;
            z-index: -1;
            opacity: 0;
            width: 0;
            height: 85dvh;
            border-top-right-radius: 80px;
            border-top-left-radius: 80px;
            border-bottom-left-radius: 80px;
            border-bottom-right-radius: 80px;
            display: block;
            background-color: #E7ECEF;
            left: 10dvw;
            top:7.5dvh;
            transition: width 1s ease-out, opacity 0s;
            overflow:hidden;
            box-shadow: 0 10px 13px rgba(0, 0, 0, 1);
        }

        #inner-output-box{
            width:100%;
            height:100%;
            transform:translateX(-200%);
            opacity:0;
            transition:all 1.5s ease-in-out;
        }

        #output-box.visible{
            width: 80dvw;
            opacity: 1;
            transition: width 1s ease-out, opacity 0s;
            z-index: 1;
        }
        
        #inner-output-box.visible{
            transform:translateX(0%);
            opacity:1;
            transition:all 1.5s ease-in-out;
        }

        #output-header{
            width: 100%;
            height: 15%;
            display: flex;
        }

        #output-content{
            width:90%;
            height: 55%;
            margin-left:5%;
            display: inline-flex;
            gap:7%;
            justify-content:center;
            align-items:center;
            /* overflow:auto; */
        }

        #output-variable {
            width:100%;
            height: 30%;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
        }

        #reload-btn{
            height: 60%;
            width: 5%;
            background-color: black;
            border-radius: 1vw;
            color: white;
            font-size:3dvw;
            display: flex;
            justify-content: center;
            align-items: center;
            padding-top: .5%;
            padding-left: .4%;
            cursor: pointer;
            margin: 1% 0 0 6%;
            float: left;
        }

        #reload-btn:hover{
            font-size:2.7dvw;
        }

        #output-header-content {
            font-size: 4dvw;
            margin-left: 20%;
            align-self: center;
            text-decoration: underline;
            text-decoration-thickness: 0.04em;
            font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        }

        #output-variable div{
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            flex-direction: column;
        }
        #output-variable div span{
            display: block;
            font-size:1.5vw;
            font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        #output-content div{
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            flex-direction: column;
            padding: 0 2.5% 0 2.5%;
            max-width:15%;
            max-height:100%;
            width: 100%;
            
        }

        .weather{
            font-size:1.5vw;
            font-weight:bold;
            font-family:Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
            padding:10% 0 10% 0 ;

        }

        .probability{
            font-size:1.5vw;
            font-weight:bold;
            height: contain;
            width: contain;
            font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

    </style>
</head>
<body>
    <video autoplay muted loop id="myVideo">
        <source src="picture/advancedPageVideo.mp4" type="video/mp4">
        Your browser does not support HTML5 video.
    </video>
    <div id="panelLeft">
        <div id="title">
            <span class="typed" data-typed-items="Advanced Function, Make Your Own Future Make Your Own Pass"></span>
            <br>
        </div>
        <div id="sub-title-container"><span id="sub-title">IS HERE</span></div>
        <div id="button" onclick="input()">Get Started</div>
    </div>
    <div id="panelRight">
        <div id="input-box">
            <span id="input-topic">Meteorological Data</span>
            <div id="input-field">
                <form method="post">
                    <input type="text" id="max-temperature" name="max-temperature" required minlength="1" oninput="checkForm(), checkTemp()">
                    <label for="max-temperature">Max Temperature</label>
                    <span id="max-temperature-alert">*Please Insert Valid Temperature In Unit of °C or null</span>
                
                    <input type="text" id="min-temperature" name="min-temperature" required minlength="1" oninput="checkForm(), checkTemp()">
                    <label for="min-temperature">Min Temperature</label>
                    <span id="min-temperature-alert">*Please Insert Valid Temperature In Unit of °C or null</span>
                    
                    <input type="text" id="humidity" name="humidity" required minlength="1" oninput="checkForm(), checkHumidity()">
                    <label for="humidity">Humidity</label>
                    <span id="humidity-alert">*Please Insert Valid Humidity In Unit of % or null</span>
                    
                    <input type="text" id="barometer" name="barometer" required minlength="1" oninput="checkForm(), checkBarometer()">
                    <label for="barometer">Barometer</label>
                    <span id="barometer-alert">*Please Insert Valid Barometer In Unit of mbar or null</span>
                    
                    <input type="text" id="wind-speed" name="wind-speed" required minlength="1" oninput="checkForm(), checkWindSpeed()">
                    <label for="wind-speed">Wind Speed</label>
                    <span id="wind-speed-alert">*Please Insert Valid Wind Speed In Unit of km/h or null</span>
                    
                    <input type="time" id="time" name="time" required minlength="1" oninput="checkForm()">
                    <label for="time">Time</label>
                    <br>
                    <div id="submit-box">
                        <input type="submit" value="" id="submit" name="submit" disabled onclick="load()">
                        <div id="loader"></div>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
    
    <div id></div>

    <div id="output-box">
        <div id="inner-output-box">
            <div id="output-header">
                <div id="reload-btn" onclick="closeResult()">&#10227</div>
                <div id="output-header-content">Predicted Weather</div>
            </div>

            <div id="output-content">
                <?php
                if(isset($fog)&&$fog!=0){
                ?>
                <div class="content">
                    <span class="weather">Fog</span>
                    <img src="picture/fog.png" width="150%" height="150%">
                    <span class="probability"><?php echo $fog ?>%</span>
                </div>
                <?php
                }
                ?>

                <?php
                if(isset($haze)&&$haze!=0){
                ?>
                <div class="content">
                    <span class="weather">Haze</span>
                    <img src="picture/haze.png" width="150%" height="150%">
                    <span class="probability"><?php echo $haze ?>%</span>
                </div>
                <?php
                }
                ?>

                <?php
                if(isset($lightRainCloud)&&$lightRainCloud!=0){
                ?>
                <div class="content">
                    <span class="weather">Light Rain</span>
                    <img src="picture/lightRain.png" width="150%" height="150%">
                    <span class="probability"><?php echo $lightRainCloud ?>%</span>
                </div>
                <?php
                }
                ?>

                <?php
                if(isset($lightRainScattered)&&$lightRainScattered!=0){
                ?>
                <div class="content">
                    <span class="weather">Light Rain Broken Cloud</span>
                    <img src="picture/lightRainScattered.png" width="150%" height="150%">
                    <span class="probability"><?php echo $lightRainScattered ?>%</span>
                </div>
                <?php
                }
                ?>

                <?php
                if(isset($cloud)&&$cloud!=0){
                ?>
                <div class="content">
                    <span class="weather">Passing Clouds</span>
                    <img src="picture/cloud.png" width="150%" height="150%">
                    <span class="probability"><?php echo $cloud ?>%</span>
                </div>
                <?php
                }
                ?>

                <?php
                if(isset($rain)&&$rain!=0){
                ?>
                <div class="content">
                    <span class="weather">Rain</span>
                    <img src="picture/rain.png" width="150%" height="150%">
                    <span class="probability"><?php echo $rain ?>%</span>
                </div>
                <?php
                }
                ?>

                <?php
                if(isset($rainShower)&&$rainShower!=0){
                ?>
                <div class="content"> 
                    <span class="weather">Rain Shower</span>
                    <img src="picture/rainShower.png" width="150%" height="150%">
                    <span class="probability"><?php echo $rainShower ?>%</span>
                </div>
                <?php
                }
                ?>

                <?php
                if(isset($scatteredCloud)&&$scatteredCloud!=0){
                ?>
                <div class="content">
                    <span class="weather">Broken Clouds</span>
                    <img src="picture/scatteredCloud.png" width="150%" height="150%">
                    <span class="probability"><?php echo $scatteredCloud ?>%</span>
                </div>
                <?php
                }
                ?>

                <?php
                if(isset($thunderstorm)&&$thunderstorm!=0){
                ?>
                <div class="content">
                    <span class="weather">Thunderstorm</span>
                    <img src="picture/thunderstorm.png" width="150%" height="150%">
                    <span class="probability"><?php echo $thunderstorm ?>%</span>
                </div>
                <?php
                }
                ?>

                <?php
                if(isset($thunderstormScatteredCloud)&&$thunderstormScatteredCloud!=0){
                ?>
                <div class="content">
                    <span class="weather">Thunderstorm Broken Clouds</span>
                    <img src="picture/thunderstormScattered.png" width="150%" height="150%">
                    <span class="probability"><?php echo $thunderstormScatteredCloud ?>%</span>
                </div>
                <?php
                }
                ?>
                
                
                
                
                
                

            </div>

            <div id="output-variable">
                <div>
                    <span><b>Max Temperature</b></span>
                    <?php if(isset($maxTemp)){
                        if($maxTemp=="null"){
                    ?>
                        <span><?php echo $maxTemp ?></span>
                    <?php
                        }else{ 
                    ?>
                        <span><?php echo $maxTemp ?>°C</span>
                    <?php
                    }}
                    ?>
                </div>
                <div>
                    <span><b>Min Temperature</b></span>
                    <?php if(isset($minTemp)){
                        if($minTemp=="null"){
                    ?>
                        <span><?php echo $minTemp ?></span>
                    <?php
                        }else{ 
                    ?>
                        <span><?php echo $minTemp ?>°C</span>
                    <?php
                    }}
                    ?>
                </div>
                <div>
                    <span><b>Humidity</b></span>
                    <?php if(isset($humidity)){
                        if($humidity=="null"){
                    ?>
                        <span><?php echo $humidity ?></span>
                    <?php
                        }else{ 
                    ?>
                        <span><?php echo $humidity ?>%</span>
                    <?php
                    }}
                    ?>
                </div>
                <div>
                    <span><b>Barometer</b></span>
                    <?php if(isset($barometer)){
                        if($barometer=="null"){
                    ?>
                        <span><?php echo $barometer ?></span>
                    <?php
                        }else{ 
                    ?>
                        <span><?php echo $barometer ?>mbar</span>
                    <?php
                    }}
                    ?>
                </div>
                <div>
                    <span><b>Wind Speed</b></span>
                    <?php if(isset($windSpeed)){
                        if($windSpeed=="null"){
                    ?>
                        <span><?php echo $windSpeed ?></span>
                    <?php
                        }else{ 
                    ?>
                        <span><?php echo $windSpeed ?>km/h</span>
                    <?php
                    }}
                    ?>
                </div>
                <div>
                    <span><b>Time</b></span>
                    <?php if(isset($time)){
                    ?>
                        <span><?php echo $time ?></span>
                    <?php
                    }
                    ?>
                </div>
            </div>
        <div>
    </div>
</body>
</html>
<script src="typed.js/typed.umd.js"></script>
<script>
    
    document.addEventListener("DOMContentLoaded", function() {
            var element = document.getElementById("myVideo");
            var subTitle = document.getElementById("sub-title");
            var btn = document.getElementById("button");
            setTimeout(function() {
                element.style.opacity = 1;
            }, 1000);

            setTimeout(function() {
                subTitle.style.opacity = 1;
                btn.style.opacity = 1;
            }, 4000);

            setTimeout(function() {
                subTitle.style.opacity = 0;
            }, 7000);

            setTimeout(function() {
                subTitle.innerHTML = "WITH US";         
            }, 12000);

            setTimeout(function() {
                subTitle.style.opacity = 1;
            }, 12100);

        });

    document.addEventListener("DOMContentLoaded", function() {
        setTimeout(function() {
            const typed = document.querySelector('.typed')
            if (typed) {
                let typed_strings = typed.getAttribute('data-typed-items')
                typed_strings = typed_strings.split(',')
                new Typed('.typed', {
                strings: typed_strings,
                loop: false,
                typeSpeed: 80,
                backSpeed: 50,
                backDelay: 3000
                });
            }
        }, 1500);
        
    });

    function input(){
        document.getElementById("panelLeft").style.width = "65dvw";
        document.getElementById("panelRight").style.width = "35dvw";
        document.getElementById("button").style.display="none";
        document.getElementById("title").style.fontSize="2.4vw";
        document.getElementById("title").style.marginTop="30%";
        setTimeout(function() {
            document.getElementById("input-box").style.display="grid";
        }, 1000);
        setTimeout(function() {
            document.getElementById("input-box").style.opacity=1;
        }, 1100);
    }


    function checkForm() {
        let maxTempCheck = (document.getElementById('max-temperature').value>0 && document.getElementById('max-temperature').value<100||document.getElementById('max-temperature').value=="null");
        let minTempCheck = (document.getElementById('min-temperature').value>0 && document.getElementById('min-temperature').value<100||document.getElementById('min-temperature').value=="null");
        let tempCheck = true;
        if((document.getElementById('max-temperature').value!="null")&&(document.getElementById('min-temperature').value!="null")){
            tempCheck = (document.getElementById('max-temperature').value >= document.getElementById('min-temperature').value);
        }else{
            tempCheck = true;
        }
        let humidityCheck = (document.getElementById('humidity').value>0 && document.getElementById('humidity').value<100)||document.getElementById('humidity').value=="null";
        let barometerCheck  = (document.getElementById('barometer').value>900 && document.getElementById('barometer').value<1100)||document.getElementById('barometer').value=="null";
        let windCheck  = (document.getElementById('wind-speed').value>=0 && document.getElementById('wind-speed').value<407)||document.getElementById('wind-speed').value=="null";
        let timeCheck = document.getElementById('time').value;
        document.getElementById("submit").disabled = !(maxTempCheck&&minTempCheck&&humidityCheck&&barometerCheck&&windCheck&&timeCheck&&tempCheck);
        if (maxTempCheck&&minTempCheck&&humidityCheck&&barometerCheck&&windCheck&&timeCheck&&tempCheck){
            document.getElementById('submit-box').style.borderColor = "black";
        }else{
            document.getElementById('submit-box').style.borderColor = "grey";
        }
    }
    
    function checkTemp() {
        var maxTemp = document.getElementById('max-temperature').value;
        var minTemp = document.getElementById('min-temperature').value
        if(maxTemp.length>0){
            let flag = true;
            let maxTempCheck = maxTemp>0 && maxTemp<100;
            if (minTemp.length > 0 && minTemp>0) {
            flag = parseFloat(maxTemp) >= parseFloat(minTemp);
            }

            if (maxTemp=="null"){
                maxTempCheck=true;
                flag=true;
            }

            if (maxTempCheck&&flag){
                document.getElementById('max-temperature').style.borderColor = "black";
                document.getElementById('max-temperature-alert').style.opacity = "0";
            }
            else{
                document.getElementById('max-temperature').style.borderColor = "red";
                document.getElementById('max-temperature-alert').style.opacity = "1";
            }
        }

        if(minTemp.length>0){
            let minTempCheck = minTemp>0 && minTemp<100;
            let flag = true;
            if (maxTemp.length > 0&&maxTemp>0) {
            flag = parseFloat(maxTemp) >= parseFloat(minTemp);
            }

            if (minTemp=="null"){
                minTempCheck=true;
                flag=true;
            }

            if (minTempCheck&&flag){
                document.getElementById('min-temperature').style.borderColor = "black";
                document.getElementById('min-temperature-alert').style.opacity = "0";
            }
            else{
                document.getElementById('min-temperature').style.borderColor = "red";
                document.getElementById('min-temperature-alert').style.opacity = "1";
            }
        }
        
    }


    function checkHumidity() {
        let humidityCheck = (document.getElementById('humidity').value>0 && document.getElementById('humidity').value<100)||document.getElementById('humidity').value=="null";
        if (humidityCheck){
            document.getElementById('humidity').style.borderColor = "black";
            document.getElementById('humidity-alert').style.opacity = "0";
        }
        else{
            document.getElementById('humidity').style.borderColor = "red";
            document.getElementById('humidity-alert').style.opacity = "1";
        }
    }

    function checkBarometer() {
        let barometerCheck  = (document.getElementById('barometer').value>900 && document.getElementById('barometer').value<1100)||document.getElementById('barometer').value=="null";
        if (barometerCheck){
            document.getElementById('barometer').style.borderColor = "black";
            document.getElementById('barometer-alert').style.opacity = "0";
        }
        else{
            document.getElementById('barometer').style.borderColor = "red";
            document.getElementById('barometer-alert').style.opacity = "1";
        }
    }

    function checkWindSpeed() {
        let windCheck  = (document.getElementById('wind-speed').value>=0 && document.getElementById('wind-speed').value<407)||document.getElementById('wind-speed').value=="null";
        if (windCheck){
            document.getElementById('wind-speed').style.borderColor = "black";
            document.getElementById('wind-speed-alert').style.opacity = "0";
        }
        else{
            document.getElementById('wind-speed').style.borderColor = "red";
            document.getElementById('wind-speed-alert').style.opacity = "1";
        }
    }

    function closeResult(){
        document.getElementById('output-box').classList.remove('visible');
        document.getElementById('title').style.opacity=1;
        document.getElementById('myVideo').style.filter='blur(0px)';
        input();
    }

    function load(){
        document.getElementById('submit').style.display = "none";
        document.getElementById('loader').style.display = "block";
        document.getElementById('submit-box').classList.add('small');
    }

    function adjustGap() {
        let divCount = document.getElementById('output-content').querySelectorAll('div').length;

        let gap = (14/divCount) + '%'; 
        if(divCount>5){
            let weathers = document.getElementsByClassName('weather');
            let probabilities = document.getElementsByClassName('probability');
            for (let i = 0; i < weathers.length; i++) {
            weathers[i].style.fontSize = "1.2vw";
            probabilities[i].style.fontSize = "1.2vw";
            }
        }

        if(divCount>7){
            let weathers = document.getElementsByClassName('weather');
            let probabilities = document.getElementsByClassName('probability');
            for (let i = 0; i < weathers.length; i++) {
            weathers[i].style.fontSize = "1vw";
            probabilities[i].style.fontSize = "1vw";
            }
        }
        document.getElementById('output-content').style.gap = gap;
    }

    function setDiv() {
        document.getElementById('output-box').classList.add('visible');
        let divs = document.getElementsByClassName('content');

        let maxHeight = 0;

        for (let i = 0; i < divs.length; i++) {
            let height = divs[i].offsetHeight;
            
            if (height > maxHeight) {
                maxHeight = height;
            }
        }
        
        for (let i = 0; i < divs.length; i++) {
            divs[i].style.height = maxHeight + 'px';
        }

        let spans = document.getElementsByClassName('weather');

        maxHeight = 0;

        for (let i = 0; i < spans.length; i++) {
            let height = spans[i].offsetHeight;

            if (height > maxHeight) {
                maxHeight = height;
            }

        }
        
        for (let i = 0; i < spans.length; i++) {
            spans[i].style.height = maxHeight + 'px';
        }
        document.getElementById('output-box').classList.remove('visible');
    }


    document.addEventListener('DOMContentLoaded', setDiv());
    window.onload = adjustGap(), setDiv();
    window.onresize = adjustGap(), setDiv();
    
</script>

<?php
    if (isset($_SESSION['display'])) {
        echo "<script>document.getElementById('myVideo').style.filter='blur(15px)';</script>";
        echo "<script>document.getElementById('myVideo').style.opacity=1;</script>";
        echo "<script>document.getElementById('title').style.opacity=0;</script>";
        echo "<script>document.getElementById('output-box').classList.add('visible');</script>";
        sleep(1.5);
        echo "<script>document.getElementById('inner-output-box').classList.add('visible');</script>";
        sleep(1.5);
        unset($_SESSION['display']);
    }   
?>