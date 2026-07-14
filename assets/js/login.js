function togglePassword() {
    const password = document.getElementById("password");
    const icon = document.querySelector(".fa-eye, .fa-eye-slash");

    if (password.type === "password") {
        password.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        password.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}

// ===============================
// GeoSant Luxury Login JavaScript
// ===============================

// Show / Hide Password
function togglePassword() {

    const password = document.getElementById("password");

    const icon = document.querySelector(".toggle-password i");

    if (password.type === "password") {

        password.type = "text";

        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");

    } else {

        password.type = "password";

        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");

    }

}

// ===============================
// Floating Input Effect
// ===============================

document.querySelectorAll(".input-field input").forEach(input => {

    input.addEventListener("focus", function () {

        this.parentElement.classList.add("active");

    });

    input.addEventListener("blur", function () {

        if (this.value === "") {

            this.parentElement.classList.remove("active");

        }

    });

});

// ===============================
// Login Button Ripple Animation
// ===============================

const loginButton = document.querySelector(".login-btn");

if (loginButton) {

    loginButton.addEventListener("click", function () {

        this.classList.add("clicked");

        setTimeout(() => {

            this.classList.remove("clicked");

        }, 250);

    });

}