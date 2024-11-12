<?php
include('db_connect.php');
session_start();
$user_id = $_SESSION['user_id'];  


$userProfileQuery = "SELECT picture_path FROM user_profile WHERE user_id = ?";
$userStmt = $conn->prepare($userProfileQuery);
$userStmt->bind_param("i", $user_id);
$userStmt->execute();
$userResult = $userStmt->get_result();

$userProfilePath = "https://via.placeholder.com/32";  
if ($userRow = $userResult->fetch_assoc()) {
    $userProfilePath = $userRow['picture_path'];
}
$userStmt->close();
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - FilipinoBlog</title>
    <link rel="shortcut icon" type="x-icon" href="logo.png" />
    <link rel="stylesheet" href="bootstrap.min.css" />
    <link rel="stylesheet" href="dashboard.css" />
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
            <a class="navbar-brand" href="index.php">
                <img src="logo.png" alt="FilipinoBlog Logo" width="30" height="30" class="d-inline-block align-top">
                <span class="ms-2 text-filipino">FilipinoBlog</span>
            </a>
            <button class="navbar-toggler" type="button" id="sidebarToggle">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <button id="themeToggle" class="btn btn-link nav-link">
                            <i class="bi bi-sun-fill"></i>
                        </button>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="<?php echo htmlspecialchars($userProfilePath); ?>" alt="User Avatar" class="rounded-circle" width="32" height="32">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="settings.php">Profile</a></li>
                            <li><a class="dropdown-item" href="settings.php">Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
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
                            <a class="nav-link active" href="dashboard.php">
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
                    <h1 class="h2">Dashboard</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
                            <i class="bi bi-calendar"></i>
                            This week
                        </button>
                    </div>
                </div>

                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-4 mb-4">
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Total Posts</h5>
                                <p class="card-text display-4">150</p>
                                <p class="card-text text-success"><i class="bi bi-arrow-up"></i> 12% increase</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Total Views</h5>
                                <p class="card-text display-4">10.5k</p>
                                <p class="card-text text-success"><i class="bi bi-arrow-up"></i> 8% increase</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Comments</h5>
                                <p class="card-text display-4">324</p>
                                <p class="card-text text-danger"><i class="bi bi-arrow-down"></i> 3% decrease</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Followers</h5>
                                <p class="card-text display-4">1.2k</p>
                                <p class="card-text text-success"><i class="bi bi-arrow-up"></i> 15% increase</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Views Over Time</h5>
                                <div class="chart-container">
                                    <canvas id="viewsChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Top Categories</h5>
                                <div class="chart-container">
                                    <canvas id="categoriesChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Recent Posts</h5>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0">The Beauty of Philippine Beaches</h6>
                                            <small class="text-muted">Posted 2 days ago</small>
                                        </div>
                                        <span class="badge bg-primary rounded-pill">1.2k views</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0">Filipino Cuisine: A Gastronomic Journey</h6>
                                            <small class="text-muted">Posted 5 days ago</small>
                                        </div>
                                        <span class="badge bg-primary rounded-pill">980 views</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0">The Rich History of Philippine Literature</h6>
                                            <small class="text-muted">Posted 1 week ago</small>
                                        </div>
                                        <span class="badge bg-primary rounded-pill">756 views</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Recent Comments</h5>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Juan dela Cruz</h6>
                                            <small class="text-muted">3 days ago</small>
                                        </div>
                                        <p class="mb-1">Great article! I learned so much about our local beaches.</p>
                                        <small class="text-muted">On: The Beauty of Philippine Beaches</small>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Maria Santos</h6>
                                            <small class="text-muted">5 days ago</small>
                                        </div>
                                        <p class="mb-1">Your recipes are amazing! Can't wait to try them.</p>
                                        <small class="text-muted">On: Filipino Cuisine: A Gastronomic Journey</small>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Carlos Reyes</h6>
                                            <small class="text-muted">1 week ago</small>
                                        </div>
                                        <p class="mb-1">This is a comprehensive overview of our literary heritage.</p>
                                        <small class="text-muted">On: The Rich History of Philippine Literature</small>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src ="theme.js"></script>
    <script>
        const viewsCtx = document.getElementById('viewsChart').getContext('2d');
        new Chart(viewsCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Views',
                    data: [1200, 1900, 3000, 5000, 4500, 6000],
                    borderColor: '#FCD116',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });


        const categoriesCtx = document.getElementById('categoriesChart').getContext('2d');
        new Chart(categoriesCtx, {
            type: 'doughnut',
            data: {
                labels: ['Travel', 'Food', 'Culture', 'History', 'Lifestyle'],
                datasets: [{
                    data: [30, 25, 20, 15, 10],
                    backgroundColor: ['#FCD116', '#0038A8', '#CE1126', '#00A86B', '#7D0063']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    </script>
</body>
</html>