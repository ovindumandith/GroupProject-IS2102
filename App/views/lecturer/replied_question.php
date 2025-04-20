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
    <link rel="stylesheet" href="../../../assets/css/replied_questions.css" />
    
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
            <form action="../../controller/LecturerController.php" method="GET">
    <input type="hidden" name="action" value="myProfile">
    <button type="submit" class="login"><b>Profile</b></button>
</form>
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
    <div class="action-buttons">
        <button class="action-btn view-btn" 
                data-reply-id="<?= $reply['reply_id'] ?>"
                data-student-name="<?= htmlspecialchars($reply['student_name']) ?>"
                data-category="<?= htmlspecialchars($reply['category']) ?>"
                data-question="<?= htmlspecialchars($reply['question']) ?>"
                data-reply="<?= htmlspecialchars($reply['reply_text']) ?>"
                data-date="<?= date('M d, Y h:i A', strtotime($reply['reply_date'])) ?>">
            <i class="fas fa-eye"></i> View                           
        </button>
        <button class="action-btn edit-btn" 
                data-reply-id="<?= $reply['reply_id'] ?>"
                data-reply="<?= htmlspecialchars($reply['reply_text']) ?>">
                <i class="fas fa-edit"></i> Edit
        </button>
        <button class="action-btn delete-btn" 
                data-reply-id="<?= $reply['reply_id'] ?>"
                data-student-name="<?= htmlspecialchars($reply['student_name']) ?>">
               <i class="fas fa-trash"></i> Delete
        </button>
    </div>
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
                <input type="hidden" id="view-reply-id" value="">
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
                    <!-- Add at the bottom of the question-details div in the view modal -->
<div class="modal-actions">
    <button class="action-btn edit-btn" id="modal-edit-btn">
        <i class="fas fa-edit"></i> Edit Reply
    </button>
    <button class="action-btn delete-btn" id="modal-delete-btn">
        <i class="fas fa-trash"></i> Delete Reply
    </button>
