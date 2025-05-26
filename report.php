<?php
    include 'dbConn.php';
    include 'navibar.php';
    $month = $_SESSION['month'];
    $nmonth = date('m',strtotime($month));
    
    $sql = "SELECT
            (SELECT COUNT(*) FROM  `user table`) AS total_users,
            VIsitCount AS visitors
            FROM `visit count table`";

    $query = $connection->query($sql); // as table only
    if (!$query){
        die("Invalid query: ".$connection->error);
    }
    
    // Initialize arrays to store the results
    $userMetrics = [];
    $visitCounts = [];

    while ($row = $query->fetch_assoc()) {
        $userMetrics[] = $row['total_users'];
        $visitCounts[] = $row['visitors'];
    }
    
    // Encode data into JSON format for JavaScript
    $userMetricsJson = json_encode($userMetrics);
    $visitCountsJson = json_encode($visitCounts);
    
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
        $ave = $sumScore/$totalReview;
    }

    
                        
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing:border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: rgba(96,190,186,0.5);
            position: relative;
            min-height: 80vh;
            
            
        }
        h2 {
            /* color: #2A507D; */
            color: rgba(152,52,109,1.0);
            text-align:center;
            padding-top: 1.2%;
            font-size: 50px;
            font-weight: 500;
            font-family: Georgia, serif;
            font-style: italic;
            margin-bottom:5px;
        }
        


        @keyframes floatAnimation {
            0% {
            transform: translateY(0);
            }
            50% {
            transform: translateY(-20px); /* Adjust floating distance */
            }
            100% {
            transform: translateY(0);
            }
        }
        .chart-container{
            background: #E7ECEF;
            /* background: rgba(225,217,25,0.2); */
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 1;
            margin: 1%;  
        }
        .bar {
            width: 100%;
            height: 450px;
        }
        .background-wrapper {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100dvh;
            z-index: 0; /* Behind chart container */
            overflow: hidden; /* Prevent scroll due to overflow */
            
        }

        .ocean {
         height: 0px;
         width:100%;
         position:absolute;
         bottom:0;
         left:0;
         
        }
        .wave {
            background: url(https://s3-us-west-2.amazonaws.com/s.cdpn.io/85486/wave.svg) repeat-x;
            position: relative;
            top: -198px;
            width: 200%;
            height: 300px;
            animation: wave 7s cubic-bezier( 0.36, 0.45, 0.63, 0.53) infinite;
            transform: translate3d(0, 0, 0);
        }

         
        @keyframes wave {
            0% {
            margin-left: 0; }
            100% {
                margin-left: -1200px;
            }}

        canvas {
            
        }
        #userMetricsChart{
            max-width:40dvw;
            max-height:20dvh;
        }
        #accuracyChart{
            max-width:45dvw;
            max-height:45dvh;
        }
        #ratingHistogramChart{
            max-width:auto;
            max-height:16dvh;
        }

        .section-title {
            text-align: left;

            color: #275A77;
            font-family: "Times New Roman", Times, serif;
            font-style: oblique;
            padding:10px;
            display: flex;
            align-items: center;
            font-size: 2vw;
            font-weight:500;
            padding-bottom:0;
            
        }
        .item1 { 
            grid-area: right; 
            height:auto;
            margin: 0% 0% 1% 1%;
        }
        .item2 { 
            grid-area: left;
            height:63dvh;
            width: 40dvw;
            margin:0% 0 1% 1%;
         }
        .item3 { 
            grid-area: middle;
            height:31.5dvh;
            width: auto;  
            margin:0% 0% 1% 1%;
         }
        .item4 { 
            grid-area: bottom;
            width: auto;
            height: 23dvh;
            margin: 0 0% 0 0.5%;
            transform:translateY(-0%);
        }
        
        
            /* .grid-container > div {
                background-color: rgba(255, 255, 255, 0.8);
                text-align: center;
                padding: 20px 0;
                font-size: 30px;
                } */
        #chart-wrapper {
            width: 80vw;
            overflow:hidden;
            margin: auto;
            height:87dvh;
            display: grid; /*horizontal*/
            grid-template:
            'left right'
            'left middle'
            'bottom bottom';
        } 

        /* Styles for the comment carousel container */
        .comment-carousel {
            width: 60dvw; /* Adjust the width as needed */
            margin: 0 auto;
           overflow: hidden;
            position: relative;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
        }

        /* Styles for the comment container */
        .comment-container {
            display: flex;
            /* overflow-x: auto;
            scroll-behavior: smooth; 
            width:25dvw;
            height:20dvh; */
            white-space: nowrap;
            overflow: hidden;
        }

        /* Style for individual comments */
        .comment {
            display: inline-block;
            width: 100%; /* Fixed width for each comment */
            padding: 5px;
            box-sizing: border-box;
            background-color: #f2f2f2;
            border-radius: 5px;
            margin-right: 10px;
            text-align: center;
            font-size: 14px;
        }

          
        /* Add CSS for the modal popup */
        .modal {
            display: none;
            position: fixed;
            z-index: 3; /* Ensure it sits above other elements */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4); /* Black w/ opacity */
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto; /* 15% from the top and centered */
            padding: 20px;
            z-index:4;
            border: 1px solid #888;
            width: 80%; /* Could be more or less, depending on screen size */
            max-height: 70%; /* Limit height to 70% of the screen */
            overflow-y: auto; /* Enable scrolling if content overflows */
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor:pointer;
        }

        .close:hover {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .comment-list {
            margin-top: 10px;
            padding-left: 0;
            list-style: none;
        }

        .comment-list li {
            background-color: #f2f2f2;
            padding: 10px;
            margin-bottom: 5px;
            border-radius: 5px;
        }
        
        .fas{
            color:#2958B2;
            margin-right: 15px;
            font-size: 36px;
        }
        .word{
            text-align: center;
            margin-bottom: 10px;
            font-family: Garamond, serif;
            font-weight: bold;
        }

        #allCommentsTitle{
            animation: floatAnimation 1.5s ease-in-out infinite; /* Animation properties */
            color: rgba(152,52,109,1.0);
            margin: auto;
            text-align:center;
            font-size: 4vw;
            font-weight: 500;
            font-family: Georgia, serif;
            font-style: italic;
            padding: 0;
            width: 60%;
        }

        #picture {
            position: absolute;
            left:0.5%;
            bottom:-35%;
            
        }

        @media print{
            .background-wrapper {
                overflow: visible !important; /* Ensure overflow is visible when printing */
            }
        }
        @keyframes bounce {
            0% {
            transform: translateY(0);
            }
            100% {
            transform: translateY(-20px);
            }
        }
        .title {
            animation: bounce 0.8s infinite alternate;
        }
        
    </style>
     <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
     
