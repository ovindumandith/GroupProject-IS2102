<?php
// File: views/admin/appointment_details.php
session_start();

// Check if user is logged in as admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../login.php?error=unauthorized');
    exit();
}

// Get appointment details from session
$appointment = $_SESSION['appointment_details'] ?? null;

if (!$appointment) {
    header('Location: ../../controller/AppointmentController.php?action=viewAppointments');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
   <link rel="stylesheet" href="../../../../assets/css/header_footer.css" />
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../../../assets/css/appointment_details.css" />
    <link rel="stylesheet" href="../../../../assets/css/admin_home.css" />
    
   <style>
       /* Main content styles */
       main {
           max-width: 1000px;
           margin: 30px auto;
           padding: 0 20px;
       }
       
       h2 {
           color: #009f77;
           margin-bottom: 25px;
           text-align: center;
           font-size: 28px;
       }
       
       /* Alert styles */
       .alert {
           padding: 15px;
           border-radius: 4px;
           margin-bottom: 20px;
           font-weight: 500;
       }
       
       .alert-success {
           background-color: #d4edda;
           color: #155724;
           border: 1px solid #c3e6cb;
       }
       
       .alert-error {
           background-color: #f8d7da;
           color: #721c24;
           border: 1px solid #f5c6cb;
       }
       
       /* Back button */
       .back-btn {
           display: inline-flex;
           align-items: center;
           color: #009f77;
           text-decoration: none;
           font-weight: 500;
           margin-bottom: 20px;
       }
       
       .back-btn i {
           margin-right: 8px;
       }
       
       .back-btn:hover {
           text-decoration: underline;
       }
       
       /* Detail container */
       .detail-container {
           background-color: #fff;
           border-radius: 10px;
           padding: 25px;
           box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
           margin-bottom: 30px;
       }
       
       .appointment-header {
           display: flex;
           justify-content: space-between;
           align-items: center;
           margin-bottom: 20px;
           padding-bottom: 15px;
           border-bottom: 1px solid #eee;
       }
       
       .appointment-id {
           font-size: 1.1rem;
           color: #555;
       }
       
       .appointment-id span {
           font-weight: 600;
           color: #333;
       }
       
       .status-badge {
           display: inline-block;
           padding: 5px 15px;
           border-radius: 20px;
           font-size: 0.9rem;
           font-weight: 500;
       }
       
       .status-pending {
           background-color: #fff8e0;
           color: #f39c12;
       }
       
       .status-accepted {
           background-color: #e0f5f0;
           color: #009f77;
       }
       
       .status-denied {
           background-color: #ffe0e0;
           color: #e74c3c;
       }
       
       .detail-section {
           margin-bottom: 25px;
       }
       
       .section-title {
           font-size: 1.1rem;
           color: #009f77;
           margin-bottom: 15px;
           font-weight: 600;
       }
       
       .detail-grid {
           display: grid;
           grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
           gap: 15px;
       }
       
       .detail-item {
           margin-bottom: 10px;
       }
       
       .item-label {
           font-weight: 500;
           color: #555;
           margin-bottom: 5px;
       }
       
       .item-value {
           color: #333;
       }
       
       .counselor-type {
           display: inline-block;
           padding: 3px 10px;
           border-radius: 15px;
           font-size: 0.8rem;
           margin-left: 10px;
           background-color: #f1f1f1;
       }
       
       .counselor-type.professional {
           background-color: #e0f5f0;
           color: #009f77;
       }
       
       .counselor-type.student {
           background-color: #e0f0ff;
           color: #3498db;
       }
       
       .topic-box {
           background-color: #f9f9f9;
           padding: 15px;
           border-radius: 5px;
           margin-top: 10px;
       }
       
       .action-container {
           margin-top: 30px;
           display: flex;
           justify-content: center;
           gap: 15px;
       }
       
       .action-btn {
           padding: 10px 20px;
           border-radius: 5px;
           text-decoration: none;
           color: white;
           font-weight: 500;
           display: inline-flex;
           align-items: center;
           gap: 8px;
           transition: all 0.3s;
           border: none;
           cursor: pointer;
           font-size: 1rem;
       }
       
       .btn-accept {
           background-color: #009f77;
       }
       
       .btn-deny {
           background-color: #e74c3c;
       }
       
       .btn-back {
           background-color: #3498db;
       }
       
       .action-btn:hover {
           opacity: 0.9;
           transform: translateY(-2px);
       }
       
       /* Responsive */
       @media (max-width: 768px) {
           .detail-grid {
               grid-template-columns: 1fr;
           }
           
           .action-container {
               flex-direction: column;
           }
           
           .action-btn {
               width: 100%;
               justify-content: center;
           }
       }
   </style>
</head>
<body>
   <!-- Header Section -->
   <header class="header">
       <div class="logo">
           <img src="../../../../assets/images/logo.jpg" alt="RelaxU Logo" />
           <h1>RelaxU</h1>
       </div>
      <nav class="navbar">
        <ul>
          <li><a href="../../../controller/AdminDashboardController.php?action=loadDashboard">Home</a></li>
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
           <a href="#" class="profile-btn"><b>Profile</b></a>
           <form action="../../../../util/logout.php" method="post" style="display: inline">
               <button type="submit" class="logout-btn"><b>Log Out</b></button>
           </form>
       </div>
   </header>

   <main>
       <a href="../../controller/AppointmentController.php?action=viewAppointments" class="back-btn">
           <i class="fas fa-arrow-left"></i> Back to Appointments
       </a>
       
       <h2>Appointment Details</h2>
       
       <div class="detail-container">
           <div class="appointment-header">
               <div class="appointment-id">
                   Appointment ID: <span>#<?= $appointment['id'] ?></span>
               </div>
               <div class="status">
                   <span class="status-badge status-<?= strtolower($appointment['status']) ?>">
                       <?= $appointment['status'] ?>
                   </span>
               </div>
           </div>
           
           <div class="detail-section">
               <h3 class="section-title">Appointment Information</h3>
               <div class="detail-grid">
                   <div class="detail-item">
                       <div class="item-label">Date & Time</div>
                       <div class="item-value"><?= date('F d, Y h:i A', strtotime($appointment['appointment_date'])) ?></div>
                   </div>
                   <div class="detail-item">
                       <div class="item-label">Created On</div>
                       <div class="item-value"><?= date('F d, Y', strtotime($appointment['created_at'])) ?></div>
                   </div>
                   <div class="detail-item">
                       <div class="item-label">Topic</div>
                       <div class="item-value topic-box"><?= htmlspecialchars($appointment['topic']) ?></div>
                   </div>
               </div>
           </div>
           
           <div class="detail-section">
               <h3 class="section-title">Student Information</h3>
               <div class="detail-grid">
                   <div class="detail-item">
                       <div class="item-label">Name</div>
                       <div class="item-value"><?= htmlspecialchars($appointment['student_name']) ?></div>
                   </div>
                   <div class="detail-item">
                       <div class="item-label">Email</div>
                       <div class="item-value"><?= htmlspecialchars($appointment['student_email']) ?></div>
                   </div>
                   <div class="detail-item">
                       <div class="item-label">Phone</div>
                       <div class="item-value"><?= htmlspecialchars($appointment['student_phone']) ?></div>
                   </div>
               </div>
           </div>
           
           <div class="detail-section">
               <h3 class="section-title">Counselor Information</h3>
               <div class="detail-grid">
                   <div class="detail-item">
                       <div class="item-label">Name</div>
                       <div class="item-value">
                           <?= htmlspecialchars($appointment['counselor_name']) ?>
                           <span class="counselor-type <?= strtolower($appointment['counselor_type']) ?>">
                               <?= $appointment['counselor_type'] ?>
                           </span>
                       </div>
                   </div>
                   <div class="detail-item">
                       <div class="item-label">Email</div>
                       <div class="item-value"><?= htmlspecialchars($appointment['counselor_email']) ?></div>
                   </div>
               </div>
           </div>
           
           
       </div>
   </main>
   
   <!-- Footer Section -->
   <footer class="footer">
       <div class="footer-container">
           <div class="footer-logo">
               <h1>RelaxU</h1>
               <p>Your mental health, your priority.</p>
               <img id="footer-logo" src="../../../../assets/images/logo.jpg" alt="RelaxU Logo" />
           </div>
           <div class="footer-section">
               <h3>Quick Links</h3>
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