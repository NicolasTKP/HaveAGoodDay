<?php
include 'dbConn.php';
include 'navibar.php';

date_default_timezone_set('Asia/Kuala_Lumpur');

if(isset($_POST['SubmitButton'])){
    $current_date = date('Y-m-d');
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
    $comments = $_POST['comments'];
    if(isset($_SESSION['Username'])){
        $username = $_SESSION['Username'];
    }else{
        $username = 'Guest';
    }
    $query = "INSERT INTO `review table`(`Username`, `Date`, `Rate`, `Comments`) VALUES ('$username','$current_date','$rating','$comments')";

    $result = mysqli_query($connection, $query);
    if($result){
        echo "<script>alert('Feedback Submitted Successfully');</script>";
    }else {
        echo "Error: " . mysqli_error($connection);
    }
    mysqli_close($connection);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rating and Feedback</title>
    <link rel="stylesheet" href="comments.css">
    <link href="https://fonts.googleapis.com/css2?family=Comic+Neue:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/435767a11b.js" crossorigin="anonymous"></script>
</head>
<body>
    <video autoplay muted loop id = "backgroundVideo" src="picture\Background Video.mp4"></video>
    <h1>SYSTEM REVIEW</h1>
    <form id="review" method="post">
        <div id="rating-container">
            <h2>RATING:</h2>
            <div class="star">
                <i class="fa-solid fa-star" data-value="1"><span class="number">1</span></i>
                <i class="fa-solid fa-star" data-value="2"><span class="number">2</span></i>
                <i class="fa-solid fa-star" data-value="3"><span class="number">3</span></i>
                <i class="fa-solid fa-star" data-value="4"><span class="number">4</span></i>
                <i class="fa-solid fa-star" data-value="5"><span class="number">5</span></i>
            </div>
        </div>
        <input type="hidden" name="rating" id="rating">

        <div id="comments">
            <h2>COMMENTS</h2>
            <textarea name="comments" placeholder="Drop your feedback here..."></textarea>
            <div class="button"><button id="btnSubmit" type="submit" name="SubmitButton">Submit</button></div>
        </div>
    </form>
    <script defer>
        //Select all elements and store in a NodeList called "stars"
        const stars = document.querySelectorAll(".star i");
        const ratingInput = document.getElementById('rating');

    //loop through the "stars" NodeList
    stars.forEach((star, index) => {
        //Add an event listener that runs a function "click"
        star.addEventListener("click", () => {
            if(star.classList.contains("active") && index === 0){
                stars.forEach((star) => {
                    star.classList.remove("active");
                });
                ratingInput.value = 0; //Reset rating
            } else{
                stars.forEach((star, index2) => {
                    index >= index2 ? star.classList.add("active") : star.classList.remove("active");
                });
                ratingInput.value = index + 1;//Set rating value
            }
        });
    });
    </script>

</body>
</html>