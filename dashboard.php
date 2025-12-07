<?php
include('includes/db_connect.php');
session_start();

function time_ago($timestamp) {
    $time_ago = strtotime($timestamp);
    $current_time = time();
    $time_difference = $current_time - $time_ago;
    $seconds = $time_difference;
    $minutes = round($seconds / 60);      
    $hours = round($seconds / 3600);         
    $days = round($seconds / 86400);       
    $weeks = round($seconds / 604800);       
    $months = round($seconds / 2629440);      
    $years = round($seconds / 31553280);     

    if ($seconds <= 60) {
        return "Just Now";
    } else if ($minutes <= 60) {
        return $minutes == 1 ? "one minute ago" : "$minutes minutes ago";
    } else if ($hours <= 24) {
        return $hours == 1 ? "an hour ago" : "$hours hours ago";
    } else if ($days <= 7) {
        return $days == 1 ? "yesterday" : "$days days ago";
    } else if ($weeks <= 4.3) { 
        return $weeks == 1 ? "one week ago" : "$weeks weeks ago";
    } else if ($months <= 12) {
        return $months == 1 ? "one month ago" : "$months months ago";
    } else {
        return $years == 1 ? "one year ago" : "$years years ago";
    }
}

$user_id = $_SESSION['user_id'];  

$unreadNotificationsQuery = "
    SELECT COUNT(*) AS unread_count
    FROM comments c
    JOIN posts p ON c.post_id = p.id
    WHERE p.user_id = ?  -- The logged-in user is the owner of the post
    AND c.is_read = 0    -- The comment is unread
    AND c.is_deleted = 0 -- The comment is not deleted
    AND c.user_id != ?   -- Exclude comments made by the logged-in user
";

$unreadStmt = $conn->prepare($unreadNotificationsQuery);
$unreadStmt->bind_param("ii", $user_id, $user_id);  
$unreadStmt->execute();
$unreadResult = $unreadStmt->get_result();
$unreadRow = $unreadResult->fetch_assoc();
$unreadCount = $unreadRow['unread_count'] ?? 0;
$unreadStmt->close();

$sql_posts = "SELECT COUNT(*) AS total_posts FROM posts WHERE user_id = $user_id";
$result_posts = $conn->query($sql_posts);
$row_posts = $result_posts->fetch_assoc();
$total_posts = $row_posts['total_posts'];

$sql_previous_posts = "SELECT COUNT(*) AS previous_total_posts FROM posts WHERE user_id = $user_id AND created_at >= NOW() - INTERVAL 7 DAY";
$result_previous_posts = $conn->query($sql_previous_posts);
$row_previous_posts = $result_previous_posts->fetch_assoc();
$previous_total_posts = $row_previous_posts['previous_total_posts'];

$post_rate = $previous_total_posts > 0 ? (($total_posts - $previous_total_posts) / $previous_total_posts) * 100 : 0;

$sql_comments = "SELECT COUNT(*) AS total_comments FROM comments c
                 JOIN posts p ON c.post_id = p.id
                 WHERE p.user_id = ?"; 
$result_comments = $conn->prepare($sql_comments);
$result_comments->bind_param("i", $user_id);
$result_comments->execute();
$row_comments = $result_comments->get_result()->fetch_assoc();
$total_comments = $row_comments['total_comments'];

$sql_previous_comments = "SELECT COUNT(*) AS previous_total_comments FROM comments c
                          JOIN posts p ON c.post_id = p.id
                          WHERE p.user_id = ? AND c.created_at >= NOW() - INTERVAL 7 DAY"; // Comments in the last 7 days
$result_previous_comments = $conn->prepare($sql_previous_comments);
$result_previous_comments->bind_param("i", $user_id);
$result_previous_comments->execute();
$row_previous_comments = $result_previous_comments->get_result()->fetch_assoc();
$previous_total_comments = $row_previous_comments['previous_total_comments'];

$comments_rate = $previous_total_comments > 0 ? (($total_comments - $previous_total_comments) / $previous_total_comments) * 100 : 0;

$categories = [];
$category_counts = [];

$sql_categories = "SELECT category, COUNT(*) AS category_count FROM posts WHERE user_id = ? GROUP BY category";
$stmt = $conn->prepare($sql_categories);
$stmt->bind_param("i", $user_id); 
$stmt->execute();
$result_categories = $stmt->get_result();

if ($result_categories->num_rows > 0) {
    while ($row = $result_categories->fetch_assoc()) {
        $categories[] = $row['category'];
        $category_counts[] = (int)$row['category_count'];
    }
}

$sql_recent_posts = "SELECT id, title, category, created_at FROM posts WHERE user_id = $user_id ORDER BY created_at DESC LIMIT 3";
$result_recent_posts = $conn->query($sql_recent_posts);
$recent_posts = [];
if ($result_recent_posts->num_rows > 0) {
    while ($row = $result_recent_posts->fetch_assoc()) {
        $recent_posts[] = $row;
    }
}

