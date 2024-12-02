<?php
session_start();

// Check if the user is logged in and has the correct role
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'lecturer') {
    header('Location: ../login.php');
    exit();
}

// Retrieve academic requests from the session

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RelaxU - Lecturer Dashboard</title>
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="../../../assets/css/header_footer.css"
      type="text/css"
    />
    <link rel="stylesheet" href="../../../assets/css/lecturer_home.css" type="text/css" />
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
          <li><a href="../../views/lecturer/lecturer_home.php">Dashboard</a></li>
          <li><a href="#"> Fowarded Academic Requests</a></li>
          

        </ul>
      </nav>
      <div class="auth-buttons">
        <!-- Profile button form -->
<form action="hous_profile.php" method="GET">
    <button type="submit" class="login"><b>Profile</b></button>
</form>

    
        <!-- Logout button form -->
        <form action="../../../util/logout.php" method="POST" style="display: inline;">
          <button type="submit" class="login"><b>Log Out</b></button>
        </form>
      </div>
    </header>
        <div class="table-container">
        <h2>Forwarded Lecturer Requests</h2>
        <table>
            <caption>List of Requests Forwarded to Lecturers</caption>
            <thead>
                <tr>
                    <th>Request ID</th>
                    <th>Student Name</th>
                    <th>Question</th>
                    <th>Lecturer</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>John Doe</td>
                    <td>What are the prerequisites for Thermodynamics?</td>
                    <td>Dr. Smith</td>
                    <td>Pending</td>
                    <td>
                        <a href="#">Approve</a>
                        <a href="#">Reject</a>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Jane Smith</td>
                    <td>How can I improve my research methodology skills?</td>
                    <td>Prof. Johnson</td>
                    <td>Pending</td>
                    <td>
                        <a href="#">Approve</a>
                        <a href="#">Reject</a>
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Robert Green</td>
                    <td>When will the next lecture on Data Structures be held?</td>
                    <td>Dr. Clark</td>
                    <td>Approved</td>
                    <td>
                        <a href="#">Approve</a>
                        <a href="#">Reject</a>
                    </td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>Emily Clark</td>
                    <td>How do I join the Machine Learning course?</td>
                    <td>Prof. Evans</td>
                    <td>Rejected</td>
                    <td>
                        <a href="#">Approve</a>
                        <a href="#">Reject</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>


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


