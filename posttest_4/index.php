<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learning Management System</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="logo">LMS Platform</div>
            <nav>
                <ul class="nav-list">
                    <li><a href="#home" class="nav-link">Home</a></li>
                    <li><a href="#courses" class="nav-link">Courses</a></li>
                    <li><a href="#about" class="nav-link">About</a></li>
                    <li><a href="#contact" class="nav-link">Contact</a></li>
                    
                    <?php if (isset($_SESSION['username'])): ?>
                        <li><a href="dashboard.php" class="nav-link">Dashboard</a></li>
                        <li><a href="logout.php" class="nav-link">Logout</a></li>
                    <?php else: ?>
                        <li><a href="login.php" class="nav-link">Login</a></li>
                        <li><a href="register.php" class="nav-link">Register</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
            <button id="theme-toggle">🌙</button>
        </div>
    </header>

    <section id="home" class="hero-section">
        <div class="container hero-content">
            <button id="ctaBtn" class="btn btn-primary">Gabung Sekarang!</button>
            <h1 class="section-title">Welcome to Our Learning Platform</h1>
            <p class="hero-text">Explore a wide range of courses to enhance your skills and knowledge. Join thousands of learners today!</p>
            <a href="#courses" class="btn btn-primary">Browse Courses</a>
        </div>
    </section>

    <section id="courses" class="section courses-section">
        <div class="container">
            <h2 class="section-title" id="coursesTitle">Our Courses</h2>
            <p class="section-text">Discover our curated selection of courses designed for all levels.</p>
            <div class="courses-grid">
                <div class="course-card">
                    <img src="images/kelas1.jpg" alt="Web Development" class="course-image">
                    <div class="course-content">
                        <h3 class="course-title">Web Development Fundamentals</h3>
                        <p class="course-description">Learn the basics of HTML, CSS, and JavaScript to build modern websites from scratch.</p>
                        <a href="course-detail.php?course=web-dev" class="btn btn-secondary">View Details</a>
                    </div>
                </div>
                <div class="course-card">
                    <img src="images/kelas2.webp" alt="Data Science" class="course-image">
                    <div class="course-content">
                        <h3 class="course-title">Data Science with Python</h3>
                        <p class="course-description">Master data analysis and machine learning techniques using Python and popular libraries.</p>
                        <a href="course-detail.php?course=data-science" class="btn btn-secondary">View Details</a>
                    </div>
                </div>
                <div class="course-card">
                    <img src="images/kelas3.jpg" alt="Digital Marketing" class="course-image">
                    <div class="course-content">
                        <h3 class="course-title">Digital Marketing Strategy</h3>
                        <p class="course-description">Develop comprehensive digital marketing strategies to grow your online presence and business.</p>
                        <a href="course-detail.php?course=digital-marketing" class="btn btn-secondary">View Details</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
         </footer>

    <script src="script.js"></script>
</body>
</html>