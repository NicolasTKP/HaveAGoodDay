<?php
    include 'dbConn.php';
    include 'navibar.php';
    $sql = "SELECT *
    FROM `visit count table` ";
    $result = $connection->query($sql);
    $row = $result->fetch_assoc();
    $visitCount = $row['VisitCount'];
    $visitCount +=1;
    $sql = "UPDATE `visit count table` SET `VisitCount`='$visitCount'";
    $connection->query($sql);

    $sql = "SELECT Actual_Weather, Date, Max_Temperature, Min_Temperature, Humidity, Barometer, Wind_Speed, Actual_Temperature 
    FROM `weather status table` 
    WHERE DATE(Date) = CURDATE() 
    AND HOUR(Time) = HOUR(NOW()) 
    ORDER BY Date DESC 
    LIMIT 1";

    $result = $connection->query($sql);

    $Max_Temperature = '';
    $Min_Temperature = '';
    $Humidity = '';
    $Barometer = '';
    $Wind_Speed = '';
    $weather_status_String = '';
    $data = array();
    $Actual_Weather= '';

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $Actual_Weather = $row['Actual_Weather'];
            $Max_Temperature = $row['Max_Temperature'];
            $Min_Temperature = $row['Min_Temperature'];
            $Humidity = $row['Humidity'];
            $Barometer = $row['Barometer'];
            $Wind_Speed = $row['Wind_Speed'];
            $Actual_Temperature = $row['Actual_Temperature'];
            
            
            $weather_status_array = explode('|', $row['Actual_Weather']);
            // Identify the position of the 1.0 value
            $weather_index = array_search('1.0', $weather_status_array);
            // Define the weather status types
            $weather_types = [
                "Fog", "Haze", "Light rain passing clouds", "Light rain scattered clouds",
                "Passing clouds", "Rain passing clouds", "Rain showers scattered clouds",
                "Scattered clouds", "Thunderstorms passing clouds", "Thunderstorms scattered clouds"
            ];
            // Determine the actual weather
            $Actual_Weather = isset($weather_types[$weather_index]) ? $weather_types[$weather_index] : 'Unknown';

            // Assign the processed Actual_Weather to $row
            $row['Actual_Weather'] = $Actual_Weather;

            $data[] = $row;
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prediction</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/dark.css">

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <style>
        #upperPart{
            display:flex;
            justify-content:center;
            align-items:center;
            flex-direction:column;
            height: 100dvh;
            
        }

        h2{
            margin-top:.5%;
            font-size:4vw;
            color:white;
            font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        }

        .gallery{
            width: 100%;
            margin-top: 5%;
        }

        .gallery-container{
            align-items: center;
            display:flex;
            height: 50vh;
            max-width:100%;
            position: relative;
        }

        .gallery-container a{
            align-items: center;
            display:flex;
            max-width:100%;
            height: 100%;
        }

        .gallery-item{
            opacity: 0;
            position: absolute;
            transition: all 0.3s ease-in-out;
            z-index: 0;
            border-radius: 15px;
            background-size: contain;
        }

        .gallery-item-1, .gallery-item-3{
            height: 70%;
            opacity: 0.8;
            width: 20%;
            z-index: 1;
        }

        .gallery-item-1{
            left: 30%;
            transform: translateX(-60%);
        }

        .gallery-item-2{
            box-shadow: -2px 5px 33px 6px rgba(0,0,0,0.35);
            height: 80%;
            opacity: 1;
            left: 50%;
            transform: translateX(-50%);
            width: 25%;
            z-index: 2;
        }

        .gallery-item-3{
            left: 70%;
            transform: translateX(-40%);
        }

        .gallery-controls{
            display: flex;
            position: absolute;
            justify-content: space-between;
            margin: 0px 0;
            height: 5vw;
            width: 100%;
            z-index: 5;
            top:0%;
            transform: translateY(250%);
        }

        .gallery-controls button{
            background-color: transparent;
            border: 0;
            cursor: pointer;
            padding: 0 0px 0 0;
            text-transform: capitalize;
            font-size: 0;
            z-index:5;
        }

        .gallery-controls-button:focus{
            outline: none;
        }

        .gallery-controls-previous{
            position: relative;
            margin-left: 10%;
            transition: all 0.15s ease-in-out;
        }

        .gallery-controls-previous::before{
            border: solid #000;
            border-width: 0 4px 4px 0;
            content: '';
            display: inline-block;
            height: 2.6vh;
            transform: rotate(135deg);
            width: 1.3vw;

        }

        .gallery-controls-previous:hover{
            margin-left: 9.8%;
        }


        .gallery-controls-next{
            position: relative;
            margin-right: 10%;
            transition: all 0.15s ease-in-out;
        }

        .gallery-controls-next::before{
            border: solid #000;
            border-width: 0 4px 4px 0;
            content:'';
            display: inline-block;
            height: 2.6vh;
            transform: rotate(-45deg);
            width: 1.3vw;
        }

        .gallery-controls-next:hover{
            margin-right: 9.8%;
        }

        .gallery-nav{
            bottom: -15px;
            display: flex;
            justify-content: center;
            list-style: none;
            padding: 0;
            position: absolute;
            width: 100%;
        }

        .gallery-nav li{
            background: #ccc;
            border-radius: 50%;
            height: 10px;
            margin: 0 16px;
            width: 10px;
        }

        .gallery-nav li.gallery-item-selected{
            background: #555;
        }

        .gallery {
            position: relative;
        }

        h1 {
            text-align: center;
            font-family: "Playfair Display", serif;
            padding: 0;
            margin: 0;
            font-size: 2vw;
            padding-top: 1%;
            color:#E7ECEF;
            -webkit-text-stroke-width: 0.5px;
            -webkit-text-stroke-color: black;
        }

        #description {
            font-family: "Playfair Display", serif;
            font-weight: 500;
            text-align: center;
            width: 100%;
            font-size: 2.5vw;
            margin-top:2%;
            background-color:lightgrey;
            opacity:0.8;
            border-radius:4vw;
            /* -webkit-text-stroke-width: 0.1px;
            -webkit-text-stroke-color: #E7ECEF; */
        }

        #news-date {
            font-family: "Playfair Display", serif;
            font-weight: 500;
            text-align: center;
            font-size: 2vw;
            background-color:lightgrey;
            width: 25%;
            border-radius:2vw;
            margin: auto;
            margin-bottom: .5%;
            opacity:0.7;
        }

        line {
            width: 50vw;
            height: 1.5px;
            background-color:white;
            border-radius:2vw;
        }

        #Aweather{
            width: 50%;
            height: 80%;
            margin-top:2%;
            background-color: white;
            border-radius: 4vw;
            display:grid;
            grid-template-rows:70% 30%; 
            box-shadow: 10px 10px 20px rgba(0, 0, 0, 1);
            opacity:0.8;
        }

        #picture-box{
            grid-area:first;
            width: auto;
            height: auto;
            border-radius: 4vw;
            background-size:80%;
            background-position:center;
            background-repeat:no-repeat;
            margin: 3%;
            background-color: #E7ECEF;
            box-shadow: 6px 6px 10px rgba(0, 0, 0, 0.7), 
            -3px -3px 15px rgba(230, 230, 230, 0.4), 
            inset -5px -5px 10px rgba(230, 230, 230, 0.4),
            inset 5px 5px 10px rgba(0, 0, 0, 0.5);
        }

        #weather_text{
            width: auto;    
            height: auto; 
            margin: 1%;
            display:flex;
            justify-content:center;
            align-items:center;
            grid-area:fourth;
            background-color: #E7ECEF;
            border-radius: 100px;
            font-family: "Holtwood One SC", serif;
            overflow:hidden;
            background-color: #E7ECEF;
            box-shadow: 6px 6px 10px rgba(0, 0, 0, 0.7), 
            -3px -3px 15px rgba(230, 230, 230, 0.4), 
            inset -5px -5px 10px rgba(230, 230, 230, 0.4),
            inset 5px 5px 10px rgba(0, 0, 0, 0.5);
        }

        #weather_text_content{
            font-size:2vw;
            white-space: nowrap;
            overflow-wrap: break-word;
        }

        #time-box {
            width: auto;
            height: auto;
            text-align: center;
            font-size: 2vw;
            font-weight: bold;
            background-color: #E7ECEF;
            border-radius: 5vw;
            grid-area:second;
            display: flex;
            justify-content:center;
            align-items:center;
            margin: 3%;
            background-color: #E7ECEF;
            box-shadow: 6px 6px 10px rgba(0, 0, 0, 0.7), 
            -3px -3px 15px rgba(230, 230, 230, 0.4), 
            inset -5px -5px 10px rgba(230, 230, 230, 0.4),
            inset 5px 5px 10px rgba(0, 0, 0, 0.5);
        }

        #temperature-box {
            width: auto;
            height: auto; /* Adjusted height to fit all elements */
            background-color: #E7ECEF;
            border-radius: 2vw; /* Reduced border-radius for better alignment */
            display: grid;
            align-items: center; /* Center align elements horizontally */
            padding: 5px; /* Add some padding for better spacing */
            box-sizing: border-box; /* Ensure padding and border are included in the total width and height */
            grid-area:third;
            margin: 4%;
            background-color: #E7ECEF;
            box-shadow: 6px 6px 10px rgba(0, 0, 0, 0.7), 
            -3px -3px 15px rgba(230, 230, 230, 0.4), 
            inset -5px -5px 10px rgba(230, 230, 230, 0.4),
            inset 5px 5px 10px rgba(0, 0, 0, 0.5);
        }

        #temperature {
            width: 100%; /* Take full width of the container */
            text-align: center; /* Center text inside the div */
            display:flex;
            justify-content: center; /* Center text horizontally */
            align-items: center; /* Center text vertically */
            margin-top: 1px;
            font-size: 5vw;
        
        }

        .temp-detail-container {
            display: flex;
            width: 100%;
            justify-content: space-evenly; /* Distribute space evenly between elements */
            margin-top: 10px; /* Space between Temperature and detail divs */
        }

        .temp-detail {
            width: auto; /* Adjust width as per content */
            text-align: center; /* Center text inside the div */
            justify-content: center;
            font-size: 2em;
        }

        .container-flex{
            width: 95%;
            height: 90%;
            justify-self: center;
            align-self: center;
            background-color: #E7ECEF;
            border-radius: 3vw;
            display: flex ;
            flex-direction: row;
            box-sizing: border-box;
            justify-content: space-evenly;
            align-items:center;
            background-color: #E7ECEF;
            box-shadow: 6px 6px 10px rgba(0, 0, 0, 0.7), 
            -3px -3px 15px rgba(230, 230, 230, 0.4), 
            inset -5px -5px 10px rgba(230, 230, 230, 0.4),
            inset 5px 5px 10px rgba(0, 0, 0, 0.5);
            
        }


        .variable {
            height: 60%;
            width: 30%;
            border-radius:20px;
            right: 40px;
            padding: 10px ;
            padding-top: 10px;
            flex-direction: column;
            box-sizing: border-box;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .tittle {
            width: 100%;
            text-align: center;
            padding: 5px;
            height: 100px; 
            box-sizing: border-box;
            margin-bottom: 10px;
            margin-top: 0;
            display: flex; /* Use flexbox */
            align-items: center; /* Center vertically */
            justify-content: center; /* Center horizontally */
            font-size: 15px;
        }

        .tittle .icon {
            width: 35px;
            height: 35px;
            margin-right: 5px;
            
        }

        .value {
            width: 100%;
            height: 180px;
            text-align: center;
            padding: 5px;
            box-sizing: border-box;
            font-size: 2em;

        }
    
        #top-actual {
            width: 100%;
            height: 100%;
            display:grid;
            grid-template:
            'first second'
            'first third'
            'fourth third';
            grid-template-columns: 50% 50%;
            grid-template-rows: 33% 33% 33%;

        }

        #bottomPart{
            width: 100%;
            height: 100dvh;
            display:flex;
            justify-content:start;
            align-items:start;
            flex-direction:column;
            background-image: url(picture/background4.jpg);
            background-size:100%;
        }

        #myVideo {
            position: absolute;
            left: 0;
            top: 0;
            width:100%;
            opacity: 1;
            transition: all 2.5s ease;
            z-index: -1;
        }


        #secondPart{
            width: 100%;
            height: 100dvh;
            display:flex;
            align-items:center;
            flex-direction:column;
            background-image: url(picture/background5.jpg);
            background-size:100%;
        }

        #weather-predict{
            width:100%;
            height:100%;
            display:flex;
            align-items:center;
            justify-content:center;
        }

        .predict-card{
            width:75%;
            height:80%;
            background-color:white;
            border-radius:1.5vw;
            margin:0 1%;
            grid-template:
            'date'
            'prediction'
            'variable';
            grid-template-rows: 10% 60% 25%;
            text-align:center;
            font-size:1.5vw;
            font-weight:bold;
            display:none;
            animation: slide 1s ease ; 
            opacity:.95;
            backdrop-filter: blur(50px);
        }
        @keyframes slide{
            0% {
                opacity: 0.5;
                transform: translateX(10%);
            }
            100% {
                opacity: .95;
                transform: translateX(0);
            }
        }

        .predict-image {
            background-color:lightcyan;
            width:100%;
            height:100%;
            display:block;
            margin:auto;
            border-radius:2vw;
            background-size:80%;
            background-position:center;
            background-repeat:no-repeat;
            box-shadow: 6px 6px 10px rgba(0, 0, 0, 0.7), 
            -3px -3px 15px rgba(230, 230, 230, 0.4), 
            inset -5px -5px 10px rgba(230, 230, 230, 0.4),
            inset 5px 5px 10px rgba(0, 0, 0, 0.5);
        }
        .predict-variable{
            display:flex;
            height:70%;
            width:100%;
            align-items:center;
            justify-content:center;
            margin:5% 0 3% 0;
            gap:10%;
        }

        .predicted-output{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            width: contain;
        }

        .output-items{
            width:20%; 
            height:100%; 
            margin:0 1%;
            display:grid;
            grid-template-rows: 15% 70% 15%;
            align-items:center;
        }

        .weather-predict-controller{
            font-weight:bold;
            font-size:10vw;
            display:block;
            margin:auto;
            color:white;
            cursor:pointer;
            transition:all .3s ease;
        }
        .weather-predict-controller:hover{
            color:darkgrey;
        }
    </style>