</head>
<body>
<div class="title">
    <h2>Monthly Report-- <?php echo $month; ?></h2>
</div>
    <div class="background-wrapper">
        <div class="ocean">
            <div class="wave"></div>
        </div>
        <img id="picture" src="picture/sign.png" width="99%" height="20%">
    </div>
    
    <div id="chart-wrapper"class="grid-container" >
        <div class="chart-container item1" >
            <div class="section-title"><i class="fas fa-users rotate"></i>User Metrics</div>
            <canvas id="userMetricsChart" class="bar"></canvas>
        </div>
        <div class="chart-container item2">
        <div class="section-title"> <i class="fas fa-cloud-sun bounce"></i>Weather Prediction Accuracy</div>
            <div id="weatherAccuracy">
                <?php
                    $count = 0;
                    $countTrue = 0;
                    $take = "SELECT `Predicted_Weather` AS `w1`, `Actual_Weather` AS `w2` FROM `weather status table` WHERE MONTH(`Date`) = $nmonth";
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
                        }
                        $countTrue++;
                    }

                    if ($countTrue == 0) {
                        echo "NO DATA AVAILABLE";
                    } else {
                        $accu = ($count / $countTrue) * 100;
                        echo "<p>Accuracy: " . number_format($accu, 2) . "%</p>";
                    }
                ?>
                <canvas id="accuracyChart" ></canvas>
            </div>
        </div>
    
        <div class="chart-container item3">
            <div class="section-title"> <i class="fas fa-star rotate"></i> User Rating</div>
                <p class="word">Average Rating: <span id="average-rating"><?php echo round($ave,2) ?></span></p>
                <div id="ratingHistogram">
                    <?php
                       // Initialize array to store counts for each rating
                        $ratingCounts = [0, 0, 0, 0, 0]; // Index represents rating 1 to 5

                        // Query to fetch ratings data from your database table
                        $query = "SELECT `Rate` FROM `review table`WHERE MONTH(`Date`) = $nmonth";
                        $result = mysqli_query($connection, $query);

                        // Loop through results and count occurrences of each rating
                        while ($row = mysqli_fetch_assoc($result)) {
                            $rating = intval($row['Rate']); // Convert rating to integer
                            if ($rating >= 1 && $rating <= 5) {
                                $ratingCounts[$rating - 1]++; // Increment count for the corresponding rating
                            }
                        }

                        if (mysqli_num_rows($result) == 0) {
                            // No ratings found for the selected month, set all counts to zero
                            $ratingCounts = [0, 0, 0, 0, 0];
                        }

                        // Prepare arrays for Chart.js data
                        $labels = ['1 Star', '2 Stars', '3 Stars', '4 Stars', '5 Stars'];
                        $counts = $ratingCounts;


                        // Display the histogram bars
                        echo '<canvas id="ratingHistogramChart"></canvas>';
                    ?>
                </div>
            </div> 
             
            <div class="chart-container item4" >
                <div class="section-title"><i class="fas fa-comments bounce"></i>User Feedback</div>
                    
                    <p class="word">Feedback Count: <span id="feedback-count"><?php echo $totalReview ?></span></p>
                    <div class="comment-carousel" id="commentCarousel">
                    <div class="comment-container" id="commentContainer">
                        <?php
                        
                            // Query to fetch comments from the review table
                            $query = "SELECT * FROM `review table` WHERE MONTH(`Date`) = $nmonth ORDER BY `Date` DESC  ";
                            $result = mysqli_query($connection, $query);

                            // Display comments in the container
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<div class="comment">' . htmlspecialchars($row['Comments']) . '</div>';
                            }

                            
                        ?>
                    </div>
                   
                </div>
            </div> 
            <div id="commentModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <p id="allCommentsTitle">All Comments</p>
                    <ul id="allComments" class="comment-list"></ul>
                </div>
    
            </div>
            
