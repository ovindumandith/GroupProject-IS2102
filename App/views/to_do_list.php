<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if not logged in
    header('Location: login.php');
    exit();
}
include "./templates/header.php";
?>



<!-- Content Section (for demonstration) -->
<div class="dashboard-container">

    <!-- Main Content -->
    <main class="main-content">
        <!-- Working version of https://dribbble.com/shots/14552329--Exploration-Task-Management-Dashboard -->
        <div class='app'>
            <main class='project'>
                <div class='project-info'>
                <div class="user-info">
                        <div class="avatar">
                            <img src="../../assets/images/workload/avatar.jpeg" alt="User Avatar">
                        </div>
                        <h1 class="welcome-text">Welcome User👋 </h1>
                    </div>
                    <div class='project-participants'>
                        <button  class="add-task-btn " onclick="showPopup()">
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
                                    <label for="time">Time:</label>
                                    <input type="time" id="time" name="time" required>
                                    <label for="description">Description:</label>
                                    <textarea id="description" name="description" rows="4" cols="50" ></textarea>
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
                        <div id="done-tasks"></div>
                    </div>

                </div>
                <button class="project-participants_back" onclick="window.history.back()">
                    <i class="fas fa-arrow-left"></i> Back
                </button>
            </main>
            <aside class='task-details'>
                
                <div class='tag-progress'>

                    <h2>Weekly Task</h2>
                    <div id="timeline" class="timeline"></div>
                    <!-- <div class="event">
                                <span class="date">01 Oct</span>
                                <div class="details">Doctor Appointment</div>
                            </div>
                            <div class="event">
                                <span class="date">29 Sep</span>
                                <div class="details">AWS Conference</div>
                            </div>
                            <div class="event">
                                <span class="date">20 Sep</span>
                                <div class="details">Adsense Call</div>
                            </div>
                            <div class="event">
                                <span class="date">15 Sep</span>
                                <div class="details">New York Flight</div>
                            </div>
                            <div class="event">
                                <span class="date">13 Sep</span>
                                <div class="details">Affiliate Asia</div>
                            </div>
                            <div class="event">
                                <span class="date">12 Sep</span>
                                <div class="details">Cuffing Season</div>
                            </div>
                            <div class="event">
                                <span class="date">10 Sep</span>
                                <div class="details">Job Debut</div>
                            </div>
                            <div class="event">
                                <span class="date">05 Sep</span>
                                <div class="details">Gary’s Wedding</div>
                            </div> -->
                </div>

        </div>
        </aside>
</div>
<div id="popupMessage" style="display: none; position: fixed; top: 20px; right: 20px; background: #4caf50; color: white; padding: 15px; border-radius: 5px; z-index: 1000;">
    <span id="popupText"></span>
</div>
</main>
<script src="../../assets/js/to_do_list.js"></script>
</div>

</body>

</html>