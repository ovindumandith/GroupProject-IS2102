<?php


// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../views/login.php');
    exit();
}

// Check if questions data is available
if (!isset($questions)) {
    echo "No academic questions data available.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RelaxU - My Academic Requests</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/header_footer.css" type="text/css">
    <link rel="stylesheet" href="../../assets/css/showstudentacademicrequest.css" type="text/css">
    <!-- Add Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <script src="../../assets/js/appointment_search_student.js"></script>
</head>
<body>
    <!-- Header Section -->
    <header class="header">
        <div class="logo">
            <img src="../../assets/images/logo.jpg" alt="RelaxU Logo">
            <h1>RelaxU</h1>
        </div>
        <nav class="navbar">
            <ul>
                <li><a href="../views/home.php">Home</a></li>
                <li class="services">
                    <a href="#">Services</a>
                    <ul class="dropdown">
                        <li><a href="../views/stress_management/stress_management_index.php">Stress Monitoring</a></li>
                        <li><a href="../views/relaxation_activities.php">Relaxation Activities</a></li>
                        <li><a href="../views/workload.php">Workload Management Tools</a></li>
                    </ul>
                </li>
                <li><a href="../views/Academic_Help.php">Academic Help</a></li>
                <li><a href="../controller/CounselorController.php?action=list">Counseling</a></li>
                <li><a href="../controller/CommunityController.php?action=list">Community</a></li>
                <li><a href="../views/About_Us.php">About Us</a></li>
            </ul>
        </nav>
      <div class="auth-buttons">
        <button class="signup" onclick="location.href='../controller/UserProfileController.php?action=showProfile'"><b>Profile</b></button>
        <form action="../../util/logout.php" method="post" style="display: inline">
          <button type="submit" class="login"><b>Log Out</b></button>
        </form>
      </div>
    </header>

    <!-- Main Content -->
    <main>
        <h1 class="student-questions">My Academic Requests</h1>

        <!-- Search Bar -->
        <div class="search-container">
            <input 
                type="text" 
                id="search-bar" 
                placeholder="🔍 Search..." 
                class="search-input"
                aria-label="Search Questions"
            >
        </div>



        <!-- Questions Table -->
        <div class="container">
            <?php if (!empty($questions)): ?>
                <table>
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag"></i> Index No</th>
                            <th><i class="fas fa-id-card"></i> Reg No</th>
                            <th><i class="fas fa-user"></i> Full Name</th>
                            <th><i class="fas fa-university"></i> Faculty</th>
                            <th><i class="fas fa-question-circle"></i> Question</th>
                            <th><i class="fas fa-info-circle"></i> Status</th>
                            <th><i class="far fa-calendar-alt"></i> Created At</th>
                            <th><i class="fas fa-cogs"></i> Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($questions as $question): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($question['index_no']); ?></td>
                                <td><?php echo htmlspecialchars($question['reg_no']); ?></td>
                                <td><?php echo htmlspecialchars($question['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($question['faculty']); ?></td>
                                <td class="truncate"><?php echo htmlspecialchars($question['question']); ?></td>
                                <td class="status-<?php echo strtolower($question['status']); ?>">
                                    <?php echo htmlspecialchars($question['status']); ?>
                                </td>
                                <td class="created-at-column"><?php echo date("M d, Y - h:i A", strtotime($question['created_at'])); ?></td>
                                <td>
                                    <div class="button-group">
                                        <!-- View Button -->
                                        <form action="../controller/Academic_QuestionsController.php?action=getQuestion" method="POST" class="inline-form">
                                            <input type="hidden" name="question_id" value="<?php echo htmlspecialchars($question['id']); ?>">
                                            <button 
                                                type="submit" 
                                                name="action" 
                                                value="view" 
                                                class="action-btn view-btn">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                        </form>
                                        
                                        <?php if (strtolower($question['status']) !== 'resolved'): ?>
                                            <button 
                                                type="button" 
                                                class="action-btn update-btn" 
                                                onclick="openModal('<?php echo htmlspecialchars($question['id']); ?>', '<?php echo htmlspecialchars($question['question'], ENT_QUOTES, 'UTF-8'); ?>')">
                                                <i class="fas fa-edit"></i> Update
                                            </button>
                                        <?php endif; ?>
                                        
                                        <!-- Delete Button -->
                                        <form action="../controller/Academic_QuestionsController.php?action=deleteQuestion" method="POST" class="inline-form">
                                            <input type="hidden" name="question_id" value="<?php echo htmlspecialchars($question['id']); ?>">
                                            <button 
                                                type="submit" 
                                                name="action" 
                                                value="delete" 
                                                class="action-btn delete-btn" 
                                                onclick="return confirm('Are you sure you want to delete this question?');">
                                                <i class="fas fa-trash-alt"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>You have no academic questions. Feel free to submit a new question for academic help.</p>
                <a href="../views/Academic_Help.php" class="add-appointment-btn">
                    <i class="fas fa-plus-circle"></i> Submit Your First Question
                </a>
            <?php endif; ?>
        </div>

        <!-- Update Question Modal -->
        <div id="updateModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <h2><i class="fas fa-edit"></i> Update Question</h2>
                <form id="updateForm" action="../controller/Academic_QuestionsController.php?action=updateQuestionModalOpen" method="POST">
                    <input type="hidden" name="question_id" id="question_id">
                    <label for="updated_question">Question:</label>
                    <textarea name="updated_question" id="updated_question" rows="4" required></textarea>
                    <button type="submit" class="action-btn update-btn">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer Section -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-logo">
                <h1>RelaxU</h1>
                <p>Relax and Refresh while Excelling in your Studies</p>
                <img id="footer-logo" src="../../assets/images/logo.jpg" alt="RelaxU Logo">
            </div>
            <div class="footer-section">
                <h3>Services</h3>
                <ul>
                    <li><a href="../views/stress_management/stress_management_index.php">Stress Monitoring</a></li>
                    <li><a href="../views/relaxation_activities.php">Relaxation Activities</a></li>
                    <li><a href="../views/Academic_Help.php">Academic Help</a></li>
                    <li><a href="../controller/CounselorController.php?action=list">Counseling</a></li>
                    <li><a href="../controller/CommunityController.php?action=list">Community</a></li>
                    <li><a href="../views/workload.php">Workload Management Tools</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contact</h3>
                <p><i class="fas fa-phone"></i> +14 5464 8272</p>
                <p><i class="fas fa-envelope"></i> contact@relaxu.com</p>
                <p><i class="fas fa-map-marker-alt"></i> University Campus, Building 192</p>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="../views/About_Us.php">About Us</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms Of Use</a></li>
                </ul>
            </div>
        </div>
        <div class="social-media">
            <ul>
                <li><a href="#"><img src="../../assets/images/facebook.png" alt="Facebook"></a></li>
                <li><a href="#"><img src="../../assets/images/twitter.png" alt="Twitter"></a></li>
                <li><a href="#"><img src="../../assets/images/instagram.png" alt="Instagram"></a></li>
                <li><a href="#"><img src="../../assets/images/youtube.png" alt="YouTube"></a></li>
            </ul>
        </div>
        <div class="footer-bottom">
            <p>Copyright © 2024 RelaxU - All Rights Reserved</p>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="../../assets/js/academic_search_student.js"></script>
    <script>
        // Modal functions
        function openModal(questionId, questionText) {
            document.getElementById('question_id').value = questionId;
            document.getElementById('updated_question').value = questionText;
            document.getElementById('updateModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('updateModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('updateModal');
            if (event.target === modal) {
                closeModal();
            }
        });
    </script>
</body>
</html>