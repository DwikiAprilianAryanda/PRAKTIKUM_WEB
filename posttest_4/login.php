<?php
session_start();

// 1. If user is already logged in, redirect to the dashboard
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}

$error_message = '';

// 2. Check if the form was submitted using the POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // 3. Simple validation (for demonstration, any non-empty user/pass is valid)
    if (!empty($username) && !empty($password)) {
        // 4. Store username in the session to mark the user as logged in
        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
        exit();
    } else {
        $error_message = "Username and password are required!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Learning Management System</title>
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
                    <li><a href="index.php#courses" class="nav-link">Courses</a></li>
                    <li><a href="register.php" class="nav-link">Register</a></li>
                </ul>
            </nav>
            <button id="theme-toggle">🌙</button>
        </div>
    </header>

    <section class="section auth-section">
        <div class="container auth-container">
            <h2 class="section-title">Login</h2>
            <p class="section-text">Access your account to continue learning.</p>
            
            <form id="loginForm" class="auth-form" method="POST" action="login.php">
                <?php if ($error_message): ?>
                    <p style="color: red; text-align: center; margin-bottom: 1rem;"><?php echo $error_message; ?></p>
                <?php endif; ?>
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" class="form-input" required>
                </div>
                <div class="form-group password-wrapper">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" class="form-input" required>
                    <button type="button" id="toggleLoginPassword" class="toggle-password">Lihat</button>
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
            </form>
            <p class="text-center mt-3">Don't have an account? <a href="register.php" class="reference-link">Register here</a></p>
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