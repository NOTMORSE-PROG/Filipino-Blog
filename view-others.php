<?php
session_start();
$referrer = isset($_SESSION['referrer']) ? $_SESSION['referrer'] : 'index.php';
include('db_connect.php');

$postId = isset($_GET['post_id']) ? (int)$_GET['post_id'] : 0;

if ($postId == 0) {
    die('Invalid post ID');
}

$postQuery = $conn->prepare('
    SELECT p.title, p.content, p.created_at AS publish_date, p.featured_image AS image_path, 
           u.id AS user_id, u.fullName, up.bio, up.picture_path, p.tags, p.category 
    FROM posts p 
    INNER JOIN users u ON p.user_id = u.id 
    LEFT JOIN user_profile up ON u.id = up.user_id 
    WHERE p.id = ?
');
$postQuery->bind_param('i', $postId);
$postQuery->execute();
$postResult = $postQuery->get_result();

if ($postResult->num_rows == 0) {
    die('Post not found');
}

$post = $postResult->fetch_assoc();
$tags = !empty($post['tags']) ? explode(',', $post['tags']) : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    if (isset($_POST['comment'])) {
        $comment = trim($_POST['comment']);
        $userId = $_SESSION['user_id'];

        if (!empty($comment)) {
            $insertCommentQuery = $conn->prepare('INSERT INTO comments (post_id, user_id, comment) VALUES (?, ?, ?)');
            $insertCommentQuery->bind_param('iis', $postId, $userId, $comment);
            $insertCommentQuery->execute();
            header("Location: view-others.php?post_id=$postId"); 
            exit();
        }
    } elseif (isset($_POST['delete_comment_id'])) {
        $commentId = (int)$_POST['delete_comment_id'];
        $userId = $_SESSION['user_id'];
        $deleteQuery = $conn->prepare('DELETE FROM comments WHERE id = ? AND user_id = ?');
        $deleteQuery->bind_param('ii', $commentId, $userId);
        $deleteQuery->execute();
        header("Location: view-others.php?post_id=$postId");
        exit();
    }
}

