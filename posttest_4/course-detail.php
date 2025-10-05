<?php
session_start();

// A PHP array to store course data, replacing the need for multiple HTML files
$courses = [
    "web-dev" => [
        "title" => "Web Development Fundamentals",
        "image" => "images/kelas1.jpg",
        "level" => "Beginner Level | 10 Lessons | 20 Hours",
        "rating" => "★★★★★ (4.8/5 - 120 Reviews)",
        "price" => "Free",
        "overview" => "Learn the basics of HTML, CSS, and JavaScript to build modern websites from scratch.",
        "learnings" => [
            "Master HTML5 to create structured web content",
            "Style websites with CSS3 for responsive design",
            "Add interactivity using JavaScript basics"
        ],
        "requirements" => [
            "Basic computer literacy",
            "A modern web browser",
            "Stable internet connection"
        ],
        "instructor" => [
            "name" => "Dio Brando",
            "title" => "Expert Web Developer with 10+ years experience. Passionate about teaching modern web technologies.",
            "students" => "300+",
            "rating" => "4.7"
        ]
    ],
    "data-science" => [
        "title" => "Data Science with Python",
        "image" => "images/kelas2.webp",
        "level" => "Beginner Level | 10 Lessons | 20 Hours",
        "rating" => "★★★★★ (4.8/5 - 120 Reviews)",
        "price" => "Free",
        "overview" => "Master data analysis and machine learning techniques using Python and popular libraries.",
        "learnings" => [
            "Perform data analysis with Pandas",
            "Visualize data using Matplotlib and Seaborn",
            "Build machine learning models with Scikit-learn"
        ],
        "requirements" => [
            "Basic Python programming knowledge",
            "Familiarity with statistics",
            "Laptop with Python 3 installed"
        ],
        "instructor" => [
            "name" => "Jonathan Joestar",
            "title" => "Expert Data Science with 15+ years experience. Passionate about teaching Data Science.",
            "students" => "500+",
            "rating" => "4.9"
        ]
    ],
    "digital-marketing" => [
        "title" => "Digital Marketing Strategy",
        "image" => "images/kelas3.jpg",
        "level" => "Beginner Level | 10 Lessons | 20 Hours",
        "rating" => "★★★★★ (4.8/5 - 120 Reviews)",
        "price" => "Free",
        "overview" => "Develop comprehensive digital marketing strategies to grow your online presence and business.",
        "learnings" => [
            "Create effective SEO strategies",
            "Run successful social media campaigns",
            "Analyze marketing performance with analytics tools"
        ],
        "requirements" => [
            "Basic understanding of marketing concepts",
            "Access to social media platforms",
            "Interest in digital trends"
        ],
        "instructor" => [
            "name" => "Kujo Jotaro",
            "title" => "Expert Digital Marketing with 13+ years experience. Passionate about teaching Digital Marketing Strategy.",
            "students" => "100+",
            "rating" => "4.3"
        ]
    ]
];

// Get the requested course ID from the URL query string
$course_id = isset($_GET['course']) ? $_GET['course'] : null;

// Check if the requested course exists in our array
if (!$course_id || !array_key_exists($course_id, $courses)) {
    // If not, redirect to the home page
    header("Location: index.php");
    exit();
}

// Get the specific course data to display
$course = $courses[$course_id];
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
        <div class="container">
            <div class="logo">LMS Platform</div>
            <nav>
                <ul class="nav-list">
                    <li><a href="index.php" class="nav-link">Home</a></li>
                    <li><a href="index.php#courses" class="nav-link">Courses</a></li>
                    <?php if (isset($_SESSION['username'])): ?>
                        <li><a href="dashboard.php" class="nav-link">Dashboard</a></li>
                        <li><a href="logout.php" class="nav-link">Logout</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
            <button id="theme-toggle-detail">🌙</button>
        </div>
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
                    <button id="enroll-detail-btn" class="btn btn-primary" data-course="<?php echo $course_id; ?>">Enroll Now</button>
                </div>
            </div>

            <div class="course-tabs">
                <button class="tab-btn active" data-tab="overview">Overview</button>
                <button class="tab-btn" data-tab="curriculum">Curriculum</button>
                <button class="tab-btn" data-tab="instructor">Instructor</button>
            </div>

            <div class="tab-content">
                <div id="overview" class="tab-pane active">
                    <h2>Course Overview</h2>
                    <p><?php echo htmlspecialchars($course['overview']); ?></p>
                    <h3>What You'll Learn</h3>
                    <ul>
                        <?php foreach ($course['learnings'] as $learning): ?>
                            <li><?php echo htmlspecialchars($learning); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <h3>Requirements</h3>
                    <ul>
                        <?php foreach ($course['requirements'] as $requirement): ?>
                            <li><?php echo htmlspecialchars($requirement); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div id="curriculum" class="tab-pane">
                    <h2>Curriculum</h2>
                    <p>Curriculum details coming soon.</p>
                </div>

                <div id="instructor" class="tab-pane">
                    <h2>Instructor</h2>
                    <div class="instructor-info">
                        <img src="https://via.placeholder.com/100x100?text=Instructor" alt="Instructor" class="instructor-avatar">
                        <div>
                            <h3><?php echo htmlspecialchars($course['instructor']['name']); ?></h3>
                            <p><?php echo htmlspecialchars($course['instructor']['title']); ?></p>
                            <div class="instructor-stats">
                                <span>Students: <?php echo htmlspecialchars($course['instructor']['students']); ?></span>
                                <span>Rating: <?php echo htmlspecialchars($course['instructor']['rating']); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
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