<?php
function time_ago($timestamp) {
  $time_ago = strtotime($timestamp);
  $current_time = time();
  $time_difference = $current_time - $time_ago;
  $seconds = $time_difference;

  $minutes      = round($seconds / 60);           
  $hours        = round($seconds / 3600);         
  $days         = round($seconds / 86400);        
  $weeks        = round($seconds / 604800);       
  $months       = round($seconds / 2629440);      
  $years        = round($seconds / 31553280);     

  if ($seconds <= 60) {
      return "Just Now";
  } else if ($minutes <= 60) {
      if ($minutes == 1) {
          return "one minute ago";
      } else {
          return "$minutes minutes ago";
      }
  } else if ($hours <= 24) {
      if ($hours == 1) {
          return "an hour ago";
      } else {
          return "$hours hours ago";
      }
  } else if ($days <= 7) {
      if ($days == 1) {
          return "yesterday";
      } else {
          return "$days days ago";
      }
  } else if ($weeks <= 4.3) { 
      if ($weeks == 1) {
          return "one week ago";
      } else {
          return "$weeks weeks ago";
      }
  } else if ($months <= 12) {
      if ($months == 1) {
          return "one month ago";
      } else {
          return "$months months ago";
      }
  } else {
      if ($years == 1) {
          return "one year ago";
      } else {
          return "$years years ago";
      }
  }
}
session_start();
include('includes/db_connect.php');  
$referrer = isset($_SESSION['referrer']) ? $_SESSION['referrer'] : 'index.php';

