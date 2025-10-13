<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lms_db";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// --- TAMBAHKAN KODE PENGECEKAN DI SINI ---
if ($conn->connect_error) {
    die("<div style='color: red; font-family: sans-serif;'>Koneksi Gagal: " . $conn->connect_error . "</div>");
// } else {
//     echo "<div style='color: green; font-family: sans-serif;'>Koneksi Berhasil!</div>";
}
?>