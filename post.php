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

$post_deleted = false;

if (isset($_POST['delete_post_id'])) {
    $delete_post_id = $conn->real_escape_string($_POST['delete_post_id']);
    $query = "SELECT featured_image FROM posts WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $delete_post_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $image_path = $row['featured_image'];

        if (file_exists($image_path)) {
            unlink($image_path);
        }

        $query = "DELETE FROM posts WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $delete_post_id, $user_id);
        if ($stmt->execute() === TRUE) {
            $post_deleted = true;
        } else {
            echo "Error deleting post: " . $conn->error;
        }
    }

    $stmt->close();
}

$filter_category = $_POST['filter_category'] ?? '';
$sort_order = $_POST['sort_order'] ?? 'asc';

$sql = "SELECT * FROM posts WHERE user_id = ?";
$params = [$user_id];

if (!empty($filter_category)) {
    $sql .= " AND category = ?";
    $params[] = $filter_category;
}

$sql .= " ORDER BY created_at " . ($sort_order == 'desc' ? 'DESC' : 'ASC');

$stmt = $conn->prepare($sql);
$stmt->bind_param(str_repeat("s", count($params)), ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Posts - FilipinoBlog</title>
    <link rel="stylesheet" href="bootstrap.min.css" />
    <link rel="stylesheet" href="post.css" />
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
<nav class="navbar navbar-expand-lg sticky-top navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            <img src="logo.png" alt="FilipinoBlog Logo" width="30" height="30" class="d-inline-block align-top">
            <span class="ms-2 text-filipino">FilipinoBlog</span>
        </a>
        <button class="navbar-toggler" type="button" id="sidebarToggle" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle sidebar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item">
                    <button id="themeToggle" class="btn btn-link nav-link"><i class="bi bi-sun-fill"></i></button>
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
        <nav class="col-md-3 col-lg-2 sidebar bg-dark" id="sidebar">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link text-light" href="dashboard.php"><i class="bi bi-house-door me-2"></i>Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link text-light active" href="post.php"><i class="bi bi-file-earmark-text me-2"></i>My Posts</a></li>
                    <li class="nav-item"><a class="nav-link text-light" href="others.php"><i class="bi bi-people me-2"></i>See Others' Posts</a></li>
                    <li class="nav-item"><a class="nav-link text-light" href="settings.php"><i class="bi bi-gear me-2"></i>Settings</a></li>
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

            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4">
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $content_preview = substr($row['content'], 0, 100) . '...';
                        echo '<div class="col">';
                        echo '    <div class="card h-100">';
                        echo '        <img src="' . htmlspecialchars($row['featured_image']) . '" class="card-img-top post-image" alt="' . htmlspecialchars($row['title']) . '">';
                        echo '        <div class="card-body">';
                        echo '            <h5 class="card-title">' . htmlspecialchars($row['title']) . '</h5>';
                        echo '            <p class="card-text">' . htmlspecialchars($content_preview) . '</p>';
                        echo '            <div class="d-flex justify-content-between align-items-center">';
                        echo '                <small class="text-muted">Posted on ' . htmlspecialchars($row['created_at']) . '</small>';
                        echo '                <span class="badge bg-primary rounded-pill">Views</span>';
                        echo '            </div>';
                        echo '        </div>';
                        echo '        <div class="card-footer bg-transparent border-top-0">';
                        echo '            <div class="d-flex justify-content-between align-items-center">';
                        echo '                <div class="btn-group">';
                        echo '                    <a href="edit.php?id=' . $row['id'] . '" class="btn btn-sm btn-outline-primary">Edit</a>';
                        echo '                    <a href="view.php?id=' . $row['id'] . '" class="btn btn-sm btn-outline-success">View</a>';
                        echo '                </div>';
                        echo '                <form method="post" class="delete-form">';
                        echo '                    <input type="hidden" name="delete_post_id" value="' . $row['id'] . '">';
                        echo '                    <button type="button" class="btn btn-sm btn-outline-danger delete-button"><i class="bi bi-trash"></i></button>';
                        echo '                </form>';
                        echo '            </div>';
                        echo '        </div>';
                        echo '    </div>';
                        echo '</div>';
                    }
                } else {
                    echo '<span class="no_post" style="margin-top: 20px;">No posts found.</span>';
                }

                $stmt->close();
                $conn->close();
                ?>
            </div>
        </main>
    </div>
</div>

<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="deleteConfirmationForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this post?
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="delete_post_id" id="deletePostId">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if ($post_deleted) { ?>
<script>
    var postDeletedModal = new bootstrap.Modal(document.getElementById('postDeletedModal'));
    postDeletedModal.show();
</script>
<?php } ?>

<script src="bootstrap.bundle.min.js"></script>
<script src ="theme.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const deleteButtons = document.querySelectorAll('.delete-button');
        const deletePostIdInput = document.getElementById('deletePostId');
        const deleteConfirmationModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));

        deleteButtons.forEach(button => {
            button.addEventListener('click', (event) => {
                const deleteForm = button.closest('form');
                const postId = deleteForm.querySelector('input[name="delete_post_id"]').value;
                deletePostIdInput.value = postId;
                deleteConfirmationModal.show();
            });
        });

        document.getElementById('deleteConfirmationForm').addEventListener('submit', () => {
            deleteConfirmationModal.hide();
        });
    });
</script>
</body>
</html>