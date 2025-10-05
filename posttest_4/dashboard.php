<?php
session_start();

// If the user is not logged in, redirect them to the login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit(); // Stop script execution
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Learning Management System</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="logo">LMS Platform</div>
            <nav>
                <ul class="nav-list">
                    <li><a href="index.php" class="nav-link">Home</a></li>
                    <li><a href="#my-courses" class="nav-link">My Courses</a></li>
                    <li><a href="logout.php" class="nav-link">Logout</a></li>
                </ul>
            </nav>
            <button id="theme-toggle">🌙</button>
        </div>
    </header>

    <section class="section dashboard-section">
        <div class="container">
            <h2 class="section-title">User Dashboard</h2>
            <p class="section-text">Welcome back, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>! Track your enrolled courses and progress here.</p>
            <div id="my-courses" class="courses-grid">
                </div>
            <div id="progress-tracker" class="progress-tracker">
                <h3>Your Progress</h3>
                <div id="progress-list"></div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container footer-content">
            <p>© 2025 Learning Management System. All rights reserved.</p>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>