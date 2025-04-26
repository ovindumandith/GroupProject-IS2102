<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if not logged in
    header('Location: login.php');
    exit();
}
//include "./templates/header.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>to-do-list</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css' rel='stylesheet' />
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css"
        rel="stylesheet">
    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../../public/styles/main.css" />
    <link rel="stylesheet" href="../../assets/css/schedule_event.css" type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="./../../assets/css/to_do_list.css" type="text/css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js'></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>

<body>
    <!-- Content Section (for demonstration) -->
    <div class="dashboard-container">

        <!-- Main Content -->

        <!-- Working version of https://dribbble.com/shots/14552329--Exploration-Task-Management-Dashboard -->
        <div class='main-content'>
            <main class='project'>

                <aside class="sidebar">
                    <div class="logo">
                        
                    </div>
                    <nav class="nav-icons">
                    <a href="workload.php"><i class="fas fa-power-off"></i> </a>
                    </nav>
                </aside>

                <div class='project-info'>
                    <h1>Daily Planner </h1>
                    <div class="search-container">
                        <div class="search-task">
                            <input type="text" id="search-bar" name="search" placeholder="search task here...">
                            <button class="search-button"><i class="fas fa-search"></i></button>
                        </div>
                        <div class="results-container">
                            <ul id="search-results">
                            </ul>
                        </div>
                    </div>

                    <div class='project-participants'>
                        <button class="add-task-btn " onclick="showPopup()">
                            <span class="icon">+</span>Add Task
                        </button>
                        <!-- Popup Form (Initially Hidden) -->
                        <div class="popup" id="eventPopup">
                            <div class="popup-content">
                                <span class="close-btn" onclick="closePopup()">&times;</span>
                                <h2 id="popupTitle">Add Task</h2>
                                <form id="eventForm" method="POST">
                                    <input type="hidden" id="taskId" name="id"> <!-- Hidden field for event ID -->
                                    <!-- Error container (for displaying error messages) -->
                                    <div id="errorContainer" style="color: red; margin-bottom: 10px;"></div>
                                    <label for="title">Title:</label>
                                    <input type="text" id="title" name="title" required>
                                    <label for="date">Date:</label>
                                    <input type="date" id="date" name="date" required>
                                    <label for="description">Description:</label>
                                    <textarea id="description" name="description" rows="4" cols="50"></textarea>
                                    <button type="submit" id="formSubmitButton">Save Task</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class='project-tasks'>
                    <div class='project-column'>
                        <div class='project-column-heading'>
                            <h2 class='project-column-heading__title'>Today</h2>
                        </div>
                        <div id="today-tasks"></div>
                    </div>

                    <div class='project-column'>
                        <div class='project-column-heading'>
                            <h2 class='project-column-heading__title'>Upcoming</h2>
                        </div>
                        <div id="upcoming-tasks"></div>
                    </div>

                    <div class='project-column'>
                        <div class='project-column-heading'>
                            <h2 class='project-column-heading__title'>Overdue</h2>
                        </div>
                        <div id="overdue-tasks"></div>
                    </div>

                    <div class='project-column'>
                        <div class='project-column-heading'>
                            <h2 class='project-column-heading__title'>Done</h2>
                        </div>
                        <div id="done-tasks"><i class="fas fa-check-circle" style="color:green;"></i> Completed
                        </div>
                    </div>
                    <aside class='task-details'>
                        <div class="achievement-card">

                            <div class="achievement-text">
                                <div class="achievement-header">
                                    <div class="profile-icon">
                                        <img src="https://avatar.iran.liara.run/public" alt="User">
                                    </div>
                                    <h2 id="username">Hey <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest'; ?></h2>
                                </div>
                                <p>You are almost there</p>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 80%;"></div>
                                    <!-- 80% complete, adjust as needed -->
                                </div>
                                <p class="progress-status">20 out of 25 tasks are completed</p>
                            </div>
                            <div class="achievement-image">
                                
                            </div>
                        </div>

                        <div class='tag-progress'>

                            <h2> Weekly Grind</h2>
                            <div id="timeline" class="timeline"></div>
                            <!-- <div class="event">
                                <span class="date">01 Oct</span>
                                <div class="details">Doctor Appointment</div>
                            </div>
                            <div class="event">
                                <span class="date">29 Sep</span>
                                <div class="details">AWS Conference</div>
                            </div> -->
                        </div>
                </div>
                </aside>
        </div>

        </main>

    </div>
    <div id="popupMessage"
        style="display: none; position: fixed; top: 20px; right: 20px; background: #4caf50; color: white; padding: 15px; border-radius: 5px; z-index: 1000;">
        <span id="popupText"></span>
    </div>
    </main>
    <script src="../../assets/js/to_do_list.js"></script>
    </div>

</body>

</html>