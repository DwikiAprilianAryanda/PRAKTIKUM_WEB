<?php
// Ganti 'admin123' dengan password yang Anda inginkan untuk akun admin
$password_untuk_admin = '001';

// Proses hashing password
$hash = password_hash($password_untuk_admin, PASSWORD_DEFAULT);

// Tampilkan hasilnya di layar
echo "<h1>Password Hash Generator</h1>";
echo "<p>Password Plain Text: <strong>" . htmlspecialchars($password_untuk_admin) . "</strong></p>";
echo "<p>Hasil Hash (salin teks di bawah ini):</p>";
echo "<textarea rows='3' cols='80' readonly>" . htmlspecialchars($hash) . "</textarea>";
?>