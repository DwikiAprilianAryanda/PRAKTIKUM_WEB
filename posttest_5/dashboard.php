<?php
session_start();
require 'koneksi.php';

// Hanya user biasa yang bisa akses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Proses update progress
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_progress'])) {
    $enrollment_id = $_POST['enrollment_id'];
    $progress = (int)$_POST['progress'];
    if ($progress >= 0 && $progress <= 100) {
        $stmt = $conn->prepare("UPDATE enrollments SET progress = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("iii", $progress, $enrollment_id, $user_id);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: dashboard.php"); // Refresh halaman
    exit();
}

// Ambil kursus yang diikuti user
$sql = "SELECT c.title, c.image, c.course_id AS course_slug, e.progress, e.id AS enrollment_id
        FROM courses c
        JOIN enrollments e ON c.id = e.course_id
        WHERE e.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$enrolled_courses = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - LMS</title>
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
            
            <div class="courses-grid">
                <?php if ($enrolled_courses->num_rows > 0): ?>
                    <?php while($course = $enrolled_courses->fetch_assoc()): ?>
                        <div class="course-card">
                            <img src="<?php echo htmlspecialchars($course['image']); ?>" alt="<?php echo htmlspecialchars($course['title']); ?>" class="course-image">
                            <div class="course-content">
                                <h3 class="course-title"><?php echo htmlspecialchars($course['title']); ?></h3>
                                <div class="progress-item">
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: <?php echo $course['progress']; ?>%;"><?php echo $course['progress']; ?>%</div>
                                    </div>
                                    <form action="dashboard.php" method="POST" class="progress-update-form">
                                        <input type="hidden" name="enrollment_id" value="<?php echo $course['enrollment_id']; ?>">
                                        <input type="number" name="progress" value="<?php echo $course['progress']; ?>" min="0" max="100" class="form-input" style="width: 80px; padding: 5px;">
                                        <button type="submit" name="update_progress" class="btn btn-secondary" style="padding: 5px 10px;">Update</button>
                                    </form>
                                </div>
                                <a href="course-detail.php?course=<?php echo $course['course_slug']; ?>" class="btn btn-primary">Continue Learning</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-center">You haven't enrolled in any courses yet. <a href="index.php#courses">Browse courses now!</a></p>
                <?php endif; ?>
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