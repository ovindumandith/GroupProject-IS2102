<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RelaxU - Head of Undergraduate Studies Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../../assets/css/header_footer.css" type="text/css" />
    <link rel="stylesheet" href="../../assets/css/houg_home.css" type="text/css" />
    <link rel="stylesheet" href="../../assets/css/hous_academicHelp.css" type="text/css" />
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
          <li><a href="../views/houg/houg_home.php">Dashboard</a></li>
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
        <form action="../../util/logout.php" method="POST" style="display: inline;">
          <button type="submit" class="login"><b>Log Out</b></button>
        </form>
      </div>
    </header>

    <main>
      <h2>Student Academic Queries</h2>

      <!-- Search Bar -->
      <div class="search-container">
        <div class="search-box">
          <input type="text" id="searchBar" placeholder="Search by student name, category, or question..." />
          <i class="fas fa-search search-icon"></i>
        </div>
      </div>



      <!-- Questions Table -->
      <table class="questions-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Student Name</th>
            <th>Category</th>
            <th>Question</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($questions)): ?>
            <?php foreach ($questions as $question): ?>
              <tr>
                <td><?php echo htmlspecialchars($question['id']); ?></td>
                <td class="student-name"><?php echo htmlspecialchars($question['full_name']); ?></td>
                <td>
                  <span class="category-badge category-<?= strtolower($question['category']) ?>">
                    <?php echo htmlspecialchars($question['category']); ?>
                  </span>
                </td>
                <td class="question-text"><?php echo htmlspecialchars($question['question']); ?></td>                  
                <td class="status-<?= strtolower(str_replace(' ', '', $question['status'])) ?>">
                  <?php echo htmlspecialchars($question['status']); ?>
                </td>
                <td><?php echo date('M d, Y g:i A', strtotime($question['created_at'])); ?></td>
<td>
  <div class="button-group">
    <button class="action-btn reply-btn" 
            data-question-id="<?php echo $question['id']; ?>" 
            data-student-name="<?php echo htmlspecialchars($question['full_name']); ?>" 
            data-question-text="<?php echo htmlspecialchars($question['question']); ?>">
      <i class="fas fa-reply"></i> Reply
    </button>
    <button class="action-btn forward-btn" 
            data-question-id="<?php echo $question['id']; ?>"
            data-category="<?php echo htmlspecialchars($question['category']); ?>">
      <i class="fas fa-share"></i> Forward
    </button>
  </div>
</td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="7">No academic queries found at this time.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>

      <!-- Modal Structure -->
      <div id="replyModal" class="modal">
        <div class="modal-content">
          <span class="close">&times;</span>
          <h2>Reply to Academic Query</h2>
          <div class="student-info">
            <p><strong>Student:</strong> <span id="modal-student-name"></span></p>
            <p><strong>Question:</strong> <span id="modal-question-text"></span></p>
          </div>
          <form id="replyForm" action="../controller/Academic_QuestionsController.php?action=replyQuestion_hous" method="POST">
            <input type="hidden" id="modal-question-id" name="question_id">
            <label for="replyText">Your Response:</label>
            <textarea id="replyText" name="reply_text" placeholder="Type your reply here..." required></textarea>
            <button type="submit" class="action-btn">
              <i class="fas fa-paper-plane"></i> Send Reply
            </button>
          </form>
        </div>
      </div>
    </main>

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
            <li><a href="../../views/houg/houg_home.php">Dashboard</a></li>
            <li><a href="../../controller/Academic_QuestionsController.php?action=viewAllQuestions_hous">Academic Requests</a></li>
            <li><a href="../../controller/LecturerController.php?action=list">List of Lecturers</a></li>
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
      
      // Add success message handling
      document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const success = urlParams.get('success');
        
        if (success === 'reply') {
          // Create a toast notification
          const toast = document.createElement('div');
          toast.className = 'toast-notification';
          toast.innerHTML = '<i class="fas fa-check-circle"></i> Reply sent successfully!';
          document.body.appendChild(toast);
          
          // Show and then hide the toast
          setTimeout(() => {
            toast.classList.add('show');
          }, 100);
          
          setTimeout(() => {
            toast.classList.remove('show');
          }, 5000);
        }
      });
    </script>
    <script>
      // Handle forward button clicks
const forwardButtons = document.querySelectorAll('.forward-btn');

forwardButtons.forEach((button) => {
  button.addEventListener('click', () => {
    const questionId = button.getAttribute('data-question-id');
    const category = button.getAttribute('data-category');
    
    if (confirm(`Are you sure you want to forward this question to all lecturers in the ${category} category?`)) {
      // Create form data
      const formData = new FormData();
      formData.append('question_id', questionId);
      
      // Send AJAX request
      fetch('../controller/ForwardedQuestionController.php?action=forwardQuestion', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Show success message
          const toast = document.createElement('div');
          toast.className = 'toast-notification';
          toast.innerHTML = '<i class="fas fa-check-circle"></i> ' + data.message;
          document.body.appendChild(toast);
          
          // Show and then hide the toast
          setTimeout(() => {
            toast.classList.add('show');
          }, 100);
          
          setTimeout(() => {
            toast.classList.remove('show');
          }, 5000);
          
          // Disable the button
          button.disabled = true;
          button.textContent = 'Forwarded';
          button.style.backgroundColor = '#999';
        } else {
          alert(data.message);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
      });
    }
  });
});
    </script>
  </body>
</html>