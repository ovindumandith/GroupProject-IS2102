<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Academic Help Management | RelaxU Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../../assets/css/header_footer.css" type="text/css" />
    <link rel="stylesheet" href="../../assets/css/admin_home.css" type="text/css" />
    <link rel="stylesheet" href="../../assets/css/admin_academicHelp.css" type="text/css" />
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
          <li><a href="./admin_home.php">Home</a></li>
          <li class="services">
            <a href="#">Services </a>
            <ul class="dropdown">
              <li><a href="../controller/AdminStressAssessmentController.php?action=viewAllAssessments">Stress Monitoring</a></li>
              <li><a href="./admin_activities_portal.php">Relaxation Activities</a></li>
              <li><a href="./workload.php">Workload Management Tools</a></li>
            </ul>
          </li>
          <li><a href="../controller/Academic_QuestionsController.php?action=viewAllQuestions">Academic Help</a></li>
          <li><a href="../controller/AppointmentController.php?action=viewAppointments">Counseling</a></li>
          <li><a href="#">Community</a></li>
          <li><a href="#">About Us</a></li>
        </ul>
      </nav>
      <div class="auth-buttons">
        <form action="../../App/controller/AdminProfileController.php" method="GET">
  <input type="hidden" name="action" value="viewProfile">
  <button type="submit" class="login"><b>Profile</b></button>
</form>
        <form action="../../util/logout.php" method="post" style="display: inline">
          <button type="submit" class="login"><b>Log Out</b></button>
        </form>
      </div>
    </header>

    <main>
      <h2>Academic Help Questions</h2>

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
                <td class="<?php echo strtolower($question['status']) === 'pending' ? 'status-pending' : 'status-answered'; ?>">
                  <?php echo htmlspecialchars($question['status']); ?>
                </td>
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
                    <form action="../controller/Academic_QuestionsController.php?action=deleteQuestion_admin" method="POST" style="display:inline;">
                      <input type="hidden" name="question_id" value="<?php echo $question['id']; ?>">
                      <button type="submit" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this question?');">Delete</button>
                    </form>
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
          <form id="replyForm" action="../controller/Academic_QuestionsController.php?action=replyQuestion" method="POST">
            <input type="hidden" id="modal-question-id" name="question_id">
            <textarea id="replyText" name="reply_text" placeholder="Type your reply here..." required></textarea>
            <button type="submit" class="action-btn">Send Reply</button>
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
          <img id="footer-logo" src="../../assets/images/logo.jpg" alt="RelaxU Logo" />
        </div>
        <div class="footer-section">
          <h3>Services</h3>
          <ul>
            <li><a href="../controller/AdminStressAssessmentController.php?action=viewAllAssessments">Stress Monitoring</a></li>
            <li><a href="./relaxation_activities.php">Relaxation Activities</a></li>
            <li><a href="../controller/Academic_QuestionsController.php?action=viewAllQuestions">Academic Help</a></li>
            <li><a href="#">Counseling</a></li>
            <li><a href="#">Community</a></li>
            <li><a href="#">Workload Management Tools</a></li>
          </ul>
        </div>
        <div class="footer-section">
          <h3>Contact</h3>
          <p><i class="fa fa-phone"></i> +14 5464 8272</p>
          <p><i class="fa fa-envelope"></i> admin@relaxu.com</p>
          <p><i class="fa fa-map-marker"></i> University Admin Building</p>
        </div>
        <div class="footer-section">
          <h3>Links</h3>
          <ul>
            <li><a href="#">Privacy Policy</a></li>
            <li><a href="#">Terms Of Use</a></li>
          </ul>
        </div>
      </div>
      <div class="social-media">
        <ul>
          <li>
            <a href="#"><img src="../../assets/images/facebook.png" alt="Facebook" /></a>
          </li>
          <li>
            <a href="#"><img src="../../assets/images/twitter.png" alt="Twitter" /></a>
          </li>
          <li>
            <a href="#"><img src="../../assets/images/instagram.png" alt="Instagram" /></a>
          </li>
          <li>
            <a href="#"><img src="../../assets/images/youtube.png" alt="YouTube" /></a>
          </li>
        </ul>
      </div>
      <div class="footer-bottom">
        <p>copyright 2024 @RelaxU all rights reserved</p>
      </div>
    </footer>

    <div id="toast" class="toast">
      <span id="toast-message"></span>
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

      // Function to show toast message
      function showToast(message) {
        const toast = document.getElementById("toast");
        const toastMessage = document.getElementById("toast-message");

        // Set the toast message
        toastMessage.textContent = message;

        // Show the toast
        toast.classList.add("show");

        // Hide the toast after 3 seconds
        setTimeout(() => {
          toast.classList.remove("show");
        }, 3000);
      }

      // Handle form submission
      document.getElementById("replyForm").addEventListener("submit", function (e) {
        e.preventDefault(); // Prevent the default form submission

        const formData = new FormData(this);

        fetch(this.action, {
          method: "POST",
          body: formData,
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              // Show success toast message
              showToast("Reply added successfully!");
              closeModal(); // Close the modal
              window.location.reload(); // Reload the page to reflect changes
            } else {
              // Show error toast message
              showToast("Failed to add reply. Please try again.");
            }
          })
          .catch((error) => {
            console.error("Error:", error);
            showToast("An error occurred. Please try again.");
          });
      });

      // Check for success/error messages in URL parameters on page load
      document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const successMsg = urlParams.get('success');
        const errorMsg = urlParams.get('error');
        
        if (successMsg) {
          showToast(decodeURIComponent(successMsg));
        } else if (errorMsg) {
          showToast(decodeURIComponent(errorMsg));
        }
      });
    </script>
  </body>
</html>