$commentsQuery = $conn->prepare('
    SELECT c.id AS comment_id, c.comment, c.created_at, u.fullName, up.picture_path, u.id AS user_id 
    FROM comments c 
    INNER JOIN users u ON c.user_id = u.id 
    LEFT JOIN user_profile up ON u.id = up.user_id 
    WHERE c.post_id = ? 
    ORDER BY c.created_at DESC
');
$commentsQuery->bind_param('i', $postId);
$commentsQuery->execute();
$commentsResult = $commentsQuery->get_result();
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?> - FilipinoBlog</title>
    <link rel="shortcut icon" href="logo.png" />
    <link rel="stylesheet" href="bootstrap.min.css" />
    <link rel="stylesheet" href="view-others.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css"/>
</head>
<body>
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="logo.png" alt="FilipinoBlog Logo" width="30" height="30" class="d-inline-block align-top"/>
                <span class="text-filipino">FilipinoBlog</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="discover.php">Discover</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <li class="nav-item"><a class="nav-link" href="login.php">Log In</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><button id="themeToggle" class="btn btn-link nav-link"><i class="bi bi-sun-fill"></i></button></li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-5">
        <div class="row">
            <div class="col-lg-8">
            <a href="<?php echo htmlspecialchars($referrer); ?>" class="btn btn-sm btn-outline-secondary" style="margin-bottom: 20px;">
        <i class="bi bi-arrow-left"></i> Back to Posts</a>
                <article>
                    <h1 class="mb-4"><?php echo htmlspecialchars($post['title']); ?></h1>
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="d-flex align-items-center">
                            <img src="<?php echo !empty($post['picture_path']) ? htmlspecialchars($post['picture_path']) : 'https://via.placeholder.com/64'; ?>" alt="Author Avatar" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                            <div>
                                <h5 class="mb-0"><?php echo htmlspecialchars($post['fullName']); ?></h5>
                                <small class="text-muted">Published on <?php echo date('F j, Y', strtotime($post['publish_date'])); ?></small>
                            </div>
                        </div>
                    </div>
                    <img src="<?php echo !empty($post['image_path']) ? htmlspecialchars($post['image_path']) : 'https://via.placeholder.com/800x400'; ?>" alt="Featured Image" class="img-fluid rounded mb-4 post-image">
                    <div class="content">
                        <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                    </div>
                    <div class="mt-4">
                        <h3 style="margin-bottom: 20px;">Tags</h3>
                        <div class="d-flex flex-wrap gap-2">
                            <?php if (empty($tags)): ?>
                                <p>No tags available.</p>
                            <?php else: ?>
                                <?php foreach ($tags as $tag): ?>
                                    <a href="#" class="btn btn-sm btn-outline-secondary" style="margin-bottom: 20px;">#<?php echo htmlspecialchars(trim($tag)); ?></a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
                <section class="mt-5">
                    <h3 style="margin-bottom: 50px;">Comments</h3>
                    <?php if ($commentsResult->num_rows > 0): ?>
                            <?php while ($comment = $commentsResult->fetch_assoc()): ?>
                                <div class="mb-4">
                                    <div class="d-flex mb-3">
                                        <img src="<?php echo !empty($comment['picture_path']) ? htmlspecialchars($comment['picture_path']) : 'https://via.placeholder.com/48'; ?>" alt="User Avatar" class="rounded-circle me-3 comment-avatar" style="width: 50px; height: 50px; object-fit: cover;">
                                        <div>
                                            <h5 class="mb-0"><?php echo htmlspecialchars($comment['fullName']); ?></h5>
                                            <small class="text-muted">Posted on <?php echo date('F j, Y, g:i a', strtotime($comment['created_at'])); ?></small>
                                            <p class="mt-2"><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></p>
                                        </div>
                                        <div class="ms-auto">
                                            <button class="btn btn-primary" style="height: 50px;" onclick="window.location.href='user-profile.php?id=<?= htmlspecialchars($comment['user_id']) ?>'">View Profile</button>
                                            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $comment['user_id']): ?>
                                                <form method="post" class="d-inline" onsubmit="return confirmDelete();">
                                                    <input type="hidden" name="delete_comment_id" value="<?= htmlspecialchars($comment['comment_id']) ?>">
                                                    <button type="submit" class="btn btn-danger" style="height: 50px;">Delete</button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <hr class="comment-separator">
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p style="margin-bottom: 50px;">No comments yet.</p>
                        <?php endif; ?>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <form method="post">
                            <div class="mb-3">
                                <label for="commentText" class="form-label">Leave a comment</label>
                                <textarea class="form-control" id="commentText" name="comment" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-filipino" style = "color: black;">Post Comment</button>
                        </form>
                    <?php else: ?>
                        <p><a href="login.php">Log in</a> to leave a comment.</p>
                    <?php endif; ?>
                </section>
            </div>
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h4 class="card-title">About the Author</h4>
                        <div class="d-flex align-items-center mb-3">
                            <img src="<?php echo !empty($post['picture_path']) ? htmlspecialchars($post['picture_path']) : 'https://via.placeholder.com/64'; ?>" alt="Author Avatar" class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;">
                            <div>
                                <h5 class="mb-0"><?php echo htmlspecialchars($post['fullName']); ?></h5>
                            </div>
                        </div>
                        <p><?php echo !empty($post['bio']) ? nl2br(htmlspecialchars($post['bio'])) : 'No bio available.'; ?></p>
                        <button class="btn btn-primary" onclick="window.location.href='user-profile.php?id=<?= htmlspecialchars($post['user_id']) ?>'">View Profile</button>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-body">
                        <h4 class="card-title">Categories</h4>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="#" class="btn btn-outline-secondary btn-sm"><?php echo htmlspecialchars($post['category']); ?></a>
                        </div>
                    </div>
                </div>
        </main>

    <script src="bootstrap.bundle.min.js"></script>
    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this comment?");
            }
        const themeToggle = document.getElementById("themeToggle");
        const htmlElement = document.documentElement;
        const iconElement = themeToggle.querySelector("i");

        function setTheme(theme) {
            htmlElement.setAttribute("data-bs-theme", theme);
            iconElement.classList.toggle("bi-moon-fill", theme === "dark");
            iconElement.classList.toggle("bi-sun-fill", theme === "light");
        }

        window.addEventListener("DOMContentLoaded", () => {
            const savedTheme = localStorage.getItem("theme") || "light";
            setTheme(savedTheme);
        });

        themeToggle.addEventListener("click", () => {
            const newTheme = htmlElement.getAttribute("data-bs-theme") === "light" ? "dark" : "light";
            setTheme(newTheme);
            localStorage.setItem("theme", newTheme);
        });
    </script>
</body>
</html>
