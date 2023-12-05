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
            width: 100%;
            overflow: hidden;
            position: relative; /* Added for positioning the bottom button */
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
            height: 900px; /* Set a fixed height for all carousel items */
            overflow: hidden; /* Hide any overflow content */
        }

        /* Image within carousel item */
        .carousel-item img {
            width: 100%;
            height: 100%;
            object-fit: cover; /* Ensure the image covers the entire container */
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
            left: 10px;
        }

        .next-btn {
            right: 10px;
        }

        .bottom-btn {
            width: 100%;
            bottom: 10px;
        }
    </style>
</head>

<body>
<div class="change-password-container pad-2em">
    <!-- Add the carousel code here -->
    <div class="carousel">
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
