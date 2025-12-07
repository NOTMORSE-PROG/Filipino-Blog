<?php
session_start();
$_SESSION['referrer'] = $_SERVER['REQUEST_URI'];
include('includes/db_connect.php');

$loggedInUserId = $_SESSION['user_id'] ?? 0;

$userProfileQuery = "SELECT picture_path FROM user_profile WHERE user_id = ?";
$userStmt = $conn->prepare($userProfileQuery);
$userStmt->bind_param("i", $loggedInUserId);
$userStmt->execute();
$userResult = $userStmt->get_result();

$userProfilePath = "https://via.placeholder.com/32";  
if ($userRow = $userResult->fetch_assoc()) {
    $userProfilePath = $userRow['picture_path'];
}
$userStmt->close();

if (isset($_POST['mark_all_read'])) {
    $updateAllQuery = $conn->prepare("UPDATE comments SET is_read = 1 WHERE post_id IN (SELECT id FROM posts WHERE user_id = ?)");
    $updateAllQuery->bind_param("i", $loggedInUserId);
    $updateAllQuery->execute();
    header("Location: notification.php");  
    exit();
}

if (isset($_GET['read_id'])) {
    $readId = (int)$_GET['read_id'];
    $updateQuery = $conn->prepare("UPDATE comments SET is_read = 1 WHERE id = ? AND post_id IN (SELECT id FROM posts WHERE user_id = ?)");
    $updateQuery->bind_param("ii", $readId, $loggedInUserId);
    $updateQuery->execute();
    header("Location: notification.php");
    exit();
}
if (isset($_GET['delete_id'])) {
    $deleteId = (int)$_GET['delete_id'];
    $updateQuery = $conn->prepare("
        UPDATE comments 
        SET is_deleted = 1, is_read = 1 
        WHERE id = ? AND post_id IN (SELECT id FROM posts WHERE user_id = ?)
    ");
    $updateQuery->bind_param("ii", $deleteId, $loggedInUserId);
    $updateQuery->execute();

    if ($updateQuery->affected_rows > 0) {
        echo "Notification marked as read and removed!";
    } else {
        echo "Failed to remove notification or no matching notification found.";
    }
    header("Location: notification.php");
    exit();
}
if (isset($_POST['clear_all'])) {
    echo "Clear All form submitted<br>"; 
    $clearQuery = $conn->prepare("
        UPDATE comments 
        SET is_deleted = 1, is_read = 1
        WHERE post_id IN (SELECT id FROM posts WHERE user_id = ?)
    ");
    $clearQuery->bind_param("i", $loggedInUserId);
    $clearQuery->execute();

    if ($clearQuery->affected_rows > 0) {
        echo "All notifications marked as read and cleared!<br>";
    } else {
        echo "No notifications were cleared.<br>";
    }

    header("Location: notification.php");
    exit();
}

$filter = $_GET['filter'] ?? 'all';
$filterCondition = "";

switch ($filter) {
    case 'oldest':
        $filterCondition = "ORDER BY c.created_at ASC";
        break;
    case 'recent':
        $filterCondition = "ORDER BY c.created_at DESC";
        break;
    case 'read':
        $filterCondition = "AND c.is_read = 1 ORDER BY c.created_at DESC";
        break;
    case 'unread':
        $filterCondition = "AND c.is_read = 0 ORDER BY c.created_at DESC";
        break;
    default:
        $filterCondition = "ORDER BY c.created_at DESC";
}

$notificationsQuery = $conn->prepare("
    SELECT c.id AS comment_id, c.comment, c.is_read, c.created_at, 
           p.id AS post_id, p.title, 
           u.fullName AS commenter_name 
    FROM comments c
    INNER JOIN posts p ON c.post_id = p.id
    INNER JOIN users u ON c.user_id = u.id
    WHERE p.user_id = ? 
    AND c.is_deleted = 0 
    AND c.user_id != ? -- Exclude logged-in user's own comments
    $filterCondition
");
$notificationsQuery->bind_param("ii", $loggedInUserId, $loggedInUserId);
$notificationsQuery->execute();
$notifications = $notificationsQuery->get_result();
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>See Others' Posts - FilipinoBlog</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/notification.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">

</head>
<body>
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img src="assets/images/logo.png" alt="FilipinoBlog Logo" width="30" height="30" class="d-inline-block align-top">
                <span class="ms-2 style = "color: black;">FilipinoBlog</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="post.php">My Post</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="others.php">Other's Posts</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="settings.php">Setting</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
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

    <main class="container py-5">
        <h1 class="mb-4">Notifications</h1>
        <div class="d-flex justify-content-between align-items-center mb-4">
                <form method="POST" onsubmit="return confirmClearAll()">
                <button class="btn btn-outline-secondary btn-sm me-2" type="submit" name="mark_all_read">Mark all as read</button>
                <button type="submit" name="clear_all" class="btn btn-outline-danger btn-sm">Clear All</button>
                </form>
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        Filter
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" href="notification.php?filter=all">All Notifications</a></li>
                        <li><a class="dropdown-item" href="notification.php?filter=oldest">Oldest</a></li>
                        <li><a class="dropdown-item" href="notification.php?filter=recent">Recent</a></li>
                        <li><a class="dropdown-item" href="notification.php?filter=read">Read</a></li>
                        <li><a class="dropdown-item" href="notification.php?filter=unread">Unread</a></li>
                    </ul>
                </div>
            </div>
            <div class="notification-list">
                <?php if ($notifications->num_rows > 0): ?>
                    <?php while ($notification = $notifications->fetch_assoc()): ?>
                        <div class="card <?= $notification['is_read'] ? 'read' : 'unread' ?> mb-3">
                            <div class="card-body d-flex align-items-center">
                                <i class="bi bi-chat-dots notification-icon text-primary me-3"></i>
                                <div class="flex-grow-1">
                                    <h5 class="card-title"><?= htmlspecialchars($notification['commenter_name']) ?> commented on your post</h5>
                                    <p class="card-text"><?= htmlspecialchars($notification['comment']) ?></p>
                                    <p class="text-muted">Post: <?= htmlspecialchars($notification['title']) ?></p>
                                    <small class="text-muted"><?= date('F j, Y, g:i a', strtotime($notification['created_at'])) ?></small>
                                </div>
                                <div>
                                    <a href="?read_id=<?= $notification['comment_id'] ?>" class="btn btn-outline-primary btn-sm me-2 mb-3 mb-sm-0">Mark as Read</a>
                                    <a href="view-others.php?post_id=<?= $notification['post_id'] ?>" class="btn btn-outline-success btn-sm me-2 mb-3 mb-sm-0">See Post</a>
                                    <a href="?delete_id=<?= $notification['comment_id'] ?>" class="btn btn-outline-danger btn-sm mb-3 mb-sm-0" onclick="return confirm('Are you sure you want to delete this notification?')">Delete</a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-center text-muted">No notifications yet.</p>
                <?php endif; ?>
            </div>
        </main>

    

    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/theme.js"></script>
    </body>
    </html>