<?php
    include 'dbConn.php';
    include 'navibar.php';
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


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historical</title>
    <style>
        #thirdPart{
            width: 100%;
            height: 100dvh;
            display:flex;
            justify-content:center;
            align-items:center;
            flex-direction:column;
            background-image: url(picture/background7.jpg);
            background-size:100%;
        }

        #date-time{
            display: inline-block;
            justify-content: center;
            text-align: center;
            width: 100%;
        }
        #date{
            font-size: 2vw;
            margin-left: .5vw;
            margin-right:15vw;
            font-family: "Playfair Display", serif;
            font-weight: 500;
            text-align: center;
            font-size: 2vw;
            background-color:lightgrey;
            width: 20%;
            border-radius:2vw;
            margin-bottom: .5%;
            opacity:0.7;
        }
        #time{
            font-size: 2vw;
            background-color:lightgrey;
            border-radius:2vw;
            width: 15vw;
            opacity:0.8;
            height:6vh;
            margin-top:0vh;
        }
        #date-time label{
            font-size: 4vw;
            color:white;
            -webkit-text-stroke-width: 0.3px;
            -webkit-text-stroke-color: black;
        }

        #formContainer{
            width: 100%;
            display: flex;
            justify-content: center;
            text-align: center;
            margin-bottom:2%;
        }

        #output-box{
            width:85%;
            height:80%;
            background-color:#E7ECEF;
            border-radius:2vw;
            display:grid;
            grid-template:
            'top'
            'middle'
            'bottom';
            grid-template-rows: 20% 60% 20%;
            opacity:.9;
        }

        #output-header{
            width:100%;
            height:100%;
            display:flex;
            justify-content:center;
            align-items:center;
            gap:30%;
            font-size:3.5vw;
            grid-area:top;
        }

        #output-middle{
            width:100%;
            height:100%;
            display:grid;
            align-items:center;
            grid-area: middle;
            grid-template:
            'title'
            'picture';
            grid-template-rows:15% 85%;
        }

        #output-middle span {
            font-size:3vw;
            font-weight:bold;   
            width:100%;
            grid-area:title;
            text-align:center;
        }

        #output-image{
            background-color:white;
            width:30%;
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

        #output-bottom{
            grid-area:bottom;
            height:100%;
            width:100%;
            display:flex;
            justify-content:center;
            align-items:center;
            gap:6%;
            font-size:2vw;
        }
    </style>
</head>
<body>
    <div id="thirdPart">
            <?php
                if($_SERVER['REQUEST_METHOD']==='POST'){
                    echo "<script>console.log('ok');</script>";
                    $_SESSION['date'] = $_POST['date'];
                    $_SESSION['time'] = $_POST['time'];
                }
                if(isset($_SESSION['date'])){
                    $date = $_SESSION['date'];
                    $time = $_SESSION['time'];
                    $sessionHour = date('H', strtotime($time));
                    echo "<script>console.log('$date');</script>";
                    echo "<script>console.log('$time');</script>";

                    $sql = "SELECT Actual_Weather, Date, Time, Max_Temperature, Min_Temperature, Humidity, Barometer, Wind_Speed, Actual_Temperature 
                    FROM `weather status table` 
                    WHERE Date = '$date' 
                    AND HOUR(Time) = '$sessionHour'
                    ORDER BY Date DESC 
                    LIMIT 1";
                    unset($_SESSION['date']);
                    unset($_SESSION['time']);

                }else{
                    $sql = "SELECT Actual_Weather, Date, Time, Max_Temperature, Min_Temperature, Humidity, Barometer, Wind_Speed, Actual_Temperature 
                    FROM `weather status table` 
                    WHERE DATE(Date) = CURDATE() 
                    AND HOUR(Time) = HOUR(NOW()) 
                    ORDER BY Date DESC 
                    LIMIT 1";
                }
                
            
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
                        $Date = $row['Date'];
                        $Time = $row['Time'];
                        
                        
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
                }else{
                    $Actual_Weather = "no data";
                    $Max_Temperature = "no data";
                    $Min_Temperature = "no data";
                    $Humidity = "no data";
                    $Barometer = "no data";
                    $Wind_Speed = "no data";
                    $Actual_Temperature = "no data";
                    $Date = "no data";
                    $Time = "no data";
                    $Actual_Weather = "no data";
                    $weather_index = 10;
                }
            
                $connection->close();
                date_default_timezone_set('Asia/Kuala_Lumpur');
                $time = date("H");
                if($time<18&&$time>6){
                    $flag = true;
                }else{
                    $flag = false;
                }
                
                
            ?>
            <div id="formContainer">
                <form id="date-time" method="post">
                    <label for="date">Date:</label>
                    <input type="date" id="date" name="date" required onchange="checkSubmit()">
                    <label for="time" >Time:</label>
                    <input type="time" id="time" name="time" required onchange="checkSubmit()">
                    <input type="submit" value="submit" style= "display: none;">
                </form>
            </div>

            <div id="output-box">
                <div id="output-header">
                    <span>Date: <?php echo $Date; ?></span>
                    <span>Time: <?php echo date('H:m:s', strtotime($Time));  ?></span>
                </div>

                <div id="output-middle">
                    <span id="output-title"><?php echo "<script>document.getElementById('output-title').innerHTML = '$Actual_Weather';</script>"  ?></span>
                    <div id="output-image">
                        <?php
                            if ($flag){
                                echo "<script>document.getElementById('output-image').style.backgroundImage='url($day[$weather_index])';</script>";
                            }else{
                                echo "<script>document.getElementById('output-image').style.backgroundImage='url($night[$weather_index])';</script>";
                            }
                        ?>
                    </div>
                </div>

                <div id="output-bottom">
                    <span><b>Barometer:</b><?php echo $Barometer;  ?>mbar</span>
                    <span><b>Humidity:</b><?php echo $Humidity;  ?>%</span>
                    <span><b>Wind Speed:</b><?php echo $Wind_Speed;  ?>km/h</span>
                    <span><b>Temperature:</b><?php echo $Actual_Temperature;  ?>°C</span>
                </div>
            </div>

        </div>
    </body>
</html>
<script>
    function checkSubmit(){
        const date = document.getElementById('date').value;
        const time = document.getElementById('time').value;
        if(date!="" && time != ""){
            document.getElementById('date-time').submit();
        }
    }
</script>