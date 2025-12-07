<?php
include 'includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($email) && !empty($password)) {
        $stmt = $conn->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['user_id'] = $user['id']; 

                header('Location: dashboard.php');
                exit();
            } else {
                $errorMessage = "Incorrect password";
            }
        } else {
            $errorMessage = "User not found";
        }
    } else {
        $errorMessage = "Email and password are required";
    }
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FilipinoBlog</title>
    <link rel="shortcut icon" type="x-icon" href="assets/images/logo.png" />
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/login.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" />
</head>
<body>
<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="assets/images/logo.png" alt="FilipinoBlog Logo" width="30" height="30" class="d-inline-block align-top" />
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
                    <h2 class="text-center mb-4">Login to FilipinoBlog</h2>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control" name="email" id="email" required />
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="password" id="password" required />
                                <button type="button" class="btn btn-outline-secondary" id="togglePassword" aria-label="Toggle password visibility">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Log In</button>
                        </div>
                    </form>
                    <?php if (isset($errorMessage)): ?>
                        <div class="alert alert-danger mt-3"><?= $errorMessage ?></div>
                    <?php endif; ?>
                    <p class="text-center mt-3">Don't have an account? <a href="register.php" class="text-filipino">Sign up</a></p>
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

<script src="assets/js/bootstrap.bundle.min.js"></script>
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
        if (type === 'password') {
            togglePasswordIcon.classList.remove('bi-eye-slash');
            togglePasswordIcon.classList.add('bi-eye');
        } else {
            togglePasswordIcon.classList.remove('bi-eye');
            togglePasswordIcon.classList.add('bi-eye-slash');
        }
    });
</script>
</body>
</html>
