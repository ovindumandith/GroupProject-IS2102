<?php

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../views/login.php');
    exit();
}

// Check if appointments data is available
if (!isset($appointments)) {
    echo "No appointment data available.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RelaxU - My Appointments</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/header_footer.css" type="text/css">
    <link rel="stylesheet" href="../../assets/css/showstudentappointments.css" type="text/css">
    <!-- Add Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
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
        <h1 class="student-appointments">My Appointments</h1>
        
        <!-- Display success message if available -->
        <?php if(isset($_SESSION['success_message'])): ?>
        <div class="success-message">
            <p><?php echo $_SESSION['success_message']; ?></p>
        </div>
        <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>
        
        <!-- Display error message if available -->
        <?php if(isset($_SESSION['error_message'])): ?>
        <div class="error-message">
            <p><?php echo $_SESSION['error_message']; ?></p>
        </div>
        <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <!-- Search Bar -->
        <div class="search-container">
            <input 
                type="text" 
                id="search-bar" 
                placeholder="🔍 Search..." 
                class="search-input"
            >
        </div>



        <!-- Appointments Table -->
        <div id="appointment-results">
            <?php if (!empty($appointments)): ?>
                <table>
                    <thead>
                        <tr>
                            <th><i class="far fa-calendar-alt"></i> Appointment Date</th>
                            <th><i class="fas fa-user-md"></i> Counselor</th>
                            <th><i class="fas fa-comments"></i> Topic</th>
                            <th><i class="fas fa-info-circle"></i> Status</th>
                            <th><i class="fas fa-cogs"></i> Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($appointments as $appointment): ?>
                            <tr>
                                <td><?php echo date("M d, Y - h:i A", strtotime($appointment['appointment_date'])); ?></td>
                                <td><?php echo htmlspecialchars($appointment['counselor_name']); ?></td>
                                <td><?php echo htmlspecialchars($appointment['topic']); ?></td>
                                <td class="status-<?php echo strtolower($appointment['status']); ?>">
                                    <?php echo htmlspecialchars($appointment['status']); ?>
                                </td>
                                <td>
                                    <div class="button-group">
                                        <?php if ($appointment['status'] === 'Pending'): ?>
                                            <a href="../controller/AppointmentController.php?action=updateAppointmentForm&appointment_id=<?php echo $appointment['id']; ?>" 
                                               class="btn update-btn">
                                                <i class="fas fa-edit"></i> Update
                                            </a>
                                        <?php endif; ?>
                                        
                                        <form method="POST" action="../controller/AppointmentController.php?action=deleteAppointment" style="display: inline;">
                                            <input type="hidden" name="appointment_id" value="<?php echo $appointment['id']; ?>">
                                            
                                            <?php if ($appointment['status'] === 'Accepted'): ?>
                                                <button type="submit" class="btn delete-btn" 
                                                        onclick="return confirm('This appointment is already scheduled. Are you sure you want to cancel it?');">
                                                    <i class="fas fa-calendar-times"></i> Cancel
                                                </button>
                                            <?php elseif ($appointment['status'] !== 'Completed'): ?>
                                                <button type="submit" class="btn delete-btn" 
                                                        onclick="return confirm('Are you sure you want to delete this appointment?');">
                                                    <i class="fas fa-trash-alt"></i> Delete
                                                </button>
                                            <?php endif; ?>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="student-appointments">You have no appointments.</p>
                <a href="../controller/CounselorController.php?action=list" class="add-appointment-btn">
                    <i class="fas fa-plus-circle"></i> Schedule Your First Appointment
                </a>
            <?php endif; ?>
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
    <script >
        // JavaScript for student appointment search functionality

document.addEventListener('DOMContentLoaded', function() {
    // Get the search input
    const searchInput = document.getElementById('search-bar');
    
    // Initialize search on input
    if (searchInput) {
        searchInput.addEventListener('input', performSearch);
    }
    
    // Function to perform the search
    function performSearch() {
        const searchTerm = searchInput.value.toLowerCase();
        const table = document.querySelector('table');
        
        // If there's no table, exit the function
        if (!table) return;
        
        const rows = table.querySelectorAll('tbody tr');
        
        // Loop through all table rows, and hide those that don't match the search query
        rows.forEach(row => {
            let found = false;
            const cells = row.querySelectorAll('td');
            
            cells.forEach(cell => {
                if (cell.textContent.toLowerCase().includes(searchTerm)) {
                    found = true;
                }
            });
            
            // Toggle row visibility based on search match
            if (found) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
        
        // Show a message when no results are found
        const noResultsMsg = document.getElementById('no-results-message');
        const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
        
        if (visibleRows.length === 0 && searchTerm !== '') {
            // Create a "no results" message if it doesn't exist
            if (!noResultsMsg) {
                const resultsContainer = document.getElementById('appointment-results');
                const message = document.createElement('p');
                message.id = 'no-results-message';
                message.className = 'no-results';
                message.textContent = 'No appointments match your search.';
                
                // Insert before or after the table depending on the structure
                if (table.parentNode === resultsContainer) {
                    resultsContainer.appendChild(message);
                } else {
                    resultsContainer.insertBefore(message, table.nextSibling);
                }
            } else {
                noResultsMsg.style.display = 'block';
            }
        } else if (noResultsMsg) {
            // Hide the "no results" message if there are results or the search is empty
            noResultsMsg.style.display = 'none';
        }
    }
    
    // Add data-label attributes to table cells for responsive design
    function addDataLabels() {
        const table = document.querySelector('table');
        if (!table) return;
        
        const headerCells = table.querySelectorAll('thead th');
        const headerLabels = Array.from(headerCells).map(th => th.textContent.trim());
        
        const bodyRows = table.querySelectorAll('tbody tr');
        
        bodyRows.forEach(row => {
            const cells = row.querySelectorAll('td');
            cells.forEach((cell, index) => {
                if (index < headerLabels.length) {
                    cell.setAttribute('data-label', headerLabels[index]);
                }
            });
        });
    }
    
    // Call the function to add data labels
    addDataLabels();
    
    // Show success or error messages and auto-hide after a few seconds
    function handleMessages() {
        const successMsg = document.querySelector('.success-message');
        const errorMsg = document.querySelector('.error-message');
        
        if (successMsg || errorMsg) {
            setTimeout(() => {
                if (successMsg) successMsg.style.display = 'none';
                if (errorMsg) errorMsg.style.display = 'none';
            }, 5000); // Hide after 5 seconds
        }
    }
    
    // Call the function to handle messages
    handleMessages();
});
    </script>
</body>
</html>