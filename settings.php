<?php
session_start();

include('db_connect.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

$unreadNotificationsQuery = "SELECT COUNT(*) AS unread_count FROM comments WHERE user_id = ? AND is_read = 0";
$unreadStmt = $conn->prepare($unreadNotificationsQuery);
$unreadStmt->bind_param("i", $userId);
$unreadStmt->execute();
$unreadResult = $unreadStmt->get_result();
$unreadRow = $unreadResult->fetch_assoc();
$unreadCount = $unreadRow['unread_count'] ?? 0;
$unreadStmt->close();

function deleteDirectory($dir) {
    if (!file_exists($dir)) {
        return true;
    }
    if (!is_dir($dir)) {
        return unlink($dir);
    }
    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }
        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }
    }
    return rmdir($dir);
}


$userQuery = $conn->prepare("SELECT fullName, email, (SELECT picture_path FROM user_profile WHERE user_id = users.id) as picture_path, (SELECT bio FROM user_profile WHERE user_id = users.id) as bio FROM users WHERE id = ?");
$userQuery->bind_param("i", $userId);
$userQuery->execute();
$userResult = $userQuery->get_result();
$userData = $userResult->fetch_assoc();
$fullName = $userData['fullName'];
$email = $userData['email'];
$picturePath = $userData['picture_path'] ?: 'https://via.placeholder.com/32';
$bio = $userData['bio'] ?: '';
$safeEmail = preg_replace('/[^\w.@]+/', '_', $email);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_account'])) {
    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare("DELETE FROM posts WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        $stmt = $conn->prepare("DELETE FROM comments WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        
        $stmt = $conn->prepare("DELETE FROM user_profile WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        $uploadDir = 'C:/xampp/htdocs/Filipino-Blog/uploads/' . $safeEmail;
        $profileDir = 'C:/xampp/htdocs/Filipino-Blog/profile/' . $safeEmail;

        deleteDirectory($uploadDir);
        deleteDirectory($profileDir);

        $conn->commit();

        session_destroy();
        header("Location: login.php");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        echo "Failed to delete account: " . $e->getMessage();
    }
}

$userQuery = $conn->prepare("SELECT fullName, email, (SELECT picture_path FROM user_profile WHERE user_id = users.id) as picture_path FROM users WHERE id = ?");
$userQuery->bind_param("i", $userId);
$userQuery->execute();
$userResult = $userQuery->get_result();
$userData = $userResult->fetch_assoc();

$fullName = $userData['fullName'];
$email = $userData['email'];
$picturePath = $userData['picture_path'] ?: 'https://via.placeholder.com/32';

