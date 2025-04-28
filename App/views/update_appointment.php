<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../views/login.php');
    exit();
}

// Check if appointment_id is provided
if (!isset($_GET['appointment_id'])) {
    header('Location: ../controller/AppointmentController.php?action=showStudentAppointments');
    exit();
}

$appointmentId = $_GET['appointment_id'];

// Get appointment details from session (set in controller)
$appointment = isset($_SESSION['appointment_to_update']) ? $_SESSION['appointment_to_update'] : null;

if (!$appointment) {
    header('Location: ../controller/AppointmentController.php?action=showStudentAppointments');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Appointment - RelaxU</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/header_footer.css" type="text/css">
    <link rel="stylesheet" href="../../assets/css/update_appointment.css" type="text/css">
    <!-- Add Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<style>
    /* Update Appointment CSS */

main {
    min-height: calc(100vh - 250px);
    padding: 30px 0;
    background-color: #f9f9f9;
}

.update-appointment-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 30px;
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.update-appointment-container h1 {
    color: #333;
    text-align: center;
    margin-bottom: 30px;
    font-size: 28px;
    font-weight: 600;
}

/* Current appointment details */
.current-appointment {
    background-color: #f5f8fa;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 30px;
    border-left: 4px solid #2196F3;
}

.current-appointment h2 {
    font-size: 20px;
    margin-bottom: 15px;
    color: #333;
    font-weight: 500;
}

.detail-row {
    display: flex;
    margin-bottom: 10px;
    padding: 8px 0;
    border-bottom: 1px solid #e9e9e9;
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-label {
    width: 150px;
    font-weight: 500;
    color: #555;
}

.detail-value {
    flex: 1;
    color: #333;
}

/* Status color coding */
.status-pending {
    color: #ff9800;
    font-weight: 500;
}

.status-accepted, .status-approved {
    color: #4CAF50;
    font-weight: 500;
}

.status-denied, .status-rejected {
    color: #f44336;
    font-weight: 500;
}

/* Info and error messages */
.info-message {
    background-color: #e7f3ff;
    border-left: 4px solid #2196F3;
    margin-bottom: 20px;
    padding: 12px 15px;
    border-radius: 3px;
}

.info-message p {
    margin: 0;
    color: #0c5460;
    font-size: 14px;
}

.error-message {
    background-color: #f8d7da;
    border-left: 4px solid #dc3545;
    padding: 12px 15px;
    margin-bottom: 20px;
    border-radius: 3px;
    color: #721c24;
}

/* Form styling */
.update-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-group label {
    font-weight: 500;
    color: #444;
    font-size: 16px;
}

.form-group input {
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 15px;
    transition: border-color 0.3s;
}

.form-group input:focus {
    border-color: #2196F3;
    outline: none;
    box-shadow: 0 0 0 2px rgba(33, 150, 243, 0.1);
}

.form-text {
    color: #6c757d;
    font-size: 13px;
    margin-top: 5px;
}

/* Button group */
.button-group {
    display: flex;
    gap: 15px;
    margin-top: 10px;
    justify-content: center;
}

.submit-btn {
    background-color: #4CAF50;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-width: 160px;
}

.submit-btn:hover {
    background-color: #45a049;
}

.cancel-btn {
    background-color: #f44336;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.3s;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-width: 160px;
}

.cancel-btn:hover {
    background-color: #d32f2f;
}

/* Icons */
i {
    margin-right: 5px;
}

/* Responsive design */
@media (max-width: 768px) {
    .update-appointment-container {
        width: 90%;
        padding: 20px;
    }
    
    .button-group {
        flex-direction: column;
    }
    
    .submit-btn, .cancel-btn {
        width: 100%;
    }
    
    .detail-row {
        flex-direction: column;
    }
    
    .detail-label {
        width: 100%;
        margin-bottom: 5px;
    }
}
</style>
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
        <div class="update-appointment-container">
            <h1>Update Appointment</h1>
            
            <!-- Display current appointment details -->
            <div class="current-appointment">
                <h2>Current Appointment Details</h2>
                <div class="detail-row">
                    <span class="detail-label"><i class="far fa-calendar-alt"></i> Current Date:</span>
                    <span class="detail-value"><?php echo date("M d, Y - h:i A", strtotime($appointment['appointment_date'])); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label"><i class="fas fa-user-md"></i> Counselor:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($appointment['counselor_name']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label"><i class="fas fa-comments"></i> Topic:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($appointment['topic']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label"><i class="fas fa-info-circle"></i> Status:</span>
                    <span class="detail-value status-<?php echo strtolower($appointment['status']); ?>"><?php echo htmlspecialchars($appointment['status']); ?></span>
                </div>
            </div>
            
            <!-- Info message about appointment rescheduling restrictions -->
            <div class="info-message">
                <p><strong>Note:</strong> Appointments must be scheduled at least 2 days in advance to give our counselors enough time to prepare.</p>
            </div>
            
            <!-- Error message display if needed -->
            <?php if(isset($_SESSION['reschedule_error'])): ?>
            <div class="error-message">
                <p><strong>Error:</strong> <?php echo $_SESSION['reschedule_error']; ?></p>
            </div>
            <?php unset($_SESSION['reschedule_error']); ?>
            <?php endif; ?>
            
            <!-- Form for updating the appointment -->
            <form class="update-form" action="../controller/AppointmentController.php?action=updateAppointment" method="POST" id="updateForm">
                <input type="hidden" name="appointment_id" value="<?php echo $appointmentId; ?>">
                
                <div class="form-group">
                    <label for="new_appointment_date"><i class="far fa-calendar-alt"></i> New Appointment Date & Time:</label>
                    <input type="datetime-local" id="new_appointment_date" name="new_appointment_date" required>
                    <small id="dateHelp" class="form-text">Earliest available appointment is <span id="earliestDate">calculating...</span></small>
                </div>
                
                <div class="form-group">
                    <label for="topic"><i class="fas fa-comments"></i> Topic:</label>
                    <input type="text" id="topic" name="topic" value="<?php echo htmlspecialchars($appointment['topic']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($appointment['email']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="phone"><i class="fas fa-phone"></i> Phone:</label>
                    <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($appointment['phone']); ?>" required>
                </div>
                
                <div class="button-group">
                    <button type="submit" class="submit-btn"><i class="fas fa-save"></i> Save Changes</button>
                    <a href="../controller/AppointmentController.php?action=showStudentAppointments" class="cancel-btn"><i class="fas fa-times"></i> Cancel</a>
                </div>
            </form>
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

    <!-- JavaScript for date validation -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get the date input element
        const dateInput = document.getElementById('new_appointment_date');
        const dateHelp = document.getElementById('earliestDate');
        const form = document.getElementById('updateForm');
        
        // Calculate the minimum date (2 days from now)
        const now = new Date();
        const minDate = new Date(now);
        minDate.setDate(now.getDate() + 2); // Add 2 days
        
        // Format the date for the datetime-local input
        // Format: YYYY-MM-DDThh:mm
        function formatDateForInput(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}T00:00`;
        }
        
        // Set the minimum date attribute
        dateInput.min = formatDateForInput(minDate);
        
        // Display the earliest available date in a user-friendly format
        dateHelp.textContent = minDate.toDateString();
        
        // Form validation
        form.addEventListener('submit', function(event) {
            const selectedDate = new Date(dateInput.value);
            
            // Check if the selected date is at least 2 days in the future
            if (selectedDate < minDate) {
                event.preventDefault();
                alert('Please select a date that is at least 2 days from today.');
                dateInput.focus();
            }
        });
    });
    </script>
    <script>
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