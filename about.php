<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>About - FilipinoBlog</title>
    <link rel="shortcut icon" type="x-icon" href="logo.png" />
    <link rel="stylesheet" href="bootstrap.min.css" />
    <link rel="stylesheet" href="about.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css"/>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg sticky-top">
      <div class="container">
        <a class="navbar-brand" href="index.php">
          <img
            src="logo.png"
            alt="FilipinoBlog Logo"
            width="30"
            height="30"
            class="d-inline-block align-top"
          />
          <span class="text-filipino">FilipinoBlog</span>
        </a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNav"
          aria-controls="navbarNav"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a class="nav-link" href="index.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="discover.php">Discover</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="about.php"
                >About</a
              >
            </li>
            <?php if (!isset($_SESSION['user_id'])): ?>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Log In</a>
                </li>
              <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">Dashboard</a>
                </li>
              <?php endif; ?>
            <li class="nav-item">
              <button id="themeToggle" class="btn btn-link nav-link">
                <i class="bi bi-sun-fill"></i>
              </button>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <header class="headimg d-flex align-items-center justify-content-center">
        <div class="container text-center">
          <h1 class="display-2 fw-bold mb-4">About FilipinoBlog</h1>
          <p class="lead">Empowering Filipino Voices in the Digital Age</p>
        </div>
      </header>
      

    <main class="container my-5">
      <section class="row mb-5">
        <div class="col-md-6">
          <h2 class="text-filipino mb-3">Our Mission</h2>
          <p>
            FilipinoBlog is dedicated to amplifying the voices of Filipino
            writers and bloggers. We provide a platform for sharing stories,
            experiences, and insights that reflect the rich tapestry of Filipino
            culture, both in the Philippines and across the global diaspora.
          </p>
        </div>
        <div class="col-md-6">
          <h2 class="text-filipino mb-3">Our Story</h2>
          <p>
            Founded in 2024, FilipinoBlog emerged from a desire to create a
            dedicated space for Filipino voices in the digital realm. Our
            founders, a group of passionate Filipino writers and tech
            enthusiasts, recognized the need for a platform that could showcase
            the diversity and depth of Filipino experiences and perspectives.
          </p>
        </div>
      </section>

      <section class="mb-5">
        <h2 class="text-filipino mb-4 text-center">What We Offer</h2>
        <div class="row row-cols-1 row-cols-md-3 g-4">
          <div class="col">
            <div class="card h-100 text-center">
              <div class="card-body">
                <i class="bi bi-people-fill text-filipino fs-1 mb-3"></i>
                <h3 class="card-title">Community</h3>
                <p class="card-text">
                  Connect with fellow Filipino writers and readers from around
                  the world.
                </p>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="card h-100 text-center">
              <div class="card-body">
                <i class="bi bi-laptop text-filipino fs-1 mb-3"></i>
                <h3 class="card-title">Platform</h3>
                <p class="card-text">
                  A user-friendly interface for creating, publishing, and
                  discovering content.
                </p>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="card h-100 text-center">
              <div class="card-body">
                <i class="bi bi-graph-up-arrow text-filipino fs-1 mb-3"></i>
                <h3 class="card-title">Growth</h3>
                <p class="card-text">
                  Opportunities for writers to expand their reach and hone their
                  craft.
                </p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="mb-5">
        <h2 class="text-filipino mb-4 text-center">Meet Our Team</h2>
        <div
          class="row row-cols-1 row-cols-md-3 row-cols-lg-5 g-4 justify-content-center"
        >
          <div class="col">
            <div class="h-100 text-center">
              <img
                src="condino.jpg"
                alt="Maria Santos"
                class="card-img-top rounded-circle mx-auto mt-3 team-member"
              />
              <div class="card-body">
                <h3 class="card-title">Mark Andrei A. Condino</h3>
                <p class="card-text">Founder & CEO</p>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="h-100 text-center">
              <img
                src="concepcion.jpg"
                alt="Liza Reyes"
                class="card-img-top rounded-circle mx-auto mt-3 team-member"
              />
              <div class="card-body">
                <h3 class="card-title">Marc Laurence Concepcion</h3>
                <p class="card-text">Head of Content</p>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="h-100 text-center">
              <img
                src="munoz.jfif"
                alt="Carlos Bautista"
                class="card-img-top rounded-circle mx-auto mt-3 team-member"
              />
              <div class="card-body">
                <h3 class="card-title">Marvin Aranas Munoz</h3>
                <p class="card-text">Community Manager</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="mb-5">
        <h2 class="text-filipino mb-4 text-center">Our Impact</h2>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
          <div class="col">
            <div class="card h-100 text-center">
              <div class="card-body">
                <h3 class="card-title display-4 fw-bold text-filipino">10k+</h3>
                <p class="card-text">Registered Writers</p>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="card h-100 text-center">
              <div class="card-body">
                <h3 class="card-title display-4 fw-bold text-filipino">30+</h3>
                <p class="card-text">Countries Represented</p>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="card h-100 text-center">
              <div class="card-body">
                <h3 class="card-title display-4 fw-bold text-filipino">50k+</h3>
                <p class="card-text">Articles Published</p>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="card h-100 text-center">
              <div class="card-body">
                <h3 class="card-title display-4 fw-bold text-filipino">1M+</h3>
                <p class="card-text">Monthly Readers</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="text-center mb-5">
        <h2 class="text-filipino mb-4">Join Our Community</h2>
        <p class="lead mb-4">
          Be part of the growing Filipino digital storytelling movement.
        </p>
        <?php if (!isset($_SESSION['user_id'])): ?>
          <a href="login.php" class="btn btn-start btn-lg">Start Writing Today</a>
          <?php else: ?>
            <a href="post.php" class="btn btn-start btn-lg">Start Writing Today</a>
        <?php endif; ?>
      </section>
    </main>

    <footer class="py-4 cente">
      <div class="container">
        <div class="row">
          <div class="col-md-4 mb-3 mb-md-0">
            <h5 class="text-filipino">FilipinoBlog</h5>
            <p class="text-muted small">
              Empowering Filipino Voices Since 2024
            </p>
          </div>
          <div class="col-md-4 mb-3 mb-md-0">
            <h5 class="text-filipino">Quick Links</h5>
            <ul class="list-unstyled">
              <li><a href="index.php">Home</a></li>
              <li><a href="discover.php">Discover</a></li>
              <li><a href="about.php">About</a></li>
              <?php if (!isset($_SESSION['user_id'])): ?>
                <li><a href="login.php">Log in</a></li>
                <?php else: ?>
                  <li><a href="dashboard.php">Dashboard</a></li>
              <?php endif; ?>
            </ul>
          </div>
          <div class="col-md-4">
            <h5 class="text-filipino">Connect With Us</h5>
            <ul class="list-inline">
              <li class="list-inline-item">
                <a href="https://www.facebook.com/" target = "_blank" class="facebook-link">
                  <i class="bi bi-facebook"></i>
                </a>
              </li>
              <li class="list-inline-item">
                <a href="https://x.com/?lang=en" target = "_blank" class="text-muted twitter-link">
                  <i class="bi bi-twitter"></i>
                </a>
              </li>
              <li class="list-inline-item">
                <a href="https://www.instagram.com/" target = "_blank" class="text-muted instagram-link">
                  <i class="bi bi-instagram"></i>
                </a>
              </li>
              <li class="list-inline-item">
                <a href="https://www.linkedin.com/" target = "_blank" class="text-muted linkedin-link">
                  <i class="bi bi-linkedin"></i>
                </a>
              </li>
            </ul>
          </div>
        </div>
        <hr class="my-4 bg-secondary" />
        <p class="text-center text-muted mb-0">
          &copy; 2024 FilipinoBlog. All rights reserved.
        </p>
      </div>
    </footer>
  </body>
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
  </script>
</html>
