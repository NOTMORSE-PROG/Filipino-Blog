<?php
include('db_connect.php');
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

$user_id = $_SESSION['user_id'];

$unreadNotificationsQuery = "SELECT COUNT(*) AS unread_count FROM comments WHERE user_id = ? AND is_read = 0";
$unreadStmt = $conn->prepare($unreadNotificationsQuery);
$unreadStmt->bind_param("i", $user_id);
$unreadStmt->execute();
$unreadResult = $unreadStmt->get_result();
$unreadRow = $unreadResult->fetch_assoc();
$unreadCount = $unreadRow['unread_count'] ?? 0;
$unreadStmt->close();


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

$userQuery = $conn->prepare("SELECT email FROM users WHERE id = ?");
$userQuery->bind_param("i", $user_id);
$userQuery->execute();
$userResult = $userQuery->get_result();

if ($userResult->num_rows > 0) {
    $userData = $userResult->fetch_assoc();
    $userEmail = $userData['email'];
    $safeEmail = preg_replace('/[^\w.@]+/', '_', $userEmail);
} else {
    die("User not found.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);
    $category = $conn->real_escape_string($_POST['category']);
    $tags = $conn->real_escape_string($_POST['tags']);

    $uploadDir = "uploads/{$safeEmail}/";

    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $target_file = $uploadDir . basename($_FILES["featured_image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $check = getimagesize($_FILES["featured_image"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "<div class='alert alert-danger'>File is not an image.</div>";
        $uploadOk = 0;
    }

    if (file_exists($target_file)) {
        echo "<div class='alert alert-danger'>Sorry, file already exists.</div>";
        $uploadOk = 0;
    }

    if ($_FILES["featured_image"]["size"] > 20971520) { 
        echo "<div class='alert alert-danger'>Sorry, your file is too large.</div>";
        $uploadOk = 0;
    }

    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "<div class='alert alert-danger'>Sorry, only JPG, JPEG, PNG, and GIF files are allowed.</div>";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        echo "<div class='alert alert-danger'>Sorry, your file was not uploaded.</div>";
    } else {
        if (move_uploaded_file($_FILES["featured_image"]["tmp_name"], $target_file)) {

            $featured_image = $target_file;

            $sql = "INSERT INTO posts (user_id, title, content, category, tags, featured_image) 
                    VALUES ('$user_id', '$title', '$content', '$category', '$tags', '$featured_image')";

            if ($conn->query($sql) === TRUE) {
                echo "<div class='alert alert-success'>New post created successfully.</div>";
                header("Location: post.php");
                exit();  
            } else {
                echo "<div class='alert alert-danger'>Error: " . $sql . "<br>" . $conn->error . "</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Sorry, there was an error uploading your file.</div>";
        }
    }
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
                            <a class="nav-link" href="dashboard.php">
                                <i class="bi bi-grid me-2"></i> Dashboard
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

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content-wrapper">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Create Post</h1>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                            <form action="create-post.php" method="post" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Title</label>
                                    <input type="text" name="title" id="title" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="content" class="form-label">Content</label>
                                    <textarea name="content"  id="postContent" rows="10" class="form-control" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="category" class="form-label">Category</label>
                                    <select class="form-select" id="postCategory" name="category" required>
                                        <option value="">Select a category</option>
                                        <option value="travel">Travel</option>
                                        <option value="food">Food</option>
                                        <option value="culture">Culture</option>
                                        <option value="lifestyle">Lifestyle</option>
                                        <option value="technology">Technology</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="tags" class="form-label">Tags (comma separated)</label>
                                    <input type="text" name="tags" id="tags" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="featured_image" class="form-label">Featured Image</label>
                                    <input type="file" name="featured_image" id="featured_image" class="form-control" required>
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