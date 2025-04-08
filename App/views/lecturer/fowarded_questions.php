<?php
session_start();

// Check if user is logged in as lecturer
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'lecturer') {
    header('Location: ../login.php?error=unauthorized');
    exit();
}

// Get forwarded questions
$forwardedQuestions = $_SESSION['forwarded_questions'] ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Forwarded Academic Questions - RelaxU</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../../assets/css/header_footer.css" />
    <link rel="stylesheet" href="../../assets/css/lecturer_forwarded.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Header Section -->
    <header class="header">
        <!-- Similar to other headers -->
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
                        <tr class="<?= $question['status'] === 'Unread' ? 'unread-row' : '' ?>">
                            <td><?= htmlspecialchars($question['student_name']) ?></td>
                            <td>
                                <span class="category-badge category-<?= strtolower($question['category']) ?>">
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
                                            data-forwarded-id="<?= $question['id'] ?>"
                                            data-question-id="<?= $question['question_id'] ?>"
                                            data-student-name="<?= htmlspecialchars($question['student_name']) ?>"
                                            data-question-text="<?= htmlspecialchars($question['question']) ?>"
                                            data-status="<?= $question['status'] ?>">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                    <?php if ($question['status'] !== 'Responded'): ?>
                                        <button class="action-btn respond-btn"
                                                data-forwarded-id="<?= $question['id'] ?>"
                                                data-question-id="<?= $question['question_id'] ?>"
                                                data-student-name="<?= htmlspecialchars($question['student_name']) ?>"
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
                <div class="student-info">
                    <p><strong>Student:</strong> <span id="modal-student-name"></span></p>
                    <p><strong>Question:</strong> <span id="modal-question-text"></span></p>
                </div>
                <div class="modal-actions">
                    <button id="markAsReadBtn" class="action-btn">Mark as Read</button>
                    <button id="respondBtn" class="action-btn respond-btn">Respond</button>
                </div>
            </div>
        </div>
        
        <!-- Respond Modal -->
        <div id="respondModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Respond to Question</h2>
                <div class="student-info">
                    <p><strong>Student:</strong> <span id="respond-student-name"></span></p>
                    <p><strong>Question:</strong> <span id="respond-question-text"></span></p>
                </div>
                <form id="responseForm" action="../controller/ForwardedQuestionController.php?action=respondToQuestion" method="POST">
                    <input type="hidden" id="respond-forwarded-id" name="forwarded_id">
                    <input type="hidden" id="respond-question-id" name="question_id">
                    <label for="replyText">Your Response:</label>
                    <textarea id="replyText" name="reply_text" placeholder="Type your response here..." required></textarea>
                    <button type="submit" class="action-btn respond-btn">
                        <i class="fas fa-paper-plane"></i> Send Response
                    </button>
                </form>
            </div>
        </div>
    </main>
    
    <!-- Footer Section -->
    <footer class="footer">
        <!-- Similar to other footers -->
    </footer>
    
    <script>
        // Modal handling code for view and respond modals
        // Mark as read AJAX functionality
        // Search functionality
        document.addEventListener('DOMContentLoaded', function() {
            // View button click
            const viewButtons = document.querySelectorAll('.view-btn');
            const viewModal = document.getElementById('viewModal');
            const viewClose = viewModal.querySelector('.close');
            
            viewButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const forwardedId = this.getAttribute('data-forwarded-id');
                    const questionId = this.getAttribute('data-question-id');
                    const studentName = this.getAttribute('data-student-name');
                    const questionText = this.getAttribute('data-question-text');
                    const status = this.getAttribute('data-status');
                    
                    document.getElementById('modal-student-name').textContent = studentName;
                    document.getElementById('modal-question-text').textContent = questionText;
                    
                    const markAsReadBtn = document.getElementById('markAsReadBtn');
                    const respondBtn = document.getElementById('respondBtn');
                    
                    if (status === 'Unread') {
                        markAsReadBtn.style.display = 'block';
                        markAsReadBtn.onclick = function() {
                            markAsRead(forwardedId);
                        };
                    } else {
                        markAsReadBtn.style.display = 'none';
                    }
                    
                    if (status !== 'Responded') {
                        respondBtn.style.display = 'block';
                        respondBtn.onclick = function() {
                            openRespondModal(forwardedId, questionId, studentName, questionText);
                            closeViewModal();
                        };
                    } else {
                        respondBtn.style.display = 'none';
                    }
                    
                    viewModal.classList.add('show');
                    viewModal.querySelector('.modal-content').classList.add('show');
                    
                    // If unread, mark as read
                    if (status === 'Unread') {
                        markAsRead(forwardedId);
                    }
                });
            });
            
            // Response button click
            const respondButtons = document.querySelectorAll('.respond-btn');
            const respondModal = document.getElementById('respondModal');
            const respondClose = respondModal.querySelector('.close');
            
            respondButtons.forEach(button => {
                if (button.classList.contains('action-btn')) { // Only target table buttons
                    button.addEventListener('click', function() {
                        const forwardedId = this.getAttribute('data-forwarded-id');
                        const questionId = this.getAttribute('data-question-id');
                        const studentName = this.getAttribute('data-student-name');
                        const questionText = this.getAttribute('data-question-text');
                        
                        openRespondModal(forwardedId, questionId, studentName, questionText);
                    });
                }
            });
            
            // Close modals
            viewClose.addEventListener('click', closeViewModal);
            respondClose.addEventListener('click', closeRespondModal);
            
            window.addEventListener('click', function(event) {
                if (event.target === viewModal) {
                    closeViewModal();
                }
                if (event.target === respondModal) {
                    closeRespondModal();
                }
            });
            
            // Search functionality
            const searchInput = document.getElementById('searchInput');
            const tableRows = document.querySelectorAll('.forwarded-table tbody tr');
            
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                
                tableRows.forEach(row => {
                    const studentName = row.cells[0].textContent.toLowerCase();
                    const question = row.cells[2].textContent.toLowerCase();
                    
                    if (studentName.includes(searchTerm) || question.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
            
            function markAsRead(forwardedId) {
                const formData = new FormData();
                formData.append('forwarded_id', forwardedId);
                
                fetch('../controller/ForwardedQuestionController.php?action=markAsRead', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update UI to reflect read status
                        document.querySelector(`[data-forwarded-id="${forwardedId}"]`).closest('tr').classList.remove('unread-row');
                        document.querySelector(`[data-forwarded-id="${forwardedId}"]`).setAttribute('data-status', 'Read');
                        document.getElementById('markAsReadBtn').style.display = 'none';
                    }
                })
                .catch(error => console.error('Error:', error));
            }
            
            function openRespondModal(forwardedId, questionId, studentName, questionText) {
                document.getElementById('respond-forwarded-id').value = forwardedId;
                document.getElementById('respond-question-id').value = questionId;
                document.getElementById('respond-student-name').textContent = studentName;
                document.getElementById('respond-question-text').textContent = questionText;
                
                respondModal.classList.add('show');
                respondModal.querySelector('.modal-content').classList.add('show');
            }
            
            function closeViewModal() {
                viewModal.classList.remove('show');
                viewModal.querySelector('.modal-content').classList.remove('show');
            }
            
            function closeRespondModal() {
                respondModal.classList.remove('show');
                respondModal.querySelector('.modal-content').classList.remove('show');
            }
        });
    </script>
</body>
</html>