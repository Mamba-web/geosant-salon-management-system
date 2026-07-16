document.addEventListener("DOMContentLoaded", function () {

    const body = document.body;
    const themeToggle = document.getElementById("themeToggle");

    // Apply saved theme
    const savedTheme = localStorage.getItem("theme");

    if (savedTheme === "dark") {
        body.classList.add("dark-mode");

        if (themeToggle) {
            themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
        }
    }

    // Toggle theme
    if (themeToggle) {

        themeToggle.addEventListener("click", function () {

            body.classList.toggle("dark-mode");

            if (body.classList.contains("dark-mode")) {

                localStorage.setItem("theme", "dark");
                themeToggle.innerHTML = '<i class="fas fa-sun"></i>';

            } else {

                localStorage.setItem("theme", "light");
                themeToggle.innerHTML = '<i class="fas fa-moon"></i>';

            }

        });

    }

});