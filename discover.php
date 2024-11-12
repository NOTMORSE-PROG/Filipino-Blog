<?php
session_start();
$_SESSION['referrer'] = $_SERVER['REQUEST_URI'];
include('db_connect.php');

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 6;
$offset = ($page - 1) * $limit;
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
$selectedCategory = isset($_GET['category']) ? $_GET['category'] : '';

// Form the initial count query
$totalPostsQuery = "SELECT COUNT(*) as total FROM posts";
if ($searchQuery || $selectedCategory) {
    $totalPostsQuery .= " WHERE ";

    if ($searchQuery) {
        $totalPostsQuery .= "(title LIKE '%$searchQuery%' OR content LIKE '%$searchQuery%' OR fullName LIKE '%$searchQuery%')";
    }

    if ($searchQuery && $selectedCategory) {
        $totalPostsQuery .= " AND ";
    }

    if ($selectedCategory) {
        $totalPostsQuery .= "category = '$selectedCategory'";
    }
}

// Execute the count query
$totalPostsResult = $conn->query($totalPostsQuery);
if ($totalPostsResult === false) {
    die("Error: " . $conn->error);
}
$totalPostsRow = $totalPostsResult->fetch_assoc();
$totalPosts = $totalPostsRow['total'];
$totalPages = ceil($totalPosts / $limit);

// Form the fetch posts query
$postsQuery = "
    SELECT 
        p.*, 
        u.fullName 
    FROM 
        posts p 
    JOIN 
        users u 
    ON 
        p.user_id = u.id ";

if ($searchQuery || $selectedCategory) {
    $postsQuery .= " WHERE ";

    if ($searchQuery) {
        $postsQuery .= "(title LIKE '%$searchQuery%' OR content LIKE '%$searchQuery%' OR fullName LIKE '%$searchQuery%')";
    }

    if ($searchQuery && $selectedCategory) {
        $postsQuery .= " AND ";
    }

    if ($selectedCategory) {
        $postsQuery .= "category = '$selectedCategory'";
    }
}

$postsQuery .= "
    LIMIT 
        $limit 
    OFFSET 
        $offset";

$postsResult = $conn->query($postsQuery);
if (!$postsResult) {
    die("Error executing query: " . $conn->error);
}
$posts = $postsResult->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discover - FilipinoBlog</title>
    <link rel="shortcut icon" type="x-icon" href="logo.png" />
    <link rel="stylesheet" href="bootstrap.min.css" />
    <link rel="stylesheet" href="discover.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css"/>
</head>
<body>
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
          <a class="navbar-brand" href="index.php">
            <img
              src="logo.png"
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
                <a class="nav-link active" aria-current="page" href="discover.php">Discover</a>
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
        <h1 class="text-center mb-5">Discover Filipino Blogs</h1>
        <div class="row mb-4">
            <div class="col-md-8 mb-3 mb-md-0">
                <input id="searchInput" type="search" class="form-control" placeholder="Search for blogs, topics, or authors..." value="<?php echo htmlspecialchars($searchQuery); ?>">
            </div>
            <div class="col-md-4">
    <select id="categorySelect" class="form-select">
        <option value="All Categories" <?php echo ($selectedCategory === '' || $selectedCategory === 'All Categories') ? 'selected' : ''; ?>>All Categories</option>
        <option value="Travel" <?php echo $selectedCategory === 'Travel' ? 'selected' : ''; ?>>Travel</option>
        <option value="Food" <?php echo $selectedCategory === 'Food' ? 'selected' : ''; ?>>Food</option>
        <option value="Culture" <?php echo $selectedCategory === 'Culture' ? 'selected' : ''; ?>>Culture</option>
        <option value="Lifestyle" <?php echo $selectedCategory === 'Lifestyle' ? 'selected' : ''; ?>>Lifestyle</option>
        <option value="Technology" <?php echo $selectedCategory === 'Technology' ? 'selected' : ''; ?>>Technology</option>
    </select>
