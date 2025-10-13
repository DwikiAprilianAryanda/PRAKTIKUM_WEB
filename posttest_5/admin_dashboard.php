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

// Logika untuk menghapus user (jika masih diperlukan, biarkan di sini)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $user_id_to_delete = $_POST['user_id'];
    if ($user_id_to_delete == $admin_id) {
        $message = "<div class='notification error'>Anda tidak bisa menghapus akun Anda sendiri.</div>";
    } else {
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

// Ambil semua data progres user
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
        if ($row['title']) {
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
    <link rel="stylesheet" href="/POSTTEST_5/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        /* ... CSS yang sudah ada ... */
        .user-card-footer { margin-top: auto; padding-top: 1rem; border-top: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; }
        .btn-manage { background-color: #3b82f6; color: white; padding: 8px 15px; border-radius: 6px; text-decoration: none; font-size: 0.9rem; }
        .btn-manage:hover { background-color: #2563eb; }
        .notification { padding: 1rem; margin-bottom: 1.5rem; border-radius: 8px; color: white; text-align: center; }
        .notification.success { background-color: #22c55e; }
        .notification.error { background-color: #ef4444; }
    </style>
</head>
<body>
    <header class="header">
        </header>

    <section class="section">
        <div class="container">
            <h2 class="section-title">Admin Dashboard</h2>
            <p class="section-text">Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>! Monitor and manage all users here.</p>
            
            <?php echo $message; ?>

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
                                            <div class="progress-bar"><div class="progress-fill" style="width: <?php echo $course['progress']; ?>%;"><?php echo $course['progress']; ?>%</div></div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p><em>Pengguna ini belum mengambil kursus.</em></p>
                                <?php endif; ?>
                            </div>
                            <div class="user-card-footer">
                                <a href="manage_user.php?id=<?php echo $data['id']; ?>" class="btn-manage">Kelola User</a>
                                
                                <form method="POST" action="" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                                    <input type="hidden" name="user_id" value="<?php echo $data['id']; ?>">
                                    <button type="submit" name="delete_user" class="btn-delete">Hapus</button>
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