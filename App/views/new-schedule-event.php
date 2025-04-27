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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Calendar</title>
    <link rel="stylesheet" href="../../assets/css/new-schedule-event.css">
    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="dashboard">
        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar content goes here -->
            <div class="logo">
                <div class="profile-icon">
                    <img src="https://avatar.iran.liara.run/public" alt="User">
                </div>
                </div>
            <nav class="nav-icons">
            <a href="workload.php"><i class="fas fa-power-off"></i> </a>
            </nav>
        </div>


           

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <div class="header-content">
                <div class="header-left">
                    <h1>Event Scheduler</h1>
                    <div class="search-and-add">
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
                        <button class="add-event-button" onclick="showPopup()">
                            <span class="add-icon">+</span>
                            Add event
                        </button>

                        <!-- Popup Form (Initially Hidden) -->
                        <div class="popup" id="eventPopup">
                            <div class="popup-content">
                                <span class="close-btn" onclick="closePopup()">&times;</span>
                                <h2 id="popupTitle">Add Event</h2>
                                <form id="eventForm" method="POST">
                                    <input type="hidden" id="eventId" name="id">
                                    <div id="errorContainer" style="color: red; margin-bottom: 10px;"></div>
                                    <label for="title">Title:</label>
                                    <input type="text" id="title" name="title" required>

                                    <label for="description">Description:</label>
                                    <textarea id="description" name="description"></textarea>

                                    <label for="date">Date:</label>
                                    <input type="date" id="date" name="date" required>

                                    <label for="startTime">Start Time:</label>
                                    <input type="time" id="startTime" name="startTime" required>

                                    <label for="endTime">End Time:</label>
                                    <input type="time" id="endTime" name="endTime" required>

                                    <button type="submit" id="formSubmitButton">Save Event</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Calendar and Event Details -->
            <div class="content-section">
                <!-- Left Panel -->
                <div class="left-panel">
                    <div class="calendar-panel">
                        <div class="calendar-navigation">
                            <button id="prevMonth">&lt;</button>
                            <h2 id="monthYear"></h2>

                            <button id="nextMonth">&gt;</button>
                        </div>
                        <!-- Calendar Table -->
                        <table id="calendar"></table>


                    </div>
                   
                    <div class="upcoming-events">
                       
                   

                       

                        <!-- Add more .event-card blocks dynamically if needed -->
                    </div>
                </div>
                <!-- Right Panel -->
                <div class="schedule-panel">
                    <!-- Date Header -->
                    <div class="date-header">
                        <div class="date">
                            <span class="day" id="current-day"></span>
                            <span class="month-year">
                                <span id="current-day-name"></span>
                                <span id="current-month-year"></span>
                            </span>
                        </div>
                        <button class="today-button" onclick="setToday()">Today</button>
                    </div>
                    <div class="course-card-container">
                        <!-- Weekly Days Navigation -->
                        <div class="week-navigation" id="week-navigation">
                            <span data-day="0">S</span>
                            <span data-day="1">M</span>
                            <span data-day="2">T</span>
                            <span data-day="3" class="active">W</span>
                            <span data-day="4">T</span>
                            <span data-day="5">F</span>
                            <span data-day="6">S</span>
                        </div>

                        <!-- Schedule List -->

                        <div class="schedule">

                            <div class="course-card math">
                                <!-- Content will be added dynamically -->
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Popup Message -->
            <div id="popupMessage"
                style="display: none; position: fixed; top: 20px; right: 20px; background: #4caf50; color: white; padding: 15px; border-radius: 5px; z-index: 1000;">
                <span id="popupText"></span>
            </div>
        </div>
    </div>

    <script src="../../assets/js/new-schedule-event.js"></script>
</body>

</html>