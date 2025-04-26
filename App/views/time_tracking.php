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
    <title>Goal Tracker - Time Management Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/time_tracking.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
</head>

<body>
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header" id="sidebarHeader">
            <div class="header-title">
                    <p id="current-date">Loading date...</p>
                </div>
            </div>

            <nav class="sidebar-nav">
                <ul>
                    <li class="active"><a href="#" data-target="dashboard-content"><span class="material-symbols-rounded">dashboard</span> Dashboard</a></li>
                    <li><a href="#" data-target="task-content"><span class="material-symbols-rounded">task</span> Tasks</a></li>
                    <li><a href="#" data-target="achievement-content"><span class="material-symbols-rounded">emoji_events</span> Achievements</a></li>
                </ul>
            </nav>
            <div class="sidebar-footer">
                <button id="back-button" class="btn btn-secondary">
                    <span class="material-symbols-rounded">arrow_back</span> Back
                </button>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="main-header">
               
                <div class="header-title">
                    <h2>Activity Tracker</h2>
                </div>
                <div class="header-actions">
                    <button id="add-task-btn" class="btn btn-primary">
                        <span class="material-symbols-rounded">add</span> Add New Task
                    </button>
                </div>

            </header>

            <!-- Dashboard Content -->
            <div class="dashboard-content content-section">
                <!-- Stats Cards -->
                <section class="stats-section">
                    <div class="stats-card">
                        <div class="stats-icon">
                            <span class="material-symbols-rounded">schedule</span>
                        </div>
                        <div class="stats-info">
                            <h3>Total Time Tracked</h3>
                            <p id="total-time-tracked">0h 0m</p>
                        </div>
                    </div>
                    <div class="stats-card">
                        <div class="stats-icon">
                            <span class="material-symbols-rounded">task</span>
                        </div>
                        <div class="stats-info">
                            <h3>Tasks Completed</h3>
                            <p id="tasks-completed">0/0</p>
                        </div>
                    </div>
                    <div class="stats-card">
                        <div class="stats-icon">
                            <span class="material-symbols-rounded">emoji_events</span>
                        </div>
                        <div class="stats-info">
                            <h3>Badges Earned</h3>
                            <p id="badges-earned">0</p>
                        </div>
                    </div>
                </section>

                <!-- Tasks Section -->
                <section class="tasks-section">
                    <div class="section-header">
                        <h3>Today's Tasks</h3>
                    </div>
                    <div class="tasks-container" id="tasks-container">
                        <!-- Tasks will be added here dynamically -->
                        <!-- <div class="empty-state" >
                            <span class="material-symbols-rounded">task</span>
                            <p>No tasks added yet. Click "Add New Task" to get started.</p>
                        </div> -->
                    </div>
                </section>

                <!-- Achievements Section -->
                <section class="achievements-section">
                    <div class="section-header">
                        <h3>Recent Achievements</h3>
                        <div class="section-actions">
                            <a href="#" class="view-all">View All</a>
                        </div>
                    </div>
                    <div class="badges-container" id="badges-container">
                        <!-- Badges will be added here dynamically -->
                        <div class="empty-state">
                            <span class="material-symbols-rounded">emoji_events</span>
                            <p>Complete tasks to earn badges and achievements.</p>
                        </div>
                    </div>
                </section>
            </div>
            <div class="task-content content-section" style="display:flex;">
                <div id="goalsList"></div>
            </div>
            <div class="achievement-content content-section" style="display: flex;">
    <div id="viewBadgs"></div>
</div>

        </main>
    </div>

    <!-- Add Task Modal -->
    <div id="add-task-modal" class="modal">
        <div class="modal-content pop-up-form">
            <div class="modal-header">
                <h3>Add New Task</h3>
                <button class="close-modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="add-task-form">
                    <div id="errorContainer" style="color: red; margin-bottom: 10px;"></div>
                    <div class="form-group">
                        <label for="task-name">Task Name</label>
                        <input type="text" id="task-name" name="task-name" placeholder="e.g., Math Assignment" required>
                    </div>
                    <div class="form-group">
                        <label for="task-description">Description (Optional)</label>
                        <textarea id="task-description" name="task-description"
                            placeholder="Brief description of the task"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="time-goal">Time Goal</label>
                        <div class="time-input-group">
                            <input type="number" id="time-goal-hours" name="time-goal-hours" min="0" max="24"
                                placeholder="0" required>
                            <label for="time-goal-hours">hours</label>
                            <input type="number" id="time-goal-minutes" name="time-goal-minutes" min="0" max="59"
                                placeholder="0" required>
                            <label for="time-goal-minutes">minutes</label>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary cancel-modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Task Details Modal -->
    <div id="task-details-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="detail-task-name">Task Details</h3>
                <button class="close-modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="task-detail-content">
                    <p id="detail-task-description" class="task-description"></p>

                    <div class="detail-stats">
                        <div class="detail-stat">
                            <span class="stat-label">Priority:</span>
                            <span id="detail-task-priority" class="stat-value"></span>
                        </div>
                        <div class="detail-stat">
                            <span class="stat-label">Goal Time:</span>
                            <span id="detail-goal-time" class="stat-value"></span>
                        </div>
                        <div class="detail-stat">
                            <span class="stat-label">Time Spent:</span>
                            <span id="detail-time-spent" class="stat-value"></span>
                        </div>
                        <div class="detail-stat">
                            <span class="stat-label">Status:</span>
                            <span id="detail-task-status" class="stat-value"></span>
                        </div>
                    </div>

                    <div class="detail-progress">
                        <h4>Progress</h4>
                        <div class="progress-bar">
                            <div id="detail-progress-fill" class="progress-fill"></div>
                        </div>
                        <div class="progress-stats">
                            <span id="detail-progress-percent">0%</span>
                            <span id="detail-progress-ratio">0/0</span>
                        </div>
                    </div>

                    <div class="detail-actions">
                        <button id="detail-start-btn" class="btn btn-success">
                            <span class="material-symbols-rounded">play_arrow</span> Start Timer
                        </button>
                        <button id="detail-complete-btn" class="btn btn-primary">
                            <span class="material-symbols-rounded">check</span> Mark Complete
                        </button>
                        <button id="detail-delete-btn" class="btn btn-danger">
                            <span class="material-symbols-rounded">delete</span> Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Achievement Notification -->
    <div id="achievement-notification" class="achievement-notification">
        <div class="achievement-content">
            <div class="achievement-icon">🏆</div>
            <div class="achievement-info">
                <h4>Achievement Unlocked!</h4>
                <p id="achievement-name">Badge Name</p>
            </div>
        </div>
    </div>
    <div id="popupMessage" style="display: none; position: fixed; top: 20px; right: 20px; background: #4caf50; color: white; padding: 15px; border-radius: 5px; z-index: 1000;">
        <span id="popupText"></span>
    </div>

    <script src="../../assets/js/time_tracking.js"></script>

</body>

</html>