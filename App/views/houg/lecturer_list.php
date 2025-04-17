<?php


// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../views/login.php?error=unauthorized');
    exit();
}

// Get lecturers data from session
$lecturers = $_SESSION['lecturers'] ?? [];
$categories = $_SESSION['categories'] ?? [];
$selectedCategory = $_SESSION['selected_category'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Lecturers List - RelaxU</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../../assets/css/header_footer.css" />
    <link rel="stylesheet" href="../../assets/css/lecturer_list.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Header Section -->
    <header class="header">
      <div class="logo">
        <img src="../../assets/images/logo.jpg" alt="RelaxU Logo" />
        <h1>RelaxU</h1>
      </div>
      <nav class="navbar">
        <ul>
          <li><a href="../../views/houg/houg_home.php">Dashboard</a></li>
          <li><a href="../../controller/Academic_QuestionsController.php?action=viewAllQuestions_hous">Academic Requests</a></li>
          <li><a href="../../controller/RepliedQuestionsController.php?action=viewRepliedQuestions">Forwarded-Replied Questions</a></li>
          <li><a href="../../controller/LecturerController.php?action=list">List of Lecturers</a></li>
        </ul>
      </nav>
      <div class="auth-buttons">
        <!-- Profile button form -->
<form action="hous_profile.php" method="GET">
    <button type="submit" class="login"><b>Profile</b></button>
</form>

    
        <!-- Logout button form -->
        <form action="../../../util/logout.php" method="POST" style="display: inline;">
          <button type="submit" class="login"><b>Log Out</b></button>
        </form>
      </div>
    </header>

    <main>
        <h2>Academic Staff Directory</h2>
        
        <!-- Category Filter -->
        <div class="filter-container">
            <div class="category-filter">
                <form action="../../controller/LecturerController.php" method="GET">
                    <input type="hidden" name="action" value="list">
                    <select name="category" id="categoryFilter" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= htmlspecialchars($category) ?>" <?= $selectedCategory === $category ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>
            
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Search lecturers..." />
                <i class="fas fa-search search-icon"></i>
            </div>
        </div>
        
        <!-- Lecturers Cards Grid -->
        <div class="lecturers-grid">
            <?php if (!empty($lecturers)): ?>
                <?php foreach ($lecturers as $lecturer): ?>
                    <div class="lecturer-card" data-name="<?= strtolower(htmlspecialchars($lecturer['name'])) ?>" data-category="<?= strtolower(htmlspecialchars($lecturer['category'])) ?>">
                        <div class="lecturer-image">
                            <?php if (!empty($lecturer['profile_image'])): ?>
                                <img src="../../assets/images/lecturers/<?= htmlspecialchars($lecturer['profile_image']) ?>" alt="<?= htmlspecialchars($lecturer['name']) ?>">
                            <?php else: ?>
                                <div class="default-avatar">
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="lecturer-info">
                            <h3><?= htmlspecialchars($lecturer['name']) ?></h3>
                            <p class="department"><?= htmlspecialchars($lecturer['department']) ?></p>
                            <span class="category-badge category-<?= strtolower(str_replace(' ', '-', $lecturer['category'])) ?>">
                                <?= htmlspecialchars($lecturer['category']) ?>
                            </span>
                        </div>
                        <div class="lecturer-actions">
                            <a href="../../controller/LecturerController.php?action=viewProfile&id=<?= $lecturer['id'] ?>" class="profile-link">
                                View Profile <i class="fas fa-chevron-right"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-results">
                    <p>No lecturers found. <?= $selectedCategory ? "Try a different category or view all lecturers." : "" ?></p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Footer Section -->
<footer class="footer">
      <div class="footer-container">
        <div class="footer-logo">
          <h1>RelaxU</h1>
          <p>Your mental health, your priority.</p>
          <img
            id="footer-logo"
            src="../../assets/images/logo.jpg"
            alt="RelaxU Logo"
          />
        </div>
        <div class="footer-section">
          <h3>Quick Links</h3>
          <ul>
          <li><a href="../views/houg/houg_home.php">Dashboard</a></li>
          <li><a href="#">Academic Requests</a></li>
          <li><a href="#">List of Lecturers</a></li>
          </ul>
        </div>
        <div class="footer-section">
          <h3>Contact Support</h3>
          <p>Email: support@relaxu.com</p>
          <p>Phone: +1 800-RELAXU</p>
        </div>
      </div>
      <div class="footer-bottom">
        <p>&copy; 2024 RelaxU. All Rights Reserved.</p>
      </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const lecturerCards = document.querySelectorAll('.lecturer-card');
            
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                
                lecturerCards.forEach(card => {
                    const name = card.getAttribute('data-name');
                    const category = card.getAttribute('data-category');
                    
                    if (name.includes(searchTerm) || category.includes(searchTerm)) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });
                
                // Check if no results are visible
                const visibleCards = document.querySelectorAll('.lecturer-card[style=""]');
                const noResults = document.querySelector('.no-results');
                
                if (visibleCards.length === 0 && !noResults) {
                    const noResultsDiv = document.createElement('div');
                    noResultsDiv.className = 'no-results';
                    noResultsDiv.innerHTML = '<p>No lecturers match your search.</p>';
                    document.querySelector('.lecturers-grid').appendChild(noResultsDiv);
                } else if (visibleCards.length > 0 && noResults) {
                    noResults.remove();
                }
            });
        });
    </script>
</body>
</html>