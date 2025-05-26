<?php
include 'dbConn.php';
include 'navibar.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Page</title>
    <link rel="stylesheet" href="weather.css">
    <link href="https://fonts.googleapis.com/css2?family=Comic+Neue:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&display=swap" rel="stylesheet">

</head>
<body>
    <div id="vid-container">
        <div class="vid">
            <video autoplay muted loop class = "bg-video" src="picture/Prediction Accuracy.mp4"></video>
            <div class="text displayText">Weather for Prediction Accuracy</div>
        </div>

        <div class="vid">
            <video autoplay muted loop class = "bg-video" src="picture/Roads.mp4"></video>
            <div class="text displayText">Weather for Roads</div>
        </div>

        <div class="vid">
            <video autoplay muted loop class = "bg-video" src="picture/Construction.mp4"></video>
            <div class="text displayText">Weather for Construction</div>
        </div>

        <div class="vid">
            <video autoplay muted loop class = "bg-video" src="picture/Airplane Industry.mp4"></video>
            <div class="text displayText">Weather for Airplane Industry</div>
        </div>
    </div>

    <div id="desc">
        <h3>A WEATHER FORECASTING SYSTEM THAT ARE CAPABLE WITH ADVACNED FUNCTIONALITIES AND USER CENTRIC DESIGN TO DELIVER ACCURATE AND RELIABLE WEATHER PREDICTION. </h3>
    </div>



    <div id="prediction">
        <button id="btnWeather" onclick="redirect()">View Weather Prediction</button>
    </div>


</body>
</html>
<script>
        function redirect(){
            window.location.href = "newPrediction.php";
        }

        const video = document.querySelectorAll(".vid video");
        const text = document.querySelectorAll(".vid .text");
        let videoIndex = 0;
        let textIndex = 0;
        let intervalID = null;

        document.addEventListener("DOMContentLoaded", firstVideo);
        document.addEventListener("DOMContentLoaded", function() {
            setTimeout(function() {
                document.getElementById('prediction').style.display="block";
            }, 1000);
            
            setTimeout(function() {
                document.getElementById('prediction').style.opacity=1;
            }, 2000);
        });
            

        function firstVideo(){
            if(video.length > 0){
                video[videoIndex].classList.add("displayVideo");
                text[videoIndex].classList.add("displayText");
                intervalID = setInterval(nextVideo, 3000);
            }
        }
        function showVideo(index){
            if(index >= video.length){
                videoIndex = 0;
            }
            else if(index < 0){
                videoIndex = video.length - 1;
            }

            video.forEach(bgvideo => {bgvideo.classList.remove("displayVideo");});
            text.forEach(texts => texts.classList.remove("displayText"));

            video[videoIndex].classList.add("displayVideo");
            text[videoIndex].classList.add("displayText");

        }   
        function nextVideo(){
            videoIndex++;
            showVideo(videoIndex)  
            
            textIndex++;
            if(textIndex >= texts.length){
                textIndex = 0;
            }
            showText(textIndex)
        }

        const galleryContainer = document.querySelector('.gallery-container');
    const galleryControlContainer = document.querySelector('.gallery-controls');
    const galleryControls = ['previous','next']; // Corrected typo here
    const galleryItems = document.querySelectorAll('.gallery-item');

    var url = 'https://newsapi.org/v2/everything?' +
            'q=Weather in Kuala Lumpur OR Thunderstorm in Kuala Lumpur OR Rain in Kuala Lumpur&' +
            'from=2024-06-01&' +
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
            document.getElementById("date").innerHTML = articles[1].publishedAt;

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
            document.getElementById("date").innerHTML = this.articles[this.carouselArray[1].getAttribute('data-index') - 1].publishedAt;
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