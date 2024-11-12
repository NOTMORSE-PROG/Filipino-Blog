<?php
include('db_connect.php');
session_start();
$_SESSION['referrer'] = $_SERVER['REQUEST_URI'];
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

$filterCategory = isset($_POST['filter_category']) ? $_POST['filter_category'] : '';
$sortOrder = isset($_POST['sort_order']) ? $_POST['sort_order'] : 'desc'; // Default: Newest

$postsPerPage = 4;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $postsPerPage;

$countQuery = "SELECT COUNT(*) as cnt FROM posts";
if ($filterCategory) {
    $countQuery .= " WHERE category = ?";
}
$countStmt = $conn->prepare($countQuery);
if ($filterCategory) {
    $countStmt->bind_param("s", $filterCategory);
}
$countStmt->execute();
$countResult = $countStmt->get_result();
$totalPosts = $countResult->fetch_assoc()['cnt'];
$totalPages = ceil($totalPosts / $postsPerPage);

$postQuery = "SELECT p.*, u.fullName as username, up.picture_path as user_picture
              FROM posts p
              JOIN users u ON p.user_id = u.id
              JOIN user_profile up ON up.user_id = u.id";
if ($filterCategory) {
    $postQuery .= " WHERE p.category = ?";
}
$postQuery .= " ORDER BY p.created_at $sortOrder LIMIT ?, ?";
$postStmt = $conn->prepare($postQuery);
if ($filterCategory) {
    $postStmt->bind_param("sii", $filterCategory, $offset, $postsPerPage);
} else {
    $postStmt->bind_param("ii", $offset, $postsPerPage);
}
$postStmt->execute();
$postResult = $postStmt->get_result();

$posts = [];
while ($row = $postResult->fetch_assoc()) {
    $posts[] = $row;
}
$postStmt->close();
?>

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


            <main class="col-12 col-md-9 ms-sm-auto col-lg-10 px-md-4 content-wrapper">
            <div class="d-flex justify-content-between flex-wrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2 text-heading">My Posts</h1>
                <div class="btn-toolbar mb-2 mb-md-0 d-flex flex-nowrap gap-2">
                    <form method="POST" class="d-flex flex-nowrap gap-2 flex-grow-1 flex-md-grow-0 form-responsive">
                        <select class="form-select form-select-sm" name="filter_category" >
                            <option value="" >All</option>
                            <?php
                            $categories = ['Travel', 'Food', 'Culture', 'Lifestyle', 'Technology']; 
                            foreach ($categories as $category) {
                                echo '<option value="' . $category . '">' . $category . '</option>';
                            }
                            ?>
                        </select>
                        <select class="form-select form-select-sm" name="sort_order">
                            <option value="asc">Oldest</option>
                            <option value="desc">Newest</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-outline-secondary d-flex align-items-center btn-responsive">
                            <i class="bi bi-filter"></i> Apply
                        </button>
                    </form>
                    <a href="create-post.php" class="btn btn-sm btn-filipino d-flex align-items-center btn-responsive">
                        <i class="bi bi-plus-lg"></i> New
                    </a>
                </div>
            </div>

                <?php if (empty($posts)): ?>
                <div class="text-center">
                    <h3>No posts available</h3>
                </div>
                <?php else: ?>
                <div class="row row-cols-1 row-cols-md-2 g-4">
                    <?php foreach ($posts as $post): ?>
                    <div class="col">
                        <div class="card">
                            <img src="<?php echo htmlspecialchars($post['featured_image']); ?>" class="card-img-top post-image" alt="Post Image">
                            <div class="card-body">
                                <h5 class="card-title mb-0"><?php echo htmlspecialchars($post['username']); ?></h5>
                                <small class="text-muted">Posted <?php echo htmlspecialchars($post['created_at']); ?></small>
                                <h4 class="card-title mt-3"><?php echo htmlspecialchars($post['title']); ?></h4>
                                <p class="card-text">
                                    <?php
                                    $content = htmlspecialchars($post['content']);
                                    echo (strlen($content) > 150) ? substr($content, 0, 150) . '...' : $content;
                                    ?>
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="view-others.php?post_id=<?= $post['id']; ?>" class="btn btn-sm btn-filipino">Read More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <nav aria-label="Page navigation" class="my-4">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="others.php?page=<?php echo max(1, $page - 1); ?>">Previous</a>
                        </li>
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                            <a class="page-link" href="others.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                        <?php endfor; ?>
                        <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="others.php?page=<?php echo min($totalPages, $page + 1); ?>">Next</a>
                        </li>
                    </ul>
                </nav>
                <?php endif; ?>
            </main>
        </div>
    </div>

    <script src="bootstrap.bundle.min.js"></script>
    <script src ="theme.js"></script>
</body>
</html>