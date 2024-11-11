<?php
include('db_connect.php');

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$userEmailQuery = "SELECT email FROM users WHERE id = ?";
$userEmailStmt = $conn->prepare($userEmailQuery);
$userEmailStmt->bind_param("i", $user_id);
$userEmailStmt->execute();
$userEmailResult = $userEmailStmt->get_result();
$userEmailRow = $userEmailResult->fetch_assoc();
$userEmail = $userEmailRow['email'];
$safeEmail = preg_replace('/[^\w.@]+/', '_', $userEmail); 

$userEmailStmt->close();

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

if (isset($_GET['id'])) {
    $postId = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM posts WHERE id = ? AND user_id = ?");
    $stmt->bind_param('ii', $postId, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $post = $result->fetch_assoc();

    if (!$post) {
        header('Location: post.php');
        exit();
    }
} else {
    header('Location: post.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['postTitle'];
    $content = $_POST['postContent'];
    $category = $_POST['postCategory'];
    $tags = $_POST['postTags'];
    $publishImmediately = isset($_POST['publishImmediately']) ? 1 : 0;

    $targetDir = "uploads/" . $safeEmail . "/";
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $featuredImage = $post['featured_image']; 
    if (isset($_FILES['postImage']) && $_FILES['postImage']['error'] === UPLOAD_ERR_OK) {
        $targetFile = $targetDir . basename($_FILES['postImage']['name']);
        if (move_uploaded_file($_FILES['postImage']['tmp_name'], $targetFile)) {
            $featuredImage = $targetFile; 
        }
    }

    $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ?, category = ?, tags = ?, featured_image = ?, updated_at = NOW() WHERE id = ? AND user_id = ?");
    $stmt->bind_param('sssssii', $title, $content, $category, $tags, $featuredImage, $postId, $user_id);
    $stmt->execute();

    header('Location: post.php');
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create/Edit Post - FilipinoBlog</title>
    <link rel="stylesheet" href="bootstrap.min.css" />
    <link rel="stylesheet" href="create-post.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
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
</head>
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

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content-wrapper">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Edit Post</h1>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                            <form id="postForm" method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="postTitle" class="form-label">Title</label>
                                    <input type="text" class="form-control" id="postTitle" name="postTitle" value="<?= htmlspecialchars($post['title']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="postContent" class="form-label">Content</label>
                                    <textarea class="form-control" id="postContent" name="postContent" rows="10" required><?= htmlspecialchars($post['content']) ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="postCategory" class="form-label">Category</label>
                                    <select class="form-select" id="postCategory" name="postCategory" required>
                                        <option value="travel" <?= $post['category'] == 'travel' ? 'selected' : '' ?>>Travel</option>
                                        <option value="food" <?= $post['category'] == 'food' ? 'selected' : '' ?>>Food</option>
                                        <option value="culture" <?= $post['category'] == 'culture' ? 'selected' : '' ?>>Culture</option>
                                        <option value="lifestyle" <?= $post['category'] == 'lifestyle' ? 'selected' : '' ?>>Lifestyle</option>
                                        <option value="technology" <?= $post['category'] == 'technology' ? 'selected' : '' ?>>Technology</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="postTags" class="form-label">Tags (comma-separated)</label>
                                    <input type="text" class="form-control" id="postTags" name="postTags" value="<?= htmlspecialchars($post['tags']) ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="postImage" class="form-label">Featured Image</label>
                                    <input type="file" class="form-control" id="postImage" name="postImage" accept="image/*">
                                    <?php if ($post['featured_image']): ?>
                                        <img src="<?= htmlspecialchars($post['featured_image']) ?>" alt="Featured Image" class="img-fluid mt-2">
                                    <?php endif; ?>
                                </div>
                                <div class="mb-3 form-check">
                                <input type="checkbox" name="agree_to_terms" id="agree_to_terms" class="form-check-input" required>
                                <label class="form-check-label" for="agreeTerms">I agree to the <a href="#" class="text-filipino">Terms of Service</a> and <a href="#" class="text-filipino">Privacy Policy</a></label>
                                </div>
                                <button type="submit" class="btn btn-filipino">Save Post</button>
                                <a href="post.php" class="btn btn-secondary ms-2">Cancel</a>
                            </form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="bootstrap.bundle.min.js"></script>
    <script src ="theme.js"></script>
</body>
</html>