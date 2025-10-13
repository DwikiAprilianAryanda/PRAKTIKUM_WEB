document.addEventListener("DOMContentLoaded", function () {
  // Fungsi untuk beralih antara mode terang dan gelap (dark mode)
  function applyTheme(darkMode, toggle) {
    if (darkMode) {
      document.body.classList.add("dark-mode");
      toggle.textContent = "☀️"; // Ikon matahari untuk mode terang
    } else {
      document.body.classList.remove("dark-mode");
      toggle.textContent = "🌙"; // Ikon bulan untuk mode gelap
    }
  }

  // Menangani tombol untuk mengubah tema
  const themeToggle = document.getElementById("theme-toggle");
  if (themeToggle) {
    // Memeriksa preferensi tema yang tersimpan di localStorage
    let darkMode = localStorage.getItem("darkMode") === "true";
    applyTheme(darkMode, themeToggle); // Terapkan tema saat halaman dimuat

    // Tambahkan event listener untuk tombol tema
    themeToggle.addEventListener("click", function () {
      darkMode = !darkMode; // Balikkan status mode gelap
      localStorage.setItem("darkMode", darkMode); // Simpan preferensi baru
      applyTheme(darkMode, themeToggle); // Terapkan tema yang baru
    });
  }
  
  // Menangani tombol "Lihat/Sembunyikan" password pada form login
  const toggleLoginPassword = document.getElementById("toggleLoginPassword");
  const loginPasswordInput = document.querySelector("#loginForm #password");
  if (toggleLoginPassword && loginPasswordInput) {
    toggleLoginPassword.addEventListener("click", function () {
      const type = loginPasswordInput.getAttribute("type") === "password" ? "text" : "password";
      loginPasswordInput.setAttribute("type", type);
      this.textContent = type === "password" ? "Lihat" : "Sembunyikan";
    });
  }

  // Menangani tombol "Lihat/Sembunyikan" password pada form registrasi
  const toggleRegisterPassword = document.getElementById("toggleRegisterPassword");
  const registerPasswordInput = document.querySelector("#registerForm #password");
  if (toggleRegisterPassword && registerPasswordInput) {
    toggleRegisterPassword.addEventListener("click", function () {
      const type = registerPasswordInput.getAttribute("type") === "password" ? "text" : "password";
      registerPasswordInput.setAttribute("type", type);
      this.textContent = type === "password" ? "Lihat" : "Sembunyikan";
    });
  }

  // Menangani fungsionalitas tab pada halaman detail kursus
  const tabBtns = document.querySelectorAll(".tab-btn");
  const tabPanes = document.querySelectorAll(".tab-pane");
  if (tabBtns.length > 0 && tabPanes.length > 0) {
    tabBtns.forEach(btn => {
      btn.addEventListener("click", function () {
        const tab = this.getAttribute("data-tab");
        
        // Nonaktifkan semua tombol dan panel tab
        tabBtns.forEach(b => b.classList.remove("active"));
        tabPanes.forEach(p => p.classList.remove("active"));
        
        // Aktifkan tombol dan panel tab yang dipilih
        this.classList.add("active");
        document.getElementById(tab).classList.add("active");
      });
    });
  }

  // Animasi fade-in untuk kartu kursus saat halaman dimuat
  const courseCards = document.querySelectorAll(".course-card");
  courseCards.forEach((card, index) => {
    card.style.opacity = "0";
    setTimeout(() => {
      card.style.transition = "opacity 0.5s ease-in-out";
      card.style.opacity = "1";
    }, 150 * index);
  });
});