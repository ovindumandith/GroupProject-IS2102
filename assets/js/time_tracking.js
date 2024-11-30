// Pomodoro Timer
let timerInterval;
let timeLeft = 25 * 60; // 25 minutes in seconds
const display = document.getElementById("time-display");
const startBtn = document.getElementById("start-btn");
const pauseBtn = document.getElementById("pause-btn");
const resetBtn = document.getElementById("reset-btn");

function updateTimer() {
  const minutes = Math.floor(timeLeft / 60);
  const seconds = timeLeft % 60;
  display.textContent = `${minutes.toString().padStart(2, "0")}:${seconds.toString().padStart(2, "0")}`;
}

function startTimer() {
  if (!timerInterval) {
    timerInterval = setInterval(() => {
      if (timeLeft > 0) {
        timeLeft--;
        updateTimer();
      } else {
        clearInterval(timerInterval);
        alert("Time's up! Take a break.");
      }
    }, 1000);
  }
}

function pauseTimer() {
  clearInterval(timerInterval);
  timerInterval = null;
}

function resetTimer() {
  clearInterval(timerInterval);
  timerInterval = null;
  timeLeft = 25 * 60;
  updateTimer();
}

startBtn.addEventListener("click", startTimer);
pauseBtn.addEventListener("click", pauseTimer);
resetBtn.addEventListener("click", resetTimer);

// Time Tracking
const taskForm = document.getElementById("task-form");
const taskInput = document.getElementById("task-input");
const taskList = document.getElementById("task-list");
let tasks = [];

taskForm.addEventListener("submit", (e) => {
  e.preventDefault();
  const taskName = taskInput.value;
  const startTime = new Date();
  tasks.push({ taskName, startTime, completed: false });
  taskInput.value = "";
  renderTasks();
});

function renderTasks() {
  taskList.innerHTML = tasks
    .map(
      (task, index) =>
        `<li>
          <span>${task.taskName}</span>
          <button onclick="markComplete(${index})">Complete</button>
        </li>`
    )
    .join("");
}

function markComplete(index) {
  const endTime = new Date();
  tasks[index].completed = true;
  tasks[index].endTime = endTime;
  const taskDuration = (endTime - tasks[index].startTime) / 1000 / 60; // in minutes
  document.getElementById("insight-text").textContent = `You spent ${taskDuration.toFixed(
    1
  )} minutes on "${tasks[index].taskName}". Great job!`;
  renderTasks();
}
