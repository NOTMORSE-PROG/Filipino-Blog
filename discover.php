<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discover - FilipinoBlog</title>
    <link rel="shortcut icon" type="x-icon" href="logo.png" />
    <link rel="stylesheet" href="bootstrap.min.css" />
    <link rel="stylesheet" href="discover.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css"/>
</head>
<body>
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
          <a class="navbar-brand" href="#">
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
                <a class="nav-link active" aria-current="page" href="discover.php">Discover</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="about.php"
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

      <main class="container py-5">
        <h1 class="text-center mb-5">Discover Filipino Blogs</h1>
        <div class="row mb-4">
            <div class="col-md-8 mb-3 mb-md-0">
                <input id="searchInput" type="search" class="form-control" placeholder="Search for blogs, topics, or authors...">
            </div>
            <div class="col-md-4">
                <select id="categorySelect" class="form-select">
                    <option value="" selected>All Categories</option>
                    <option value="Travel">Travel</option>
                    <option value="Food">Food</option>
                    <option value="Culture">Culture</option>
                    <option value="Lifestyle">Lifestyle</option>
                    <option value="Technology">Technology</option>
                </select>
            </div>
        </div>

        <div id="blogContainer" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <div class="col">
                <div class="card h-100" data-title="The Beauty of Philippine Beaches" data-content="Explore the pristine shores and crystal-clear waters of the Philippines' most beautiful beaches." data-author="Maria Santos" data-category="Travel">
                    <img src="https://images.unsplash.com/photo-1518509562904-e7ef99cdcc86?auto=format&fit=crop&w=800&q=80" class="card-img-top post-image" alt="Philippine Beach">
                    <div class="card-body">
                        <h5 class="card-title">The Beauty of Philippine Beaches</h5>
                        <p class="card-text">Explore the pristine shores and crystal-clear waters of the Philippines' most beautiful beaches.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">By Maria Santos</small>
                            <span class="badge bg-primary rounded-pill">Travel</span>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-top-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">5 min read</small>
                            <button type="button" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-bookmark"></i> Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100" data-title="Filipino Cuisine: A Gastronomic Journey" data-content="Discover the rich flavors and diverse culinary traditions of the Philippines." data-author="Juan dela Cruz" data-category="Food">
                    <img src="https://images.unsplash.com/photo-1528137871618-79d2761e3fd5?auto=format&fit=crop&w=800&q=80" class="card-img-top post-image" alt="Filipino Cuisine">
                    <div class="card-body">
                        <h5 class="card-title">Filipino Cuisine: A Gastronomic Journey</h5>
                        <p class="card-text">Discover the rich flavors and diverse culinary traditions of the Philippines.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">By Juan dela Cruz</small>
                            <span class="badge bg-primary rounded-pill">Food</span>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-top-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">7 min read</small>
                            <button type="button" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-bookmark"></i> Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100" data-title="The Rich History of Philippine Literature" data-content="Explore the evolution of Filipino literature from pre-colonial times to the modern era." data-author="Ana Reyes" data-category="Culture">
                    <img src="https://images.unsplash.com/photo-1533478684236-61e1192879e8?q=80&w=2069&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="card-img-top post-image" alt="Philippine Literature">
                    <div class="card-body">
                        <h5 class="card-title">The Rich History of Philippine Literature</h5>
                        <p class="card-text">Explore the evolution of Filipino literature from pre-colonial times to the modern era.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">By Ana Reyes</small>
                            <span class="badge bg-primary rounded-pill">Culture</span>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-top-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">10 min read</small>
                            <button type="button" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-bookmark"></i> Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100" data-title="Colorful Filipino Festivals" data-content="Experience the vibrant and lively festivals that showcase Filipino culture and traditions." data-author="Carlos Gomez" data-category="Culture">
                    <img src="https://images.unsplash.com/photo-1518548419970-58e3b4079ab2?auto=format&fit=crop&w=800&q=80" class="card-img-top post-image" alt="Filipino Festivals">
                    <div class="card-body">
                        <h5 class="card-title">Colorful Filipino Festivals</h5>
                        <p class="card-text">Experience the vibrant and lively festivals that showcase Filipino culture and traditions.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">By Carlos Gomez</small>
                            <span class="badge bg-primary rounded-pill">Culture</span>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-top-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">8 min read</small>
                            <button type="button" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-bookmark"></i> Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100" data-title="Rising Filipino Tech Startups" data-content="Discover the innovative Filipino startups that are making waves in the tech industry." data-author="Mia Lopez" data-category="Technology">
                    <img src="https://images.unsplash.com/photo-1621924239958-8815bd30e670?auto=format&fit=crop&w=800&q=80" class="card-img-top post-image" alt="Filipino Tech Startups">
                    <div class="card-body">
                        <h5 class="card-title">Rising Filipino Tech Startups</h5>
                        <p class="card-text">Discover the innovative Filipino startups that are making waves in the tech industry.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">By Mia Lopez</small>
                            <span class="badge bg-primary rounded-pill">Technology</span>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-top-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">6 min read</small>
                            <button type="button" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-bookmark"></i> Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100" data-title="Modern Filipino Fashion Trends" data-content="Explore the latest fashion trends in the Philippines, blending traditional and contemporary styles." data-author="Sofia Cruz" data-category="Lifestyle">
                    <img src="https://images.unsplash.com/photo-1584559582128-b8be739912e1?auto=format&fit=crop&w=800&q=80" class="card-img-top post-image" alt="Filipino Fashion">
                    <div class="card-body">
                        <h5 class="card-title">Modern Filipino Fashion Trends</h5>
                        <p class="card-text">Explore the latest fashion trends in the Philippines, blending traditional and contemporary styles.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">By Sofia Cruz</small>
                            <span class="badge bg-primary rounded-pill">Lifestyle</span>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-top-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">5 min read</small>
                            <button type="button" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-bookmark"></i> Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

        <nav aria-label="Page navigation" class="mt-5">
            <ul class="pagination justify-content-center">
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                </li>
                <li class="page-item active" aria-current="page">
                    <a class="page-link" href="#">1</a>
                </li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                    <a class="page-link" href="#">Next</a>
                </li>
            </ul>
        </nav>
    </main>

    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>About FilipinoBlog</h5>
                    <p>Discover and share the best Filipino content from across the web.</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                    <li><a href="index.php" class="text-muted">Home</a></li>
                    <li><a href="discover.php" class="text-muted">Discover</a></li>
                    <li><a href="about.php" class="text-muted">About</a></li>
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <li><a href="login.php" class="text-muted">Log in</a></li>
                        <?php else: ?>
                        <li><a href="dashboard.php" class="text-muted">Dashboard</a></li>
                    <?php endif; ?>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5 class="text-filipino">Connect With Us</h5>
                    <ul class="list-inline">
                    <li class="list-inline-item">
                        <a href="#" class="facebook-link">
                        <i class="bi bi-facebook"></i>
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a href="#" class="text-muted twitter-link">
                        <i class="bi bi-twitter"></i>
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a href="#" class="text-muted instagram-link">
                        <i class="bi bi-instagram"></i>
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a href="#" class="text-muted linkedin-link">
                        <i class="bi bi-linkedin"></i>
                        </a>
                    </li>
                    </ul>
                </div>
            <hr class="mt-4 mb-3">
            <p class="text-center mb-0">&copy; 2024 FilipinoBlog. All rights reserved.</p>
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

    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('searchInput');
            const categorySelect = document.getElementById('categorySelect');
            const blogContainer = document.getElementById('blogContainer');
            const blogCards = blogContainer.getElementsByClassName('card');

            function filterBlogs() {
                const searchValue = searchInput.value.toLowerCase();
                const selectedCategory = categorySelect.value;
                
                for (let card of blogCards) {
                    const title = card.getAttribute('data-title').toLowerCase();
                    const content = card.getAttribute('data-content').toLowerCase();
                    const author = card.getAttribute('data-author').toLowerCase();
                    const category = card.getAttribute('data-category');
                    
                    const matchesSearch = title.includes(searchValue) || content.includes(searchValue) || author.includes(searchValue);
                    const matchesCategory = !selectedCategory || category === selectedCategory;
                    
                    if (matchesSearch && matchesCategory) {
                        card.parentElement.style.display = '';
                    } else {
                        card.parentElement.style.display = 'none';
                    }
                }
            }

            searchInput.addEventListener('input', filterBlogs);
            categorySelect.addEventListener('change', filterBlogs);
        });
    </script>
</body>
</html>
  