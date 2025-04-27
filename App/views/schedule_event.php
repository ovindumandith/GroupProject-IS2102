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

        <div class='app'>
            <div class="event-sidebar">
            <div class="calendar-panel">
                    <div class="calendar-navigation">

                        <button id="prevMonth">&lt;</button>
                        <h2 id="monthYear"></h2>
                        <button id="nextMonth">&gt;</button>
                    </div>
                    <!-- Calendar Table -->
                    <table id="calendar"></table>
                </div>

                <div class="sidebar-section upcoming">
                    <h5>UpComing</h5>
                    <div class='tag-progress'>
                        <div id="timeline" class="timeline"></div>
                    </div>
                </div>
            </div>

            <!-- Calendar-->
           <div class="container mt-5">
               <!-- <h1>Event Schedular</h1>-->

                <div id="calendar-container">
                    <div id="calendar"></div>
                </div>
            </div>

            <!-- Modal for adding/editing events -->
            <div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="eventModalLabel">Add/Edit Event</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="eventForm">
                                <input type="hidden" id="eventId">
                                <div class="form-group">
                                    <label for="eventTitle">Event Title</label>
                                    <input type="text" class="form-control" id="eventTitle" required>
                                </div>
                                <div class="form-group">
                                    <label for="startTime">Start Time</label>
                                    <input type="text" class="form-control datetimepicker" id="startTime" required>
                                </div>
                                <div class="form-group">
                                    <label for="endTime">End Time</label>
                                    <input type="text" class="form-control datetimepicker" id="endTime" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Save Event</button>
                                <button type="button" class="btn btn-danger" id="deleteEvent">Delete Event</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </main>
    <script src="../../assets/js/schedule_event.js"></script>

</div>

</body>

</html>