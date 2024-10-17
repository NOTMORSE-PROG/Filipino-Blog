<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Posts - FilipinoBlog</title>
    <link rel="stylesheet" href="bootstrap.min.css" />
    <link rel="stylesheet" href="post.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
</head>
<style>
@media (max-width: 767.98px) {
  .sidebar {
    position: fixed;
    top: 56px;
    bottom: 0;
    left: -100%;
    z-index: 1000;
    transition: all 0.3s ease-in-out;
    width: 200px;
  }

  .sidebar.show {
    left: 0;
  }

  .content-wrapper {
    margin-left: 0 !important;
  }
}

@media (min-width: 768px) {
  .content-wrapper {
    margin-left: 200px;
  }
}
</style>
<body>
<nav class="navbar navbar-expand-lg sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.html">
                <img src="logo.png" alt="FilipinoBlog Logo" width="30" height="30" class="d-inline-block align-top">
                <span class="ms-2 text-filipino">FilipinoBlog</span>
            </a>
            <button class="navbar-toggler" type="button" id="sidebarToggle">
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
            <nav class="col-md-3 col-lg-2 sidebar" id="sidebar">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php">
                                <i class="bi bi-house-door me-2"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="post.php">
                                <i class="bi bi-file-earmark-text me-2"></i>
                                My Posts
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="others.php">
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
                    <h1 class="h2">My Posts</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-filter"></i> Filter
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-sort-down"></i> Sort
                            </button>
                        </div>
                        <a href="#" class="btn btn-sm btn-filipino">
                            <i class="bi bi-plus-lg"></i> New Post
                        </a>
                    </div>
                </div>

                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    <div class="col">
                        <div class="card h-100">
                            <img src="https://images.unsplash.com/photo-1518509562904-e7ef99cdcc86?auto=format&fit=crop&w=800&q=80" class="card-img-top post-image" alt="Philippine Beach">
                            <div class="card-body">
                                <h5 class="card-title">The Beauty of Philippine Beaches</h5>
                                <p class="card-text">Explore the pristine shores and crystal-clear waters of the Philippines' most beautiful beaches.</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">Posted 2 days ago</small>
                                    <span class="badge bg-primary rounded-pill">1.2k views</span>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent border-top-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="btn-group">
                                        <a href="#" class="btn btn-sm btn-outline-secondary">Edit</a>
                                        <a href="#" class="btn btn-sm btn-outline-secondary">View</a>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card h-100">
                            <img src="https://images.unsplash.com/photo-1528137871618-79d2761e3fd5?auto=format&fit=crop&w=800&q=80" class="card-img-top post-image" alt="Filipino Cuisine">
                            <div class="card-body">
                                <h5 class="card-title">Filipino Cuisine: A Gastronomic Journey</h5>
                                <p class="card-text">Discover the rich flavors and diverse culinary traditions of the Philippines.</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">Posted 5 days ago</small>
                                    <span class="badge bg-primary rounded-pill">980 views</span>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent border-top-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="btn-group">
                                        <a href="#" class="btn btn-sm btn-outline-secondary">Edit</a>
                                        <a href="#" class="btn btn-sm btn-outline-secondary">View</a>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card h-100">
                            <img src="https://images.unsplash.com/photo-1518548419970-58e3b4079ab2?auto=format&fit=crop&w=800&q=80" class="card-img-top post-image" alt="Philippine Literature">
                            <div class="card-body">
                                <h5 class="card-title">The Rich History of Philippine Literature</h5>
                                <p class="card-text">Explore the evolution of Filipino literature from pre-colonial times to the modern era.</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">Posted 1 week ago</small>
                                    <span class="badge bg-primary rounded-pill">756 views</span>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent border-top-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="btn-group">
                                        <a href="#" class="btn btn-sm btn-outline-secondary">Edit</a>
                                        <a href="#" class="btn btn-sm btn-outline-secondary">View</a>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card h-100">
                            <img src="https://images.unsplash.com/photo-1518548419970-58e3b4079ab2?auto=format&fit=crop&w=800&q=80" class="card-img-top post-image" alt="Filipino Festivals">
                            <div class="card-body">
                                <h5 class="card-title">Colorful Filipino Festivals</h5>
                                <p class="card-text">Experience the vibrant and lively festivals that showcase Filipino culture and traditions.</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">Posted 2 weeks ago</small>
                                    <span class="badge bg-primary rounded-pill">632 views</span>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent border-top-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="btn-group">
                                        <a href="#" class="btn btn-sm btn-outline-secondary">Edit</a>
                                        <a href="#" class="btn btn-sm btn-outline-secondary">View</a>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <nav aria-label="Page navigation" class="mt-4">
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
    <script src ="theme.js"></script>
</body>
</html>