if (isset($_GET['id'])) {
    $userId = (int)$_GET['id']; 
    $userQuery = "SELECT fullName, email FROM users WHERE id = ?";
    if ($stmt = $conn->prepare($userQuery)) {
        $stmt->bind_param("i", $userId);  
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($fullName, $email);
        $stmt->fetch();
        $user = ['fullName' => $fullName, 'email' => $email];
        $stmt->close();
    }

    $profileQuery = "SELECT picture_path, bio FROM user_profile WHERE user_id = ?";
    if ($stmt = $conn->prepare($profileQuery)) {
        $stmt->bind_param("i", $userId);  
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($picturePath, $bio);
        $stmt->fetch();
        $profile = ['picture_path' => $picturePath, 'bio' => $bio];
        $stmt->close();
    }

    $postsQuery = "SELECT id, title, content, category, tags, featured_image, created_at FROM posts WHERE user_id = ? ORDER BY created_at DESC";
    if ($stmt = $conn->prepare($postsQuery)) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($postId, $title, $content, $category, $tags, $featuredImage, $createdAt);

        $posts = [];
        while ($stmt->fetch()) {
            $posts[] = [
                'id' => $postId,
                'title' => $title,
                'content' => $content,
                'category' => $category,
                'tags' => $tags,
                'featured_image' => $featuredImage,
                'created_at' => $createdAt
            ];
        }
        $stmt->close();
    }

    $recentActivitiesQuery = "SELECT title, created_at FROM posts WHERE user_id = ? ORDER BY created_at DESC LIMIT 3";
    if ($stmt = $conn->prepare($recentActivitiesQuery)) {
        $stmt->bind_param("i", $userId); 
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($activityTitle, $activityCreatedAt);

        $recentActivities = [];
        while ($stmt->fetch()) {
            $recentActivities[] = [
                'title' => $activityTitle,
                'created_at' => $activityCreatedAt
            ];
        }
        $stmt->close();
    }

} else {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>About - FilipinoBlog</title>
    <link rel="shortcut icon" type="x-icon" href="assets/images/logo.png" />
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/user-profile.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css"/>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg sticky-top">
      <div class="container">
        <a class="navbar-brand" href="index.php">
          <img
            src="assets/images/logo.png"
            alt="FilipinoBlog Logo"
            width="30"
            height="30"
            class="d-inline-block align-top"
          />
          <span class="text-filipino">FilipinoBlog</span>
        </a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNav"
          aria-controls="navbarNav"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a class="nav-link" href="index.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="discover.php">Discover</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="about.php"
                >About</a
              >
            </li>
            <?php if (!isset($_SESSION['user_id'])): ?>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Log In</a>
                </li>
              <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">Dashboard</a>
                </li>
              <?php endif; ?>
            <li class="nav-item">
              <button id="themeToggle" class="btn btn-link nav-link">
                <i class="bi bi-sun-fill"></i>
              </button>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <main class="container py-5">
        <a href="<?php echo htmlspecialchars($referrer); ?>" class="btn btn-sm btn-outline-secondary" style="margin-bottom: 20px;">
            <i class="bi bi-arrow-left"></i> Back to Posts
        </a>
        <div class="profile-header">
            <div class="row align-items-center">
                <div class="col-md-3 text-center text-md-start mb-3 mb-md-0">
                    <img src="<?= $profile['picture_path'] ?: 'https://via.placeholder.com/150' ?>" alt="<?= htmlspecialchars($user['fullName']) ?>" class="rounded-circle profile-avatar">
                </div>
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <h1 class="mb-2"><?= htmlspecialchars($user['fullName']) ?></h1>
                    <p class="mb-3"><?= htmlspecialchars($profile['bio']) ?: 'This user has not provided a bio yet.' ?></p>
                    <p class="mb-3">
                        <i class="bi bi-envelope-fill"></i> 
                        <a href="mailto:<?= htmlspecialchars($user['email']) ?>" class="text-decoration-none"><?= htmlspecialchars($user['email']) ?></a>
                    </p>
                </div>
            </div>
        </div>

        <h2 class="mb-4"><?= htmlspecialchars($user['fullName']) ?>'s Blog Posts</h2>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-5">
              <?php if (!empty($posts)): ?>
                  <?php foreach ($posts as $post): ?>
                      <div class="col">
                          <div class="card post-card">
                              <img src="<?= $post['featured_image'] ?: 'https://via.placeholder.com/800x400' ?>" class="card-img-top post-image" alt="<?= htmlspecialchars($post['title']) ?>">
                              <div class="card-body">
                                  <h5 class="card-title"><?= htmlspecialchars($post['title']) ?></h5>
                                  <p class="card-text"><?= htmlspecialchars(substr($post['content'], 0, 100)) ?>...</p>
                                  <div class="d-flex justify-content-between align-items-center">
                                      <small class="text-muted"><?= date('F j, Y', strtotime($post['created_at'])) ?></small>
                                      <span class="badge bg-primary rounded-pill"><?= htmlspecialchars($post['category']) ?></span>
                                  </div>
                              </div>
                              <div class="card-footer bg-transparent border-top-0">
                                  <a href="view-others.php?post_id=<?= $post['id'] ?>" class="btn btn-sm btn-outline-primary">Read More</a>
                              </div>
                          </div>
                      </div>
                  <?php endforeach; ?>
              <?php else: ?>
                  <div class="col">
                      <p class="text-muted text-center">No blog posted yet.</p>
                  </div>
              <?php endif; ?>
          </div>
          <div class="col-md-6 mb-4">
              <div class="card">
                  <div class="card-body">
                      <h3 class="card-title">Recent Activity</h3>
                      <?php if (!empty($recentActivities)): ?>
                          <ul class="list-group list-group-flush">
                              <?php foreach ($recentActivities as $activity): ?>
                                  <li class="list-group-item">
                                      <i class="bi bi-pencil-fill me-2 text-primary"></i>
                                      Posted a new article: "<?= htmlspecialchars($activity['title']) ?>"
                                      <small class="d-block text-muted"><?= time_ago($activity['created_at']) ?></small>
                                  </li>
                              <?php endforeach; ?>
                          </ul>
                      <?php else: ?>
                          <p class="text-muted mt-3">No activity yet.</p>
                      <?php endif; ?>
                  </div>
              </div>
          </div>
        </div>
    </main>
  <script src="assets/js/bootstrap.bundle.min.js"></script>
  <script>
    const themeToggle = document.getElementById("themeToggle");
    const htmlElement = document.documentElement;
    const iconElement = themeToggle.querySelector("i");

    function setTheme(theme) {
      htmlElement.setAttribute("data-bs-theme", theme);
      if (theme === "dark") {
        iconElement.classList.replace("bi-sun-fill", "bi-moon-fill");
      } else {
        iconElement.classList.replace("bi-moon-fill", "bi-sun-fill");
      }
    }

    window.addEventListener("DOMContentLoaded", () => {
      const savedTheme = localStorage.getItem("theme") || "light";
      setTheme(savedTheme);
    });

    themeToggle.addEventListener("click", () => {
      let currentTheme = htmlElement.getAttribute("data-bs-theme");
      let newTheme = currentTheme === "light" ? "dark" : "light";
      setTheme(newTheme);

      localStorage.setItem("theme", newTheme);
    });
  </script>
</body>
</html>