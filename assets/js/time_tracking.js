// Pomodoro Timer Logic
let timerInterval;
let timeLeft;
let sessionsCompleted = 0;
let totalTime = 0; // Total productive time
let currentTask = null;
let isLongBreak = false;

// Get input values for the timer durations
function getTimerSettings() {
    const workDuration = parseInt(document.getElementById('work-time').value) * 60; // Convert to seconds
    const breakDuration = parseInt(document.getElementById('break-time').value) * 60; // Convert to seconds
    const longBreakDuration = parseInt(document.getElementById('long-break-time').value) * 60; // Convert to seconds
    return { workDuration, breakDuration, longBreakDuration };
}

// Initialize the timer with work duration
function initTimer() {
    const { workDuration } = getTimerSettings();
    timeLeft = workDuration;
    updateDisplay();
}

// Update the timer display
function updateDisplay() {
    const minutes = Math.floor(timeLeft / 60);
    const seconds = timeLeft % 60;
    document.getElementById('timer-display').textContent =
        `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
}

// Start the timer
function startTimer() {
    if (!timerInterval) {
        timerInterval = setInterval(() => {
            if (timeLeft > 0) {
                timeLeft--;
                updateDisplay();
            } else {
                clearInterval(timerInterval);
                timerInterval = null;
                sessionComplete();
            }
        }, 1000);
    }
}

// Pause the timer
function pauseTimer() {
    clearInterval(timerInterval);
    timerInterval = null;
}

// Reset the timer
function resetTimer() {
    clearInterval(timerInterval);
    timerInterval = null;
    initTimer();
}

// Session completion logic
function sessionComplete() {
    const { breakDuration, longBreakDuration } = getTimerSettings();
    sessionsCompleted++;
    totalTime += parseInt(document.getElementById('work-time').value); // Add work duration to total productive time
    document.getElementById('sessions-completed').textContent = sessionsCompleted;
    document.getElementById('total-time').textContent = totalTime;

    // Play notification sound or visual cue
    alert('Session complete! Time for a break!');
    
    // After every 4 work sessions, take a long break
    if (sessionsCompleted % 4 === 0) {
        isLongBreak = true;
        timeLeft = longBreakDuration;
        alert('Long break time!');
    } else {
        isLongBreak = false;
        timeLeft = breakDuration;
        alert('Short break time!');
    }

    startTimer();
}

// Add Task to Task List
function addTask() {
    const taskInput = document.getElementById('task-name');
    const taskName = taskInput.value.trim();
    if (!taskName) return;

    const taskList = document.getElementById('task-list');
    const taskItem = document.createElement('li');
    taskItem.textContent = taskName;

    const startTaskButton = document.createElement('button');
    startTaskButton.textContent = "Start Task";
    startTaskButton.onclick = () => startTask(taskName);

    taskItem.appendChild(startTaskButton);
    taskList.appendChild(taskItem);

    taskInput.value = '';
}

// Start Task (integrates with Pomodoro Timer)
function startTask(taskName) {
    currentTask = taskName;
    alert(`Starting Pomodoro for task: ${taskName}`);
    initTimer();
    startTimer();
}

// Goal Setting Logic
function addGoal() {
    const goalInput = document.getElementById('goal');
    const priorityInput = document.getElementById('priority');
    const goal = goalInput.value;
    const priority = priorityInput.value;

    if (goal.trim() === '') {
        alert('Please enter a goal.');
        return;
    }

    const goalList = document.getElementById('goal-list');
    const goalElement = document.createElement('div');
    goalElement.classList.add('goal');
    goalElement.innerHTML = `<p><strong>${goal}</strong> (Priority: ${priority})</p>`;
    goalList.appendChild(goalElement);

    goalInput.value = '';
    priorityInput.value = 'medium';

    if (sessionsCompleted > 0) {
        const badges = document.getElementById('badges');
        const badge = document.createElement('span');
        badge.textContent = '🏆';
        badges.appendChild(badge);
    }
}
