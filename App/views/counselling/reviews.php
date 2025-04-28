<?php


// Only start session if one doesn't exist already
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// Redirect to login if not authenticated
if (!isset($_SESSION['counselor'])) {
    header('Location: counselor_login.php');
    exit();
}

// Get counselor details from the session
$counselor = $_SESSION['counselor'];

// Make sure the required variables are available
if (!isset($reviews) || !isset($stats)) {
    // If this file is accessed directly without going through the controller
    header('Location: ../../controller/CounselorReviewsController.php');
    exit();
}

// Process reviews data for display
$totalReviews = $stats['total_reviews'];
$averageRating = $stats['average_rating'];
$ratingDistribution = $stats['rating_distribution'];

// Calculate percentages for rating distribution
$ratingPercentages = [];
if ($totalReviews > 0) {
    foreach ($ratingDistribution as $rating => $count) {
        $ratingPercentages[$rating] = round(($count / $totalReviews) * 100);
    }
} else {
    // Default percentages if there are no reviews
    for ($i = 5; $i >= 1; $i--) {
        $ratingPercentages[$i] = 0;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Your Reviews - RelaxU</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link
        rel="stylesheet"
        href="../../assets/css/header_footer.css"
        type="text/css"
    />
    <link rel="stylesheet" href="../../assets/css/counselor_reviews.css" type="text/css" />
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
                <li><a href="../views/counselling/counselor_dashboard.php">Dashboard</a></li>
                <li class="services">
                    <a href="#">Appointments </a>
                    <ul class="dropdown">
                        <li><a href="../controller/AppointmentController.php?action=showPendingAppointments">Pending</a></li>
                        <li><a href="../controller/AppointmentController.php?action=showApprovedAppointments">Approved</a></li>
                        <li><a href="../controller/AppointmentController.php?action=showDeniedAppointments">Denied</a></li>
                    </ul>
                </li>
                <li><a href="../views/messages.php">Messages</a></li>
                <li><a href="../views/counselling/reviews.php" class="active">Reviews (<?php echo $totalReviews; ?>)</a></li>
            </ul>
        </nav>
        <div class="auth-buttons">
            <a href="/GroupProject-IS2102/App/controller/CounselorController.php?action=viewLoggedInCounselorProfile" class="login" style="display: inline-block; text-decoration: none; background-color: #fa8128; color: white; border: none; padding: 10px 20px; border-radius: 20px; cursor: pointer; margin-left: 10px; font-size: 1rem; transition: background-color 0.3s ease;">
                <b>Profile</b>
            </a>

            <!-- Logout button form -->
            <form action="../../util/counselor_logout.php" method="POST" style="display: inline;">
                <button type="submit" class="login"><b>Log Out</b></button>
            </form>
        </div>
    </header>

    <!-- Page Header -->
    <section class="page-header">
        <h1>Your Student Reviews</h1>
        <p class="page-description">
            See what students are saying about your counseling sessions and use their feedback to improve your services.
        </p>
    </section>

    <div class="reviews-container">
        <?php if ($totalReviews > 0): ?>
            <!-- Reviews Summary -->
            <div class="reviews-summary">
                <div class="rating-overview">
                    <div class="average-rating"><?php echo number_format($averageRating, 1); ?></div>
                    <div class="rating-stars">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <?php $halfStar = ($i - 0.5 <= $averageRating && $averageRating < $i); ?>
                            <?php if ($i <= $averageRating): ?>
                                <span class="star filled"><i class="fas fa-star"></i></span>
                            <?php elseif ($halfStar): ?>
                                <span class="star filled"><i class="fas fa-star-half-alt"></i></span>
                            <?php else: ?>
                                <span class="star"><i class="fas fa-star"></i></span>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                    <div class="total-reviews">Based on <?php echo $totalReviews; ?> reviews</div>
                </div>
                
                <div class="rating-distribution">
                    <?php for ($rating = 5; $rating >= 1; $rating--): ?>
                        <div class="rating-row">
                            <div class="rating-label">
                                <i class="fas fa-star"></i> <?php echo $rating; ?>
                            </div>
                            <div class="rating-bar-container">
                                <div class="rating-bar" style="width: <?php echo $ratingPercentages[$rating]; ?>%;"></div>
                            </div>
                            <div class="rating-count"><?php echo $ratingDistribution[$rating]; ?></div>
                            <div class="rating-percent"><?php echo $ratingPercentages[$rating]; ?>%</div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
            
            <!-- Sorting Options -->
            <div class="sorting-options">
                <button class="sort-button active" data-sort="newest">Newest First</button>
                <button class="sort-button" data-sort="oldest">Oldest First</button>
                <button class="sort-button" data-sort="highest">Highest Rated</button>
                <button class="sort-button" data-sort="lowest">Lowest Rated</button>
            </div>
            
            <!-- Reviews List -->
            <div class="reviews-list">
                <?php foreach ($reviews as $review): ?>
                    <div class="review-card">
                        <div class="review-header">
                            <div class="reviewer-info">
                                <div class="reviewer-avatar">
                                    <?php echo strtoupper(substr($review['student_name'], 0, 1)); ?>
                                </div>
                                <div>
                                    <div class="reviewer-name"><?php echo htmlspecialchars($review['student_name']); ?></div>
                                    <div class="review-date">
                                        <?php 
                                            $date = new DateTime($review['created_at']);
                                            echo $date->format('F j, Y'); 
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="review-rating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span class="star <?php echo ($i <= $review['rating']) ? 'filled' : ''; ?>">
                                        <i class="fas fa-star"></i>
                                    </span>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <div class="review-content">
                            <?php echo htmlspecialchars($review['review_text']); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div class="pagination">
                    <button class="pagination-button <?php echo ($page <= 1) ? 'disabled' : ''; ?>" 
                            <?php echo ($page <= 1) ? 'disabled' : ''; ?> 
                            onclick="location.href='?page=<?php echo $page - 1; ?>'">
                        <i class="fas fa-chevron-left"></i> Previous
                    </button>
                    
                    <?php
                    // Determine range of pages to show
                    $startPage = max(1, $page - 2);
                    $endPage = min($totalPages, $page + 2);
                    
                    // Always show first page
                    if ($startPage > 1) {
                        echo '<button class="pagination-button" onclick="location.href=\'?page=1\'">1</button>';
                        if ($startPage > 2) {
                            echo '<span class="pagination-ellipsis">...</span>';
                        }
                    }
                    
                    // Show page numbers
                    for ($i = $startPage; $i <= $endPage; $i++) {
                        $activeClass = ($i == $page) ? 'active' : '';
                        echo "<button class=\"pagination-button $activeClass\" onclick=\"location.href='?page=$i'\">$i</button>";
                    }
                    
                    // Always show last page
                    if ($endPage < $totalPages) {
                        if ($endPage < $totalPages - 1) {
                            echo '<span class="pagination-ellipsis">...</span>';
                        }
                        echo "<button class=\"pagination-button\" onclick=\"location.href='?page=$totalPages'\">$totalPages</button>";
                    }
                    ?>
                    
                    <button class="pagination-button <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>" 
                            <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?> 
                            onclick="location.href='?page=<?php echo $page + 1; ?>'">
                        Next <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            <?php endif; ?>
            
        <?php else: ?>
            <!-- No Reviews State -->
            <div class="no-reviews">
                <i class="fas fa-star"></i>
                <h2>No Reviews Yet</h2>
                <p>You haven't received any student reviews yet. Reviews will appear here after students rate their counseling sessions with you.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer Section -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-logo">
                <h1>RelaxU</h1>
                <p>Your mental health, your priority.</p>
                <img id="footer-logo" src="../../assets/images/logo.jpg" alt="RelaxU Logo" />
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="../../views/counselling/counselor_dashboard.php">Dashboard</a></li>
                    <li><a href="../../controller/AppointmentController.php?action=showPendingAppointments">Appointments</a></li>
                    <li><a href="../views/messages.php">Messages</a></li>
                    <li><a href="../views/reviews.php">Reviews</a></li>
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

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sorting functionality
            const reviewsList = document.querySelector('.reviews-list');
            const sortButtons = document.querySelectorAll('.sort-button');
            const reviewCards = Array.from(document.querySelectorAll('.review-card'));
            
            // Store original order
            const originalOrder = [...reviewCards];
            
            // Add click event listeners to sort buttons
            sortButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Update active state
                    sortButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                    
                    const sortType = this.dataset.sort;
                    
                    // Sort the reviews
                    let sortedReviews;
                    switch (sortType) {
                        case 'newest':
                            // Already sorted by newest in PHP
                            sortedReviews = [...originalOrder];
                            break;
                        case 'oldest':
                            sortedReviews = [...originalOrder].reverse();
                            break;
                        case 'highest':
                            sortedReviews = [...originalOrder].sort((a, b) => {
                                const ratingA = parseInt(a.querySelectorAll('.star.filled').length);
                                const ratingB = parseInt(b.querySelectorAll('.star.filled').length);
                                return ratingB - ratingA;
                            });
                            break;
                        case 'lowest':
                            sortedReviews = [...originalOrder].sort((a, b) => {
                                const ratingA = parseInt(a.querySelectorAll('.star.filled').length);
                                const ratingB = parseInt(b.querySelectorAll('.star.filled').length);
                                return ratingA - ratingB;
                            });
                            break;
                        default:
                            sortedReviews = [...originalOrder];
                    }
                    
                    // Remove all reviews from the DOM
                    while (reviewsList.firstChild) {
                        reviewsList.removeChild(reviewsList.firstChild);
                    }
                    
                    // Add sorted reviews back to the DOM
                    sortedReviews.forEach(review => {
                        reviewsList.appendChild(review);
                    });
                });
            });
        });
    </script>
</body>
</html>