$sql_comments = "SELECT c.comment, c.created_at, p.title AS post_title, u.fullName 
                 FROM comments c
                 JOIN posts p ON c.post_id = p.id
                 JOIN users u ON c.user_id = u.id
                 WHERE p.user_id = ? AND c.user_id != ?  -- Exclude the logged-in user
                 ORDER BY c.created_at DESC LIMIT 3"; 

$stmt_comments = $conn->prepare($sql_comments);
$stmt_comments->bind_param("ii", $user_id, $user_id); 
$stmt_comments->execute();
$result_comments = $stmt_comments->get_result();

$comments = [];
if ($result_comments->num_rows > 0) {
    while ($row = $result_comments->fetch_assoc()) {
        $comments[] = $row;
    }
}

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
    <link rel="shortcut icon" type="x-icon" href="assets/images/logo.png" />
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/dashboard.css" />
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
                <img src="assets/images/logo.png" alt="FilipinoBlog Logo" width="30" height="30" class="d-inline-block align-top">
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
                        <a class="nav-link" href="notification.php">
                            Notifications
                            <?php if ($unreadCount > 0): ?>
                                <span class="badge bg-danger"><?php echo $unreadCount; ?></span>
                            <?php endif; ?>
                        </a>
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
                    <li class="nav-item d-md-none">
                            <a class="nav-link" href="index.php">
                                <i class="bi bi-house-door me-2"></i>
                                Home
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="dashboard.php">
                                <i class="bi bi-grid me-2"></i> Dashboard
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
                        <li class="nav-item d-md-none">
                            <a class="nav-link" href="notification.php">
                                <i class="bi bi-bell me-2"></i>
                                Notifications
                                <?php if ($unreadCount > 0): ?>
                                    <span class="badge bg-danger"><?php echo $unreadCount; ?></span>
                                <?php endif; ?>
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
                </div>

                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-4 mb-4">
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                            <h5 class="card-title">Total Posts</h5>
                            <p class="card-text display-4"><?php echo $total_posts; ?></p>
                            <?php
                                if ($post_rate > 0) {
                                    echo '<p class="card-text text-success"><i class="bi bi-arrow-up"></i> ' . round($post_rate, 2) . '% increase</p>';
                                } elseif ($post_rate < 0) {
                                    echo '<p class="card-text text-danger"><i class="bi bi-arrow-down"></i> ' . round(abs($post_rate), 2) . '% decrease</p>';
                                } else {
                                    echo '<p class="card-text text-muted"><i class="bi bi-arrow-right"></i> No change</p>';
                                }
                            ?>
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
                            <p class="card-text display-4"><?php echo $total_comments; ?></p>
                            <?php
                                if ($comments_rate > 0) {
                                    echo '<p class="card-text text-success"><i class="bi bi-arrow-up"></i> ' . round($comments_rate, 2) . '% increase</p>';
                                } elseif ($comments_rate < 0) {
                                    echo '<p class="card-text text-danger"><i class="bi bi-arrow-down"></i> ' . round(abs($comments_rate), 2) . '% decrease</p>';
                                } else {
                                    echo '<p class="card-text text-muted"><i class="bi bi-arrow-right"></i> No change</p>';
                                }
                            ?>
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
                                    <?php if (empty($recent_posts)): ?>
                                        <li class="list-group-item">
                                            Nothing posted yet
                                        </li>
                                    <?php else: ?>
                                        <?php foreach ($recent_posts as $post): ?>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-0"><?php echo htmlspecialchars($post['title']); ?></h6>
                                                    <small class="text-muted">Posted <?php echo time_ago($post['created_at']); ?> ago</small>
                                                </div>
                                                <span class="badge bg-primary rounded-pill"><?php echo htmlspecialchars($post['category']); ?></span>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Recent Comments</h5>
                                <ul class="list-group list-group-flush">
                                    <?php if (count($comments) > 0): ?>
                                        <?php foreach ($comments as $comment): ?>
                                            <li class="list-group-item">
                                                <div class="d-flex w-100 justify-content-between">
                                                    <h6 class="mb-1"><?php echo htmlspecialchars($comment['fullName']); ?></h6>
                                                    <small class="text-muted"><?php echo time_ago($comment['created_at']); ?></small>
                                                </div>
                                                <p class="mb-1"><?php echo htmlspecialchars($comment['comment']); ?></p>
                                                <small class="text-muted">On: <?php echo htmlspecialchars($comment['post_title']); ?></small>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <li class="list-group-item">
                                            <div class="text-center">No comments</div>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="assets/js/theme.js"></script>
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


        const categories = <?php echo json_encode($categories); ?>;
        const categoryCounts = <?php echo json_encode($category_counts); ?>;
        const chartContainer = document.getElementById('categoriesChart').parentElement;
        
        if (categories.length === 0 || categoryCounts.every(count => count === 0)) {
            chartContainer.innerHTML = '<p class="text-center">Nothing posted yet</p>';
        } else {
            const categoriesCtx = document.getElementById('categoriesChart').getContext('2d');
            new Chart(categoriesCtx, {
                type: 'doughnut',
                data: {
                    labels: categories,
                    datasets: [{
                        data: categoryCounts,
                        backgroundColor: ['#FCD116', '#0038A8', '#CE1126', '#00A86B', '#7D0063'],
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }
    </script>
</body>
</html>