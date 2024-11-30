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
    <title>Time tracker</title>
    <link rel="stylesheet" href="../../assets/css/time_tracking.css">
  <script src="../../assets/js/time_tracking.js"></script>
</head>
<body>
    <div class="container">
        <header>
            <h1>Time Tracker</h1>
        </header>
        <div class="main">
        <div class="pomodoro-timer"> 
        <div class="pomodoro-header">
            
                <h2>Pomodoro Timer</h2>
                <button type="button" onclick="addTask()">Add Task</button>
            </div>
            
                <div class="adjust-timer">
                    <label for="work-time">Work Duration (minutes):</label>
                    <input type="number" id="work-time" value="25" min="1">
                    <label for="break-time">Break Duration (minutes):</label>
                    <input type="number" id="break-time" value="5" min="1">
                    <div class="timer-display" id="timer-display">25:00</div>

                    <div class="controls">
                    <button onclick="startTimer()">Start</button>
                    <button onclick="pauseTimer()">Pause</button>
                    <button onclick="resetTimer()">Reset</button>
                </div>
                </div>
                
               
                <div class="session-tracking">
                    <p>Completed Sessions: <span id="sessions-completed">0</span></p>
                    
                </div>
                
            </div>
            <div class="goal-setting">
                <h2>Create Your Milestones</h2>
                <form id="goal-form">
                    <label for="goal">Define Your Goal:</label>
                    <input type="text" id="goal" placeholder="E.g., Complete 3 sessions">
                    <label for="priority">Priority:</label>
                    <select id="priority">
                        <option value="high">High</option>
                        <option value="medium">Medium</option>
                        <option value="low">Low</option>
                    </select>
                    <button type="button" onclick="addGoal()">Set goal</button>
                </form>
                <div id="goal-list"></div>
                <div id="rewards">
                    <h2>Earn badges for your achievements!</h2>
                   <button type="button" >Get your rewards!</butto>
                    <div id="badges">
                        <!-- Badges will appear here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>
