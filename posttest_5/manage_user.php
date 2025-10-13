<?php
session_start();
require 'koneksi.php';

// Keamanan: Hanya admin yang bisa mengakses halaman ini
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Ambil user ID dari URL, pastikan itu adalah angka
$user_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$user_id) {
    header("Location: admin_dashboard.php");
    exit();
}

// PERUBAHAN 1: Logika untuk "Flash Message" dari Session
// Pesan ini akan muncul setelah redirect
$message = '';
if (isset($_SESSION['flash_message'])) {
    $message = $_SESSION['flash_message'];
    unset($_SESSION['flash_message']); // Hapus pesan agar tidak muncul lagi
}

// PROSES UPDATE DATA JIKA FORM DI-SUBMIT
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action_taken = false;

    // 1. Update Username
    if (isset($_POST['update_username'])) {
        $new_username = $_POST['username'];
        $stmt = $conn->prepare("UPDATE users SET username = ? WHERE id = ?");
        $stmt->bind_param("si", $new_username, $user_id);
        if ($stmt->execute()) {
            $_SESSION['flash_message'] = "<div class='notification success'>Username berhasil diperbarui.</div>";
        } else {
            $_SESSION['flash_message'] = "<div class='notification error'>Gagal memperbarui username. Mungkin sudah digunakan.</div>";
        }
        $stmt->close();
        $action_taken = true;
    }

    // 2. Update Password
    if (isset($_POST['update_password'])) {
        $new_password = $_POST['password'];
        if (strlen($new_password) < 6) {
            $_SESSION['flash_message'] = "<div class='notification error'>Password minimal harus 6 karakter.</div>";
        } else {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $hashed_password, $user_id);
            if ($stmt->execute()) {
                $_SESSION['flash_message'] = "<div class='notification success'>Password berhasil direset.</div>";
            } else {
                $_SESSION['flash_message'] = "<div class='notification error'>Gagal mereset password.</div>";
            }
            $stmt->close();
        }
        $action_taken = true;
    }

    // 3. Update Progress Kursus
    if (isset($_POST['update_progress'])) {
        $enrollment_id = $_POST['enrollment_id'];
        $progress = (int)$_POST['progress'];
        if ($progress >= 0 && $progress <= 100) {
            $stmt = $conn->prepare("UPDATE enrollments SET progress = ? WHERE id = ? AND user_id = ?");
            $stmt->bind_param("iii", $progress, $enrollment_id, $user_id);
            if($stmt->execute()){
                $_SESSION['flash_message'] = "<div class='notification success'>Progress kursus berhasil diperbarui.</div>";
            }
            $stmt->close();
        }
        $action_taken = true;
    }
    
    // PERUBAHAN 2: Redirect setelah aksi selesai
    // Ini adalah kunci perbaikan bug
    if ($action_taken) {
        header("Location: manage_user.php?id=" . $user_id);
        exit();
    }
}

// Ambil data user yang akan di-edit
$stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) { // Jika user tidak ditemukan
    header("Location: admin_dashboard.php");
    exit();
}

// Ambil data kursus yang diikuti user
$stmt_courses = $conn->prepare("
    SELECT c.title, e.progress, e.id as enrollment_id
    FROM enrollments e
    JOIN courses c ON e.course_id = c.id
    WHERE e.user_id = ?
");
$stmt_courses->bind_param("i", $user_id);
$stmt_courses->execute();
$courses = $stmt_courses->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage User - <?php echo htmlspecialchars($user['username']); ?></title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        .manage-container { max-width: 800px; margin: 0 auto; }
        .form-section { background: #fff; padding: 2rem; border-radius: 12px; margin-bottom: 2rem; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
        .form-section h3 { margin-top: 0; margin-bottom: 1.5rem; }
        .dark-mode .form-section { background: #1f2937; }
        .course-manage-item { display: flex; align-items: center; justify-content: space-between; gap: 1rem; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #f0f0f0;}
        .course-manage-item:last-child { border-bottom: none; }
        .dark-mode .course-manage-item { border-bottom-color: #374151; }
        .notification { padding: 1rem; margin-bottom: 1.5rem; border-radius: 8px; color: white; text-align: center; animation: fadeIn 0.5s; }
        .notification.success { background-color: #22c55e; }
        .notification.error { background-color: #ef4444; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    </style>
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="logo">LMS Platform</div>
            <nav>
                <ul class="nav-list">
                    <li><a href="admin_dashboard.php" class="nav-link">Back to Dashboard</a></li>
                    <li><a href="logout.php" class="nav-link">Logout</a></li>
                </ul>
            </nav>
            <button id="theme-toggle">🌙</button>
        </div>
    </header>

    <section class="section">
        <div class="manage-container">
            <h2 class="section-title">Manage User: <?php echo htmlspecialchars($user['username']); ?></h2>
            
            <?php echo $message; ?>

            <div class="form-section">
                <h3>Ubah Username</h3>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="username">Username Baru:</label>
                        <input type="text" id="username" name="username" class="form-input" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                    </div>
                    <button type="submit" name="update_username" class="btn btn-primary">Simpan Username</button>
                </form>
            </div>

            <div class="form-section">
                <h3>Reset Password</h3>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="password">Password Baru:</label>
                        <input type="password" id="password" name="password" class="form-input" placeholder="Masukkan password baru" required>
                    </div>
                    <button type="submit" name="update_password" class="btn btn-primary">Reset Password</button>
                </form>
            </div>

            <div class="form-section">
                <h3>Update Progress Kursus</h3>
                <?php if ($courses->num_rows > 0): ?>
                    <?php while($course = $courses->fetch_assoc()): ?>
                        <form method="POST" action="" class="course-manage-item">
                            <span><?php echo htmlspecialchars($course['title']); ?></span>
                            <div>
                                <input type="number" name="progress" value="<?php echo $course['progress']; ?>" min="0" max="100" class="form-input" style="width: 80px;">
                                <input type="hidden" name="enrollment_id" value="<?php echo $course['enrollment_id']; ?>">
                                <button type="submit" name="update_progress" class="btn btn-secondary" style="padding: 5px 10px;">Update</button>
                            </div>
                        </form>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Pengguna ini belum mengambil kursus apa pun.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>
    
    <script src="script.js"></script>
</body>
</html>