</head>
<body>

    <div id="upperPart">
        <video autoplay muted loop id="myVideo">
            <source src="picture\Background Video.mp4" type="video/mp4">
            Your browser does not support HTML5 video.
        </video>
        <h2>Current Weather</h2>

        <line></line>

        <div id = "Aweather">
            <div id="top-actual">
                <div id="picture-box">
                    <?php
                        date_default_timezone_set('Asia/Kuala_Lumpur');
                        $time = date("H");
                        if($time<18&&$time>6){
                            $flag = true;
                        }else{
                            $flag = false;
                        }
                        $day = ["picture/fog2.png", "picture/hazemorning.png", "picture/lightrainpassingmorning.png", "picture/light_rain_scattered.png", "picture/passing_cloud.png", "picture/rainpassingcloud.png", "picture/rainshowermorning.png", "picture/scatteredclouds.png", "picture/thunderstormpassingcloud.png", "picture/thunderstormscattereds.png", "picture/error.png"];
                        $night = ["picture/fog2.png", "picture/hazenight.png", "picture/lightrainpassingnight.png", "picture/light_rain_scattered.png", "picture/passing_cloud_night.png", "picture/rainpassingcloud.png", "picture/rainshowernight.png", "picture/scattered_cloud.png", "picture/thunderstormpassingcloud.png", "picture/thunderstormscattereds.png", "picture/error.png"];
                        echo "<script>console.log('$day[$weather_index]');</script>";
                        if ($flag){
                            echo "<script>document.getElementById('picture-box').style.backgroundImage='url($day[$weather_index])';</script>";
                        }else{
                            echo "<script>document.getElementById('picture-box').style.backgroundImage='url($night[$weather_index])';</script>";
                        }

                    ?>
                </div>

                <div id = "weather_text">
                    <span id="weather_text_content"></span>
                    <?php
                        echo "<script>document.getElementById('weather_text_content').innerHTML = '$Actual_Weather';</script>";
                    ?>
                </div>

                <div id = 'time-box'>
                    <script>
                        setInterval(function(){
                            var date = new Date();
                            var hours = date.getHours().toString().padStart(2, '0');
                            var minutes = date.getMinutes().toString().padStart(2, '0');
                            var seconds = date.getSeconds().toString().padStart(2, '0');
                            var current_time = hours + ":" + minutes + ":" + seconds;

                            document.getElementById("time-box").innerHTML = current_time;
                        }, 1000);
                        
                    </script>
                </div>

                <div id="temperature-box">
                    <div id = "temperature">
                        <div id = "lottie-animation"></div>               
                        <?php
                        echo $Actual_Temperature."°C"
                        ?>
                    </div>

                    <div class="temp-detail-container">
                        <div class="temp-detail">
                        <?php
                        echo "H: " . $Max_Temperature."°C";

                        ?>
                        </div>
                        <div class="temp-detail">
                        <?php
                        echo "L: ".$Min_Temperature."°C"
                        ?>
                        </div>
                    </div>
                </div>
            </div>
            
                    
            <div class = "container-flex">

                <div class = "variable">
                    <div class = "tittle">
                        <img src="picture/humidity0.png" alt= "Humidity Icon" class = "icon"> Humidity</div>
                    <div class = "value"><?php echo "\n".$Humidity."%"?></div>
                </div>

                
                <div class = "variable">
                    <div class = "tittle">
                    <img src="picture/barometer.png" alt= "barometer Icon" class = "icon">Barometer</div>
                    <div class = "value"><?php echo $Barometer."mbar"?></div>
                </div>

                
                <div class = "variable">
                    <div class = "tittle">
                    <img src="picture/windSpeed1.png" alt= "Wind Speed Icon" class = "icon">Wind Speed</div>
                    <div class = "value"><?php echo $Wind_Speed."km/h"?></div>
                </div>
                

            


            </div>

        </div>
    </div>

    <div id="secondPart">
        <h2>Weather Prediction</h2>

        <line></line>

        <div id="weather-predict">
            <span class="weather-predict-controller" onclick="plusSlides(-1)"><</span>
            <div class="predict-card">
                <?php
                    $currentDateTime = new DateTime();
                    $currentDateTime->modify('+1 hour');
                    $Time = $currentDateTime->format('g a');
                ?>
                <span>Time: <?php echo $Time  ?></span>
                <div class="predicted-output">
                <?php
                    $nextHour = $currentDateTime->format('H');
                    $nextDate = $currentDateTime->format('Y-m-d');
                    $query = "SELECT * FROM `weather status table` 
                                WHERE DATE(Date) = '$nextDate' 
                                AND HOUR(Time) = $nextHour
                                Limit 1";
                    $result = mysqli_query($connection, $query);
                    $weather_types = [
                        "Fog", "Haze", "Light rain passing clouds", "Light rain scattered clouds",
                        "Passing clouds", "Rain passing clouds", "Rain showers scattered clouds",
                        "Scattered clouds", "Thunderstorms passing clouds", "Thunderstorms scattered clouds"
                    ];
                    if(mysqli_num_rows($result) == 1) {
                        $row = mysqli_fetch_assoc($result);
                        date_default_timezone_set('Asia/Kuala_Lumpur');
                        $time = date("H");
                        if($time<18&&$time>6){
                            $flag = true;
                        }else{
                            $flag = false;
                        }
                        $weather_status_array = explode('|', $row['Predicted_Weather']);
                        $Temperature = $row['Actual_Temperature'];
                        $Humidity = $row['Humidity'];
                        $Barometer = $row['Barometer'];
                        $Wind_Speed = $row['Wind_Speed'];
                        // $highest_value = max($weather_status_array);
                        // $weather_index = array_search($highest_value, $weather_status_array);
                        
                        
                ?>
                

                
                    <?php
                        for($i = 0; $i<count($weather_status_array);$i++){
                            if($weather_status_array[$i] != 0.0){
                                $Actual_Weather = isset($weather_types[$i]) ? $weather_types[$i] : 'Unknown';
                                $Probility = $weather_status_array[$i]*100;
                    ?>
                    <div class="output-items">
                        <span><?php echo $Actual_Weather  ?></span>
                        <div class="predict-image">
                            <?php
                                if ($flag){
                                    echo "<script>document.getElementsByClassName('predict-image')[document.getElementsByClassName('predict-image').length-1].style.backgroundImage='url($day[$i])';</script>";
                                }else{
                                    echo "<script>document.getElementsByClassName('predict-image')[document.getElementsByClassName('predict-image').length-1].style.backgroundImage='url($night[$i])';</script>";
                                }
                            ?>
                        </div>
                        <span ><?php echo $Probility  ?>%</span>
                    </div>
                    <?php
                        }}}
                    else{
                        $Actual_Weather = "No Data";
                        $Temperature = "No Data";
                        $Humidity = "No Data";
                        $Barometer = "No Data";
                        $Wind_Speed = "No Data";
                        $weather_index = 10;
                        $Probility = "No Data";
                    ?>
                    <div class="output-items">
                        <span><?php echo $Actual_Weather  ?></span>
                        <div class="predict-image">
                            <?php
                                if ($flag){
                                    echo "<script>document.getElementsByClassName('predict-image')[0].style.backgroundImage='url($day[$weather_index])';</script>";
                                }else{
                                    echo "<script>document.getElementsByClassName('predict-image')[0].style.backgroundImage='url($night[$weather_index])';</script>";
                                }
                            ?>
                        </div>
                        <span><?php echo $Probility  ?></span>
                    </div>
                    <?php
                    }
                    ?>
                        
                    
                </div>

                <div class="predict-variable">
                    
                    <span>Temperature: <?php echo $Temperature  ?>°C</span>
                    <span>Humidity: <?php echo $Humidity  ?>%</span>
                    <span>Barometer: <?php echo $Barometer  ?>mbar</span>
                    <span>Wind Speed: <?php echo $Wind_Speed  ?>km/h</span>
                </div>
            </div>
            <div class="predict-card">
                <?php
                    $currentDateTime = new DateTime();
                    $currentDateTime->modify('+2 hour');
                    $Time = $currentDateTime->format('g a');
                ?>
                <span>Time: <?php echo $Time  ?></span>
                <div class="predicted-output">
                <?php
                    $nextHour = $currentDateTime->format('H');
                    $nextDate = $currentDateTime->format('Y-m-d');
                    $query = "SELECT * FROM `weather status table` 
                                WHERE DATE(Date) = '$nextDate' 
                                AND HOUR(Time) = $nextHour
                                Limit 1";
                    $result = mysqli_query($connection, $query);
                    $weather_types = [
                        "Fog", "Haze", "Light rain passing clouds", "Light rain scattered clouds",
                        "Passing clouds", "Rain passing clouds", "Rain showers scattered clouds",
                        "Scattered clouds", "Thunderstorms passing clouds", "Thunderstorms scattered clouds"
                    ];
                    if(mysqli_num_rows($result) == 1) {
                        $row = mysqli_fetch_assoc($result);
                        date_default_timezone_set('Asia/Kuala_Lumpur');
                        $time = date("H");
                        if($time<18&&$time>6){
                            $flag = true;
                        }else{
                            $flag = false;
                        }
                        $weather_status_array = explode('|', $row['Predicted_Weather']);
                        $Temperature = $row['Actual_Temperature'];
                        $Humidity = $row['Humidity'];
                        $Barometer = $row['Barometer'];
                        $Wind_Speed = $row['Wind_Speed'];
                        // $highest_value = max($weather_status_array);
                        // $weather_index = array_search($highest_value, $weather_status_array);
                        
                        
                ?>
                

                
                    <?php
                        for($i = 0; $i<count($weather_status_array);$i++){
                            if($weather_status_array[$i] != 0.0){
                                $Actual_Weather = isset($weather_types[$i]) ? $weather_types[$i] : 'Unknown';
                                $Probility = $weather_status_array[$i]*100;
                    ?>
                    <div class="output-items">
                        <span><?php echo $Actual_Weather  ?></span>
                        <div class="predict-image">
                            <?php
                                if ($flag){
                                    echo "<script>document.getElementsByClassName('predict-image')[document.getElementsByClassName('predict-image').length-1].style.backgroundImage='url($day[$i])';</script>";
                                }else{
                                    echo "<script>document.getElementsByClassName('predict-image')[document.getElementsByClassName('predict-image').length-1].style.backgroundImage='url($night[$i])';</script>";
                                }
                            ?>
                        </div>
                        <span ><?php echo $Probility  ?>%</span>
                    </div>
                    <?php
                        }}}
                    else{
                        $Actual_Weather = "No Data";
                        $Temperature = "No Data";
                        $Humidity = "No Data";
                        $Barometer = "No Data";
                        $Wind_Speed = "No Data";
                        $weather_index = 10;
                        $Probility = "No Data";
                    ?>
                    <div class="output-items">
                        <span><?php echo $Actual_Weather  ?></span>
                        <div class="predict-image">
                            <?php
                                if ($flag){
                                    echo "<script>document.getElementsByClassName('predict-image')[document.getElementsByClassName('predict-image').length-1].style.backgroundImage='url($day[$weather_index])';</script>";
                                }else{
                                    echo "<script>document.getElementsByClassName('predict-image')[document.getElementsByClassName('predict-image').length-1].style.backgroundImage='url($night[$weather_index])';</script>";
                                }
                            ?>
                        </div>
                        <span><?php echo $Probility  ?></span>
                    </div>
                    <?php
                    }
                    ?>
                        
                    
                </div>

                <div class="predict-variable">
                    
                    <span>Temperature: <?php echo $Temperature  ?>°C</span>
                    <span>Humidity: <?php echo $Humidity  ?>%</span>
                    <span>Barometer: <?php echo $Barometer  ?>mbar</span>
                    <span>Wind Speed: <?php echo $Wind_Speed  ?>km/h</span>
                </div>
            </div>
            <div class="predict-card">
                <?php
                    $currentDateTime = new DateTime();
                    $currentDateTime->modify('+3 hour');
                    $Time = $currentDateTime->format('g a');
                ?>
                <span>Time: <?php echo $Time  ?></span>
                <div class="predicted-output">
                <?php
                    $nextHour = $currentDateTime->format('H');
                    $nextDate = $currentDateTime->format('Y-m-d');
                    $query = "SELECT * FROM `weather status table` 
                                WHERE DATE(Date) = '$nextDate' 
                                AND HOUR(Time) = $nextHour
                                Limit 1";
                    $result = mysqli_query($connection, $query);
                    $weather_types = [
                        "Fog", "Haze", "Light rain passing clouds", "Light rain scattered clouds",
                        "Passing clouds", "Rain passing clouds", "Rain showers scattered clouds",
                        "Scattered clouds", "Thunderstorms passing clouds", "Thunderstorms scattered clouds"
                    ];
                    if(mysqli_num_rows($result) == 1) {
                        $row = mysqli_fetch_assoc($result);
                        date_default_timezone_set('Asia/Kuala_Lumpur');
                        $time = date("H");
                        if($time<18&&$time>6){
                            $flag = true;
                        }else{
                            $flag = false;
                        }
                        $weather_status_array = explode('|', $row['Predicted_Weather']);
                        $Temperature = $row['Actual_Temperature'];
                        $Humidity = $row['Humidity'];
                        $Barometer = $row['Barometer'];
                        $Wind_Speed = $row['Wind_Speed'];
                        // $highest_value = max($weather_status_array);
                        // $weather_index = array_search($highest_value, $weather_status_array);
                        
                        
                ?>
                

                
                    <?php
                        for($i = 0; $i<count($weather_status_array);$i++){
                            if($weather_status_array[$i] != 0.0){
                                $Actual_Weather = isset($weather_types[$i]) ? $weather_types[$i] : 'Unknown';
                                $Probility = $weather_status_array[$i]*100;
                    ?>
                    <div class="output-items">
                        <span><?php echo $Actual_Weather  ?></span>
                        <div class="predict-image">
                            <?php
                                if ($flag){
                                    echo "<script>document.getElementsByClassName('predict-image')[document.getElementsByClassName('predict-image').length-1].style.backgroundImage='url($day[$i])';</script>";
                                }else{
                                    echo "<script>document.getElementsByClassName('predict-image')[document.getElementsByClassName('predict-image').length-1].style.backgroundImage='url($night[$i])';</script>";
                                }
                            ?>
                        </div>
                        <span ><?php echo $Probility  ?>%</span>
                    </div>
                    <?php
                        }}}
                    else{
                        $Actual_Weather = "No Data";
                        $Temperature = "No Data";
                        $Humidity = "No Data";
                        $Barometer = "No Data";
                        $Wind_Speed = "No Data";
                        $weather_index = 10;
                        $Probility = "No Data";
                    ?>
                    <div class="output-items">
                        <span><?php echo $Actual_Weather  ?></span>
                        <div class="predict-image">
                            <?php
                                if ($flag){
                                    echo "<script>document.getElementsByClassName('predict-image')[document.getElementsByClassName('predict-image').length-1].style.backgroundImage='url($day[$weather_index])';</script>";
                                }else{
                                    echo "<script>document.getElementsByClassName('predict-image')[document.getElementsByClassName('predict-image').length-1].style.backgroundImage='url($night[$weather_index])';</script>";
                                }
                            ?>
                        </div>
                        <span><?php echo $Probility  ?></span>
                    </div>
                    <?php
                    }
                    ?>
                        
                    
                </div>

                <div class="predict-variable">
                    
                    <span>Temperature: <?php echo $Temperature  ?>°C</span>
                    <span>Humidity: <?php echo $Humidity  ?>%</span>
                    <span>Barometer: <?php echo $Barometer  ?>mbar</span>
                    <span>Wind Speed: <?php echo $Wind_Speed  ?>km/h</span>
                </div>
            </div>
            <div class="predict-card">
                <?php
                    $currentDateTime = new DateTime();
                    $currentDateTime->modify('+4 hour');
                    $Time = $currentDateTime->format('g a');
                ?>
                <span>Time: <?php echo $Time  ?></span>
                <div class="predicted-output">
                <?php
                    $nextHour = $currentDateTime->format('H');
                    $nextDate = $currentDateTime->format('Y-m-d');
                    $query = "SELECT * FROM `weather status table` 
                                WHERE DATE(Date) = '$nextDate' 
                                AND HOUR(Time) = $nextHour
                                Limit 1";
                    $result = mysqli_query($connection, $query);
                    $weather_types = [
                        "Fog", "Haze", "Light rain passing clouds", "Light rain scattered clouds",
                        "Passing clouds", "Rain passing clouds", "Rain showers scattered clouds",
                        "Scattered clouds", "Thunderstorms passing clouds", "Thunderstorms scattered clouds"
                    ];
                    if(mysqli_num_rows($result) == 1) {
                        $row = mysqli_fetch_assoc($result);
                        date_default_timezone_set('Asia/Kuala_Lumpur');
                        $time = date("H");
                        if($time<18&&$time>6){
                            $flag = true;
                        }else{
                            $flag = false;
                        }
                        $weather_status_array = explode('|', $row['Predicted_Weather']);
                        $Temperature = $row['Actual_Temperature'];
                        $Humidity = $row['Humidity'];
                        $Barometer = $row['Barometer'];
                        $Wind_Speed = $row['Wind_Speed'];
                        // $highest_value = max($weather_status_array);
                        // $weather_index = array_search($highest_value, $weather_status_array);
                        
                        
                ?>
                

                
                    <?php
                        for($i = 0; $i<count($weather_status_array);$i++){
                            if($weather_status_array[$i] != 0.0){
                                $Actual_Weather = isset($weather_types[$i]) ? $weather_types[$i] : 'Unknown';
                                $Probility = $weather_status_array[$i]*100;
                    ?>
                    <div class="output-items">
                        <span><?php echo $Actual_Weather  ?></span>
                        <div class="predict-image">
                            <?php
                                if ($flag){
                                    echo "<script>document.getElementsByClassName('predict-image')[document.getElementsByClassName('predict-image').length-1].style.backgroundImage='url($day[$i])';</script>";
                                }else{
                                    echo "<script>document.getElementsByClassName('predict-image')[document.getElementsByClassName('predict-image').length-1].style.backgroundImage='url($night[$i])';</script>";
                                }
                            ?>
                        </div>
                        <span ><?php echo $Probility  ?>%</span>
                    </div>
                    <?php
                        }}}
                    else{
                        $Actual_Weather = "No Data";
                        $Temperature = "No Data";
                        $Humidity = "No Data";
                        $Barometer = "No Data";
                        $Wind_Speed = "No Data";
                        $weather_index = 10;
                        $Probility = "No Data";
                    ?>
                    <div class="output-items">
                        <span><?php echo $Actual_Weather  ?></span>
                        <div class="predict-image">
                            <?php
                                if ($flag){
                                    echo "<script>document.getElementsByClassName('predict-image')[document.getElementsByClassName('predict-image').length-1].style.backgroundImage='url($day[$weather_index])';</script>";
                                }else{
                                    echo "<script>document.getElementsByClassName('predict-image')[document.getElementsByClassName('predict-image').length-1].style.backgroundImage='url($night[$weather_index])';</script>";
                                }
                            ?>
                        </div>
                        <span><?php echo $Probility  ?></span>
                    </div>
                    <?php
                    }
                    ?>
                        
                    
                </div>

                <div class="predict-variable">
                    
                    <span>Temperature: <?php echo $Temperature  ?>°C</span>
                    <span>Humidity: <?php echo $Humidity  ?>%</span>
                    <span>Barometer: <?php echo $Barometer  ?>mbar</span>
                    <span>Wind Speed: <?php echo $Wind_Speed  ?>km/h</span>
                </div>
            </div>
            <div class="predict-card">
                <?php
                    $currentDateTime = new DateTime();
                    $currentDateTime->modify('+5 hour');
                    $Time = $currentDateTime->format('g a');
                ?>
                <span>Time: <?php echo $Time  ?></span>
                <div class="predicted-output">
                <?php
                    $nextHour = $currentDateTime->format('H');
                    $nextDate = $currentDateTime->format('Y-m-d');
                    $query = "SELECT * FROM `weather status table` 
                                WHERE DATE(Date) = '$nextDate' 
                                AND HOUR(Time) = $nextHour
                                Limit 1";
                    $result = mysqli_query($connection, $query);
                    $weather_types = [
                        "Fog", "Haze", "Light rain passing clouds", "Light rain scattered clouds",
                        "Passing clouds", "Rain passing clouds", "Rain showers scattered clouds",
                        "Scattered clouds", "Thunderstorms passing clouds", "Thunderstorms scattered clouds"
                    ];
                    if(mysqli_num_rows($result) == 1) {
                        $row = mysqli_fetch_assoc($result);
                        date_default_timezone_set('Asia/Kuala_Lumpur');
                        $time = date("H");
                        if($time<18&&$time>6){
                            $flag = true;
                        }else{
                            $flag = false;
                        }
                        $weather_status_array = explode('|', $row['Predicted_Weather']);
                        $Temperature = $row['Actual_Temperature'];
                        $Humidity = $row['Humidity'];
                        $Barometer = $row['Barometer'];
                        $Wind_Speed = $row['Wind_Speed'];
                        // $highest_value = max($weather_status_array);
                        // $weather_index = array_search($highest_value, $weather_status_array);
                        
                        
                ?>
                

                
                    <?php
                        for($i = 0; $i<count($weather_status_array);$i++){
                            if($weather_status_array[$i] != 0.0){
                                $Actual_Weather = isset($weather_types[$i]) ? $weather_types[$i] : 'Unknown';
                                $Probility = $weather_status_array[$i]*100;
                    ?>
                    <div class="output-items">
                        <span><?php echo $Actual_Weather  ?></span>
                        <div class="predict-image">
                            <?php
                                if ($flag){
                                    echo "<script>document.getElementsByClassName('predict-image')[document.getElementsByClassName('predict-image').length-1].style.backgroundImage='url($day[$i])';</script>";
                                }else{
                                    echo "<script>document.getElementsByClassName('predict-image')[document.getElementsByClassName('predict-image').length-1].style.backgroundImage='url($night[$i])';</script>";
                                }
                            ?>
                        </div>
                        <span ><?php echo $Probility  ?>%</span>
                    </div>
                    <?php
                        }}}
                    else{
                        $Actual_Weather = "No Data";
                        $Temperature = "No Data";
                        $Humidity = "No Data";
                        $Barometer = "No Data";
                        $Wind_Speed = "No Data";
                        $weather_index = 10;
                        $Probility = "No Data";
                    ?>
                    <div class="output-items">
                        <span><?php echo $Actual_Weather  ?></span>
                        <div class="predict-image">
                            <?php
                                if ($flag){
                                    echo "<script>document.getElementsByClassName('predict-image')[document.getElementsByClassName('predict-image').length-1].style.backgroundImage='url($day[$weather_index])';</script>";
                                }else{
                                    echo "<script>document.getElementsByClassName('predict-image')[document.getElementsByClassName('predict-image').length-1].style.backgroundImage='url($night[$weather_index])';</script>";
                                }
                            ?>
                        </div>
                        <span><?php echo $Probility  ?></span>
                    </div>
                    <?php
                    }
                    ?>
                        
                    
                </div>

                <div class="predict-variable">
                    
                    <span>Temperature: <?php echo $Temperature  ?>°C</span>
                    <span>Humidity: <?php echo $Humidity  ?>%</span>
                    <span>Barometer: <?php echo $Barometer  ?>mbar</span>
                    <span>Wind Speed: <?php echo $Wind_Speed  ?>km/h</span>
                </div>
            </div>
            <span class="weather-predict-controller" onclick="plusSlides(1)">></span>


        </div>
    </div>

    <div id="bottomPart">
        <div class="gallery">
            <h1 id="newsTitle">Title Here</h1>
            <div class="gallery-container">
                <a class="a"><img class="gallery-item gallery-item-1" data-index="1"></a>
                <a class="a"><img class="gallery-item gallery-item-2" data-index="2"></a>
                <a class="a"><img class="gallery-item gallery-item-3" data-index="3"></a>
            </div>
            <div id="news-date"></div>
            <div id="description">Description Here</div>
            <div class="gallery-controls"></div>
        </div>
    </div>



