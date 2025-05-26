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

$connection->close();

// echo json_encode($data);
?>






<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Holtwood+One+SC&display=swap" rel="stylesheet">
    <style> 

    *{
        padding: 0;
        margin: 0;
    }
    body{
    background-color: palegoldenrod;
}


#Aweather{
    width: 50%;
    height: 90%;
    margin-left: 70px;
    margin-top: 10px;
    background-color: #6096BA;
    border-radius: 4vw;
    display:grid;
    grid-template-rows:70% 30%; 
    box-shadow: 10px 10px 20px rgba(0, 0, 0, 1);
}

#picture-box{
    grid-area:first;
    width: auto;
    height: auto;
    border-radius: 4vw;
    background-size:90%;
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
    font-size:100%;
    white-space: nowrap;
}

#time-box {
    width: auto;
    height: auto;
    text-align: center;
    font-size: 4vw;
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
    font-size: 7vw;
 
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
    



 </style>
</head>

<body>
    <div>
        <h1>Actual WeatherForecast</h1>
    </div>

    <div id = "Aweather">
        <div id="top-actual">
            <div id="picture-box">
                <?php
                    date_default_timezone_set('Asia/Kuala_Lumpur');
                    $time = date("H");
                    if($time<18){
                        $flag = true;
                    }else{
                        $flag = false;
                    }
                    $day = ["picture/fog2.png", "picture/hazemorning.png", "picture/lightrainpassingmorning.png", "picture/ligh_rain_scattered.png", "picture/passing_cloud.png", "picture/rainpassingcould.png", "picture/rainshowermorning.png", "picture/scatteredcoulds.png", "picture/thunderstormpassingcould.png", "picture/thunderstormscattereds.png"];
                    $night = ["picture/fog2.png", "picture/hazenight.png", "picture/lightrainpassingnight.png", "picture/ligh_rain_scattered.png", "picture/passing_cloud_night.png", "picture/rainpassingcould.png", "picture/rainshowernight.png", "picture/scattered_could.png", "picture/thunderstormpassingcould.png", "picture/thunderstormscattereds.png"];
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


    


    
</body>
</html>

