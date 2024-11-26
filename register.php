<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = $_POST['fullName'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';

    if (!empty($email) && !empty($password) && $password === $confirmPassword) {

        $stmt = $conn->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $errorMessage = "Email is already registered";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare('INSERT INTO users (fullName, email, password) VALUES (?, ?, ?)');
            $stmt->bind_param('sss', $fullName, $email, $hashedPassword);

            if ($stmt->execute()) {
                header('Location: login.php');
                exit();
            } else {
                $errorMessage = "Registration failed: " . $stmt->error;
            }
        }
    } else {
        $errorMessage = "Passwords do not match or fields are empty";
    }
}
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - FilipinoBlog</title>
    <link rel="shortcut icon" type="x-icon" href="index.php" />
    <link rel="stylesheet" href="bootstrap.min.css" />
    <link rel="stylesheet" href="register.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" />
</head>
<body>
<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="#">
            <img src="logo.png" alt="FilipinoBlog Logo" width="30" height="30" class="d-inline-block align-top" />
            <span class="text-filipino">FilipinoBlog</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="discover.php">Discover</a></li>
                <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                <li class="nav-item"><a class="nav-link active" aria-current="page" href="login.php">Log in</a></li>
                <li class="nav-item"><button id="themeToggle" class="btn btn-link nav-link"><i class="bi bi-sun-fill"></i></button></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4">Join FilipinoBlog</h2>
                    <form id="registerForm" method="POST" action="">
                        <div class="mb-3">
                            <label for="fullName" class="form-label">Full Name</label>
                            <input type="text" class="form-control" name="fullName" id="fullName" required />
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control" name="email" id="email" required />
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="password" id="password" minlength="8" maxlength="20" oninput="validatePasswordField()" required />
                                <button type="button" class="btn btn-outline-secondary" id="togglePassword" aria-label="Toggle password visibility">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <small id="passwordError" class="text-danger" style="display: none;">Password must be between 8 and 20 characters.</small>
                        </div>
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Confirm Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" required />
                                <button type="button" class="btn btn-outline-secondary" id="toggleConfirmPassword" aria-label="Toggle confirm password visibility">
                                    <i class="bi bi-eye"></i> 
                                </button>
                            </div>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="agreeTerms" required />
                            <label class="form-check-label" for="agreeTerms">I agree to the <a href="#" class="text-filipino">Terms of Service</a> and <a href="#" class="text-filipino">Privacy Policy</a></label>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn login">Create Account</button>
                        </div>
                    </form>
                    <?php if (isset($errorMessage)): ?>
                        <div class="alert alert-danger mt-3"><?= $errorMessage ?></div>
                    <?php endif; ?>
                    <p class="text-center mt-3">Already have an account? <a href="login.php" class="text-filipino">Log in</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="mt-5 py-4">
    <div class="container text-center">
        <p>&copy; 2024 FilipinoBlog. All rights reserved.</p>
        <ul class="list-inline">
            <li class="list-inline-item"><a href="#" class="text-muted">Terms of Service</a></li>
            <li class="list-inline-item"><a href="#" class="text-muted">Privacy</a></li>
            <li class="list-inline-item"><a href="#" class="text-muted">Contact</a></li>
        </ul>
    </div>
</footer>

<script src="bootstrap.bundle.min.js"></script>
<script>
    const themeToggle = document.getElementById("themeToggle");
    const htmlElement = document.documentElement;
    const iconElement = themeToggle.querySelector("i");

    function setTheme(theme) {
        htmlElement.setAttribute("data-bs-theme", theme);
        if (theme === "dark") {
            iconElement.classList.replace("bi-sun-fill", "bi-moon-fill");
        } else {
            iconElement.classList.replace("bi-moon-fill", "bi-sun-fill");
        }
    }

    window.addEventListener("DOMContentLoaded", () => {
        const savedTheme = localStorage.getItem("theme") || "light";
        setTheme(savedTheme);
    });

    themeToggle.addEventListener("click", () => {
        let currentTheme = htmlElement.getAttribute("data-bs-theme");
        let newTheme = currentTheme === "light" ? "dark" : "light";
        setTheme(newTheme);
        localStorage.setItem("theme", newTheme);
    });

    const passwordField = document.getElementById('password');
    const togglePasswordButton = document.getElementById('togglePassword');
    const togglePasswordIcon = togglePasswordButton.querySelector('i');

    togglePasswordButton.addEventListener('click', function () {
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);

        togglePasswordIcon.classList.toggle('bi-eye');
        togglePasswordIcon.classList.toggle('bi-eye-slash');
    });

    const confirmPasswordField = document.getElementById('confirmPassword');
    const toggleConfirmPasswordButton = document.getElementById('toggleConfirmPassword');
    const toggleConfirmPasswordIcon = toggleConfirmPasswordButton.querySelector('i');

    toggleConfirmPasswordButton.addEventListener('click', function () {
        const type = confirmPasswordField.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmPasswordField.setAttribute('type', type);

        toggleConfirmPasswordIcon.classList.toggle('bi-eye');
        toggleConfirmPasswordIcon.classList.toggle('bi-eye-slash');
    });

    function validatePasswordField() {
        const passwordInput = document.getElementById('password').value;
        const passwordError = document.getElementById('passwordError');
        const minLength = 8;
        const maxLength = 20;

        if (passwordInput.length < minLength || passwordInput.length > maxLength) {
            passwordError.style.display = 'block';
            passwordError.textContent = `Password must be between ${minLength} and ${maxLength} characters.`;
        } else {
            passwordError.style.display = 'none';
        }
    }
</script>
</body>
</html>
