<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>See Others' Posts - FilipinoBlog</title>
    <link rel="stylesheet" href="bootstrap.min.css" />
    <link rel="stylesheet" href="post.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.html">
                <img src="logo.png" alt="FilipinoBlog Logo" width="30" height="30" class="d-inline-block align-top">
                <span class="text-filipino">FilipinoBlog</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.html">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Notifications</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Messages</a>
                    </li>
                    <li class="nav-item">
                        <button id="themeToggle" class="btn btn-link nav-link">
                            <i class="bi bi-sun-fill"></i>
                        </button>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="https://via.placeholder.com/32" alt="User Avatar" class="rounded-circle" width="32" height="32">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li><a class="dropdown-item" href="#">Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php">
                                <i class="bi bi-house-door me-2"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="post.php">
                                <i class="bi bi-file-earmark-text me-2"></i>
                                My Posts
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="others.php">
                                <i class="bi bi-people me-2"></i>
                                See Others' Posts
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="settings.php">
                                <i class="bi bi-gear me-2"></i>
                                Settings
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">See Others' Posts</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-filter"></i> Filter
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-sort-down"></i> Sort
                            </button>
                        </div>
                    </div>
                </div>

                <div class="row row-cols-1 row-cols-md-2 g-4">
                    <div class="col">
                        <div class="card">
                            <img src="https://images.unsplash.com/photo-1551963831-b3b1ca40c98e" class="card-img-top post-image" alt="Breakfast in Bohol">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <img src="https://via.placeholder.com/48" alt="User Avatar" class="avatar me-3">
                                    <div>
                                        <h5 class="card-title mb-0">Maria Santos</h5>
                                        <small class="text-muted">Posted 2 hours ago</small>
                                    </div>
                                </div>
                                <h4 class="card-title">A Taste of Bohol: Breakfast by the Beach</h4>
                                <p class="card-text">Starting my day with a delicious Filipino breakfast while enjoying the beautiful beaches of Bohol. The perfect way to begin any island adventure! #BreakfastGoals #BoholLife</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <button class="btn btn-sm btn-outline-secondary me-2"><i class="bi bi-heart"></i> Like</button>
                                        <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-chat"></i> Comment</button>
                                    </div>
                                    <small class="text-muted">89 likes • 23 comments</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <img src="https://images.unsplash.com/photo-1555921015-5532091f6026" class="card-img-top post-image" alt="Jeepney Art">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <img src="https://via.placeholder.com/48" alt="User Avatar" class="avatar me-3">
                                    <div>
                                        <h5 class="card-title mb-0">Juan dela Cruz</h5>
                                        <small class="text-muted">Posted 5 hours ago</small>
                                    </div>
                                </div>
                                <h4 class="card-title">The Art of Jeepneys: A Filipino Cultural Icon</h4>
                                <p class="card-text">Exploring the vibrant world of jeepney art in Manila. These moving canvases are more than just transportation - they're a testament to Filipino creativity and resilience. #JeepneyArt #PinoyPride</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <button class="btn btn-sm btn-outline-secondary me-2"><i class="bi bi-heart"></i> Like</button>
                                        <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-chat"></i> Comment</button>
                                    </div>
                                    <small class="text-muted">132 likes • 41 comments</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <img src="https://images.unsplash.com/photo-1528164344705-47542687000d" class="card-img-top post-image" alt="Philippine Eagle">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <img src="https://via.placeholder.com/48" alt="User Avatar" class="avatar me-3">
                                    <div>
                                        <h5 class="card-title mb-0">Ana Reyes</h5>
                                        <small class="text-muted">Posted 1 day ago</small>
                                    </div>
                                </div>
                                <h4 class="card-title">Majestic Philippine Eagle: A Symbol of Conservation</h4>
                                <p class="card-text">Had the rare opportunity to see a Philippine Eagle up close at the conservation center in Davao. These magnificent birds are critically endangered - it's crucial we protect them and their habitats. #WildlifeConservation #PhilippineEagle</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <button class="btn btn-sm btn-outline-secondary me-2"><i class="bi bi-heart"></i> Like</button>
                                        <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-chat"></i> Comment</button>
                                    </div>
                                    <small class="text-muted">215 likes • 67 comments</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <img src="https://images.unsplash.com/photo-1516550893923-42d28e5677af" class="card-img-top post-image" alt="Ifugao Rice Terraces">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <img src="https://via.placeholder.com/48" alt="User Avatar" class="avatar me-3">
                                    <div>
                                        <h5 class="card-title mb-0">Carlos Bautista</h5>
                                        <small class="text-muted">Posted 3 days ago</small>
                                    </div>
                                </div>
                                <h4 class="card-title">The Majestic Ifugao Rice Terraces</h4>
                                <p class="card-text">Witnessing the awe-inspiring Ifugao Rice Terraces in person is a humbling experience. These 2000-year-old terraces are a testament to our ancestors' ingenuity and harmony with nature. #8thWonderOfTheWorld #PhilippineHeritage</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <button class="btn btn-sm btn-outline-secondary me-2"><i class="bi bi-heart"></i> Like</button>
                                        <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-chat"></i> Comment</button>
                                    </div>
                                    <small class="text-muted">301 likes • 89  comments</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <nav aria-label="Page navigation" class="my-4">
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
        </div>
    </div>

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
</body>
</html>