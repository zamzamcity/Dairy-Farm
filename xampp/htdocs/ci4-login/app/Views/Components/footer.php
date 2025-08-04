<!-- Footer -->
<footer class="sticky-footer bg-white">
    <div class="container my-auto">
        <div class="copyright text-center my-auto">
            <span>&copy; 2025 Zam Zam Developers | All rights reserved.</span>
        </div>
    </div>
</footer>
<!-- End of Footer -->

<script>
  function toggleDarkTheme() {
    const body = document.body;
    const checkbox = document.getElementById('darkThemeToggle');

    if (checkbox && checkbox.checked) {
      body.classList.add('dark-theme');
      localStorage.setItem('theme', 'dark');
    } else {
      body.classList.remove('dark-theme');
      localStorage.setItem('theme', 'light');
    }
  }

  document.addEventListener('DOMContentLoaded', function () {
    const savedTheme = localStorage.getItem('theme');
    const checkbox = document.getElementById('darkThemeToggle');

    if (savedTheme === 'dark') {
      document.body.classList.add('dark-theme');
      if (checkbox) checkbox.checked = true;
    }
  });
</script>
