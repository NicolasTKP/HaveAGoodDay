<?php
    include 'dbConn.php';
    include 'navibar.php';
    $month = date("F");
    $nmonth = $nmonth = date('m',strtotime($month));
    if($_SERVER['REQUEST_METHOD']==='POST'){
        $_SESSION['month']=$_POST['hidden'];
        header('Location: dataAnalyst.php');
    }

    if (isset($_SESSION['month'])){
        $month = $_SESSION['month'];
        $nmonth = date('m',strtotime($month));
    } else {
        $_SESSION['month'] = $month;
        // header('Location: dataAnalyst.php');
    }
    
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            background-size: 100vw 100%;
            background-repeat: no-repeat;
            
        }
        .container {
            display: grid; /*staright*/
            justify-content: center;
        }
        .section {
/*             
            border: 1px solid #ddd;
            border-radius: 8px; */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin: 10px;
            padding: 10px;
            
            max-width: 100%;
            text-align: center;
            perspective: 1000px;
           
        }
        .section h2 {
            margin-bottom: 0;
            font-size: 30px;
            color: rgba(39,23,102,0.8);
            font-family: "Papyrus";
            animation: bounce 0.8s infinite alternate;
        }
       
        .month-dropdown-container {
            position: absolute;
            top: 20px;
            right: 20px;
        }
        .month-dropdown {
            padding: 10px;
            width: 200px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .generate-report-button {
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .generate-report-button:hover {
            background-color: #45a049;
        }

        /* Style the video element */
        #backgroundVideo {
            position: fixed;
            right: 0;
            bottom: 0;
            min-width: 100%;
            min-height: 100%;
            z-index: -1;
            
        }
        /* #loader {
             margin: auto;

            border: 0.5vw solid white;
            border-radius: 50%;
            border-top: 0.5vw solid black;
            width: 6.5dvw;
            height: 6.5dvw;
            -webkit-animation: spin 2s linear infinite; Safari 
            animation: spin 2s linear infinite;
            background-color: #E7ECEF;
            position: relative;
            display: block;
        } 
        #submit{
            display: block;
            position: absolute;
            opacity: 0;
            cursor: default;
        } */
        .card {
            width: 40dvw;
            height: 200px;
            position: relative;
            transform-style: preserve-3d; /* Enable 3D transformations */
            transform: rotateY(0deg); /* Start facing front */
            transition: transform 0.6s; /* Smooth transition for the flip */
            
        }

        .card-front, .card-back {
            width: 100%;
            height: 100%;
            position: absolute;
            backface-visibility: hidden; /* Hide the back face */
            display: flex;
            justify-content: center;
            align-items: center;
            border: 1px solid #ccc;
            border-radius: 10px;
            background: white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            padding: 20px;
            box-sizing: border-box;
        }

        /* Styling for the Front Side */
        .card-front {
            background:#e8f5e9;;
        }

        /* Styling for the Back Side */
        .card-back {
            background:#284D78;
            transform: rotateY(180deg); /* Rotate the back side by 180 degrees */
            display:grid;
        }

        /* Hover Effect */
        .section:hover .card {
            transform: rotateY(180deg); /* Flip the card on hover */
            
        }
        .fas{
            color:#3DF29B;
            margin-bottom: 0;
            font-size: 60px;
            animation: bounce 0.8s infinite alternate;
        }
        p {
            color: white;
            font-family: Lucida, Monospace;
            font-size: 20px;
            margin: 20px;
            
        }
        @keyframes bounce {
            0% {
            transform: translateY(0);
            }
            100% {
            transform: translateY(-20px);
            }
        }
        option{
            font-family: Times, serif;
            font-size:20px;
        }
    </style>
    
</head>
<body>
    <video autoplay muted loop id="backgroundVideo" src="picture\854541-hd_1280_720_60fps.mp4"></video>

    <!-- <div id="loader"></div> -->
    <form id="myForm" action="" method="post">
        <input type="hidden" name="hidden" value="" id="hidden">

        <input type="submit" value="submit" style= "display: none;">
    </form>
    
    <div class = "container" >
        <br>
        <br>
        <br>
        
        <div class="month-dropdown-container">
            <select class="month-dropdown" id="month-select" onchange="getMonth(this)">
                <option><?php echo $month?></option>
                <option value="January" >January</option>
                <option value="February">February</option>
                <option value="March">March</option>
                <option value="April">April</option>
                <option value="May">May</option>
                <option value="June">June</option>
                <option value="July">July</option>
                <option value="August">August</option>
                <option value="September">September</option>
                <option value="October">October</option>
                <option value="November">November</option>
                <option value="December">December</option>
            </select>
            <button class="generate-report-button" onclick="generateReport()" style="font-size:16px; font-family:Georgia;">Generate Report</button>
        </div>
            
            <div class="section ">
                <div class="card">
                    <div class="card-front">
                        <div><i class="fas fa-users "></i><h2>User Metrics<h2></div>
                    </div>
                    <div class="card-back">  
                        <?php
                            $sql = "SELECT
                            (SELECT COUNT(*) FROM  `user table`) AS total_users,
                            VIsitCount AS visitors FROM `visit count table`";
                        
                            $query = $connection->query($sql); // as table only
                            if (!$query){
                                die("Invalid query: ".$connection->error);
                            }
                            $result = ""; //empty string
                            $result2 = "";
                            if(mysqli_num_rows($query) == 1){
                                $row = mysqli_fetch_assoc($query);//change to line by line string
                                $result = $row['total_users'];
                                $result2 = $row['visitors']; // Retrieve the visitor count
                            }
                        
                            else {
                                $result1 = "No data found";
                                $result2 = "No data found"; // Handle case where no data is found
                            }
                        ?>
                        <p>Total Users: <span id="total-users"><?php echo $result?></span></p>
                        <br>
                        <p>Visitors: <span id="active-users"><?php echo $result2?></span></p>
                    </div>
                </div>
            </div>
            <div class="section ">
                <div class="card">
                    <div class="card-front">
                        <div><i class="fas fa-cloud-sun "></i><h2>Weather Prediction Accuracy<h2></div>
                    </div>
                    <div class="card-back">  
                        <?php
                        $count = 0;
                        $countTrue = 0;
                        $take = "SELECT `Predicted_Weather` AS `w1`, `Actual_Weather` AS `w2` FROM `weather status table` WHERE MONTH (`Date`) = $nmonth";
                        $taking = mysqli_query($connection, $take);
                        
                        while ($weather = mysqli_fetch_assoc($taking)) {
                            $PD = $weather['w1'];
                            $AD = $weather['w2'];

                            // Split the strings by '|'
                            $pd = explode('|', $PD);
                            $ad = explode('|', $AD);

                            $high = max($pd); //value
                            $key = array_search($high, $pd); //index
                            if ($ad[$key] == 1.0) {
                                $count++;
                                $countTrue++;
                                
                            }else{
                                $count++;
                                
                            }
                        }
                        if ($count == 0){
                            echo '<p style=color: white;font-family: Lucida, Monospace;font-size: 20px;margin: 20px;">NO DATA AVAILABLE</p>';

                        }else{
                            $accu = $countTrue/$count * 100;
                            echo '<p style=color: white;font-family: Lucida, Monospace;font-size: 20px;margin: 20px;">' . number_format($accu, 2) . '%</p>';
                        }
                        
                    ?>
                    </div>
                </div>
            </div>
            
            <div class="section ">
                <div class="card">
                    <div class="card-front">
                        <div><i class="fas fa-star"></i><h2>User Ratings & Feedback<h2></div>
                    </div>
                    <div class="card-back">  
                        <?php
                    //feedbackcount
                        $sqlCheck = "SELECT COUNT(*) AS `totalReview` FROM `review table` WHERE MONTH (`Date`) = $nmonth";
                        $result = mysqli_query($connection, $sqlCheck);
                        $row = mysqli_fetch_assoc($result);
                        $totalReview = $row['totalReview'];
                    //ratecount
                        $checkScore = "SELECT SUM(Rate) AS `SUMSCORE` FROM `review table` WHERE MONTH (`Date`) = $nmonth";
                        $check = mysqli_query($connection, $checkScore);
                        $s = mysqli_fetch_assoc($check);
                        $sumScore = $s['SUMSCORE'];
                        //averageCount
                        if ($totalReview == 0){
                            $ave = "NULL";
                        }else{
                            $ave = round(($sumScore/$totalReview),2);
                        }
                    ?>
                    <p>Average Rating: <span id="average-rating"><?php echo $ave ?></span></p>
                    <p>Feedback Count: <span id="feedback-count"><?php echo $totalReview ?></span></p> 
                  
                    </div>
                    </div>
                </div>
            </div>
            
</body>
</html>


<script>

    function getMonth(event){
        document.getElementById("hidden").value = event.value;
        document.getElementById("myForm").submit();
    }

    function generateReport() {
        var selectedMonth = document.getElementById('month-select').value;
        console.log(selectedMonth);
        if (selectedMonth) {
            window.location.href = "report.php";
        } else {
            alert('Please select a month before generating the report.');
        }
    }

    var today = new Date();
    var monthIndex = today.getMonth(); // getMonth() returns 0-based index (0 = January, 11 = December)
    var monthNames = [
        "January", "February", "March", "April", "May", "June", 
        "July", "August", "September", "October", "November", "December"
    ];
    var monthName = monthNames[monthIndex];
    document.getElementById("hidden").value = monthName;

</script>