$safeEmail = preg_replace('/[^\w.@]+/', '_', $email);
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $name = $_POST['fullName'];
    $email = $_POST['email'];
    $bio = $_POST['bio'];
    $profilePicture = $_FILES['profilePicture'];

    if (strlen($bio) > 200) {
        echo "Bio must not exceed 200 characters!";
        exit();
    }

    if (!empty($name) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->bind_param("si", $email, $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            $stmt = $conn->prepare("UPDATE users SET fullName = ?, email = ? WHERE id = ?");
            $stmt->bind_param("ssi", $name, $email, $userId);
            $stmt->execute();

            if ($profilePicture['error'] == UPLOAD_ERR_OK) {
                $uploadDir = 'C:/xampp/htdocs/Filipino-Blog/profile/' . $safeEmail . '/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $fileName = basename($profilePicture['name']);
                $uploadFile = $uploadDir . $fileName;

                if (move_uploaded_file($profilePicture['tmp_name'], $uploadFile)) {
                    $picturePath = 'profile/' . $safeEmail . '/' . $fileName;
                } else {
                    die("Error uploading file.");
                }
            }

            $stmt = $conn->prepare("SELECT id FROM user_profile WHERE user_id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $stmt2 = $conn->prepare("UPDATE user_profile SET picture_path = ?, bio = ? WHERE user_id = ?");
                $stmt2->bind_param("ssi", $picturePath, $bio, $userId);
                $stmt2->execute();
            } else {
                $stmt2 = $conn->prepare("INSERT INTO user_profile (user_id, picture_path, bio) VALUES (?, ?, ?)");
                $stmt2->bind_param("iss", $userId, $picturePath, $bio);
                $stmt2->execute();
            }

            $stmt2->close();
        } else {
            echo "Email already in use!";
        }
        
        echo "Profile updated successfully!";
        header("Location: settings.php");
        exit();
    } else {
        echo "Invalid input!";
    }
    
    $stmt->close();
    $conn->close();
}

  
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_password'])) {
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    if (!empty($newPassword) && $newPassword === $confirmPassword) {

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashedPassword, $userId);
        $stmt->execute();
        $stmt->close();
        echo "Password updated successfully!";
        header("Location: settings.php");
        exit();
    } 
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile/Settings - FilipinoBlog</title>
    <link rel="stylesheet" href="bootstrap.min.css" />
    <link rel="stylesheet" href="settings.css" />
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

    .container-fluid {
        padding-bottom: 10px;
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
                        <img src="<?php echo htmlspecialchars($picturePath); ?>" alt="User Avatar" class="rounded-circle" width="32" height="32">
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
        <nav class="col-md-3 col-lg-2 sidebar " id="sidebar">
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
                            <a class="nav-link active" href="settings.php">
                                <i class="bi bi-gear me-2"></i>
                                Settings
                            </a>
                        </li>
                </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content-wrapper">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Settings</h1>
            </div>

            
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Profile Information</h5>
                            <form method="POST" enctype="multipart/form-data" action="">
                                <div class="mb-3">
                                    <label for="fullName" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="fullName" name="fullName" value="<?php echo htmlspecialchars($fullName); ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email address</label>
                                    <input type="email" class="form-control" id="email" name="email" oninput="validateEmailField()" value="<?php echo htmlspecialchars($email); ?>">
                                    <small id="emailError" class="text-danger" style="display: none;">This field shouldn’t be empty.</small>
                                </div>
                                <div class="mb-3">
                                    <label for="bio" class="form-label">Bio</label>
                                    <textarea class="form-control" id="bio" name="bio" rows="3" maxlength="200" oninput="updateCharCount()"><?php echo htmlspecialchars($userData['bio'] ?? ''); ?></textarea>
                                    <small id="charCount" class="text-muted">0/200</small>
                                </div>
                                <div class="mb-3">
                                    <label for="profilePicture" class="form-label">Profile Picture</label>
                                    <input type="file" class="form-control" id="profilePicture" name="profilePicture" accept=".png, .jpg, .jpeg">
                                </div>
                                <button type="submit" name="update_profile" class="btn btn-filipino">Save Changes</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Profile Picture</h5>
                                <div class="avatar-upload">
                                <div class="avatar-preview">
                                    <div id="imagePreview" style="background-image: url(<?php echo htmlspecialchars($picturePath); ?>);"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Change Password</h5>
                                <form method="POST" action="">
                                    <div class="mb-3">
                                        <label for="newPassword" class="form-label">New Password</label>
                                        <input 
                                            type="password" 
                                            class="form-control" 
                                            id="newPassword" 
                                            name="newPassword" 
                                            minlength="8" 
                                            maxlength="20"
                                            oninput="validateNewPasswordField()"
                                        >
                                        <small id="newPasswordError" class="text-danger" style="display: none;">Password must be between 8 and 20 characters.</small>
                                    </div>
                                    <div class="mb-3">
                                        <label for="confirmPassword" class="form-label">Confirm New Password</label>
                                        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword">
                                        <small id="confirmPasswordError" class="text-danger" style="display: none;">Passwords must match.</small>
                                    </div>
                                    <button type="submit" name="update_password" id="updatePasswordButton" class="btn btn-filipino" disabled>Update Password</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                            <h5 class="card-title">Danger Zone</h5>
                            <p class="text-muted">These actions are irreversible. Please proceed with caution.</p>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                Delete Account
                            </button>
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
                                <h5 class="modal-title" id="deleteModalLabel">Confirm Account Deletion</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to delete your account? This action cannot be undone.
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <form method="POST">
                                    <input type="hidden" name="delete_account" value="1">
                                    <button type="submit" class="btn btn-danger">Yes, Delete My Account</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            
                <script src="bootstrap.bundle.min.js"></script>
                <script src="theme.js"></script>
                <script>
                function readURL(input) {
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            document.getElementById('imagePreview').style.backgroundImage = 'url(' + e.target.result + ')';
                            document.getElementById('imagePreview').style.display = 'none';
                            document.getElementById('imagePreview').style.fadeIn(650);
                        };
                        reader.readAsDataURL(input.files[0]);
                    }
                }

                document.getElementById('imageUpload').addEventListener('change', function () {
                    readURL(this);
                });

                const deleteConfirmationInput = document.getElementById("deleteConfirmation");
                const confirmDeleteBtn = document.getElementById("confirmDeleteBtn");

                deleteConfirmationInput.addEventListener("input", function () {
                    confirmDeleteBtn.disabled = this.value !== "DELETE";
                });
                </script>
                <script>
                function updateCharCount() {
                    const bio = document.getElementById('bio');
                    const charCount = document.getElementById('charCount');
                    charCount.textContent = `${bio.value.length}/200`;
                }

                document.addEventListener("DOMContentLoaded", updateCharCount);
                </script>
                <script>
                    function validateEmailField() {
                        const emailInput = document.getElementById('email').value.trim();
                        const emailError = document.getElementById('emailError');
                        const saveButton = document.getElementById('saveButton');

                        if (emailInput === '') {
                            emailError.style.display = 'block';
                            emailError.textContent = 'This field shouldn’t be empty.';
                            saveButton.disabled = true;
                        } else {
                            emailError.style.display = 'none';
                            saveButton.disabled = false;
                        }
                    }
                    document.addEventListener('DOMContentLoaded', validateEmailField);
                </script>
                <script>
                    function validateNewPasswordField() {
                        const newPasswordInput = document.getElementById('newPassword').value;
                        const newPasswordError = document.getElementById('newPasswordError');
                        const updatePasswordButton = document.getElementById('updatePasswordButton');
                        const minLength = 8;
                        const maxLength = 20;

                        if (newPasswordInput.length < minLength || newPasswordInput.length > maxLength) {
                            newPasswordError.style.display = 'block';
                            newPasswordError.textContent = `Password must be between ${minLength} and ${maxLength} characters.`;
                            updatePasswordButton.disabled = true;
                        } else {
                            newPasswordError.style.display = 'none';
                            updatePasswordButton.disabled = false;
                        }
                    }
                    function validateConfirmPassword() {
                        const newPassword = document.getElementById('newPassword').value;
                        const confirmPassword = document.getElementById('confirmPassword').value;
                        const confirmPasswordError = document.getElementById('confirmPasswordError');
                        const updatePasswordButton = document.getElementById('updatePasswordButton');

                        if (newPassword !== confirmPassword) {
                            confirmPasswordError.style.display = 'block';
                            confirmPasswordError.textContent = 'Passwords must match.';
                            updatePasswordButton.disabled = true;
                        } else {
                            confirmPasswordError.style.display = 'none';
                            updatePasswordButton.disabled = false;
                        }
                    }
                    document.getElementById('confirmPassword').addEventListener('input', validateConfirmPassword);
                </script>
            </body>
            </html>