</div>


     <!-- Include Chart.js -->
     <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Data from PHP
            const userMetrics = <?php echo $userMetricsJson; ?>;
            const visitCounts = <?php echo $visitCountsJson; ?>;

            // User Metrics Bar Chart
            const ctx = document.getElementById('userMetricsChart').getContext('2d');
            const userMetricsChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Total Users', 'Visitors'], // Labels for the chart
                    datasets: [{
                        label: 'Count',
                        data: [userMetrics[0], visitCounts[0]], // Data for the chart
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(54, 162, 235, 1)',
                        ],
                        borderWidth: 1
                    }]
                },
                options: {

                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
   
   
   <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get accuracy percentage from PHP into JavaScript
            const accuracyPercentage = <?php echo json_encode(number_format($accu,2)); ?>;

            // Data for Chart.js doughnut chart
            const doughnutData = {
                labels: ['Accurate', 'Inaccuracy'],
                datasets: [{
                    label: 'Weather Prediction Accuracy',
                    data: [accuracyPercentage, 100 - accuracyPercentage],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 99, 132, 0.2)' // Transparent color for the remaining part
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            };

            // Chart.js configuration for doughnut chart
            const ctx = document.getElementById('accuracyChart').getContext('2d');
            
            new Chart(ctx, {
                type: 'doughnut',
                data: doughnutData,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    console.log(tooltipItem);
                                    let label = tooltipItem.label || '';
                                    label = label + ": " + tooltipItem.formattedValue + '%';
                                    return label;
                                }
                            }
                        }
                    }
                }
                
            });
            
        });
    </script>

<script>
        document.addEventListener('DOMContentLoaded', function() {
            // Data for Chart.js histogram (bar chart)
            const barData = {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{
                    label: 'Rating Counts',
                    data: <?php echo json_encode($counts); ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            };

            // Chart.js configuration for histogram (bar chart)
            const ctx = document.getElementById('ratingHistogramChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: barData,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false,
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return 'Count: ' + tooltipItem.raw;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        });
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var commentContainer = document.getElementById('commentContainer');
        var scrollAmount = 1; // Pixels to scroll per interval
        var scrollInterval = 30; // Time in milliseconds for each scroll interval
        var direction = 1; // 1 for right, -1 for left

        function startScrolling() {
            setInterval(function() {
                if (commentContainer.scrollLeft >= (commentContainer.scrollWidth - commentContainer.clientWidth) || commentContainer.scrollLeft <= 0) {
                    direction *= -1; // Change direction when reaching the end
                }
                commentContainer.scrollLeft += scrollAmount * direction;
            }, scrollInterval);
        }

        // Start auto-scrolling
        startScrolling();
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var commentContainer = document.getElementById('commentContainer');
        var modal = document.getElementById('commentModal');
        var closeBtn = document.querySelector('.close');
        var allCommentsList = document.getElementById('allComments');

        // Load all comments into the modal when the comment container is clicked
        commentContainer.addEventListener('click', function() {
            allCommentsList.innerHTML = ''; // Clear any existing comments

            <?php
            // PHP code to fetch all comments from the database
            $query = "SELECT * FROM `review table` ORDER BY `Date` DESC";
            $result = mysqli_query($connection, $query);
            $comments = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $comments[] = $row['Comments'];
            }
            ?>

            // Add comments to the list
            var comments = <?php echo json_encode($comments); ?>;
            comments.forEach(function(comment) {
                var li = document.createElement('li');
                li.textContent = comment;
                allCommentsList.appendChild(li);
            });

            modal.style.display = 'block'; // Show the modal
        });

        // Close the modal when the close button is clicked
        closeBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });

        // Close the modal when clicking outside of the modal content
        window.addEventListener('click', function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        });
    });
</script>



</body>
</html>