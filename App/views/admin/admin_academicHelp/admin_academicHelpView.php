<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Academic Help Management | RelaxU Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../../assets/css/header_footer.css" type="text/css" />
    <link rel="stylesheet" href="../../assets/css/admin_home.css" type="text/css" />
        <style>
      .assessment-container {
  max-width: 1200px;
  margin: 40px auto;
  padding: 30px;
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.assessment-title {
  color: #009f77;
  text-align: center;
  margin-bottom: 25px;
  font-size: 1.8rem;
  font-weight: 600;
  letter-spacing: 0.5px;
  padding: 10px 20px;
  background-color: #e0f5f0;
  border-radius: 8px;
  border-bottom: 3px solid #fa8128;
}

.assessment-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

.assessment-table th,
.assessment-table td {
  padding: 12px 15px;
  border-bottom: 1px solid #ddd;
  text-align: left;
  vertical-align: top;
}

/* Add this new rule for the status column */
.assessment-table td:nth-child(4) {
  text-align: center;
}

.assessment-table th {
  background-color: #e9f5f3;
  color: #009f77;
  font-weight: 600;
}

.assessment-table tr:hover {
  background-color: #f5f5f5;
}

.status-pending {
  color: #b71c1c;
  background-color: #ffebee;
  padding: 6px 10px;
  border-radius: 20px;
  font-weight: 600;
  display: inline-block;
  text-align: center;
  min-width: 100px;
}

.status-answered {
  color: #1b5e20;
  background-color: #e8f5e9;
  padding: 6px 10px;
  border-radius: 20px;
  font-weight: 600;
  display: inline-block;
  text-align: center;
  min-width: 100px;
}

/* Rest of your CSS remains unchanged */
.button-group {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
}

.action-button {
  padding: 6px 12px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  text-decoration: none;
  font-weight: 500;
  color: #fff;
  background-color: #009f77;
  transition: all 0.2s ease;
}

.action-button:hover {
  background-color: #e66f10;
  transform: translateY(-1px);
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.delete-btn {
  background-color: #d32f2f;
}

.delete-btn:hover {
  background-color: #b71c1c;
}

/* Modal styles */
.modal {
  display: none;
  position: fixed;
  z-index: 1000;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0, 0, 0, 0.4);
}

.modal-content {
  background-color: #fff;
  margin: 10% auto;
  padding: 30px;
  border: 1px solid #ccc;
  width: 90%;
  max-width: 600px;
  border-radius: 8px;
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.2);
  position: relative;
}

.modal-content h2 {
  color: #009f77;
  margin-bottom: 20px;
}

.modal-content p {
  margin-bottom: 10px;
}

.modal-content textarea {
  width: 100%;
  min-height: 100px;
  padding: 10px;
  margin-bottom: 15px;
  border: 1px solid #ccc;
  border-radius: 4px;
  resize: vertical;
}

.modal-content .action-button {
  width: 100%;
}

.close {
  position: absolute;
  top: 15px;
  right: 20px;
  font-size: 1.5rem;
  cursor: pointer;
  color: #999;
}

.close:hover {
  color: #000;
}

    </style>

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
      <div class="assessment-container">
        <h1 class="assessment-title">Academic Help Questions</h1>

        <?php if (!empty($questions)): ?>
          <table class="assessment-table">
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
              <?php foreach ($questions as $question): ?>
                <tr>
                  <td><?= htmlspecialchars($question['id']) ?></td>
                  <td><?= htmlspecialchars($question['full_name']) ?></td>
                  <td><?= htmlspecialchars($question['question']) ?></td>
                  <td class="<?= strtolower($question['status']) === 'pending' ? 'status-pending' : 'status-answered' ?>">
                    <?= htmlspecialchars($question['status']) ?>
                  </td>
                  <td><?= htmlspecialchars($question['created_at']) ?></td>
                  <td><?= htmlspecialchars($question['updated_at']) ?></td>
                  <td>
                    <div class="button-group">
                      <button class="action-button reply-btn"
                              data-question-id="<?= $question['id'] ?>"
                              data-student-name="<?= htmlspecialchars($question['full_name']) ?>"
                              data-question-text="<?= htmlspecialchars($question['question']) ?>">
                        Reply
                      </button>
                      <form action="../controller/Academic_QuestionsController.php?action=deleteQuestion_admin" method="POST" style="display:inline;">
                        <input type="hidden" name="question_id" value="<?= $question['id'] ?>">
                        <button type="submit" class="action-button delete-btn" onclick="return confirm('Are you sure you want to delete this question?');">
                          Delete
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?>
          <p style="text-align: center;">No questions found.</p>
        <?php endif; ?>

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
              <button type="submit" class="action-button">Send Reply</button>
            </form>
          </div>
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
