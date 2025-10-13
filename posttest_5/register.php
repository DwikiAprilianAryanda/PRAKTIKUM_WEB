<?php
session_start();
require 'koneksi.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error_message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($username) && !empty($email) && !empty($password)) {
        if (strlen($password) < 6) {
            $error_message = "Password minimal harus 6 karakter.";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Cek apakah username sudah ada
            $stmt_check = $conn->prepare("SELECT id FROM users WHERE username = ?");
            $stmt_check->bind_param("s", $username);
            $stmt_check->execute();
            $stmt_check->store_result();

            if ($stmt_check->num_rows > 0) {
                $error_message = "Username sudah terdaftar, silakan pilih yang lain.";
            } else {
                $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $username, $email, $hashed_password);

                if ($stmt->execute()) {
                    // Langsung login setelah registrasi berhasil
                    $_SESSION['user_id'] = $stmt->insert_id;
                    $_SESSION['username'] = $username;
                    $_SESSION['role'] = 'user'; // Role default
                    header("Location: dashboard.php");
                    exit();
                } else {
                    $error_message = "Registrasi gagal, silakan coba lagi.";
                }
                $stmt->close();
            }
            $stmt_check->close();
        }
    } else {
        $error_message = "Semua kolom wajib diisi!";
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Learning Management System</title>
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
                    <li><a href="login.php" class="nav-link">Login</a></li>
                </ul>
            </nav>
            <button id="theme-toggle">🌙</button>
        </div>
    </header>

    <section class="section auth-section">
        <div class="container auth-container">
            <h2 class="section-title">Register</h2>
            <p class="section-text">Create an account to start your learning journey.</p>
            <form id="registerForm" class="auth-form" method="POST" action="register.php">
                <?php if ($error_message): ?>
                    <p style="color: red; text-align: center; margin-bottom: 1rem;"><?php echo $error_message; ?></p>
                <?php endif; ?>
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" class="form-input" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" class="form-input" required>
                </div>
                <div class="form-group password-wrapper">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" class="form-input" required>
                    <button type="button" id="toggleRegisterPassword" class="toggle-password">Lihat</button>
                </div>
                <button type="submit" class="btn btn-primary">Register</button>
            </form>
            <p class="text-center mt-3">Already have an account? <a href="login.php" class="reference-link">Login here</a></p>
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