</body>
</html>

<script>
    let slideIndex = 1;
    showSlides(slideIndex);

    function plusSlides(n) {
        showSlides(slideIndex += n);
    }

    function showSlides(n) {
        let i;
        const slides = document.getElementsByClassName("predict-card");
        if (n > slides.length) {
            slideIndex = 1;
            
        }

        if (n < 1) {
            slideIndex = slides.length;
            
        }

        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
            
        }
        slides[slideIndex - 1].style.display = "grid";
    }
    const galleryContainer = document.querySelector('.gallery-container');
    const galleryControlContainer = document.querySelector('.gallery-controls');
    const galleryControls = ['previous','next']; // Corrected typo here
    const galleryItems = document.querySelectorAll('.gallery-item');

    var url = 'https://newsapi.org/v2/everything?' +
            'q=Weather in Kuala Lumpur OR Thunderstorm in Kuala Lumpur OR Rain in Kuala Lumpur OR Weather&' +
            'pageSize=3&' +
            'sortBy=publishedAt,relevancy&' +
            'apiKey=2a55ab3ebe754a1391c2b7b222eab77a';
            
    var req = new Request(url);


    async function fetchData() {
        try {
            let response = await fetch(req);
            let data = await response.json();
            console.log(data);

            articles = data.articles; 
            const exampleCarousel = new Carousel(galleryContainer, galleryItems, galleryControls, articles);
            exampleCarousel.setControls();
            exampleCarousel.useControls();
            let elements = document.getElementsByClassName("gallery-item gallery-item-1");
            for (let i = 0; i < elements.length; i++) {
                elements[i].src = articles[0].urlToImage; 
            }

            elements = document.getElementsByClassName("gallery-item gallery-item-2");
            for (let i = 0; i < elements.length; i++) {
                elements[i].src = articles[1].urlToImage; 
            }

            elements = document.getElementsByClassName("gallery-item gallery-item-3");
            for (let i = 0; i < elements.length; i++) {
                elements[i].src = articles[2].urlToImage; 
            }

            document.getElementById("newsTitle").innerHTML = articles[1].title;
            document.getElementById("description").innerHTML = articles[1].description;
            document.getElementById("news-date").innerHTML = articles[1].publishedAt;

            let as = document.getElementsByClassName("a");

            for (let i = 0; i < as.length; i++) {
                as[i].href = articles[i].url; 
            }

        } catch (error) {
            console.error('Error fetching data:', error);
        }
    }

    fetchData(); // Call the fetchData function to fetch the articles




    class Carousel {
        constructor(container, items, controls, passArticles) {
            this.carouselContainer = container;
            this.carouselControls = controls; // Corrected variable name here
            this.carouselArray = [...items];
            this.articles = passArticles;
        }

        updateGallery() {
            this.carouselArray.forEach(el => {
                el.classList.remove('gallery-item-1');
                el.classList.remove('gallery-item-2');
                el.classList.remove('gallery-item-3');
            });

            this.carouselArray.slice(0, 3).forEach((el, i) => {
                el.classList.add(`gallery-item-${i + 1}`);
            });
        }

        setCurrentState(direction) {
            if (direction.className == 'gallery-controls-previous') { // Changed condition here
                this.carouselArray.unshift(this.carouselArray.pop());
            } else {
                this.carouselArray.push(this.carouselArray.shift());
            }
            this.updateGallery();
            document.getElementById("newsTitle").innerHTML = this.articles[this.carouselArray[1].getAttribute('data-index') - 1].title;
            document.getElementById("description").innerHTML = this.articles[this.carouselArray[1].getAttribute('data-index') - 1].description;
            document.getElementById("news-date").innerHTML = this.articles[this.carouselArray[1].getAttribute('data-index') - 1].publishedAt;
        }

        setControls() {
            this.carouselControls.forEach(control => {
                galleryControlContainer.appendChild(document.createElement('button')).className = `gallery-controls-${control}`;
                document.querySelector(`.gallery-controls-${control}`).innerText = control;
            });
        }

        useControls(){
            const triggers = [...galleryControlContainer.childNodes]; // Corrected variable name here
            triggers.forEach(control => {
                control.addEventListener('click', e => {
                    e.preventDefault();
                    this.setCurrentState(control);
                });
            });
        }
    }
</script>