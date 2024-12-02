<?php
session_start();

// Check if the user is logged in and has the correct role
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'hous') {
    header('Location: ../login.php');
    exit();
}

// Retrieve academic requests from the session
$pendingQuestions = isset($_SESSION['pending_questions']) ? $_SESSION['pending_questions'] : [];
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RelaxU - Counselor Dashboard</title>
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="../../../assets/css/header_footer.css"
      type="text/css"
    />
    <link rel="stylesheet" href="../../../assets/css/houg_home.css" type="text/css" />
    <script src="../../../assets/js/search_academic_requests.js" defer></script>
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
          <li><a href="../../views/houg/houg_home.php">Dashboard</a></li>
          <li><a href="#">Academic Requests</a></li>
          <li><a href="#">List of Lecturers</a></li>

        </ul>
      </nav>
      <div class="auth-buttons">
        <!-- Profile button form -->
<form action="#" method="GET">
    <button type="submit" class="login"><b>Profile</b></button>
</form>

    
        <!-- Logout button form -->
        <form action="../../../util/logout.php" method="POST" style="display: inline;">
          <button type="submit" class="login"><b>Log Out</b></button>
        </form>
      </div>
    </header>

    <?php if (count($pendingQuestions) > 0): ?>
        <script src="../../../assets/js/search_academic_requests.js" defer></script>
        <div class="search-container">
    <input type="text" id="searchInput" class="search-bar" placeholder="Search requests..." onkeyup="searchTable()">
</div>
       <div class="table-container">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Index</th>
                <th>Reg_No</th>
                <th>Full Name</th>
                <th>Faculty</th>
                <th>Email</th>
                <th>Telephone</th>
                <th>Question</th>
                <th>Submitted At</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pendingQuestions as $question): ?>
                <tr>
                    <td><?= htmlspecialchars($question['id']); ?></td>
                    <td><?= htmlspecialchars($question['index_no']); ?></td>
                    <td><?= htmlspecialchars($question['reg_no']); ?></td>
                    <td><?= htmlspecialchars($question['full_name']); ?></td>
                    <td><?= htmlspecialchars($question['faculty']); ?></td>
                    <td><?= htmlspecialchars($question['email']); ?></td>
                    <td><?= htmlspecialchars($question['telephone']); ?></td>
                    <td><?= htmlspecialchars($question['question']); ?></td>
                    <td><?= htmlspecialchars($question['created_at']); ?></td>
                    <td><?= htmlspecialchars($question['status']); ?></td>
                    <td>
                        <form action="reply.php" method="post" style="display:inline;">
                            <input type="hidden" name="question_id" value="<?= htmlspecialchars($question['id']); ?>">
                            <button type="submit" class="btn btn-primary">Reply</button>
                        </form><br>
                        <form action="delete.php" method="post" style="display:inline;">
                            <input type="hidden" name="question_id" value="<?= htmlspecialchars($question['id']); ?>">
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                        <form action="forward.php" method="post" style="display:inline;">
                            <input type="hidden" name="question_id" value="<?= htmlspecialchars($question['id']); ?>">
                            <button type="submit" class="btn btn-warning">Forward</button>
                        </form>
            </td>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

    <?php else: ?>
        <p>No pending academic requests at the moment.</p>
    <?php endif; ?>
    <footer class="footer">
      <div class="footer-container">
        <div class="footer-logo">
          <h1>RelaxU</h1>
          <p>Your mental health, your priority.</p>
          <img
            id="footer-logo"
            src="../../../assets/images/logo.jpg"
            alt="RelaxU Logo"
          />
        </div>
        <div class="footer-section">
          <h3>Quick Links</h3>
          <ul>
          <li><a href="../../views/houg/houg_home.php">Dashboard</a></li>
          <li><a href="#">Academic Requests</a></li>
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

</body>
</html>