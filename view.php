<?php
$servername = "localhost";
$username = "root";  
$password = "";  
$dbname = "FilipinoBlog";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];

if (isset($_POST['delete_post'])) {
    $postId = $_POST['post_id'];

    $query = "SELECT featured_image FROM posts WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $postId);
    $stmt->execute();
    $result = $stmt->get_result();
    $post = $result->fetch_assoc();

    if ($post && !empty($post['featured_image']) && file_exists($post['featured_image'])) {
        unlink($post['featured_image']);
    }

    $query = "DELETE FROM posts WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $postId);
    $stmt->execute();

}

$userProfileQuery = "SELECT picture_path, bio FROM user_profile WHERE user_id = ?";
$userStmt = $conn->prepare($userProfileQuery);
$userStmt->bind_param("i", $user_id);
$userStmt->execute();
$userResult = $userStmt->get_result();

$userProfilePath = "https://via.placeholder.com/32";  
$userBio = "No bio available."; 
if ($userRow = $userResult->fetch_assoc()) {
    $userProfilePath = $userRow['picture_path'];
    $userBio = $userRow['bio'];
}
$userStmt->close();


if (isset($_GET['id'])) {
    $postId = $_GET['id'];
    $postQuery = "SELECT p.id, p.title, p.content, p.category, p.tags, p.featured_image, p.created_at, 
                         u.fullName AS author 
                  FROM posts p 
                  JOIN users u ON p.user_id = u.id 
                  WHERE p.id = ? AND p.user_id = ?";  
    $postStmt = $conn->prepare($postQuery);
    $postStmt->bind_param("ii", $postId, $user_id);
    $postStmt->execute();
    $postResult = $postStmt->get_result();

    if ($postRow = $postResult->fetch_assoc()) {
        $title = $postRow['title'];
        $content = $postRow['content'];
        $category = $postRow['category'];
        $tags = $postRow['tags'];
        $featured_image = $postRow['featured_image'];
        $created_at = $postRow['created_at'];
        $author = $postRow['author'];
    } else {
        header('Location: post.php');
        exit();
    }
    $postStmt->close();
} else {
    header('Location: post.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_tag']) && isset($_POST['post_id'])) {
    $new_tag = trim($_POST['new_tag']);
    $post_id = (int) $_POST['post_id'];

    if (!empty($new_tag) && $post_id > 0) {
        $tagsQuery = "SELECT tags FROM posts WHERE id = ?";
        $tagsStmt = $conn->prepare($tagsQuery);
        $tagsStmt->bind_param("i", $post_id);
        $tagsStmt->execute();
        $tagsResult = $tagsStmt->get_result();

        if ($tagsRow = $tagsResult->fetch_assoc()) {
            $tags = $tagsRow['tags'];
            $tagsArray = explode(',', $tags);
            $tagsArray[] = $new_tag;
            $updatedTags = implode(',', $tagsArray);

            $updateTagsQuery = "UPDATE posts SET tags = ? WHERE id = ?";
            $updateStmt = $conn->prepare($updateTagsQuery);
            $updateStmt->bind_param("si", $updatedTags, $post_id);
            $updateStmt->execute();
            $updateStmt->close();

            $tags = $updatedTags;
        }
        $tagsStmt->close();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_post']) && isset($_POST['post_id'])) {
    $post_id = (int) $_POST['post_id'];

    $deleteQuery = "DELETE FROM posts WHERE id = ? AND user_id = ?";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->bind_param("ii", $post_id, $user_id);
    $deleteStmt->execute();
    $deleteStmt->close();

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
    <title>View Post - FilipinoBlog</title>
    <link rel="stylesheet" href="bootstrap.min.css" />
    <link rel="stylesheet" href="view.css" />
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
                    <h1 class="h2">View Post</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                        <a href="edit.php?id=<?php echo $postId; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete</button>
                        </div>
                        <a href="post.php" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Posts
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-8">
                        <article class="blog-post">
                            <h2 class="blog-post-title mb-1"><?php echo htmlspecialchars($title); ?></h2>
                            <p class="blog-post-meta text-muted"><?php echo htmlspecialchars($created_at); ?> by <a href="#" class="text-filipino"><?php echo htmlspecialchars($author); ?></a></p>
                            <?php if (!empty($featured_image)): ?>
                                <img src="<?php echo htmlspecialchars($featured_image); ?>" alt="Featured Image" class="img-fluid rounded mb-4 post-image">
                            <?php endif; ?>
                            <p><?php echo nl2br(htmlspecialchars($content)); ?></p>
                        </article>

                        <div class="card mt-4">
                            <div class="card-body">
                                <h4 class="card-title">Tags</h4>
                                <div class="d-flex flex-wrap gap-2 mb-3" id="tagList">
                                    <?php
                                    $tagsArray = explode(',', $tags);
                                    foreach ($tagsArray as $tag) {
                                        echo "<span class='tag'>" . htmlspecialchars(trim($tag)) . "</span>";
                                    }
                                    ?>
                                </div>
                                <form method="POST">
                                    <div class="mb-3">
                                        <label for="newTag" class="form-label">Add a new tag</label>
                                        <div class="input-group">
                                            <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($postId); ?>">
                                            <input type="text" class="form-control" id="newTag" name="new_tag" placeholder="Enter a new tag">
                                            <button class="btn btn-filipino" type="submit">Add Tag</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="position-sticky" style="top: 2rem;">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h4 class="card-title">About the Author</h4>
                                    <div class="d-flex align-items-center mb-3">
                                        <img src="<?php echo htmlspecialchars($userProfilePath); ?>" alt="Author Avatar" class="rounded-circle me-3 author-avatar">
                                        <div>
                                            <h5 class="mb-0"><?php echo htmlspecialchars($author); ?></h5>
                                            <p class="text-muted mb-0"><?php echo htmlspecialchars($userBio); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-body">
                                    <h4 class="card-title">Categories</h4>
                                    <div class="d-flex flex-wrap gap-2">
                                        <a href="#" class="btn btn-outline-secondary btn-sm"><?php echo htmlspecialchars($category); ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this post? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form method="POST">
                        <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($postId); ?>">
                        <button type="submit" name="delete_post" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="bootstrap.bundle.min.js"></script>
    <script src="theme.js"></script>
</body>
</html>