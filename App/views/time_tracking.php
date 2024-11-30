<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Workload Manager</title>
  <link rel="stylesheet" href="../../assets/css/time_tracking.css">
  <script src="../../assets/js/time_tracking.js"></script>
</head>
<body>
  <div class="dashboard">
    <!-- Sidebar -->
    <aside class="sidebar">
      <h2>RelaxU</h2>
      
    </aside>

    <!-- Main Content -->
    <main class="main-content">
      <div class="row">
        <!-- Task-Based Tracking -->
        <section class="task-tracking">
          <h3>Task Tracking</h3>
          <div class="task">
            <label>Task Name:</label>
            <input type="text" id="taskName" placeholder="Enter task name">
            <div class="timer-controls">
              <button id="startTimer">Start</button>
              <button id="stopTimer">Stop</button>
              <button id="resetTimer">Reset</button>
            </div>
            <p id="timeElapsed">Time: 00:00:00</p>
          </div>
        </section>

        <!-- Pomodoro Timer -->
        <section class="pomodoro">
          <h3>Pomodoro Timer</h3>
          <div class="pomodoro-timer">
            <p id="pomodoroTime">25:00</p>
            <button id="startPomodoro">Start</button>
            <button id="resetPomodoro">Reset</button>
          </div>
          <p>Completed Sessions: <span id="completedSessions">0</span></p>
        </section>
      </div>

      <div class="row">
        <!-- Goal Setting -->
        <section class="goal-setting">
          <h3>Goal Setting</h3>
          <div>
            <label>Set Goal Time (hours):</label>
            <input type="number" id="goalTime" placeholder="e.g., 2">
            <button id="setGoal">Set Goal</button>
          </div>
          <p id="goalStatus">Goal Status: Not Set</p>
        </section>
      </div>
    </main>
  </div>

  <script src="scripts.js"></script>
</body>
</html>
