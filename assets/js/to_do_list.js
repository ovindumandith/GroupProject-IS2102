let allTasks = [];
window.onload = async function () {
    getAllTask();
};
async function getAllTask() {
    const data = await fetchTaks();

    allTasks = data
    displayTasks(allTasks, "today")
    updateCounts();
}

// Function to fetch events from the server
async function fetchTaks() {
    try {
        // Fetch all events from the backend
        const response = await fetch('../controller/ToDoListController.php', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const tasks = await response.json(); // Parse the response as JSON
        return tasks; // Return the fetched events
    } catch (error) {
        console.error('Error fetching tasks:', error);
        return []; // Return an empty array if there's an error
    }
}

function displayTasks(tasks, filter) {
    const taskContainer = document.querySelector(".my-tasks-list");
    taskContainer.innerHTML = ""; // Clear old tasks

    const today = new Date().toISOString().split("T")[0]; // Get today's date in "YYYY-MM-DD" format

    let filteredTasks = tasks.filter(task => {
        if (filter === "today") return task.date === today;
        if (filter === "upcoming") return task.date > today;
        if (filter === "overdue") return task.date < today;
        return false;
    });

    filteredTasks.forEach(task => {
        const taskCard = document.createElement("div");
        taskCard.classList.add("my-tasks-list-card");
        taskCard.setAttribute("data-category", filter); // Set category dynamically

        taskCard.innerHTML = `
            <div class="checkbox-container">
                <input type="checkbox" id="task-done-${task.id}" class="custom-checkbox" onChange="updateTaskStatus(${task.id},this.checked)"/>
                <label for="task-done-${task.id}" class="custom-label"></label>
            </div>
            <div class="my-task-list-card-content">
                ${task.title}
                <div class="time-icon">
                    <i class="fa-solid fa-bell"></i>
                    <span>${task.date} - ${formatTime(task.time)}</span>
                </div>
            </div>
            <div class="button-container">
                <button class="edit-btn" onclick="openEditPopup(${task.id})">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="delete-btn" onclick="deleteTask(${task.id})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
        taskContainer.appendChild(taskCard);

    });
}

function updateCounts() {
    const today = new Date().toISOString().split("T")[0]; // Get today's date in YYYY-MM-DD format

    const todayTasks = allTasks.filter(task => task.date === today);
    const upcomingTasks = allTasks.filter(task => task.date > today);
    const overdueTasks = allTasks.filter(task => task.date < today);

    document.querySelector('[data-filter="today"] .count').textContent = todayTasks.length;
    document.querySelector('[data-filter="upcoming"] .count').textContent = upcomingTasks.length;
    document.querySelector('[data-filter="overdue"] .count').textContent = overdueTasks.length;
}
document.querySelectorAll(".filter-button").forEach(button => {

    button.addEventListener("click", function () {
        document.querySelectorAll(".filter-button").forEach(btn => btn.classList.remove("active"));
        this.classList.add("active");
        displayTasks(allTasks, this.dataset.filter);
    });
});
// Function to show the popup for adding a new event
function showPopup() {
    // Reset form to ensure no previous data is carried over
    document.getElementById('eventForm').reset();
    document.getElementById('taskId').value = ''; // Clear the hidden event ID field

    // Set the popup title and button text for adding a new event
    document.getElementById('popupTitle').textContent = 'Add Task';
    document.getElementById('formSubmitButton').textContent = 'Save Task';

    // Show the popup
    document.getElementById('eventPopup').style.display = 'block';
}

// Function to open the popup for editing an event with pre-filled data
function openEditPopup(taskId) {
    // Fetch event data from the server (or from a global variable, etc.)
    fetchTaskDetails(taskId).then(event => {
        // Populate form fields with existing event data
        document.getElementById('taskId').value = event.id; // Set the hidden event ID field
        document.getElementById('title').value = event.title;

        document.getElementById('date').value = event.date;
        document.getElementById('time').value = event.time;

        // Change the popup title and button text for editing an event
        document.getElementById('popupTitle').textContent = 'Edit Event';
        document.getElementById('formSubmitButton').textContent = 'Update Event';

        // Show the popup
        document.getElementById('eventPopup').style.display = 'block';
    });
}
async function fetchTaskDetails(taskId) {
    try {
        const response = await fetch(`../controller/ToDoListController.php?id=${taskId}`);

        // Check if the response is not an error (i.e., status 2xx)
        if (!response.ok) {
            throw new Error('Task not found or server error');
        }

        // Parse the JSON response
        const data = await response.json();
        return data; // Return the event data
    } catch (error) {
        console.error('Error fetching task details:', error);
        return null; // Return null in case of error
    }
}
// Function to close the popup
function closePopup() {
    document.getElementById('eventPopup').style.display = 'none';
}
function formatTime(timeString) {
    let [hours, minutes] = timeString.split(":");
    let period = hours >= 12 ? "PM" : "AM";
    hours = hours % 12 || 12; // Convert to 12-hour format, ensuring 12 stays 12
    return `${hours}:${minutes} ${period}`;
}
document.getElementById("eventForm").addEventListener("submit", async function (event) {
    event.preventDefault(); // Prevent default form submission

    const errorContainer = document.getElementById("errorContainer");
    errorContainer.innerHTML = ""; // Clear previous errors

    const eventData = {
        id: document.getElementById("taskId").value,
        title: document.getElementById("title").value,
        date: document.getElementById("date").value,
        time: document.getElementById("time").value,
    };

    const url = `../controller/ToDoListController.php`;

    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(eventData), // Format data for POST
        });

        const data = await response.json();

        // Handle errors if present
        if (data.errors) {
            displayErrors(data.errors);
        } else if (data.message) {
            // Only show success alert if there are no errors
            showAlert(data.message);
            document.getElementById("eventForm").reset(); // Reset form on success
            getAllTask();

        }
    } catch (error) {
        console.error('Error:', error);
    }
});
async function updateTaskStatus(taskId, isChecked) {
    const status = isChecked ? 1 : 0; // 1 for completed, 0 for not completed
    const url = `../controller/ToDoListController.php`;
    const taskData = {
        id: taskId,
        status: status,
       
    };
    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(taskData), // Format data for POST
        });

        const data = await response.json();

        // Handle errors if present
        if (data.errors) {
            displayErrors(data.errors);
        } else if (data.message) {
            // Only show success alert if there are no errors
            showAlert(data.message);
            document.getElementById("eventForm").reset(); // Reset form on success
            getAllTask();

        }
    } catch (error) {
        console.error('Error:', error);
    }
  
}
function displayErrors(errors) {
    const errorContainer = document.getElementById("errorContainer");

    errors.forEach((error) => {
        const errorMessage = `<p>${error}</p>`;
        errorContainer.innerHTML += errorMessage; // Display errors
    });
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

//delete event
function deleteTask(eventId) {
    if (!confirm("Are you sure you want to delete this task?")) {
        return; // Exit if the user cancels the confirmation
    }

    fetch('../controller/ToDoListController.php', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id: eventId }), // Pass the event ID
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                showAlert(data.message);
                getAllTask();
            } else {
                showAlert(data.message || 'Failed to delete the event.');

            }
        })
        .catch(error => {
            console.error('Error deleting event:', error);
        });
}
