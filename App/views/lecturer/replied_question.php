<?php
session_start();

// Check if user is logged in as lecturer
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'lecturer') {
    header('Location: ../login.php?error=unauthorized');
    exit();
}

// Get replied questions from session
$repliedQuestions = $_SESSION['replied_questions'] ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Replied Academic Questions | RelaxU</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../../../assets/css/header_footer.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Main content styles */
        main {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        h2 {
            color: #009f77;
            margin-bottom: 25px;
            text-align: center;
            font-size: 28px;
        }
        
        /* Alert styles */
        .alert {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-weight: 500;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        /* Table styles */
        .replied-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        
        .replied-table thead {
            background-color: #009f77;
            color: white;
        }
        
        .replied-table th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }
        
        .replied-table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            vertical-align: top;
        }
        
        .replied-table tbody tr:last-child td {
            border-bottom: none;
        }
        
        .replied-table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        /* Category badge styles */
        .category-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .category-coursework {
            background-color: #e0f5f0;
            color: #009f77;
        }
        
        .category-assignment {
            background-color: #e0f0ff;
            color: #0066cc;
        }
        
        .category-examination {
            background-color: #fff8e0;
            color: #ffaa00;
        }
        
        .category-financial {
            background-color: #f0e0ff;
            color: #8800cc;
        }
        
        .category-mahapola {
            background-color: #ffe0e0;
            color: #cc0000;
        }
        
        .category-registration {
            background-color: #e0ffe0;
            color: #00cc00;
        }
        
        .category-medical {
            background-color: #ffe0f0;
            color: #cc0066;
        }
        
        .category-accommodation {
            background-color: #e0e0ff;
            color: #0000cc;
        }
        
        .category-other {
            background-color: #f0f0f0;
            color: #666666;
        }
        
        /* Question text styles */
        .question-text {
            max-width: 250px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        /* Reply text styles */
        .reply-text {
            max-width: 250px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        /* Action button styles */
        .action-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background-color 0.3s;
            color: white;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .view-btn {
            background-color: #009f77;
        }
        
        .view-btn:hover {
            background-color: #00815f;
        }
        
        /* Search and filter styles */
        .search-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .search-box {
            position: relative;
            flex: 1;
            max-width: 400px;
        }
        
        .search-box input {
            width: 100%;
            padding: 10px 15px 10px 40px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 0.9rem;
        }
        
        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
        }
        
        .filter-container {
            display: flex;
            gap: 10px;
        }
        
        .filter-container select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 0.9rem;
            color: #333;
        }
        
        /* No questions message */
        .no-questions {
            text-align: center;
            padding: 30px;
            color: #666;
            font-size: 1.1rem;
        }
        
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        
        .modal-content {
            background-color: white;
            border-radius: 8px;
            padding: 25px;
            width: 90%;
            max-width: 700px;
            max-height: 80vh;
            overflow-y: auto;
            position: relative;
        }
        
        .close {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 24px;
            cursor: pointer;
            color: #aaa;
        }
        
        .close:hover {
            color: #333;
        }
        
        .modal h2 {
            color: #009f77;
            margin-bottom: 20px;
            font-size: 1.5rem;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }
        
        .question-details {
            margin-bottom: 20px;
        }
        
        .detail-row {
            display: flex;
            margin-bottom: 15px;
        }
        
        .detail-label {
            font-weight: 600;
            width: 100px;
            color: #333;
        }
        
        .detail-value {
            flex: 1;
        }
        
        .question-content {
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 4px;
            margin-top: 5px;
            white-space: pre-wrap;
        }
        
        .reply-content {
            padding: 15px;
            background-color: #f0f7f4;
            border-radius: 4px;
            margin-top: 5px;
            white-space: pre-wrap;
            border-left: 3px solid #009f77;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header class="header">
        <div class="logo">
            <img src="../../../assets/images/logo.jpg" alt="RelaxU Logo" />
            <h1>RelaxU</h1>
        </div>
        <nav class="navbar">
            <ul>
                <li><a href="lecturer_home.php">Dashboard</a></li>
                <li><a href="../../controller/ForwardedQuestionController.php?action=viewForwardedQuestions">Academic Questions</a></li>
                <li><a href="../../controller/RepliedQuestionsController.php?action=viewRepliedQuestions" class="active">Replied Questions</a></li>
                <li><a href="scheduler.php">Class Schedule</a></li>
            </ul>
        </nav>
        <div class="auth-buttons">
            <a href="lecturer_profile.php" class="profile-btn"><b>Profile</b></a>
            <form action="../../../util/logout.php" method="post" style="display: inline">
                <button type="submit" class="logout-btn"><b>Log Out</b></button>
            </form>
        </div>
    </header>

    <main>
        <h2>Replied Academic Questions</h2>
        
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <?php 
                echo $_SESSION['success_message']; 
                unset($_SESSION['success_message']);
                ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-error">
                <?php 
                echo $_SESSION['error_message']; 
                unset($_SESSION['error_message']);
                ?>
            </div>
        <?php endif; ?>
        
        <div class="search-container">
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Search by student name or question..." />
                <i class="fas fa-search search-icon"></i>
            </div>
            
            <div class="filter-container">
                <select id="categoryFilter">
                    <option value="">All Categories</option>
                    <option value="coursework">Coursework</option>
                    <option value="assignment">Assignment</option>
                    <option value="examination">Examination</option>
                    <option value="financial">Financial</option>
                    <option value="mahapola">Mahapola</option>
                    <option value="registration">Registration</option>
                    <option value="medical">Medical</option>
                    <option value="accommodation">Accommodation</option>
                    <option value="other">Other</option>
                </select>
                
                <select id="dateFilter">
                    <option value="">All Dates</option>
                    <option value="today">Today</option>
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                </select>
            </div>
        </div>
        
        <table class="replied-table">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Category</th>
                    <th>Question</th>
                    <th>Your Reply</th>
                    <th>Reply Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($repliedQuestions)): ?>
                    <?php foreach ($repliedQuestions as $reply): ?>
                        <tr data-category="<?= strtolower($reply['category']) ?>">
                            <td><?= htmlspecialchars($reply['student_name']) ?></td>
                            <td>
                                <span class="category-badge category-<?= strtolower(str_replace(' ', '_', $reply['category'])) ?>">
                                    <?= htmlspecialchars($reply['category']) ?>
                                </span>
                            </td>
                            <td class="question-text"><?= htmlspecialchars($reply['question']) ?></td>
                            <td class="reply-text"><?= htmlspecialchars($reply['reply_text']) ?></td>
                            <td><?= date('M d, Y', strtotime($reply['reply_date'])) ?></td>
                            <td>
                                <button class="action-btn view-btn" 
                                        data-reply-id="<?= $reply['reply_id'] ?>"
                                        data-student-name="<?= htmlspecialchars($reply['student_name']) ?>"
                                        data-category="<?= htmlspecialchars($reply['category']) ?>"
                                        data-question="<?= htmlspecialchars($reply['question']) ?>"
                                        data-reply="<?= htmlspecialchars($reply['reply_text']) ?>"
                                        data-date="<?= date('M d, Y h:i A', strtotime($reply['reply_date'])) ?>">
                                    <i class="fas fa-eye"></i> View
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="no-questions">You haven't replied to any questions yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <!-- View Modal -->
        <div id="viewModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Question & Reply Details</h2>
                <div class="question-details">
                    <div class="detail-row">
                        <div class="detail-label">Student:</div>
                        <div class="detail-value" id="modal-student-name"></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Category:</div>
                        <div class="detail-value">
                            <span class="category-badge" id="modal-category-badge"></span>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Question:</div>
                        <div class="detail-value question-content" id="modal-question-text"></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Your Reply:</div>
                        <div class="detail-value reply-content" id="modal-reply-text"></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Replied On:</div>
                        <div class="detail-value" id="modal-reply-date"></div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <!-- Footer Section -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-logo">
                <h1>RelaxU</h1>
                <p>Your mental health, your priority.</p>
                <img id="footer-logo" src="../../../assets/images/logo.jpg" alt="RelaxU Logo" />
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
            <ul>
                <li><a href="lecturer_home.php" class="active">Dashboard</a></li>
                <li><a href="../../controller/ForwardedQuestionController.php?action=viewForwardedQuestions">Academic Questions</a></li>
                <li><a href="../../controller/RepliedQuestionsController.php?action=viewRepliedQuestions">Replied Questions</a></li>
                <li><a href="scheduler.php">Class Schedule</a></li>
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
            // Variables for modal
            const viewModal = document.getElementById('viewModal');
            const closeButtons = document.querySelectorAll('.close');
            
            // Variables for filters
            const searchInput = document.getElementById('searchInput');
            const categoryFilter = document.getElementById('categoryFilter');
            const dateFilter = document.getElementById('dateFilter');
            const tableRows = document.querySelectorAll('.replied-table tbody tr');
            
            // View button click
            const viewButtons = document.querySelectorAll('.view-btn');
            viewButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const studentName = this.getAttribute('data-student-name');
                    const category = this.getAttribute('data-category');
                    const question = this.getAttribute('data-question');
                    const reply = this.getAttribute('data-reply');
                    const date = this.getAttribute('data-date');
                    
                    // Populate modal
                    document.getElementById('modal-student-name').textContent = studentName;
                    document.getElementById('modal-question-text').textContent = question;
                    document.getElementById('modal-reply-text').textContent = reply;
                    document.getElementById('modal-reply-date').textContent = date;
                    
                    // Set category badge
                    const categoryBadge = document.getElementById('modal-category-badge');
                    categoryBadge.textContent = category;
                    categoryBadge.className = 'category-badge category-' + category.toLowerCase().replace(' ', '_');
                    
                    // Open modal
                    viewModal.style.display = 'flex';
                });
            });
            
            // Function to close modal
            function closeModal(modal) {
                modal.style.display = 'none';
            }
            
            // Close buttons click
            closeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const modal = this.closest('.modal');
                    closeModal(modal);
                });
            });
            
            // Close modal when clicking outside
            window.addEventListener('click', function(event) {
                if (event.target === viewModal) {
                    closeModal(viewModal);
                }
            });
            
            // Search and filter functionality
            function filterTable() {
                const searchTerm = searchInput.value.toLowerCase();
                const categoryValue = categoryFilter.value.toLowerCase();
                const dateValue = dateFilter.value.toLowerCase();
                
                tableRows.forEach(row => {
                    if (row.classList.contains('no-questions')) return;
                    
                    const studentName = row.cells[0].textContent.toLowerCase();
                    const category = row.getAttribute('data-category');
                    const question = row.cells[2].textContent.toLowerCase();
                    const reply = row.cells[3].textContent.toLowerCase();
                    const date = row.cells[4].textContent.toLowerCase();
                    
                    const matchesSearch = studentName.includes(searchTerm) || 
                                         question.includes(searchTerm) || 
                                         reply.includes(searchTerm);
                    const matchesCategory = categoryValue === '' || category === categoryValue;
                    
                    // Date filtering logic
                    let matchesDate = true;
                    if (dateValue === 'today') {
                        const today = new Date().toDateString();
                        const rowDate = new Date(date).toDateString();
                        matchesDate = today === rowDate;
                    } else if (dateValue === 'week') {
                        const now = new Date();
                        const oneWeekAgo = new Date(now.getFullYear(), now.getMonth(), now.getDate() - 7);
                        const rowDate = new Date(date);
                        matchesDate = rowDate >= oneWeekAgo;
                    } else if (dateValue === 'month') {
                        const now = new Date();
                        const oneMonthAgo = new Date(now.getFullYear(), now.getMonth() - 1, now.getDate());
                        const rowDate = new Date(date);
                        matchesDate = rowDate >= oneMonthAgo;
                    }
                    
                    if (matchesSearch && matchesCategory && matchesDate) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }
            
            // Add event listeners for filters
            searchInput.addEventListener('input', filterTable);
            categoryFilter.addEventListener('change', filterTable);
            dateFilter.addEventListener('change', filterTable);
        });
    </script>
</body>
</html>