</div>
                </div>
            </div>
        </div>
        <!-- Add this after the View Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Edit Your Reply</h2>
        <form id="edit-reply-form" action="../../controller/RepliedQuestionsController.php?action=updateReply" method="POST">
            <input type="hidden" id="edit-reply-id" name="reply_id">
            <div class="form-group">
                <label for="edit-reply-text">Your Reply:</label>
                <textarea id="edit-reply-text" name="reply_text" rows="8" required></textarea>
            </div>
            <div class="modal-actions">
                <button type="button" class="action-btn btn-cancel" id="cancel-edit-btn">Cancel</button>
                <button type="submit" class="action-btn btn-submit">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Add a Delete Confirmation Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content delete-modal-content">
        <span class="close">&times;</span>
        <div class="delete-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h2>Confirm Deletion</h2>
        <p>Are you sure you want to delete your reply to <span id="delete-student-name" class="student-name"></span>?</p>
        <p>This action cannot be undone.</p>
        <form action="../../controller/RepliedQuestionsController.php?action=deleteReply" method="POST">
            <input type="hidden" id="delete-reply-id" name="reply_id">
            <div class="modal-actions">
                <button type="button" class="action-btn btn-cancel" id="cancel-delete-btn">Cancel</button>
                <button type="submit" class="action-btn delete-btn">
                    <i class="fas fa-trash"></i> Delete Reply
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
    // Variables for modals
    const viewModal = document.getElementById('viewModal');
    const editModal = document.getElementById('editModal');
    const deleteModal = document.getElementById('deleteModal');
    const closeButtons = document.querySelectorAll('.close');

    // Variables for filters
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const dateFilter = document.getElementById('dateFilter');
    const tableRows = document.querySelectorAll('.replied-table tbody tr');
    
    // Create persistent storage for reply IDs
    const persistentReplyIdInput = document.createElement('input');
    persistentReplyIdInput.type = 'hidden';
    persistentReplyIdInput.id = 'persistent-reply-id';
    document.body.appendChild(persistentReplyIdInput);

    // Function to close modal
    function closeModal(modal) {
        modal.style.display = 'none';
    }

    // View button click
    const viewButtons = document.querySelectorAll('.view-btn');
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const studentName = this.getAttribute('data-student-name');
            const category = this.getAttribute('data-category');
            const question = this.getAttribute('data-question');
            const reply = this.getAttribute('data-reply');
            const date = this.getAttribute('data-date');
            const replyId = this.getAttribute('data-reply-id');

            // Store the reply ID in multiple places for redundancy
            viewModal.setAttribute('data-current-reply-id', replyId);
            persistentReplyIdInput.value = replyId;
            console.log("View modal opened with reply ID:", replyId);

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
        } else if (event.target === editModal) {
            closeModal(editModal);
        } else if (event.target === deleteModal) {
            closeModal(deleteModal);
        }
    });
    
    // Cancel buttons
    const cancelEditBtn = document.getElementById('cancel-edit-btn');
    if (cancelEditBtn) {
        cancelEditBtn.addEventListener('click', function() {
            closeModal(editModal);
        });
    }

    const cancelDeleteBtn = document.getElementById('cancel-delete-btn');
    if (cancelDeleteBtn) {
        cancelDeleteBtn.addEventListener('click', function() {
            closeModal(deleteModal);
        });
    }

    // ========== EDIT FUNCTIONALITY ==========
    
    // Replace edit buttons in the table
    const tableEditButtons = document.querySelectorAll('.edit-btn:not(#modal-edit-btn)');
    tableEditButtons.forEach(button => {
        const newButton = button.cloneNode(true);
        button.parentNode.replaceChild(newButton, button);
        
        newButton.addEventListener('click', function() {
            const replyId = this.getAttribute('data-reply-id');
            const replyText = this.getAttribute('data-reply');
            
            persistentReplyIdInput.value = replyId;
            console.log("Table edit button clicked, stored reply ID:", replyId);
            
            // Populate edit modal
            document.getElementById('edit-reply-id').value = replyId;
            document.getElementById('edit-reply-text').value = replyText;
            
            // Open edit modal
            editModal.style.display = 'flex';
        });
    });
    
    // Replace modal edit button
    const modalEditBtn = document.getElementById('modal-edit-btn');
    if (modalEditBtn) {
        const newModalEditBtn = modalEditBtn.cloneNode(true);
        modalEditBtn.parentNode.replaceChild(newModalEditBtn, modalEditBtn);
        
        newModalEditBtn.addEventListener('click', function() {
            const replyText = document.getElementById('modal-reply-text').textContent;
            const replyId = viewModal.getAttribute('data-current-reply-id') || persistentReplyIdInput.value;
            
            console.log("Modal edit button clicked, using reply ID:", replyId);
            
            if (!replyId) {
                alert("Error: Could not identify the reply. Please try editing from the main table instead.");
                return;
            }
            
            // Populate edit modal
            document.getElementById('edit-reply-id').value = replyId;
            document.getElementById('edit-reply-text').value = replyText;
            
            // Close view modal and open edit modal
            closeModal(viewModal);
            editModal.style.display = 'flex';
        });
    }
    
    // Replace edit form submission
    const editForm = document.querySelector('#edit-reply-form');
    if (editForm) {
        const newEditForm = editForm.cloneNode(true);
        editForm.parentNode.replaceChild(newEditForm, editForm);
        
        newEditForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const replyId = document.getElementById('edit-reply-id').value || persistentReplyIdInput.value;
            const replyText = document.getElementById('edit-reply-text').value;
            
            console.log("Edit form submission - Reply ID:", replyId);
            
            if (!replyId) {
                alert("Error: Reply ID is missing. Please try again.");
                return;
            }
            
            // Submit using fetch API
            const formData = new FormData();
            formData.append('reply_id', replyId);
            formData.append('reply_text', replyText);
            
            fetch('../../controller/RepliedQuestionsController.php?action=updateReply', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                window.location.href = '../../controller/RepliedQuestionsController.php?action=viewRepliedQuestions';
            })
            .catch(error => {
                console.error('Error:', error);
                alert("An error occurred while updating the reply. Please try again.");
            });
        });
    }
    
    // ========== DELETE FUNCTIONALITY ==========
    
    // Replace delete buttons in the table
    const tableDeleteButtons = document.querySelectorAll('.delete-btn:not(#modal-delete-btn)');
    tableDeleteButtons.forEach(button => {
        const newButton = button.cloneNode(true);
        button.parentNode.replaceChild(newButton, button);
        
        newButton.addEventListener('click', function() {
            const replyId = this.getAttribute('data-reply-id');
            const studentName = this.getAttribute('data-student-name');
            
            persistentReplyIdInput.value = replyId;
            console.log("Table delete button clicked, stored reply ID:", replyId);
            
            // Populate delete modal
            document.getElementById('delete-reply-id').value = replyId;
            document.getElementById('delete-student-name').textContent = studentName;
            
            // Open delete modal
            deleteModal.style.display = 'flex';
        });
    });
    
    // Replace modal delete button
    const modalDeleteBtn = document.getElementById('modal-delete-btn');
    if (modalDeleteBtn) {
        const newModalDeleteBtn = modalDeleteBtn.cloneNode(true);
        modalDeleteBtn.parentNode.replaceChild(newModalDeleteBtn, modalDeleteBtn);
        
        newModalDeleteBtn.addEventListener('click', function() {
            const studentName = document.getElementById('modal-student-name').textContent;
            const replyId = viewModal.getAttribute('data-current-reply-id') || persistentReplyIdInput.value;
            
            console.log("Modal delete button clicked, using reply ID:", replyId);
            
            if (!replyId) {
                alert("Error: Could not identify the reply ID. Please try again from the main table.");
                return;
            }
            
            // Populate delete modal
            document.getElementById('delete-reply-id').value = replyId;
            document.getElementById('delete-student-name').textContent = studentName;
            
            // Close view modal and open delete modal
            closeModal(viewModal);
            deleteModal.style.display = 'flex';
        });
    }
    
    // Replace delete form submission
    const deleteForm = document.querySelector('#deleteModal form');
    if (deleteForm) {
        const newDeleteForm = deleteForm.cloneNode(true);
        deleteForm.parentNode.replaceChild(newDeleteForm, deleteForm);
        
        newDeleteForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const replyId = document.getElementById('delete-reply-id').value || persistentReplyIdInput.value;
            
            console.log("Delete form submission - Reply ID:", replyId);
            
            if (!replyId) {
                alert("Error: Reply ID is missing. Please try again.");
                return;
            }
            
            // Submit using fetch API
            const formData = new FormData();
            formData.append('reply_id', replyId);
            
            fetch('../../controller/RepliedQuestionsController.php?action=deleteReply', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                window.location.href = '../../controller/RepliedQuestionsController.php?action=viewRepliedQuestions';
            })
            .catch(error => {
                console.error('Error:', error);
                alert("An error occurred while deleting the reply. Please try again.");
            });
        });
    }
});
    </script>

    

</body>
</html>