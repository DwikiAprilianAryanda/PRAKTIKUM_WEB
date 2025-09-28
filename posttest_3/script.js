document.addEventListener("DOMContentLoaded", function () {
  // Course data with quizzes, image paths, learnings, and requirements
  const courses = {
    "web-dev": {
      title: "Web Development Fundamentals",
      description: "Learn the basics of HTML, CSS, and JavaScript to build modern websites from scratch.",
      image: "images/kelas1.jpg",
      quiz: {
        question: "What does HTML stand for?",
        answer: "HyperText Markup Language"
      },
      learnings: [
        "Master HTML5 to create structured web content",
        "Style websites with CSS3 for responsive design",
        "Add interactivity using JavaScript basics"
      ],
      requirements: [
        "Basic computer literacy",
        "A modern web browser",
        "Stable internet connection"
      ]
    },
    "data-science": {
      title: "Data Science with Python",
      description: "Master data analysis and machine learning techniques using Python and popular libraries.",
      image: "images/kelas2.webp",
      quiz: {
        question: "Which Python library is commonly used for data visualization?",
        answer: "Matplotlib"
      },
      learnings: [
        "Perform data analysis with Pandas",
        "Visualize data using Matplotlib and Seaborn",
        "Build machine learning models with Scikit-learn"
      ],
      requirements: [
        "Basic Python programming knowledge",
        "Familiarity with statistics",
        "Laptop with Python 3 installed"
      ]
    },
    "digital-marketing": {
      title: "Digital Marketing Strategy",
      description: "Develop comprehensive digital marketing strategies to grow your online presence and business.",
      image: "images/kelas3.jpg",
      quiz: {
        question: "What does SEO stand for?",
        answer: "Search Engine Optimization"
      },
      learnings: [
        "Create effective SEO strategies",
        "Run successful social media campaigns",
        "Analyze marketing performance with analytics tools"
      ],
      requirements: [
        "Basic understanding of marketing concepts",
        "Access to social media platforms",
        "Interest in digital trends"
      ]
    }
  };

  // Check login status and get current user
  const isLoggedIn = localStorage.getItem("loggedIn") === "true";
  const currentUser = localStorage.getItem("currentUser");
  const loginLink = document.getElementById("login-link");
  const registerLink = document.getElementById("register-link");
  const dashboardLink = document.getElementById("dashboard-link");
  const logoutLink = document.getElementById("logout-link");

  if (isLoggedIn && currentUser) {
    if (loginLink) loginLink.style.display = "none";
    if (registerLink) registerLink.style.display = "none";
    if (dashboardLink) dashboardLink.style.display = "block";
    if (logoutLink) logoutLink.style.display = "block";
  }

  // Display username in dashboard
  const usernameDisplay = document.getElementById("username-display");
  if (usernameDisplay && isLoggedIn && currentUser) {
    usernameDisplay.textContent = currentUser;
  }

  // Logout functionality for main pages
  if (logoutLink) {
    logoutLink.addEventListener("click", function (e) {
      e.preventDefault();
      localStorage.removeItem("loggedIn");
      localStorage.removeItem("currentUser");
      alert("You have been logged out!");
      window.location.href = "index.html";
    });
  }

// Toggle show/hide password login
const toggleLoginPassword = document.getElementById("toggleLoginPassword");
const loginPasswordInput = document.querySelector("#loginForm #password");
if (toggleLoginPassword && loginPasswordInput) {
  toggleLoginPassword.addEventListener("click", function () {
    if (loginPasswordInput.type === "password") {
      loginPasswordInput.type = "text";
      toggleLoginPassword.textContent = "Sembunyikan";
    } else {
      loginPasswordInput.type = "password";
      toggleLoginPassword.textContent = "Lihat";
    }
  });
}

// Toggle show/hide password register
const toggleRegisterPassword = document.getElementById("toggleRegisterPassword");
const registerPasswordInput = document.querySelector("#registerForm #password");
if (toggleRegisterPassword && registerPasswordInput) {
  toggleRegisterPassword.addEventListener("click", function () {
    if (registerPasswordInput.type === "password") {
      registerPasswordInput.type = "text";
      toggleRegisterPassword.textContent = "Sembunyikan";
    } else {
      registerPasswordInput.type = "password";
      toggleRegisterPassword.textContent = "Lihat";
    }
  });
}

  // Validasi Login
  const loginForm = document.getElementById("loginForm");
  if (loginForm) {
    loginForm.addEventListener("submit", function (e) {
      e.preventDefault();
      const username = loginForm.username.value.trim();
      const password = loginForm.password.value.trim();
      let errorMsg = "";
      if (username.length < 4) {
        errorMsg += "Username minimal 4 karakter.\n";
      }
      if (password.length < 6) {
        errorMsg += "Password minimal 6 karakter.\n";
      }
      if (errorMsg) {
        alert(errorMsg);
        return;
      }
      const userData = JSON.parse(localStorage.getItem("userData")) || {};
      if (!userData[username]) {
        alert("Anda harus registrasi terlebih dahulu sebelum login!");
        window.location.href = "register.html";
        return;
      }
      if (password !== userData[username].password) {
        alert("Username atau password salah!");
        return;
      }
      alert("Login berhasil!");
      localStorage.setItem("loggedIn", true);
      localStorage.setItem("currentUser", username);
      window.location.href = "dashboard.html";
    });
  }

  // Validasi Register
  const registerForm = document.getElementById("registerForm");
  if (registerForm) {
    registerForm.addEventListener("submit", function (e) {
      e.preventDefault();
      const username = registerForm.username.value.trim();
      const email = registerForm.email.value.trim();
      const password = registerForm.password.value.trim();
      let errorMsg = "";
      if (username.length < 4) {
        errorMsg += "Username minimal 4 karakter.\n";
      }
      if (!email.match(/^[^@\s]+@[^@\s]+\.[^@\s]+$/)) {
        errorMsg += "Email tidak valid.\n";
      }
      if (password.length < 6) {
        errorMsg += "Password minimal 6 karakter.\n";
      }
      if (errorMsg) {
        alert(errorMsg);
        return;
      }
      const userData = JSON.parse(localStorage.getItem("userData")) || {};
      if (userData[username]) {
        alert("Username sudah terdaftar!");
        return;
      }
      userData[username] = { email, password, enrolledCourses: [], progress: {} };
      localStorage.setItem("userData", JSON.stringify(userData));
      alert("Registrasi berhasil!");
      localStorage.setItem("loggedIn", true);
      localStorage.setItem("currentUser", username);
      window.location.href = "dashboard.html";
    });
  }

  // Contact Form Submission
  const contactForm = document.getElementById("contactForm");
  if (contactForm) {
    contactForm.addEventListener("submit", function (e) {
      e.preventDefault();
      alert("Message sent successfully! We'll get back to you soon.");
      contactForm.reset();
    });
  }

  // Animasi judul courses
  const coursesTitle = document.getElementById("coursesTitle");
  if (coursesTitle) {
    coursesTitle.style.opacity = 0;
    setTimeout(() => {
      coursesTitle.style.transition = "opacity 1s";
      coursesTitle.style.opacity = 1;
    }, 400);
  }

  // Floating CTA
  const ctaBtn = document.getElementById("ctaBtn");
  if (ctaBtn) {
    ctaBtn.addEventListener("mouseenter", function () {
      ctaBtn.style.transform = "scale(1.08)";
      ctaBtn.style.background = "#2563eb";
    });
    ctaBtn.addEventListener("mouseleave", function () {
      ctaBtn.style.transform = "scale(1)";
      ctaBtn.style.background = "";
    });
    ctaBtn.addEventListener("click", function () {
      if (!isLoggedIn) {
        alert("Please login or register to join!");
        window.location.href = "login.html";
      } else {
        alert("Welcome back! Explore our courses.");
        window.location.href = "#courses";
      }
    });
  }

  // Theme toggle for main pages
  const themeToggle = document.getElementById("theme-toggle");
  if (themeToggle) {
    let darkMode = localStorage.getItem("darkMode") === "true";
    applyTheme(darkMode, themeToggle);
    themeToggle.addEventListener("click", function () {
      darkMode = !darkMode;
      localStorage.setItem("darkMode", darkMode);
      applyTheme(darkMode, themeToggle);
    });
  }

  // Function to apply theme
  function applyTheme(darkMode, toggle) {
    if (darkMode) {
      document.body.classList.add("dark-mode");
      toggle.textContent = "☀️";
      toggle.style.background = "#222";
    } else {
      document.body.classList.remove("dark-mode");
      toggle.textContent = "🌙";
      toggle.style.background = "#fff3";
    }
  }

  // Animasi fade in untuk course cards
  document.querySelectorAll(".course-card").forEach((card, i) => {
    card.style.opacity = 0;
    setTimeout(() => {
      card.style.transition = "opacity 0.7s";
      card.style.opacity = 1;
    }, 700 + i * 200);
  });

  // Dashboard Course Display
  function updateDashboard() {
    const myCourses = document.getElementById("my-courses");
    const progressList = document.getElementById("progress-list");
    if (myCourses && progressList && currentUser) {
      myCourses.innerHTML = "";
      progressList.innerHTML = "";
      const userData = JSON.parse(localStorage.getItem("userData")) || {};
      const user = userData[currentUser] || { enrolledCourses: [], progress: {} };
      const enrolledCourses = user.enrolledCourses || [];
      enrolledCourses.forEach(courseId => {
        const course = courses[courseId];
        if (course) {
          const detailPage = {
            "web-dev": "web-dev-detail.html",
            "data-science": "data-science-detail.html",
            "digital-marketing": "digital-marketing-detail.html"
          }[courseId];
          const courseCard = document.createElement("div");
          courseCard.className = "course-card";
          courseCard.innerHTML = `
            <
            <img src="${course.image}" alt="${course.title}" class="course-image">
            <div class="course-content">
              <h3 class="course-title">${course.title}</h3>
              <p class="course-description">${course.description}</p>
              <a href="${detailPage}" class="btn btn-secondary view-details" data-course="${courseId}">View Details</a>
            </div>
          `;
          myCourses.appendChild(courseCard);

          const progress = user.progress[courseId] || 0;
          const progressItem = document.createElement("div");
          progressItem.className = "progress-item";
          progressItem.innerHTML = `
            <h4>${course.title}</h4>
            <p>Progress: ${progress}%</p>
            <div class="progress-bar"><div class="progress-fill" style="width: ${progress}%"></div></div>
          `;
          progressList.appendChild(progressItem);
        }
      });
    }
  }

  if (document.getElementById("my-courses")) {
    updateDashboard();
  }

  // Course Detail Page Functionality
  if (window.location.pathname.includes("-detail.html")) {
    // Determine course ID from filename
    const path = window.location.pathname;
    let courseId;
    if (path.includes("web-dev-detail.html")) {
      courseId = "web-dev";
    } else if (path.includes("data-science-detail.html")) {
      courseId = "data-science";
    } else if (path.includes("digital-marketing-detail.html")) {
      courseId = "digital-marketing";
    }
    const course = courses[courseId];

    // Login status for detail page
    const dashboardLinkDetail = document.getElementById("dashboard-link-detail");
    const logoutLinkDetail = document.getElementById("logout-link-detail");
    if (isLoggedIn && currentUser) {
      if (dashboardLinkDetail) dashboardLinkDetail.style.display = "block";
      if (logoutLinkDetail) logoutLinkDetail.style.display = "block";
    }
    if (logoutLinkDetail) {
      logoutLinkDetail.addEventListener("click", function (e) {
        e.preventDefault();
        localStorage.removeItem("loggedIn");
        localStorage.removeItem("currentUser");
        alert("You have been logged out!");
        window.location.href = "index.html";
      });
    }

    // Theme toggle for detail page
    const themeToggleDetail = document.getElementById("theme-toggle-detail");
    if (themeToggleDetail) {
      let darkMode = localStorage.getItem("darkMode") === "true";
      applyTheme(darkMode, themeToggleDetail);
      themeToggleDetail.addEventListener("click", function () {
        darkMode = !darkMode;
        localStorage.setItem("darkMode", darkMode);
        applyTheme(darkMode, themeToggleDetail);
      });
    }

    // Tab functionality
    const tabBtns = document.querySelectorAll(".tab-btn");
    const tabPanes = document.querySelectorAll(".tab-pane");
    tabBtns.forEach(btn => {
      btn.addEventListener("click", function () {
        const tab = this.getAttribute("data-tab");
        tabBtns.forEach(b => b.classList.remove("active"));
        this.classList.add("active");
        tabPanes.forEach(p => p.classList.remove("active"));
        document.getElementById(tab).classList.add("active");
      });
    });

    // Enrollment in detail page
    const enrollDetailBtn = document.getElementById("enroll-detail-btn");
    if (enrollDetailBtn && course && currentUser) {
      enrollDetailBtn.addEventListener("click", function () {
        if (!isLoggedIn) {
          alert("Please login to enroll in this course!");
          window.location.href = "login.html";
          return;
        }
        const userData = JSON.parse(localStorage.getItem("userData")) || {};
        const user = userData[currentUser] || { enrolledCourses: [], progress: {} };
        if (!user.enrolledCourses.includes(courseId)) {
          user.enrolledCourses.push(courseId);
          user.progress[courseId] = 0; // Initialize progress at 0
          userData[currentUser] = user;
          localStorage.setItem("userData", JSON.stringify(userData));
          alert(`Successfully enrolled in ${course.title}!`);
          enrollDetailBtn.textContent = "Enrolled";
          enrollDetailBtn.disabled = true;
          enrollDetailBtn.classList.remove("btn-primary");
          enrollDetailBtn.classList.add("btn-secondary");
        } else {
          alert("You are already enrolled in this course!");
        }
      });
    }

    // Check if already enrolled
    if (currentUser) {
      const userData = JSON.parse(localStorage.getItem("userData")) || {};
      const user = userData[currentUser] || { enrolledCourses: [], progress: {} };
      if (user.enrolledCourses.includes(courseId) && enrollDetailBtn) {
        enrollDetailBtn.textContent = "Enrolled";
        enrollDetailBtn.disabled = true;
        enrollDetailBtn.classList.remove("btn-primary");
        enrollDetailBtn.classList.add("btn-secondary");
      }

      // Progress update functionality
      const progressInput = document.getElementById("progress-input");
      const updateProgressBtn = document.getElementById("update-progress-btn");
      if (progressInput && updateProgressBtn) {
        // Display current progress
        progressInput.value = user.progress[courseId] || 0;
        updateProgressBtn.addEventListener("click", function () {
          if (!isLoggedIn || !currentUser) {
            alert("Please login to update progress!");
            window.location.href = "login.html";
            return;
          }
          if (!user.enrolledCourses.includes(courseId)) {
            alert("You must enroll in this course to update progress!");
            return;
          }
          const newProgress = parseInt(progressInput.value);
          if (isNaN(newProgress) || newProgress < 0 || newProgress > 100) {
            alert("Progress must be a number between 0 and 100!");
            return;
          }
          user.progress[courseId] = newProgress;
          userData[currentUser] = user;
          localStorage.setItem("userData", JSON.stringify(userData));
          alert(`Progress for ${course.title} updated to ${newProgress}%!`);
        });
      }
    }
  }
});