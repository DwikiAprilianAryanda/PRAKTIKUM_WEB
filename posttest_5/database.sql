-- Membuat database jika belum ada
CREATE DATABASE IF NOT EXISTS `lms_db`;
USE `lms_db`;

-- Menghapus tabel jika sudah ada untuk memastikan struktur baru
DROP TABLE IF EXISTS `enrollments`;
DROP TABLE IF EXISTS `courses`;
DROP TABLE IF EXISTS `users`;

-- Tabel untuk pengguna (user dan admin)
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(10) NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Memasukkan akun admin default
-- (username: admin, password: admin123)
INSERT INTO `users` (`username`, `email`, `password`, `role`) VALUES
('admin', 'admin@lms.com', '$2y$10$hEY4ev6LAjHYbU93BE40g.YKGu.ZHXzrRKLHuqkcZR5aAI7vAzGC6', 'admin');

-- Tabel untuk mata pelajaran/kursus
CREATE TABLE `courses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course_id` varchar(50) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(100) NOT NULL,
  `level` varchar(100) DEFAULT 'Beginner Level | 10 Lessons | 20 Hours',
  `rating` varchar(100) DEFAULT '★★★★★ (4.8/5 - 120 Reviews)',
  `price` varchar(20) DEFAULT 'Free',
  PRIMARY KEY (`id`),
  UNIQUE KEY `course_id` (`course_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Memasukkan data kursus awal
INSERT INTO `courses` (`course_id`, `title`, `description`, `image`) VALUES
('web-dev', 'Web Development Fundamentals', 'Learn the basics of HTML, CSS, and JavaScript to build modern websites from scratch.', 'images/kelas1.jpg'),
('data-science', 'Data Science with Python', 'Master data analysis and machine learning techniques using Python and popular libraries.', 'images/kelas2.webp'),
('digital-marketing', 'Digital Marketing Strategy', 'Develop comprehensive digital marketing strategies to grow your online presence and business.', 'images/kelas3.jpg');

-- Tabel untuk menghubungkan user dan kursus yang diambil (enrollments)
CREATE TABLE `enrollments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `progress` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `course_id` (`course_id`),
  CONSTRAINT `enrollments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `enrollments_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;