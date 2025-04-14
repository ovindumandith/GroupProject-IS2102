<?php
session_start();

// Check if user is logged in as lecturer
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'lecturer') {
    header('Location: ../login.php?error=unauthorized');
    exit();
}

// Get forwarded questions from session
$forwardedQuestions = $_SESSION['forwarded_questions'] ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forwarded Academic Questions | RelaxU</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../../assets/css/header_footer.css" />
    <link rel="stylesheet" href="../../assets/css/lecturer_forwarded.css" />
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
                <li><a href="../views/lecturer/lecturer_home.php">Dashboard</a></li>
                <li><a href="../../controller/ForwardedQuestionController.php?action=viewForwardedQuestions" class="active">Academic Questions</a></li>
                <li><a href="../views/lecturer/scheduler.php">Class Schedule</a></li>
                <li><a href="../views/lecturer/grading.php">Grading</a></li>
                <li><a href="../views/lecturer/resources.php">Resources</a></li>
            </ul>
        </nav>
        <div class="auth-buttons">
            <a href="../views/lecturer/lecturer_profile.php" class="profile-btn"><b>Profile</b></a>
            <form action="../../util/logout.php" method="post" style="display: inline">
                <button type="submit" class="logout-btn"><b>Log Out</b></button>
            </form>
        </div>
    </header>

    <main>
        <h2>Forwarded Academic Questions</h2>
        
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
                <select id="statusFilter">
                    <option value="">All Statuses</option>
                    <option value="Unread">Unread</option>
                    <option value="Read">Read</option>
                    <option value="Responded">Responded</option>
                </select>
                
                <select id="categoryFilter">
                    <option value="">All Categories</option>
                    <option value="Coursework">Coursework</option>
                    <option value="Assignment">Assignment</option>
                    <option value="Examination">Examination</option>
                    <option value="Financial">Financial</option>
                    <option value="Mahapola">Mahapola</option>
                    <option value="Registration">Registration</option>
                    <option value="Medical">Medical</option>
                    <option value="Accommodation">Accommodation</option>
                    <option value="Other">Other</option>
                </select>
            </div>
        </div>
        
        <table class="forwarded-table">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Category</th>
                    <th>Question</th>
                    <th>Forwarded By</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($forwardedQuestions)): ?>
                    <?php foreach ($forwardedQuestions as $question): ?>
                        <tr class="<?= $question['status'] === 'Unread' ? 'unread-row' : '' ?>" 
                            data-category="<?= strtolower($question['category']) ?>"
                            data-status="<?= strtolower($question['status']) ?>">
                            <td><?= htmlspecialchars($question['student_name']) ?></td>
                            <td>
                                <span class="category-badge category-<?= strtolower(str_replace(' ', '_', $question['category'])) ?>">
                                    <?= htmlspecialchars($question['category']) ?>
                                </span>
                            </td>
                            <td class="question-text"><?= htmlspecialchars($question['question']) ?></td>
                            <td><?= htmlspecialchars($question['forwarded_by_name']) ?></td>
                            <td><?= date('M d, Y', strtotime($question['forwarded_at'])) ?></td>
                            <td class="status-<?= strtolower($question['status']) ?>"><?= htmlspecialchars($question['status']) ?></td>
                            <td>
                                <div class="button-group">
                                    <button class="action-btn view-btn" 
                                            data-id="<?= $question['id'] ?>"
                                            data-question-id="<?= $question['question_id'] ?>"
                                            data-student-name="<?= htmlspecialchars($question['student_name']) ?>"
                                            data-category="<?= htmlspecialchars($question['category']) ?>"
                                            data-question-text="<?= htmlspecialchars($question['question']) ?>"
                                            data-status="<?= $question['status'] ?>">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                    <?php if ($question['status'] !== 'Responded'): ?>
                                        <button class="action-btn respond-btn"
                                                data-id="<?= $question['id'] ?>"
                                                data-question-id="<?= $question['question_id'] ?>"
                                                data-student-name="<?= htmlspecialchars($question['student_name']) ?>"
                                                data-category="<?= htmlspecialchars($question['category']) ?>"
                                                data-question-text="<?= htmlspecialchars($question['question']) ?>">
                                            <i class="fas fa-reply"></i> Respond
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No forwarded questions found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <!-- View Modal -->
        <div id="viewModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Academic Question</h2>
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
                </div>
                <div class="modal-actions">
                    <button id="markAsReadBtn" class="action-btn mark-read-btn">Mark as Read</button>
                    <button id="respondBtn" class="action-btn respond-btn">Respond</button>
                </div>
            </div>
        </div>
        
        <!-- Respond Modal -->
        <div id="respondModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Respond to Question</h2>
                <div class="question-details">
                    <div class="detail-row">
                        <div class="detail-label">Student:</div>
                        <div class="detail-value" id="respond-student-name"></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Category:</div>
                        <div class="detail-value">
                            <span class="category-badge" id="respond-category-badge"></span>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Question:</div>
                        <div class="detail-value question-content" id="respond-question-text"></div>
                    </div>
                </div>
                <form id="responseForm" action="../../controller/ForwardedQuestionController.php?action=respondToQuestion" method="POST">
                    <input type="hidden" id="respond-forwarded-id" name="forwarded_id">
                    <input type="hidden" id="respond-question-id" name="question_id">
                    <div class="form-group">
                        <label for="replyText">Your Response:</label>
                        <textarea id="replyText" name="reply_text" placeholder="Type your response here..." required></textarea>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="cancel-btn">Cancel</button>
                        <button type="submit" class="submit-btn">
                            <i class="fas fa-paper-plane"></i> Send Response
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    
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
                    <li><a href="../views/lecturer/lecturer_home.php">Dashboard</a></li>
                    <li><a href="../../controller/ForwardedQuestionController.php?action=viewForwardedQuestions">Academic Questions</a></li>
                    <li><a href="../views/lecturer/scheduler.php">Class Schedule</a></li>
                    <li><a href="../views/lecturer/grading.php">Grading</a></li>
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
            // Variables for modals
            const viewModal = document.getElementById('viewModal');
            const respondModal = document.getElementById('respondModal');
            const closeButtons = document.querySelectorAll('.close, .cancel-btn');
            
            // Variables for filters
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            const categoryFilter = document.getElementById('categoryFilter');
            const tableRows = document.querySelectorAll('.forwarded-table tbody tr');
            
            // View button click
            const viewButtons = document.querySelectorAll('.view-btn');
            viewButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const questionId = this.getAttribute('data-question-id');
                    const studentName = this.getAttribute('data-student-name');
                    const category = this.getAttribute('data-category');
                    const questionText = this.getAttribute('data-question-text');
                    const status = this.getAttribute('data-status');
                    
                    // Populate modal
                    document.getElementById('modal-student-name').textContent = studentName;
                    document.getElementById('modal-question-text').textContent = questionText;
                    
                    // Set category badge
                    const categoryBadge = document.getElementById('modal-category-badge');
                    categoryBadge.textContent = category;
                    categoryBadge.className = 'category-badge category-' + category.toLowerCase().replace(' ', '_');
                    
                    // Show/hide mark as read button based on status
                    const markAsReadBtn = document.getElementById('markAsReadBtn');
                    const respondBtn = document.getElementById('respondBtn');
                    
                    if (status === 'Unread') {
                        markAsReadBtn.style.display = 'block';
                        markAsReadBtn.onclick = function() {
                            markAsRead(id);
                        };
                    } else {
                        markAsReadBtn.style.display = 'none';
                    }
                    
                    if (status !== 'Responded') {
                        respondBtn.style.display = 'block';
                        respondBtn.onclick = function() {
                            closeModal(viewModal);
                            openRespondModal(id, questionId, studentName, category, questionText);
                        };
                    } else {
                        respondBtn.style.display = 'none';
                    }
                    
                    // Open modal
                    viewModal.style.display = 'flex';
                    
                    // If unread, mark as read
                    if (status === 'Unread') {
                        markAsRead(id);
                    }
                });
            });
            
            // Respond button click
            const respondButtons = document.querySelectorAll('.button-group .respond-btn');
            respondButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const questionId = this.getAttribute('data-question-id');
                    const studentName = this.getAttribute('data-student-name');
                    const category = this.getAttribute('data-category');
                    const questionText = this.getAttribute('data-question-text');
                    
                    openRespondModal(id, questionId, studentName, category, questionText);
                });
            });
            
            // Function to open respond modal
            function openRespondModal(id, questionId, studentName, category, questionText) {
                // Populate modal
                document.getElementById('respond-student-name').textContent = studentName;
                document.getElementById('respond-question-text').textContent = questionText;
                document.getElementById('respond-forwarded-id').value = id;
                document.getElementById('respond-question-id').value = questionId;
                
                // Set category badge
                const categoryBadge = document.getElementById('respond-category-badge');
                categoryBadge.textContent = category;
                categoryBadge.className = 'category-badge category-' + category.toLowerCase().replace(' ', '_');
                
                // Clear previous response text
                document.getElementById('replyText').value = '';
                
                // Open modal
                respondModal.style.display = 'flex';
            }
            
            // Function to close modals
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
                if (event.target === respondModal) {
                    closeModal(respondModal);
                }
            });
            
            // Mark as read function
            function markAsRead(id) {
                fetch('../../controller/ForwardedQuestionController.php?action=markAsRead', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'forwarded_id=' + id
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update UI
                        const row = document.querySelector(`tr [data-id="${id}"]`).closest('tr');
                        row.classList.remove('unread-row');
                        const statusCell = row.querySelector('.status-unread');
                        if (statusCell) {
                            statusCell.textContent = 'Read';
                            statusCell.className = 'status-read';
                        }
                        
                        // Update button's data-status
                        const viewBtn = row.querySelector('.view-btn');
                        viewBtn.setAttribute('data-status', 'Read');
                        
                        // Hide mark as read button
                        document.getElementById('markAsReadBtn').style.display = 'none';
                    }
                })
                .catch(error => console.error('Error:', error));
            }
            
            // Search and filter functionality
            function filterTable() {
                const searchTerm = searchInput.value.toLowerCase();
                const statusValue = statusFilter.value.toLowerCase();
                const categoryValue = categoryFilter.value.toLowerCase();
                
                tableRows.forEach(row => {
                    const studentName = row.cells[0].textContent.toLowerCase();
                    const category = row.getAttribute('data-category');
                    const status = row.getAttribute('data-status');
                    const question = row.cells[2].textContent.toLowerCase();
                    
                    const matchesSearch = studentName.includes(searchTerm) || question.includes(searchTerm);
                    const matchesStatus = statusValue === '' || status === statusValue;
                    const matchesCategory = categoryValue === '' || category === categoryValue;
                    
                    if (matchesSearch && matchesStatus && matchesCategory) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }
            
            // Add event listeners for filters
            searchInput.addEventListener('input', filterTable);
            statusFilter.addEventListener('change', filterTable);
            categoryFilter.addEventListener('change', filterTable);
        });
    </script>
</body>
</html>