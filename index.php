<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Filipino Blog</title>
    <link rel="stylesheet" href="index.css" />
    <link rel="shortcut icon" type="x-icon" href="logo.png" />
    <link rel="stylesheet" href="bootstrap.min.css" />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css"
    />
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
              <a class="nav-link active" aria-current="page" href="index.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="discover.php">Discover</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="about.php">About</a>
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

    <header class="headimg py-5">
      <div class="container text-center py-5">
        <h1 class="display-4 fw-bold mb-3">Share Your Filipino Voice</h1>
        <p class="lead mb-4">
          A platform for Filipino bloggers and writers to express their
          thoughts, stories, and ideas. Connect with your community and let your
          voice be heard.
        </p>
        <?php if (!isset($_SESSION['user_id'])): ?>
            <a href="login.php" class="btn btn-lg btn-outline-light me-2">Start Writing</a>
          <?php else: ?>
              <a href="post.php" class="btn btn-outline-light btn-lg">Start Writing</a>
          <?php endif; ?>
          <a href="discover.php" class="btn btn-outline-light btn-lg">Explore Blogs</a>
      </div>
    </header>

    <section class="py-5">
      <div class="container">
        <h2 class="text-center mb-5">Featured Stories</h2>
        <div class="row">
          <div class="col-md-4 mb-4">
            <div class="card h-100">
              <img
                src="https://images.unsplash.com/photo-1551352912-484163ad5be9?auto=format&fit=crop&w=800&q=80"
                class="card-img-top"
                alt="Philippine Beach"
              />
              <div class="card-body">
                <h5 class="card-title">The Beauty of Philippine Beaches</h5>
                <p class="card-text">
                  Exploring the hidden gems of our 7,641 islands...
                </p>
                <?php if (!isset($_SESSION['user_id'])): ?>
                  <a href="login.php" class="btn btn-filipino">Read More</a>
                <?php else: ?>
                  <a href="others.php" class="btn btn-filipino">Read More</a>
                <?php endif; ?>
              </div>
            </div>
          </div>
          <div class="col-md-4 mb-4">
            <div class="card h-100">
              <img
                src="https://images.unsplash.com/photo-1551352912-484163ad5be9?auto=format&fit=crop&w=800&q=80"
                class="card-img-top"
                alt="Filipino Street Food"
              />
              <div class="card-body">
                <h5 class="card-title">Filipino Street Food Adventures</h5>
                <p class="card-text">
                  From balut to isaw: A journey through local delicacies...
                </p>
                <?php if (!isset($_SESSION['user_id'])): ?>
                  <a href="login.php" class="btn btn-filipino">Read More</a>
                <?php else: ?>
                  <a href="others.php" class="btn btn-filipino">Read More</a>
                <?php endif; ?>
              </div>
            </div>
          </div>
          <div class="col-md-4 mb-4">
            <div class="card h-100">
              <img
                src="https://images.unsplash.com/photo-1551352912-484163ad5be9?auto=format&fit=crop&w=800&q=80"
                class="card-img-top"
                alt="Filipino Traditions"
              />
              <div class="card-body">
                <h5 class="card-title">Preserving Filipino Traditions</h5>
                <p class="card-text">
                  How modern Filipinos keep ancient customs alive...
                </p>
                <?php if (!isset($_SESSION['user_id'])): ?>
                  <a href="login.php" class="btn btn-filipino">Read More</a>
                <?php else: ?>
                  <a href="others.php" class="btn btn-filipino">Read More</a>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="py-5">
      <div class="container">
        <h2 class="text-center mb-5">Meet Our Filipino Bloggers</h2>
        <div class="row justify-content-center">
          <div class="col-md-3 col-sm-6 text-center mb-4">
            <img
              src="glazy.jpg"
              alt="Maria Santos"
              class="rounded-circle mb-3 img-size"
            />
            <h5>Maria Santos</h5>
            <p class="text-muted">Travel Enthusiast</p>
          </div>
          <div class="col-md-3 col-sm-6 text-center mb-4">
            <img
              src="glazy2.jpg"
              alt="Juan dela Cruz"
              class="rounded-circle mb-3 img-size"
            />
            <h5>Juan dela Cruz</h5>
            <p class="text-muted">Food Critic</p>
          </div>
          <div class="col-md-3 col-sm-6 text-center mb-4">
            <img
              src="glazy3.jpg"
              alt="Liza Reyes"
              class="rounded-circle mb-3 img-size"
            />
            <h5>Liza Reyes</h5>
            <p class="text-muted">Culture Writer</p>
          </div>
        </div>
      </div>
    </section>
    

    <section class="py-5">
      <div class="container">
        <h2 class="text-center mb-5">Discover the FilipinoBlog Community</h2>
        <div class="row">
          <div class="col-md-6 mb-4">
            <div class="card h-100 border-3 shadow-sm">
              <div class="card-body">
                <h3 class="card-title text-center mb-4">Why Join Us?</h3>
                <ul class="list-unstyled">
                  <li class="mb-3">
                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                    Connect with fellow Filipino writers and readers
                  </li>
                  <li class="mb-3">
                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                    Share your unique perspective on Filipino culture and
                    experiences
                  </li>
                  <li class="mb-3">
                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                    Gain exposure to a dedicated audience of Filipino literature
                    enthusiasts
                  </li>
                  <li class="mb-3">
                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                    Participate in writing challenges and themed events
                  </li>
                  <li>
                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                    Improve your writing skills through community feedback and
                    workshops
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-md-6 mb-4">
            <div class="card h-100 border-3 shadow-sm">
              <div class="card-body">
                <h3 class="card-title text-center mb-4">
                  Community Highlights
                </h3>
                <div class="row text-center">
                  <div class="col-6 mb-4">
                    <h4 class="display-4 fw-bold text-filipino">10k+</h4>
                    <p class="text-muted">Active Writers</p>
                  </div>
                  <div class="col-6 mb-4">
                    <h4 class="display-4 fw-bold text-filipino">50k+</h4>
                    <p class="text-muted">Monthly Readers</p>
                  </div>
                  <div class="col-6">
                    <h4 class="display-4 fw-bold text-filipino">5k+</h4>
                    <p class="text-muted">Articles Published</p>
                  </div>
                  <div class="col-6">
                    <h4 class="display-4 fw-bold text-filipino">100+</h4>
                    <p class="text-muted">Writing Circles</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="text-center mt-4">
        <?php if (!isset($_SESSION['user_id'])): ?>
          <a href="login.php" class="btn btn-filipino btn-lg">Join FilipinoBlog Today</a>
          <?php else: ?>
            <a href="post.php" class="btn btn-filipino btn-lg">Write a Blog Now</a>
        <?php endif; ?>
        </div>
      </div>
    </section>

    <footer class="py-4">
      <div class="container text-center">
        <p>&copy; 2024 FilipinoBlog. All rights reserved.</p>
        <ul class="list-inline">
          <li class="list-inline-item">
            <a href="#" class="text-muted">Terms of Service</a>
          </li>
          <li class="list-inline-item">
            <a href="#" class="text-muted">Privacy</a>
          </li>
          <li class="list-inline-item">
            <a href="#" class="text-muted">Contact</a>
          </li>
        </ul>
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
