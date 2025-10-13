<?php
session_start();
require 'koneksi.php';

$course_slug = isset($_GET['course']) ? $_GET['course'] : null;

if (!$course_slug) {
    header("Location: index.php");
    exit();
}

// Ambil detail kursus dari database
$stmt = $conn->prepare("SELECT * FROM courses WHERE course_id = ?");
$stmt->bind_param("s", $course_slug);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    header("Location: index.php");
    exit();
}
$course = $result->fetch_assoc();
$course_db_id = $course['id']; // ID integer dari kursus

$is_enrolled = false;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt_check = $conn->prepare("SELECT id FROM enrollments WHERE user_id = ? AND course_id = ?");
    $stmt_check->bind_param("ii", $user_id, $course_db_id);
    $stmt_check->execute();
    $stmt_check->store_result();
    if ($stmt_check->num_rows > 0) {
        $is_enrolled = true;
    }
    $stmt_check->close();
}

// Logika untuk mendaftar kursus
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enroll'])) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
    if (!$is_enrolled) {
        $stmt_enroll = $conn->prepare("INSERT INTO enrollments (user_id, course_id, progress) VALUES (?, ?, 0)");
        $stmt_enroll->bind_param("ii", $user_id, $course_db_id);
        $stmt_enroll->execute();
        $stmt_enroll->close();
        header("Location: course-detail.php?course=" . $course_slug); // Refresh
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($course['title']); ?> - LMS</title>
    
    <link rel="stylesheet" href="style.css"> 
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
</head>

<body>
    <header class="header">
    </header>

    <section class="course-detail-section">
        <div class="container">
            <div class="course-header">
                <img src="<?php echo htmlspecialchars($course['image']); ?>" alt="<?php echo htmlspecialchars($course['title']); ?>" class="course-hero-image">
                <div class="course-info">
                    <h1><?php echo htmlspecialchars($course['title']); ?></h1>
                    <p class="course-level"><?php echo htmlspecialchars($course['level']); ?></p>
                    <div class="course-rating">
                        <span class="stars"><?php echo htmlspecialchars($course['rating']); ?></span>
                    </div>
                    <div class="course-price"><?php echo htmlspecialchars($course['price']); ?></div>
                    
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if ($is_enrolled): ?>
                            <a href="dashboard.php" class="btn btn-secondary">Go to Dashboard</a>
                        <?php else: ?>
                            <form method="POST" action="">
                                <button type="submit" name="enroll" class="btn btn-primary">Enroll Now</button>
                            </form>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-primary">Login to Enroll</a>
                    <?php endif; ?>
                </div>
            </div>
            </div>
    </section>

    <footer class="footer">
    </footer>

    <script src="script.js"></script>
</body>
</html>