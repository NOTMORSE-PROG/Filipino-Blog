<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - FilipinoBlog</title>
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
</style>
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
                        <a class="nav-link" href="index.html">Home</a>
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
                            <img src="https://via.placeholder.com/32" alt="User Avatar" class="rounded-circle" width="32" height="32">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li><a class="dropdown-item" href="#">Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">Logout</a></li>
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
                            <a class="nav-link" href="others.php">
                                <i class="bi bi-people me-2"></i>
                                See Others' Posts
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
                                <form>
                                    <div class="mb-3">
                                        <label for="fullName" class="form-label">Full Name</label>
                                        <input type="text" class="form-control" id="fullName" value="Juan del Cruz">
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email address</label>
                                        <input type="email" class="form-control" id="email" value="juan@example.com">
                                    </div>
                                    <div class="mb-3">
                                        <label for="bio" class="form-label">Bio</label>
                                        <textarea class="form-control" id="bio" rows="3">Filipino blogger passionate about travel and culture.</textarea>
                                    </div>
                                    <button type="submit" class="btn btn-filipino">Save Changes</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Profile Picture</h5>
                                <div class="avatar-upload">
                                    <div class="avatar-edit">
                                        <input type='file' id="imageUpload" accept=".png, .jpg, .jpeg" />
                                        <label for="imageUpload"></label>
                                    </div>
                                    <div class="avatar-preview">
                                        <div id="imagePreview" style="background-image: url(https://via.placeholder.com/200);">
                                        </div>
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
                                <h5 class="card-title">Account Settings</h5>
                                <form>
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="username" value="juandelacruz">
                                    </div>
                                    <div class="mb-3">
                                        <label for="newPassword" class="form-label">New Password</label>
                                        <input type="password" class="form-control" id="newPassword">
                                    </div>
                                    <div class="mb-3">
                                        <label for="confirmPassword" class="form-label">Confirm New Password</label>
                                        <input type="password" class="form-control" id="confirmPassword">
                                    </div>
                                    <button type="submit" class="btn btn-filipino">Update Account</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Notification Preferences</h5>
                                <form>
                                    <div class="mb-3 form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="emailNotifications" checked>
                                        <label class="form-check-label" for="emailNotifications">Email Notifications</label>
                                    </div>
                                    <div class="mb-3 form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="pushNotifications" checked>
                                        <label class="form-check-label" for="pushNotifications">Push Notifications</label>
                                    </div>
                                    <div class="mb-3 form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="newsletterSubscription" checked>
                                        <label class="form-check-label" for="newsletterSubscription">Newsletter Subscription</label>
                                    </div>
                                    <button type="submit" class="btn btn-filipino">Save Preferences</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Privacy Settings</h5>
                                <form>
                                    <div class="mb-3 form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="profileVisibility" checked>
                                        <label class="form-check-label" for="profileVisibility">Public Profile</label>
                                    </div>
                                    <div class="mb-3 form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="showEmail">
                                        <label class="form-check-label" for="showEmail">Show Email Address</label>
                                    </div>
                                    <div class="mb-3 form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="allowComments" checked>
                
                                        <label class="form-check-label" for="allowComments">Allow Comments on Posts</label>
                                    </div>
                                    <button type="submit" class="btn btn-filipino">Update Privacy Settings</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Danger Zone</h5>
                                <p class="text-muted">These actions are irreversible. Please proceed with caution.</p>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                    Delete Account
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <div class="modal fade" id="deactivateAccountModal" tabindex="-1" aria-labelledby="deactivateAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deactivateAccountModalLabel">Deactivate Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to deactivate your account? This action will temporarily disable your account and hide your profile from other users.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-warning">Deactivate Account</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteAccountModalLabel">Delete Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you absolutely sure you want to delete your account? This action cannot be undone and will permanently erase all your data.</p>
                    <div class="mb-3">
                        <label for="deleteConfirmation" class="form-label">Type "DELETE" to confirm:</label>
                        <input type="text" class="form-control" id="deleteConfirmation" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn" disabled>Delete Account</button>
                </div>
            </div>
        </div>
    </div>

    <script src="bootstrap.bundle.min.js"></script>
    <script src ="theme.js"></script>
    <script>
        function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
            $("#imagePreview").css(
                "background-image",
                "url(" + e.target.result + ")"
            );
            $("#imagePreview").hide();
            $("#imagePreview").fadeIn(650);
            };
            reader.readAsDataURL(input.files[0]);
        }
        }
        $("#imageUpload").change(function () {
        readURL(this);
        });

        const deleteConfirmationInput = document.getElementById("deleteConfirmation");
        const confirmDeleteBtn = document.getElementById("confirmDeleteBtn");

        deleteConfirmationInput.addEventListener("input", function () {
        confirmDeleteBtn.disabled = this.value !== "DELETE";
        });
    </script>
</body>
</html>