<?php
session_start();
require 'koneksi.php';

// Lindungi halaman: hanya admin yang bisa akses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$admin_id = $_SESSION['user_id'];
$message = ''; // Variabel untuk menyimpan pesan notifikasi

// BAGIAN BARU: Logika untuk menghapus user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $user_id_to_delete = $_POST['user_id'];

    // Keamanan: Pastikan admin tidak menghapus akunnya sendiri
    if ($user_id_to_delete == $admin_id) {
        $message = "<div class='notification error'>Anda tidak bisa menghapus akun Anda sendiri.</div>";
    } else {
        // Hapus user dari database menggunakan prepared statement
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id_to_delete);
        
        if ($stmt->execute()) {
            $message = "<div class='notification success'>User berhasil dihapus.</div>";
        } else {
            $message = "<div class='notification error'>Gagal menghapus user.</div>";
        }
        $stmt->close();
    }
}

// Ambil semua data progres user, sekarang termasuk user ID
$progress_data = [];
$sql = "SELECT u.id as user_id, u.username, c.title, e.progress
        FROM users u
        LEFT JOIN enrollments e ON u.id = e.user_id
        LEFT JOIN courses c ON e.course_id = c.id
        WHERE u.role = 'user'
        ORDER BY u.username, c.title";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $progress_data[$row['username']]['id'] = $row['user_id'];
        if ($row['title']) { // Hanya tambahkan jika user punya kursus
            $progress_data[$row['username']]['courses'][] = [
                'title' => $row['title'],
                'progress' => $row['progress']
            ];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - LMS</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        .admin-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 2rem; }
        .user-card { background: #fff; border-radius: 12px; padding: 1.5rem; box-shadow: 0 4px 12px rgba(0,0,0,0.08); display: flex; flex-direction: column; }
        .user-card h3 { margin-bottom: 1rem; color: #3b82f6; }
        .course-progress-item { margin-bottom: 0.8rem; }
        .course-progress-item p { margin: 0; padding: 0; }
        .user-card-footer { margin-top: auto; padding-top: 1rem; border-top: 1px solid #eee; }
        .btn-delete { background-color: #ef4444; color: white; padding: 8px 15px; border-radius: 6px; text-decoration: none; font-size: 0.9rem; border: none; cursor: pointer; }
        .btn-delete:hover { background-color: #dc2626; }
        .dark-mode .user-card { background: #1f2937; }
        .dark-mode .user-card-footer { border-top-color: #374151; }
        .notification { padding: 1rem; margin-bottom: 1.5rem; border-radius: 8px; color: white; text-align: center; }
        .notification.success { background-color: #22c55e; }
        .notification.error { background-color: #ef4444; }
    </style>
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="logo">LMS Platform</div>
            <nav>
                <ul class="nav-list">
                    <li><a href="index.php" class="nav-link">Home</a></li>
                    <li><a href="logout.php" class="nav-link">Logout</a></li>
                </ul>
            </nav>
            <button id="theme-toggle">🌙</button>
        </div>
    </header>

    <section class="section">
        <div class="container">
            <h2 class="section-title">Admin Dashboard</h2>
            <p class="section-text">Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>! Monitor and manage all users here.</p>
            
            <?php echo $message; // Tampilkan notifikasi sukses/gagal ?>

            <div class="admin-grid">
                <?php if (empty($progress_data)): ?>
                    <p class="text-center">Belum ada data pengguna untuk ditampilkan.</p>
                <?php else: ?>
                    <?php foreach ($progress_data as $username => $data): ?>
                        <div class="user-card">
                            <div>
                                <h3><?php echo htmlspecialchars($username); ?></h3>
                                <?php if (isset($data['courses'])): ?>
                                    <?php foreach ($data['courses'] as $course): ?>
                                        <div class="course-progress-item">
                                            <p><strong><?php echo htmlspecialchars($course['title']); ?></strong></p>
                                            <div class="progress-bar">
                                                <div class="progress-fill" style="width: <?php echo $course['progress']; ?>%;">
                                                    <?php echo $course['progress']; ?>%
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p><em>Pengguna ini belum mengambil kursus.</em></p>
                                <?php endif; ?>
                            </div>
                            <div class="user-card-footer">
                                <form method="POST" action="admin_dashboard.php" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini? Tindakan ini tidak bisa dibatalkan.');">
                                    <input type="hidden" name="user_id" value="<?php echo $data['id']; ?>">
                                    <button type="submit" name="delete_user" class="btn-delete">Hapus User</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <script src="script.js"></script>
</body>
</html>