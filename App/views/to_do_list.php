<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
  // Redirect to the login page if not logged in
  header('Location: login.php');
  exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>to-do-list</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../../assets/css/header_footer.css" type="text/css" />
  <link rel="stylesheet" href="../../assets/css/home.css" type="text/css" />
  <link rel="stylesheet" href="../../assets/css/to_do_list.css" type="text/css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- Font Awesome CDN link -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


</head>

<body>
  
<!-- Content Section (for demonstration) -->
<div class="dashboard-container">
  
  <!-- Main Content -->
    <main class="main-content">
      <header class="header-task-planner">
          <div class="task-planner">
          
          <h2>Task Planner</h2>
          </div>
          <div class="search-and-add">
          <div class="search-bar">
          <input type="text" class="search-task" placeholder="Search your task here !">
          
          <button class="search-button">
          <i class="fa-solid fa-magnifying-glass"></i>
          </button>
          </div>
          <button class="add-event-button" onclick="window.location.href='add-task.html';" >
            <span class="add-icon"><i class="fa-solid fa-plus"></i></span>
               Add task
         </button>
        </div> 
         
      </header>

      <!-- Events Section -->
      
    <section class="my-tasks">
        <div class="my-tasks-container">
            <div class="my-tasks-container-header">
              <div class="task-filter">
                <button class="filter-button active" data-filter="today">Today <span class="count">35</span></button>
                <button class="filter-button" data-filter="upcoming">Upcoming <span class="count">14</span></button>
                <button class="filter-button" data-filter="overdue">Overdue<span class="count">19</span></button>
              </div>
            </div>
        
          <div class="my-tasks-list">
              <!-- Today Event Cards -->
              <div class="my-tasks-list-card" data-category="today">
                <div class="checkbox-container">
                  <input type="checkbox" id="task-done-1" class="custom-checkbox" />
                  <label for="task-done-1" class="custom-label"></label>
                </div>
                
                <div class="my-task-list-card-content">
                  Learn Javascript
                  <div class="time-icon">
                  <i class="fa-solid fa-bell"></i>
                    <span>7:30 PM</span>
                  </div>
                </div>
                
                <div class="button-container">
                  <button class="edit-btn">
                  <i class="fas fa-edit edit-icon" aria-hidden="true"></i>
                  </button>
                  <button class="delete-btn">
                  <i class="fas fa-trash delete-icon" aria-hidden="true"></i>
                  </button>                  
                </div>
              </div>
        
              <div class="my-tasks-list-card" data-category="today">
                <div class="checkbox-container">
                  <input type="checkbox" id="task-done-1" class="custom-checkbox" />
                  <label for="task-done-1" class="custom-label"></label>
                </div>
                
                <div class="my-task-list-card-content">
                  Learn Javascript
                  <div class="time-icon">
                  <i class="fa-solid fa-bell"></i>
                    <span>7:30 PM</span>
                  </div>
                </div>
                
                <div class="button-container">
                <button class="edit-btn">
                  <i class="fas fa-edit edit-icon" aria-hidden="true"></i>
                  </button>
                  <button class="delete-btn">
                  <i class="fas fa-trash delete-icon" aria-hidden="true"></i>
                  </button> 
                </div>
              </div>

              <div class="my-tasks-list-card" data-category="today">
                <div class="checkbox-container">
                  <input type="checkbox" id="task-done-1" class="custom-checkbox" />
                  <label for="task-done-1" class="custom-label"></label>
                </div>
                
                <div class="my-task-list-card-content">
                  Learn Javascript
                  <div class="time-icon">
                  <i class="fa-solid fa-bell"></i>
                    <span>7:30 PM</span>
                  </div>
                </div>
                
                <div class="button-container">
                  <button class="edit-btn">
                  <i class="fas fa-edit edit-icon" aria-hidden="true"></i>
                  </button>
                  <button class="delete-btn">
                  <i class="fas fa-trash delete-icon" aria-hidden="true"></i>
                  </button>                  
                </div>
              </div>
              
                <!-- Upcoming Event Card -->
              <div class="my-tasks-list-card" data-category="upcoming">
                <div class="checkbox-container">
                  <input type="checkbox" id="task-done-2" class="custom-checkbox" />
                  <label for="task-done-2" class="custom-label"></label>
                </div>
                
                <div class="my-task-list-card-content">
                  <h4>UI/UX Workshop</h4>
                  <div class="calendar-icon">
                  <i class="fa-solid fa-calendar"></i>
                    <span> Nov 23, 2024  </span>
                  </div>
                </div>
                
                <div class="button-container">
                <button class="edit-btn">
                  <i class="fas fa-edit edit-icon" aria-hidden="true"></i>
                  </button>
                  <button class="delete-btn">
                  <i class="fas fa-trash delete-icon" aria-hidden="true"></i>
                  </button> 
                </div>
              </div>
        
              <!-- Overdue Event Card -->
              <div class="my-tasks-list-card" data-category="overdue">
                <div class="checkbox-container">
                  <input type="checkbox" id="task-done-3" class="custom-checkbox" />
                  <label for="task-done-3" class="custom-label"></label>
                </div>
                
                <div class="my-task-list-card-content">
                  <h4>Finalize Project Report</h4>
                  <div class="exclamation-mark-icon">
                  <i class="fa-solid fa-triangle-exclamation"></i>
                    <span>  Nov 23, 2024  </span>
                  </div>
                </div>
                
                <div class="button-container">
                <button class="edit-btn">
                  <i class="fas fa-edit edit-icon" aria-hidden="true"></i>
                  </button>
                  <button class="delete-btn">
                  <i class="fas fa-trash delete-icon" aria-hidden="true"></i>
                  </button> 
                </div>
              </div>
            </div><!-- End of .my-tasks-list -->
            
            
          </div><!-- End of .my-tasks-container -->
          <button class="back-button" onclick="location.href='workload.php'">
            <i class="fa-solid fa-arrow-left"></i>
            </button>
  
  </section>
   
  <script src="../../assets/js/to_do_list.js" defer></script>
</body>

</html>