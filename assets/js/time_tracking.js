// scripts.js

// Task Tracking Timer
let taskTimerInterval;
let taskSeconds = 0;

document.getElementById("startTimer").addEventListener("click", () => {
  if (!taskTimerInterval) {
    taskTimerInterval = setInterval(() => {
      taskSeconds++;
      updateTimeElapsed();
    }, 1000);
  }
});

document.getElementById("stopTimer").addEventListener("click", () => {
  clearInterval(taskTimerInterval);
  taskTimerInterval = null;
});

document.getElementById("resetTimer").addEventListener("click", () => {
  clearInterval(taskTimerInterval);
  taskTimerInterval = null;
  taskSeconds = 0;
  updateTimeElapsed();
});

function updateTimeElapsed() {
  const hours = Math.floor(taskSeconds / 3600);
  const minutes = Math.floor((taskSeconds % 3600) / 60);
  const seconds = taskSeconds % 60;
  document.getElementById("timeElapsed").textContent = `Time: ${hours
    .toString()
    .padStart(2, "0")}:${minutes.toString().padStart(2, "0")}:${seconds
    .toString()
    .padStart(2, "0")}`;
}

// Pomodoro Timer
let pomodoroInterval;
let pomodoroTime = 1500; // 25 minutes in seconds
let completedSessions = 0;

document.getElementById("startPomodoro").addEventListener("click", () => {
  if (!pomodoroInterval) {
    pomodoroInterval = setInterval(() => {
      if (pomodoroTime > 0) {
        pomodoroTime--;
        updatePomodoroDisplay();
      } else {
        clearInterval(pomodoroInterval);
        pomodoroInterval = null;
        completedSessions++;
        document.getElementById("completedSessions").textContent = completedSessions;
        alert("Pomodoro session completed!");
      }
    }, 1000);
  }
});

document.getElementById("resetPomodoro").addEventListener("click", () => {
  clearInterval(pomodoroInterval);
  pomodoroInterval = null;
  pomodoroTime = 1500;
  updatePomodoroDisplay();
});

function updatePomodoroDisplay() {
  const minutes = Math.floor(pomodoroTime / 60);
  const seconds = pomodoroTime % 60;
  document.getElementById("pomodoroTime").textContent = `${minutes
    .toString()
    .padStart(2, "0")}:${seconds.toString().padStart(2, "0")}`;
}

// Goal Setting
document.getElementById("setGoal").addEventListener("click", () => {
  const goalTime = parseInt(document.getElementById("goalTime").value, 10);
  if (!isNaN(goalTime) && goalTime > 0) {
    document.getElementById("goalStatus").textContent = `Goal Status: Set to ${goalTime} hours.`;
  } else {
    document.getElementById("goalStatus").textContent = "Goal Status: Invalid input.";
  }
});
