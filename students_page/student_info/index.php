<?php
global $conn;
$var = "student_info";
include '../../students_page/header.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../../assets/css/student/change_password.css" />

    <!-- Add the carousel styles here or link to an external stylesheet if needed -->
   <style>
 /* Carousel container */
.carousel {
    width: 75%;
    margin: 0 auto;
    overflow: hidden;
    position: relative;
    padding: 20px;
}

/* Carousel wrapper */
.carousel-wrapper {
    display: flex;
    transition: transform 0.5s ease-in-out;
}

/* Carousel item */
.carousel-item {
    min-width: 100%;
    box-sizing: border-box;
    height: 500px; /* Adjust the height for smaller screens */
    overflow: hidden;
}

/* Image within carousel item */
.carousel-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Navigation buttons */
.carousel-btn {
    cursor: pointer;
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    font-size: 24px;
    color: white;
    background-color: rgba(0, 0, 0, 0.5);
    border: none;
    padding: 10px;
}

.prev-btn {
    left: 30px;
}

.next-btn {
    right: 30px;
}

.bottom-btn {
    width: 80%;
    bottom: 10px;
}

/* Media query for smaller screens */
@media (max-width: 768px) {
    .carousel {
        width: 100%;
        padding: 10px;
    }

    .carousel-item {
        height: 300px;
    }
}
.carousel-title {
            text-align: center;
            font-size: 2em;
            color: gray; /* Set your desired text color */
            margin: 0; /* Adjust margin according to your design */
            transition: color 0.5s ease-in-out; /* Add a color transition effect */
            border-radius: 10px; /* Add border-radius for rounded corners */
            border: 6px;
            padding: 10px 20px; /* Add padding for better aesthetics */
            display: inline-block; /* Ensure the padding and border-radius apply correctly */
            font-size: 45px;
        }

        .carousel-title:hover {
            color: green; /* Set the color you want to transition to on hover */
        }

        @media screen and (max-width: 600px) {
            /* Add responsive styles here */
            .carousel-title {
                font-size: 1.5em; /* Adjust font size for smaller screens */
            }
        }
        .carousel-title2{
            text-align: center;
        }
   </style>
</head>

<body>
<div class="change-password-container pad-2em">
    
    <div class="carousel">
    <h1 class="carousel-title">Mactan Airbase Elementary School</h1>
    <h3 class="carousel-title2">Description</h3>

        <div class="carousel-wrapper">
            <div class="carousel-item">
                <img src="../../assets/img/MABES3.jpg" alt="Image 1">
            </div>
            <div class="carousel-item">
                <img src="../../assets/img/mabesrooms2.jpg" alt="Image 2">
            </div>
            <div class="carousel-item">
                <img src="../../assets/img/Learning.jpg" alt="Image 3">
            </div>
            <!-- Add more carousel items as needed -->
        </div>
        <button class="carousel-btn prev-btn" onclick="prevSlide()">❮</button>
        <button class="carousel-btn next-btn" onclick="nextSlide()">❯</button>
    </div>
    <!-- End of carousel code -->

    <!-- Your existing content goes here -->
</div>

<!-- Add the carousel script at the end of your body or include it in your existing JavaScript file -->
<script>
    let currentIndex = 0;
    const totalItems = document.querySelectorAll('.carousel-item').length;
    const intervalTime = 5000; // Set the interval time in milliseconds (e.g., 5000 for 5 seconds)

    function updateCarousel() {
        const wrapper = document.querySelector('.carousel-wrapper');
        const itemWidth = document.querySelector('.carousel-item').clientWidth;
        const newPosition = -currentIndex * itemWidth;
        wrapper.style.transform = `translateX(${newPosition}px)`;
    }

    function nextSlide() {
        currentIndex = (currentIndex + 1) % totalItems;
        updateCarousel();
    }

    function prevSlide() {
        currentIndex = (currentIndex - 1 + totalItems) % totalItems;
        updateCarousel();
    }

    function bottomSlide() {
        // Example: Scroll to the bottom of the page
        window.scrollTo({
            top: document.body.scrollHeight,
            behavior: 'smooth'
        });
    }

    // Auto-run the next slide at specified intervals
    setInterval(() => {
        nextSlide();
    }, intervalTime);
</script>

</body>

</html>