</div>

        </div>

    <div id="blogContainer" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php if ($totalPosts == 0): ?>
                <div class="col-12">
                    <div class="text-center">
                        <h3>No posts available</h3>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($posts as $post): ?>
                <div class="col">
                        <div class="card h-100" 
                            data-id="<?php echo htmlspecialchars($post['id']); ?>" 
                            data-title="<?php echo htmlspecialchars($post['title']); ?>" 
                            data-content="<?php echo htmlspecialchars($post['content']); ?>" 
                            data-author="<?php echo htmlspecialchars($post['fullName']); ?>" 
                            data-category="<?php echo htmlspecialchars($post['category']); ?>">
                            <img src="<?php echo htmlspecialchars($post['featured_image']); ?>" 
                             class="card-img-top post-image" alt="Post Image">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($post['title']); ?></h5>
                            <p class="card-text"><?php echo strlen($post['content']) > 150 ? substr(htmlspecialchars($post['content']), 0, 150) . '...' : htmlspecialchars($post['content']); ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">By <?php echo htmlspecialchars($post['fullName']); ?></small>
                                <span class="badge bg-primary rounded-pill"><?php echo htmlspecialchars($post['category']); ?></span>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-top-0">
                            <div class="d-flex justify-content-between align-items-center">
                            <a href="view-others.php?post_id=<?= $post['id'] ?>" class="btn btn-sm btn-outline-primary">View</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <?php if ($totalPosts > 0): ?>
    <nav aria-label="Page navigation" class="mt-5">
        <ul class="pagination justify-content-center">
            <li class="page-item <?php echo ($page <= 1 || $totalPages == 1) ? 'disabled' : ''; ?>">
                <a class="page-link" href="?page=<?php echo max(1, $page - 1); ?>&search=<?php echo urlencode($searchQuery); ?>&category=<?php echo urlencode($selectedCategory); ?>">Previous</a>
            </li>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($searchQuery); ?>&category=<?php echo urlencode($selectedCategory); ?>"><?php echo $i; ?></a>
            </li>
            <?php endfor; ?>
            <li class="page-item <?php echo ($page >= $totalPages || $totalPages == 1) ? 'disabled' : ''; ?>">
                <a class="page-link" href="?page=<?php echo min($totalPages, $page + 1); ?>&search=<?php echo urlencode($searchQuery); ?>&category=<?php echo urlencode($selectedCategory); ?>">Next</a>
            </li>
        </ul>
    </nav>
<?php endif; ?>

    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>About FilipinoBlog</h5>
                    <p>Discover and share the best Filipino content from across the web.</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                    <li><a href="index.php" class="text-light">Home</a></li>
                    <li><a href="discover.php" class="text-light">Discover</a></li>
                    <li><a href="about.php" class="text-light">About</a></li>
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <li><a href="login.php" class="text-light">Log in</a></li>
                        <?php else: ?>
                        <li><a href="dashboard.php" class="text-light">Dashboard</a></li>
                    <?php endif; ?>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5 class="text-filipino">Connect With Us</h5>
                    <ul class="list-inline">
                    <li class="list-inline-item">
                        <a href="#" class="facebook-link">
                        <i class="bi bi-facebook"></i>
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a href="#" class="text-muted twitter-link">
                        <i class="bi bi-twitter"></i>
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a href="#" class="text-muted instagram-link">
                        <i class="bi bi-instagram"></i>
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a href="#" class="text-muted linkedin-link">
                        <i class="bi bi-linkedin"></i>
                        </a>
                    </li>
                    </ul>
                </div>
            <hr class="mt-4 mb-3">
            <p class="text-center mb-0">&copy; 2024 FilipinoBlog. All rights reserved.</p>
        </div>
    </footer>

<script src="bootstrap.bundle.min.js"></script>
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
 <script>
    document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchInput');
    const categorySelect = document.getElementById('categorySelect');

    function applyFiltersAndNavigate() {
        const searchValue = searchInput.value.trim();
        const selectedCategory = categorySelect.value;


        let urlParams = new URLSearchParams(window.location.search);
        if (searchValue) {
            urlParams.set('search', searchValue);
        } else {
            urlParams.delete('search');
        }
        if (selectedCategory && selectedCategory !== 'All Categories') {
            urlParams.set('category', selectedCategory);
        } else {
            urlParams.delete('category');
        }

        window.location.search = urlParams.toString();
    }

    searchInput.addEventListener('input', applyFiltersAndNavigate);
    categorySelect.addEventListener('change', applyFiltersAndNavigate);
});
    </script>
    
</body>
</html>
  