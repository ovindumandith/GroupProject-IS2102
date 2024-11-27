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
    <link rel="stylesheet" href="../../assets/css/schedule_event.css">
    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


</head>

<body>
    
    <div class="dashboard">
        <!-- Sidebar -->
        <div class="sidebar">
            <button class="back-button">
                <img src="back-icon.png" alt="Back Icon" />
            </button>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <header>
                <div class="header-left">
                    <h1>Event Scheduler</h1>
                    <div class="search-and-add">
                        <div class="search-bar">
                            <input type="text" id="searchInput" class="search-task" placeholder="Search your task here !" oninput="searchEvents()">
                            <button class="search-button">
                                <img src="../../assets/images/workload/search_icon.png" alt="Search" />
                            </button>
                        </div>
                        <button class="add-event-button" onclick="showPopup()">
                            <span class="add-icon"><img src="../../assets/images/workload/plus.png" alt="Add Icon" class="add-icon"></span>
                            Add event
                        </button>

                        <!-- Popup Form (Initially Hidden) -->
                        <div class="popup" id="eventPopup">
                            <div class="popup-content">
                                <span class="close-btn" onclick="closePopup()">&times;</span>
                                <h2 id="popupTitle">Add Event</h2>
                                <form id="eventForm" method="POST" action="../controller/ScheduleEventController.php">
                                    <input type="hidden" id="eventId" name="id"> <!-- Hidden field for event ID -->

                                    <label for="title">Title:</label>
                                    <input type="text" id="title" name="title" required>

                                    <label for="description">Description:</label>
                                    <textarea id="description" name="description" ></textarea>

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
            </header>

            <!-- Calendar and Event Details -->
            <div class="calendar-section">
                <!-- Left Panel -->
                <div class="calendar-panel">
                    <div class="calendar-navigation">
                        <button id="prevMonth">&lt;</button>
                        <h2 id="monthYear"></h2>
                        <button id="nextMonth">&gt;</button>
                    </div>
                    <!-- Calendar Table -->
                    <table id="calendar"></table>
                </div>

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

                    <!-- Weekly Days Navigation -->
                    <div class="week-navigation" id="week-navigation">
                        <span data-day="0">S</span> <!-- Sunday -->
                        <span data-day="1">M</span> <!-- Monday -->
                        <span data-day="2">T</span> <!-- Tuesday -->
                        <span data-day="3" class="active">W</span> <!-- Wednesday -->
                        <span data-day="4">T</span> <!-- Thursday -->
                        <span data-day="5">F</span> <!-- Friday -->
                        <span data-day="6">S</span> <!-- Saturday -->
                    </div>


                    <!-- Schedule Cards -->
                    <div class="schedule">
                        <!-- Mathematics -->
                        <div class="course-card math">
                            <div class="time">11:35 - 13:05</div>
                            <div class="course-info">
                                <h4>Mathematics</h4>

                                <div class="details"></div>

                                <div class="button-container">
                                    <button class="edit-btn">
                                        <img src="../../assets/images/workload/edit-icon.png" alt="Edit" />
                                    </button>

                                    <button class="delete-btn">
                                        <img src="../../assets/images/workload/delete-icon.png" alt="Delete" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script src="../../assets/js/schedule_event.js"></script>
    <!-- Call the function after the script is loaded -->

</body>

</html>