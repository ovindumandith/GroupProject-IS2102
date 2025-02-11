<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RelaxU - Head of Undergraduate Studies Dashboard</title>
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="../../assets/css/header_footer.css"
      type="text/css"
    />
    <link rel="stylesheet" href="../../assets/css/houg_home.css" type="text/css" />
    <link rel="stylesheet" href="../../assets/css/hous_academicHelp.css" type="text/css" />

    <script src="../../assets/js/searchacademic_requestsHOUS.js" defer></script>
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
          <li><a href="#">Academic Requests</a></li>
           <li><a href="../../controller/LecturerController.php?action=list">List of Lecturers</a></li>

        </ul>
      </nav>
      <div class="auth-buttons">
        <!-- Profile button form -->
<form action="hous_profile.php" method="GET">
    <button type="submit" class="login"><b>Profile</b></button>
</form>

    
        <!-- Logout button form -->
        <form action="../../util/logout.php" method="POST" style="display: inline;">
          <button type="submit" class="login"><b>Log Out</b></button>
        </form>
      </div>
    </header>
    <body>

<h2>Academic Help Questions</h2>

<!-- Search Bar -->
<div class="search-container">
    <input type="text" id="searchBar" placeholder="Search by student name..." />
</div


<table class="questions-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Student Name</th>
            <th>Question</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($questions)): ?>
            <?php foreach ($questions as $question): ?>
                <tr>
                    <td><?php echo htmlspecialchars($question['id']); ?></td>
                    <td><?php echo htmlspecialchars($question['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($question['question']); ?></td>
                    <td><?php echo htmlspecialchars($question['status']); ?></td>
                    <td><?php echo htmlspecialchars($question['created_at']); ?></td>
                    <td><?php echo htmlspecialchars($question['updated_at']); ?></td>
                    <td>
                        <div class="button-group">
                            <button class="action-btn reply-btn" 
                                    data-question-id="<?php echo $question['id']; ?>" 
                                    data-student-name="<?php echo htmlspecialchars($question['full_name']); ?>" 
                                    data-question-text="<?php echo htmlspecialchars($question['question']); ?>">
                                Reply
                            </button>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7">No questions found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- Modal Structure -->
<div id="replyModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Reply to Question</h2>
        <p><strong>Student:</strong> <span id="modal-student-name"></span></p>
        <p><strong>Question:</strong> <span id="modal-question-text"></span></p>
        <form id="replyForm" action="../controller/Academic_QuestionsController.php?action=replyQuestion_hous" method="POST">
            <input type="hidden" id="modal-question-id" name="question_id">
            <textarea id="replyText" name="reply_text" placeholder="Type your reply here..." required></textarea>
            <button type="submit" class="action-btn">Send Reply</button>
        </form>
    </div>
</div>
<script>
  // Get the modal and its elements
const modal = document.getElementById("replyModal");
const closeBtn = document.querySelector(".close");
const replyButtons = document.querySelectorAll(".reply-btn");
const modalStudentName = document.getElementById("modal-student-name");
const modalQuestionText = document.getElementById("modal-question-text");
const modalQuestionId = document.getElementById("modal-question-id");

// Function to open the modal and populate data
function openModal(studentName, questionText, questionId) {
    modalStudentName.textContent = studentName;
    modalQuestionText.textContent = questionText;
    modalQuestionId.value = questionId;
    modal.classList.add("show");
    modal.querySelector(".modal-content").classList.add("show");
}

// Function to close the modal
function closeModal() {
    modal.classList.remove("show");
    modal.querySelector(".modal-content").classList.remove("show");
}

// Event listeners for reply buttons
replyButtons.forEach((button) => {
    button.addEventListener("click", () => {
        const studentName = button.getAttribute("data-student-name");
        const questionText = button.getAttribute("data-question-text");
        const questionId = button.getAttribute("data-question-id");
        openModal(studentName, questionText, questionId);
    });
});

// Close modal when the close button is clicked
closeBtn.addEventListener("click", closeModal);

// Close modal when clicking outside the modal
window.addEventListener("click", (event) => {
    if (event.target === modal) {
        closeModal();
    }
});
</script>
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
          <li><a href="../../views/houg/houg_home.php">Dashboard</a></li>
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
  </body>
</html>


