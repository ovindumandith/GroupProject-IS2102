document.addEventListener("DOMContentLoaded", () => {
    // DOM elements
    const goalForm = document.getElementById("add-task-modal")
    const goalsList = document.getElementById("goalsList")
    const prioritySlider = document.getElementById("priority")
    const links = document.querySelectorAll('.sidebar-nav a');
    const sections = document.querySelectorAll('.content-section');
    fetchTasks()


    viewBadges()
    links.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();

            // Remove active class from all nav items
            document.querySelectorAll('.sidebar-nav li').forEach(li => li.classList.remove('active'));

            // Add active to the clicked item
            this.parentElement.classList.add('active');

            // Hide all content sections
            sections.forEach(section => section.style.display = 'none');

            // Show the targeted section
            const targetClass = this.getAttribute('data-target');
            document.querySelector(`.${targetClass}`).style.display = 'flex';

        });
    });
    // Load goals from localStorage or server
    function closeModal(modal) {
        // Remove active class to trigger animations
        modal.classList.remove("active")

        // Wait for animation to complete before hiding
        setTimeout(() => {
            modal.style.display = "none"
        }, 300)
    }

    goalForm.addEventListener("submit", async function (event) {
        event.preventDefault()

        const errorContainer = document.getElementById("errorContainer");
        errorContainer.innerHTML = ""; // Clear previous errors
        // Get form values

        const task_name = document.getElementById("task-name").value
        const description = document.getElementById("task-description").value
        const timeValue = Number.parseInt(document.getElementById("time-goal-hours").value)
        const time_unit = document.getElementById("time-goal-minutes").value

        // Convert time to minutes for consistency
        const timeInMinutes = (timeValue * 60) + Number(time_unit);

        // Create goal object
        // Create goal object
        const goal = {
            task_name,
            description,
            time_goal: timeInMinutes,
            time_unit,
            time_spent: 0,
            completed: false,
            createdAt: new Date().toISOString(),
        }

        const url = `../controller/TimeTrackingManagementController.php`;

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(goal), // Format data for POST
            });

            const data = await response.json();

            // Handle errors if present
            if (data.errors) {
                displayErrors(data.errors);
            } else if (data.message) {
                // Only show success alert if there are no errors
                saveGoal(data)
                showAlert(data.message);
                closeModal(document.getElementById("add-task-modal"));

            }
        } catch (error) {
            console.error('Error:', error);
        }
        // Save goal


        // Reset form
        goalForm.reset()

        // Show success message
        showNotification("Goal added successfully!", "success")
    });
    // Function to save goal (to localStorage and eventually to server)
    function saveGoal(goal) {
        // Get existing goals
        const goals = JSON.parse(localStorage.getItem("goals")) || []

        // Add new goal
        goals.push(goal)

        // Save to localStorage
        localStorage.setItem("goals", JSON.stringify(goals))

        // TODO: Save to server using fetch API
        // sendToServer(goal);

        // Refresh goals list
        loadGoals(goals)
    }
    function showAlert(message, isSuccess = true) {
        const popup = document.getElementById("popupMessage");
        const popupText = document.getElementById("popupText");

        popupText.textContent = message;
        popup.style.background = isSuccess ? "#4caf50" : "#f44336"; // Green for success, Red for error
        popup.style.display = "block";

        // Hide after 3 seconds
        setTimeout(() => {
            popup.style.display = "none";
        }, 3000);
    }
    async function fetchTasks() {

        try {
            // Fetch all events from the backend
            const response = await fetch('../controller/TimeTrackingManagementController.php', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }

            const data = await response.json(); // Parse JSON response

            // Categorize tasks
            loadGoals(data);

        } catch (error) {
            console.error('Error fetching tasks:', error);
            return []; // Return an empty array if there's an error
        }
    }
    function formatDate(isoDate) {
        const options = { year: 'numeric', month: 'short', day: 'numeric' };
        return new Date(isoDate).toLocaleDateString(undefined, options);
    }

    async function viewBadges() {
        const achievementContent = document.getElementById("viewBadgs");
        if (!achievementContent) return;

        achievementContent.innerHTML = "";

        let badges = JSON.parse(localStorage.getItem('badges')) || [];

        if (badges.length === 0) {
            achievementContent.innerHTML = `
                <div class="empty-state">
                    <span class="material-symbols-rounded">emoji_events</span>
                    <p>Complete tasks to earn badges and achievements.</p>
                </div>
            `;
            return;
        }

        const sortedBadges = [...badges].sort((a, b) => new Date(b.earnedAt) - new Date(a.earnedAt));

        sortedBadges.forEach((badge) => {
            const badgeElement = document.createElement("div");
            badgeElement.className = "badge-card";

            badgeElement.innerHTML = `
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">${badge.name}</h4>
                        <p class="card-subtitle mb-2 text-muted">Earned: ${formatDate(badge.earnedAt)}</p>
                        <div class="d-flex align-items-center mt-3">
                            <div class="badge-icon me-3" style="font-size: 2rem;">${badge.icon}</div>
                            <div>
                                <p class="card-text">${badge.description}</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            achievementContent.appendChild(badgeElement);
        });


    }

    // Function to load goals
    function loadGoals(data) {

        // Get goals from localStorage
        const goals = data
        localStorage.setItem("tasks", JSON.stringify(data))
        tasks = localStorage.getItem("tasks")
        // Clear goals list
        goalsList.innerHTML = ""

        // If no goals, show empty state
        if (goals.length === 0) {
            goalsList.innerHTML = '<div class="empty-state">No goals added yet. Add your first goal above!</div>'
            return
        }

        // Sort goals by priority (high to low)
        goals.sort((a, b) => b.id - a.id)

        // Render each goal
        goals.forEach((goal) => {
            renderGoal(goal)
        })
    }

    // Function to render a goal
    function renderGoal(goal) {

        // Create goal element
        const goalElement = document.createElement("div")
        goalElement.className = "goal-item"
        goalElement.dataset.id = goal.id

        // Calculate progress percentage
        const progressPercentage = Math.min(Math.round((goal.time_spent / goal.time_goal) * 100), 100)

        // Get priority label
        const priorityLabel = getPriorityLabel(goal.priority)
        const priorityClass = getPriorityClass(goal.priority)

        // Format time
        const time_goalFormatted = formatTime(goal.time_goal)
        const time_spentFormatted = formatTime(goal.time_spent)


        // Set inner HTML
        goalElement.innerHTML = `
              <div class="goal-header">
                  <h3 class="goal-title">${goal.task_name}</h3>
                 
              </div>
              <div class="goal-details">
                  <div class="goal-time">
                      <span>Goal: ${time_goalFormatted}</span>
                  </div>
                  
                  <div class="time-tracking">
                      <span>Time spent: ${time_spentFormatted}</span>
                      <div class="time-controls">
                          <button class="btn-time" data-action="start" title="Start timer">▶</button>
                          <button class="btn-time" data-action="add" title="Add 15 minutes">+</button>
                      </div>
                  </div>
              </div>
              <div class="progress-container">
                  <div class="progress-bar">
                      <div class="progress-fill" style="width: ${progressPercentage}%"></div>
                  </div>
                  <div class="progress-stats">
                      <span>${progressPercentage}% complete</span>
                      <span>${time_spentFormatted} / ${time_goalFormatted}</span>
                  </div>
              </div>
              <div class="goal-actions">
                  <button class="btn btn-complete" data-action="complete">${goal.completed ? "Reopen" : "Mark Complete"}</button>
                  <button class="btn btn-delete"  data-action="delete">Delete</button>
              </div>
          `

        // Add event listeners
        addGoalEventListeners(goalElement, goal)

        // Add to goals list
        goalsList.appendChild(goalElement)

        // If completed, add completed class
        if (goal.completed) {
            goalElement.classList.add("completed")
        }
    }

    // Function to add event listeners to goal element
    function addGoalEventListeners(goalElement, goal) {
        // Complete button
        const completeBtn = goalElement.querySelector('[data-action="complete"]')
        completeBtn.addEventListener("click", () => {
            toggleGoalCompletion(goal.id)
        })

        // Delete button
        const deleteBtn = goalElement.querySelector('[data-action="delete"]')
        deleteBtn.addEventListener("click", () => {

            deleteGoal(goal.id)
        })

        // Start timer button
        const startBtn = goalElement.querySelector('[data-action="start"]')
        startBtn.addEventListener("click", () => {
            toggleTimer(goal.id, startBtn)
        })

        // Add time button
        const addBtn = goalElement.querySelector('[data-action="add"]')
        addBtn.addEventListener("click", () => {
            addTimeToGoal(goal.id, 15) // Add 15 minutes
        })
    }

    // Timer tracking
    let activeTimer = null
    let timerInterval = null

    // Function to toggle timer
    function toggleTimer(goalId, button) {

        // If timer is already running for this goal
        if (activeTimer === goalId) {
            // Stop timer
            clearInterval(timerInterval)
            activeTimer = null
            button.textContent = "▶"
            button.classList.remove("active")
            return
        }

        // If timer is running for another goal, stop it
        if (activeTimer !== null) {
            const activeButton = document.querySelector(`.goal-item[data-id="${activeTimer}"] [data-action="start"]`)
            activeButton.textContent = "▶"
            activeButton.classList.remove("active")
            clearInterval(timerInterval)
        }

        // Start timer for this goal
        activeTimer = goalId
        button.textContent = "⏸"
        button.classList.add("active")

        // Update time every minute
        let startTime = Date.now()
        timerInterval = setInterval(() => {
            // Calculate elapsed minutes
            const elapsedMinutes = Math.floor((Date.now() - startTime) / 60000)

            // If at least 1 minute has passed, update the goal
            if (elapsedMinutes > 0) {
                addTimeToGoal(goalId, elapsedMinutes)
                // Reset start time
                startTime = Date.now()
            }
        }, 60000) // Check every minute
    }

    // Function to add time to goal
    function addTimeToGoal(goalId, minutes) {
        // Get goals
        const goals = JSON.parse(localStorage.getItem("tasks")) || []

        // Find goal
        const goalIndex = goals.findIndex((g) => g.id === goalId)

        if (goalIndex !== -1) {
            // Add time
            goals[goalIndex].time_spent += minutes

            // Save
            localStorage.setItem("tasks", JSON.stringify(goals))

            // Refresh
            loadGoals(goals)

            // Show notification
            showNotification(`Added ${minutes} minutes to "${goals[goalIndex].task_name}"`, "success")
        }
    }

    // Function to toggle goal completion
    function toggleGoalCompletion(goalId) {
        // Get goals

        const goals = JSON.parse(localStorage.getItem("tasks")) || []

        // Find goal
        const goalIndex = goals.findIndex((g) => g.id === goalId)

        if (goalIndex !== -1) {
            // Toggle completion
            goals[goalIndex].completed = !goals[goalIndex].completed

            // Show notification
            const action = goals[goalIndex].completed ? "completed" : "reopened";

            const taskStatus = action === 'completed'
                ? 1
                : action === 'reopened'
                    ? 0
                    : null;

            goals[goalIndex].completed = taskStatus
            
            // Save
            localStorage.setItem("tasks", JSON.stringify(goals))

            // Refresh
            loadGoals(goals)



            const goal = {
                id: goalId,
                taskStatus: taskStatus

            }
            fetch('../controller/TimeTrackingManagementController.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(goal), // Pass the event ID
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        showAlert(data.message);
                        // Refresh or update the UI to reflect the deletion
                        //    fetchTasks()
                    } else {
                        showAlert(data.message || 'Failed to delete the event.');
                    }
                })
                .catch(error => {
                    console.error('Error deleting event:', error);
                });
            showNotification(`Goal "${goals[goalIndex].task_name}" ${action}`, "success")
        }
    }

    // Function to delete goal
    function deleteGoal(goalId) {
        // Confirm deletion
        if (!confirm("Are you sure you want to delete this goal?")) {
            return
        }
        fetch('../controller/TimeTrackingManagementController.php', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: goalId }), // Pass the event ID
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    showAlert(data.message);
                    // Refresh or update the UI to reflect the deletion
                    fetchTasks()
                } else {
                    showAlert(data.message || 'Failed to delete the event.');
                }
            })
            .catch(error => {
                console.error('Error deleting event:', error);
            });
        // Get goals
        const goals = JSON.parse(localStorage.getItem("tasks")) || []

        // Find goal
        const goalIndex = goals.findIndex((g) => g.id === goalId)

        if (goalIndex !== -1) {
            // Get goal name for notification
            const goalName = goals[goalIndex].name

            // Remove goal
            goals.splice(goalIndex, 1)

            // Save
            localStorage.setItem("tasks", JSON.stringify(goals))

            // Refresh
            loadGoals()

            // Show notification
            showNotification(`Goal "${goalName}" deleted`, "warning")
        }
    }

    // Helper function to get priority label
    function getPriorityLabel(priority) {
        switch (priority) {
            case 3:
                return "High"
            case 2:
                return "Medium"
            case 1:
                return "Low"
            default:
                return "Medium"
        }
    }

    // Helper function to get priority class
    function getPriorityClass(priority) {
        switch (priority) {
            case 3:
                return "priority-high"
            case 2:
                return "priority-medium"
            case 1:
                return "priority-low"
            default:
                return "priority-medium"
        }
    }

    // Helper function to format time
    function formatTime(minutes) {
        if (minutes < 60) {
            return `${minutes} min`
        } else {
            const hours = Math.floor(minutes / 60)
            const mins = minutes % 60
            return mins > 0 ? `${hours} hr ${mins} min` : `${hours} hr`
        }
    }

    // Function to show notification
    function showNotification(message, type = "info") {
        // Create notification element
        const notification = document.createElement("div")
        notification.className = `notification ${type}`
        notification.textContent = message

        // Add to body
        document.body.appendChild(notification)

        // Show notification
        setTimeout(() => {
            notification.classList.add("show")
        }, 10)

        // Remove after 3 seconds
        setTimeout(() => {
            notification.classList.remove("show")
            setTimeout(() => {
                notification.remove()
            }, 300)
        }, 3000)
    }

    // Add notification styles
    const style = document.createElement("style")
    style.textContent = `
          .notification {
              position: fixed;
              bottom: 20px;
              right: 20px;
              padding: 12px 20px;
              border-radius: 4px;
              color: white;
              font-weight: 600;
              opacity: 0;
              transform: translateY(10px);
              transition: opacity 0.3s, transform 0.3s;
              z-index: 1000;
              max-width: 300px;
          }
          
          .notification.show {
              opacity: 1;
              transform: translateY(0);
          }
          
          .notification.success {
              background-color: #2ecc71;
          }
          
          .notification.warning {
              background-color: #f39c12;
          }
          
          .notification.error {
              background-color: #e74c3c;
          }
          
          .notification.info {
              background-color: #3498db;
          }
      `
    document.head.appendChild(style)
})
document.addEventListener("DOMContentLoaded", () => {
    // Initialize date display
    updateCurrentDate()

    // DOM elements
    const addTaskBtn = document.getElementById("add-task-btn")
    const addTaskModal = document.getElementById("add-task-modal")
    const taskDetailsModal = document.getElementById("task-details-modal")
    const closeModalBtns = document.querySelectorAll(".close-modal")
    const cancelModalBtns = document.querySelectorAll(".cancel-modal")
    const addTaskForm = document.getElementById("add-task-form")
    const tasksContainer = document.getElementById("tasks-container")
    const taskFilter = document.getElementById("task-filter")
    const badgesContainer = document.getElementById("badges-container")
    const backButton = document.getElementById("back-button")

    // Add back button event listener
    backButton.addEventListener("click", () => {
        // Navigate back to previous page
        window.history.back()
    })

    // Stats elements
    const totalTimeTrackedEl = document.getElementById("total-time-tracked")
    const tasksCompletedEl = document.getElementById("tasks-completed")
    const badgesEarnedEl = document.getElementById("badges-earned")
    const goalCompletionEl = document.getElementById("goal-completion")

    // Task details elements
    const detailTaskName = document.getElementById("detail-task-name")
    const detailTaskDescription = document.getElementById("detail-task-description")
    const detailTaskPriority = document.getElementById("detail-task-priority")
    const detailGoalTime = document.getElementById("detail-goal-time")
    const detailTimeSpent = document.getElementById("detail-time-spent")
    const detailTaskStatus = document.getElementById("detail-task-status")
    const detailProgressFill = document.getElementById("detail-progress-fill")
    const detailProgressPercent = document.getElementById("detail-progress-percent")
    const detailProgressRatio = document.getElementById("detail-progress-ratio")
    const detailStartBtn = document.getElementById("detail-start-btn")
    const detailCompleteBtn = document.getElementById("detail-complete-btn")
    const detailDeleteBtn = document.getElementById("detail-delete-btn")

    // Achievement notification
    const achievementNotification = document.getElementById("achievement-notification")
    const achievementName = document.getElementById("achievement-name")

    // Global variables
    let tasks = []
    let badges = []
    let activeTaskId = null
    let activeTimer = null
    let activeTaskElement = null

    // Badge definitions
    const badgeDefinitions = [
        {
            id: "first_task",
            name: "First Steps",
            description: "Created your first task",
            icon: "🚀",
            condition: (tasks) => tasks.length >= 1,
        },
        {
            id: "task_master",
            name: "Task Master",
            description: "Completed 5 tasks",
            icon: "🏆",
            condition: (tasks) => tasks.filter((task) => task.completed).length >= 5,
        },
        {
            id: "time_keeper",
            name: "Time Keeper",
            description: "Tracked a total of 2 hours",
            icon: "⏱️",
            condition: (tasks) => {
                const totalMinutes = tasks.reduce((total, task) => total + task.time_spent, 0)
                return totalMinutes >= 120
            },
        },
        {
            id: "efficiency_expert",
            name: "Efficiency Expert",
            description: "Completed a task under the estimated time",
            icon: "⚡",
            condition: (tasks) => tasks.some((task) => task.completed && task.time_spent < task.time_goal),
        },
        {
            id: "perfect_planner",
            name: "Perfect Planner",
            description: "Completed a task exactly on time",
            icon: "🎯",
            condition: (tasks) => tasks.some((task) => task.completed && task.time_spent === task.time_goal),
        },
    ]

    // Load data from localStorage
    loadData()

    // Event listeners
    addTaskBtn.addEventListener("click", () => {
        openModal(addTaskModal)
    })

    closeModalBtns.forEach((btn) => {
        btn.addEventListener("click", () => {
            closeModal(btn.closest(".modal"))
        })
    })

    cancelModalBtns.forEach((btn) => {
        btn.addEventListener("click", () => {
            closeModal(btn.closest(".modal"))
        })
    })

    addTaskForm.addEventListener("submit", (e) => {
        e.preventDefault()
        addNewTask()
    })

    // taskFilter.addEventListener("change", () => {
    //     renderTasks()
    // })

    // Functions
    function updateCurrentDate() {
        const now = new Date()
        const options = { weekday: "long", year: "numeric", month: "long", day: "numeric" }
        document.getElementById("current-date").textContent = now.toLocaleDateString("en-US", options)
    }

    function openModal(modal) {
        // Set display to flex first
        modal.style.display = "flex"

        // Force a reflow to enable the transition
        void modal.offsetWidth

        // Add active class to trigger animations
        modal.classList.add("active")

        // Add event listener to close modal when clicking outside
        const outsideClickHandler = (e) => {
            if (e.target === modal) {
                closeModal(modal)
            }
        }

        // Remove any existing event listener first to prevent duplicates
        modal.removeEventListener("click", outsideClickHandler)
        modal.addEventListener("click", outsideClickHandler)

        // Add keyboard event to close on Escape key
        const escKeyHandler = (e) => {
            if (e.key === "Escape" && modal.classList.contains("active")) {
                closeModal(modal)
            }
        }

        // Remove any existing event listener first
        document.removeEventListener("keydown", escKeyHandler)
        document.addEventListener("keydown", escKeyHandler)
    }



    function addNewTask() {
        const task_name = document.getElementById("task-name").value
        const taskDescription = document.getElementById("task-description").value
        const time_goalHours = Number.parseInt(document.getElementById("time-goal-hours").value) || 0
        const time_goalMinutes = Number.parseInt(document.getElementById("time-goal-minutes").value) || 0

        // Set default priority to medium since we removed the selector
        const taskPriority = "medium"

        // Convert time to minutes
        const time_goal = time_goalHours * 60 + time_goalMinutes

        if (time_goal <= 0) {
            alert("Please set a time goal greater than 0")
            return
        }

        const newTask = {
            id: Date.now().toString(),
            name: task_name,
            description: taskDescription,
            time_goal: time_goal,
            time_spent: 0,
            priority: taskPriority,
            completed: false,
            createdAt: new Date().toISOString(),
        }

        // Show success animation
        const form = document.getElementById("add-task-form")
        const submitBtn = form.querySelector('button[type="submit"]')
        const originalText = submitBtn.innerHTML

        submitBtn.innerHTML = '<span class="material-symbols-rounded">check</span> Added!'
        submitBtn.style.backgroundColor = "var(--success-color)"

        setTimeout(() => {
            // Add task after animation
            tasks.push(newTask)
            saveData()
            renderTasks()
            updateStats()
            checkBadges()

            // Reset form and close modal
            form.reset()
            submitBtn.innerHTML = originalText
            submitBtn.style.backgroundColor = ""
            closeModal(addTaskModal)
        }, 1000)
    }

    function renderTasks() {
        // Get today's date (start of day)
        const today = new Date()
        today.setHours(0, 0, 0, 0)

        // Filter tasks for today only
        const todaysTasks = tasks.filter((task) => {
            const taskDate = new Date(task.created_at)
            taskDate.setHours(0, 0, 0, 0)
            return taskDate.getTime() === today.getTime()
        })

        // Sort tasks by priority (high > medium > low) and then by creation date
        todaysTasks.sort((a, b) => {
            const priorityOrder = { high: 3, medium: 2, low: 1 }
            if (priorityOrder[a.priority] !== priorityOrder[b.priority]) {
                return priorityOrder[b.priority] - priorityOrder[a.priority]
            }
            return new Date(a.createdAt) - new Date(b.createdAt)
        })

        tasksContainer.innerHTML = ""

        if (todaysTasks.length === 0) {
            tasksContainer.innerHTML = `
          <div class="empty-state">
            <span class="material-symbols-rounded">task</span>
            <p>No tasks for today. Click "Add New Task" to get started.</p>
          </div>
        `
            return
        }

        todaysTasks.forEach((task) => {
            const taskElement = createTaskElement(task)
            tasksContainer.appendChild(taskElement)
        })
    }

    function createTaskElement(task) {
        const taskElement = document.createElement("div")
        taskElement.className = `task-card ${task.completed ? "task-completed" : ""}`
        taskElement.dataset.id = task.id

        // Calculate progress percentage
        const progressPercentage = Math.min(Math.round((task.time_spent / task.time_goal) * 100), 100)

        // Format times
        const goalTimeFormatted = formatTime(task.time_goal)
        const time_spentFormatted = formatTime(task.time_spent)

        taskElement.innerHTML = `
        <div class="task-header">
          <h3 class="task-title">${task.task_name}</h3>
        </div>
        <div class="task-timer">
          <div class="timer-display" id="timer-display-${task.id}">${time_spentFormatted}</div>
          <div class="timer-controls">
            <button class="timer-btn ${task.id === activeTaskId ? "pause-btn" : "start-btn"}" data-action="${task.id === activeTaskId ? "pause" : "start"}">
              <span class="material-symbols-rounded">${task.id === activeTaskId ? "pause" : "play_arrow"}</span>
            </button>
          </div>
        </div>
        <div class="progress-container">
          <div class="progress-bar">
            <div class="progress-fill" style="width: ${progressPercentage}%"></div>
          </div>
          <div class="progress-stats">
            <span>${progressPercentage}% complete</span>
            <span>${time_spentFormatted} / ${goalTimeFormatted}</span>
          </div>
        </div>
      `

        // Add event listeners
        taskElement.addEventListener("click", (e) => {
            // If the click is on a button, don't open the details modal
            if (e.target.closest(".timer-btn")) {
                return
            }
            openTaskDetails(task)
        })

        const timerBtn = taskElement.querySelector(".timer-btn")
        timerBtn.addEventListener("click", (e) => {
            e.stopPropagation() // Prevent opening the details modal
            toggleTimer(task.id, taskElement)
        })

        return taskElement
    }

    function openTaskDetails(task) {
        // Set task details
        detailTaskName.textContent = task.name
        detailTaskDescription.textContent = task.description || "No description provided."
        detailTaskPriority.textContent = capitalizeFirstLetter(task.priority)
        detailTaskPriority.className = `stat-value priority-${task.priority}`
        detailGoalTime.textContent = formatTime(task.time_goal)
        detailTimeSpent.textContent = formatTime(task.time_spent)
        detailTaskStatus.textContent = task.completed ? "Completed" : "In Progress"
        detailTaskStatus.className = `stat-value ${task.completed ? "priority-low" : "priority-medium"}`

        // Calculate progress
        const progressPercentage = Math.min(Math.round((task.time_spent / task.time_goal) * 100), 100)
        detailProgressFill.style.width = `${progressPercentage}%`
        detailProgressPercent.textContent = `${progressPercentage}%`
        detailProgressRatio.textContent = `${formatTime(task.time_spent)} / ${formatTime(task.time_goal)}`

        // Update buttons
        detailStartBtn.textContent = task.id === activeTaskId ? "Pause Timer" : "Start Timer"
        detailStartBtn.querySelector(".material-symbols-rounded").textContent =
            task.id === activeTaskId ? "pause" : "play_arrow"
        detailCompleteBtn.textContent = task.completed ? "Mark Incomplete" : "Mark Complete"

        // Set active task for button actions
        activeTaskElement = document.querySelector(`.task-card[data-id="${task.id}"]`)

        // Add event listeners
        detailStartBtn.onclick = () => {
            toggleTimer(task.id, activeTaskElement)
            detailStartBtn.textContent = task.id === activeTaskId ? "Pause Timer" : "Start Timer"
            detailStartBtn.querySelector(".material-symbols-rounded").textContent =
                task.id === activeTaskId ? "pause" : "play_arrow"
        }

        detailCompleteBtn.onclick = () => {
            toggleTaskCompletion(task.id)
            closeModal(taskDetailsModal)
        }

        detailDeleteBtn.onclick = () => {
            if (confirm("Are you sure you want to delete this task?")) {
                deleteTask(task.id)
                closeModal(taskDetailsModal)
            }
        }

        // Open modal
        openModal(taskDetailsModal)
    }
    async function updateTimeSpent($id) {
        const url = `../controller/TimeTrackingManagementController.php`;
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({ id: $id, timeUpdate: 1 }), // Format data for POST
            });

            const data = await response.json();

            // Handle errors if present
            if (data.errors) {
                displayErrors(data.errors);
            } else if (data.message) {
                // Only show success alert if there are no errors



            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    function toggleTimer(taskId, taskElement) {
        // If this task is already active, stop the timer
        if (taskId === activeTaskId) {
            clearInterval(activeTimer)
            activeTaskId = null
            activeTimer = null

            // Update UI
            const timerBtn = taskElement.querySelector(".timer-btn")
            timerBtn.className = "timer-btn start-btn"
            timerBtn.dataset.action = "start"
            timerBtn.innerHTML = '<span class="material-symbols-rounded">play_arrow</span>'

            return
        }

        // If another task is active, stop its timer
        if (activeTaskId) {
            const activeElement = document.querySelector(`.task-card[data-id="${activeTaskId}"]`)
            if (activeElement) {
                const activeTimerBtn = activeElement.querySelector(".timer-btn")
                activeTimerBtn.className = "timer-btn start-btn"
                activeTimerBtn.dataset.action = "start"
                activeTimerBtn.innerHTML = '<span class="material-symbols-rounded">play_arrow</span>'
            }
            clearInterval(activeTimer)
        }

        // Start timer for this task
        activeTaskId = taskId
        const startTime = Date.now()
        let elapsedSeconds = 0
        const timerDisplay = document.getElementById(`timer-display-${taskId}`)
        const task = tasks.find((t) => t.id === taskId)

        if (!task) return

        // Initial time in seconds
        let totalSeconds = task.time_spent * 60

        // Update UI
        const timerBtn = taskElement.querySelector(".timer-btn")
        timerBtn.className = "timer-btn pause-btn"
        timerBtn.dataset.action = "pause"
        timerBtn.innerHTML = '<span class="material-symbols-rounded">pause</span>'

        // Start interval - update every second for a more responsive UI
        activeTimer = setInterval(() => {
            elapsedSeconds++
            totalSeconds++

            // Update the timer display in real-time
            const hours = Math.floor(totalSeconds / 3600)
            const minutes = Math.floor((totalSeconds % 3600) / 60)
            const seconds = totalSeconds % 60

            // Format the time display
            let timeDisplay = ""
            if (hours > 0) {
                timeDisplay += `${hours}h `
            }
            timeDisplay += `${minutes}m ${seconds}s`

            if (timerDisplay) {
                timerDisplay.textContent = timeDisplay
            }

            // Every minute, update the task's time spent
            if (elapsedSeconds >= 60) {
                // Add a minute to the task
                if (task) {

                    task.time_spent += 1 // Add 1 minute
                    updateTimeSpent(taskId)
                    saveData()
                    updateTaskUI(task)
                    updateStats()
                    checkBadges()
                }

                // Reset elapsed seconds counter
                elapsedSeconds = 0
            }
        }, 1000) // Check every second
    }

    function updateTaskUI(task) {
        // Update task card
        const taskElement = document.querySelector(`.task-card[data-id="${task.id}"]`)
        if (taskElement) {
            const timerDisplay = taskElement.querySelector(".timer-display")
            timerDisplay.textContent = formatTime(task.time_spent)

            // Update progress
            const progressPercentage = Math.min(Math.round((task.time_spent / task.time_goal) * 100), 100)
            const progressFill = taskElement.querySelector(".progress-fill")
            progressFill.style.width = `${progressPercentage}%`

            const progressStats = taskElement.querySelectorAll(".progress-stats span")
            progressStats[0].textContent = `${progressPercentage}% complete`
            progressStats[1].textContent = `${formatTime(task.time_spent)} / ${formatTime(task.time_goal)}`
        }

        // Update task details if open
        if (
            taskDetailsModal.classList.contains("active") &&
            activeTaskElement &&
            activeTaskElement.dataset.id === task.id
        ) {
            detailTimeSpent.textContent = formatTime(task.time_spent)

            const progressPercentage = Math.min(Math.round((task.time_spent / task.time_goal) * 100), 100)
            detailProgressFill.style.width = `${progressPercentage}%`
            detailProgressPercent.textContent = `${progressPercentage}%`
            detailProgressRatio.textContent = `${formatTime(task.time_spent)} / ${formatTime(task.time_goal)}`
        }
    }

    function toggleTaskCompletion(taskId) {
        const taskIndex = tasks.findIndex((t) => t.id === taskId)
        if (taskIndex !== -1) {
            // Toggle completion status
            tasks[taskIndex].completed = !tasks[taskIndex].completed

            // If task is now completed and it was the active task, stop the timer
            if (tasks[taskIndex].completed && taskId === activeTaskId) {
                clearInterval(activeTimer)
                activeTaskId = null
                activeTimer = null
            }

            saveData()
            renderTasks()
            updateStats()
            checkBadges()
        }
    }

    function deleteTask(taskId) {
        // If this is the active task, stop the timer
        if (taskId === activeTaskId) {
            clearInterval(activeTimer)
            activeTaskId = null
            activeTimer = null
        }

        // Remove task from array
        tasks = tasks.filter((t) => t.id !== taskId)

        saveData()
        renderTasks()
        updateStats()
    }

    function updateStats() {
        // Calculate total time tracked
        const totalMinutes = tasks.reduce((total, task) => total + task.time_spent, 0)
        totalTimeTrackedEl.textContent = formatTime(totalMinutes)

        // Calculate tasks completed
        const completedTasks = tasks.filter((task) => task.completed).length
        tasksCompletedEl.textContent = `${completedTasks}/${tasks.length}`

        // Calculate badges earned
        badgesEarnedEl.textContent = badges.length

        // Calculate goal completion percentage
        let goalCompletionPercentage = 0
        if (tasks.length > 0) {
            const totalProgress = tasks.reduce((sum, task) => {
                return sum + Math.min(task.time_spent / task.time_goal, 1)
            }, 0)
            goalCompletionPercentage = Math.round((totalProgress / tasks.length) * 100)
        }
        // goalCompletionEl.textContent = `${goalCompletionPercentage}%`
    }

    function checkBadges() {
        badgeDefinitions.forEach((badgeDef) => {
            // Check if badge is already earned
            if (badges.some((b) => b.id === badgeDef.id)) {
                return
            }

            // Check if condition is met
            if (badgeDef.condition(tasks)) {
                // Add badge
                const newBadge = {
                    id: badgeDef.id,
                    name: badgeDef.name,
                    description: badgeDef.description,
                    icon: badgeDef.icon,
                    earnedAt: new Date().toISOString(),
                }

                badges.push(newBadge)
                saveData()
                renderBadges()
                showAchievementNotification(newBadge)
            }
        })
    }

    function renderBadges() {
        badgesContainer.innerHTML = ""

        if (badges.length === 0) {
            badgesContainer.innerHTML = `
                  <div class="empty-state">
                      <span class="material-symbols-rounded">emoji_events</span>
                      <p>Complete tasks to earn badges and achievements.</p>
                  </div>
              `
            return
        }

        // Sort badges by most recently earned
        const sortedBadges = [...badges].sort((a, b) => {
            return new Date(b.earnedAt) - new Date(a.earnedAt)
        })

        // Show only the 5 most recent badges
        const recentBadges = sortedBadges.slice(0, 5)

        recentBadges.forEach((badge) => {
            const badgeElement = document.createElement("div")
            badgeElement.className = "badge-card"
            badgeElement.innerHTML = `
                  <div class="badge-icon">${badge.icon}</div>
                  <h4 class="badge-name">${badge.name}</h4>
                  <p class="badge-description">${badge.description}</p>
              `
            badgesContainer.appendChild(badgeElement)
        })
    }


    function showAchievementNotification(badge) {
        achievementName.textContent = badge.name
        achievementNotification.classList.add("show")

        setTimeout(() => {
            achievementNotification.classList.remove("show")
        }, 5000)
    }

    function formatTime(minutes) {
        const hours = Math.floor(minutes / 60)
        const mins = minutes % 60

        if (hours === 0) {
            return `${mins}m`
        } else if (mins === 0) {
            return `${hours}h`
        } else {
            return `${hours}h ${mins}m`
        }
    }

    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1)
    }

    function saveData() {
        localStorage.setItem("tasks", JSON.stringify(tasks))
        localStorage.setItem("badges", JSON.stringify(badges))

        // Also send to server (PHP backend)
        // sendDataToServer()
    }

    function loadData() {
        // Try to load from localStorage first
        const savedTasks = localStorage.getItem("tasks")
        const savedBadges = localStorage.getItem("badges")

        if (savedTasks) {
            tasks = JSON.parse(savedTasks)
        }

        if (savedBadges) {
            badges = JSON.parse(savedBadges)
        }


        // Render UI
        renderTasks()
        renderBadges()
        updateStats()
    }

    // function sendDataToServer() {
    //     // Send data to PHP backend
    //     fetch("api.php", {
    //         method: "POST",
    //         headers: {
    //             "Content-Type": "application/json",
    //         },
    //         body: JSON.stringify({
    //             tasks: tasks,
    //             badges: badges,
    //         }),
    //     })
    //         .then((response) => response.json())
    //         .then((data) => {
    //             console.log("Data saved to server:", data)
    //         })
    //         .catch((error) => {
    //             console.error("Error saving data to server:", error)
    //         })
    // }


})
