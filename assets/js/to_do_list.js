document.addEventListener('DOMContentLoaded', (event) => {
  fetchTasks();
  var dragSrcEl = null;
  document.body.addEventListener("change", function (event) {
    if (event.target.classList.contains("task__checkbox")) {
        let taskId = event.target.closest(".task").dataset.taskId;
        if (event.target.checked) {
            moveTaskToDone(taskId);
        }
    }
});

document.getElementById("search-bar").addEventListener("input", function () {
  const query = this.value;

  // Avoid unnecessary calls if input is empty
  if (query.trim() === "") {
    document.getElementById("search-results").innerHTML = "";
    return;
  }

  // Make AJAX call to PHP backend
  fetch(`../controller/ToDoListController.php?search=${encodeURIComponent(query)}`)
    .then(response => {
      // Check if the response is ok (status 200)
      if (!response.ok) {
        throw new Error('Network response was not ok: ' + response.status);
      }

      // Check if the response is JSON
      const contentType = response.headers.get('Content-Type');
      if (contentType && contentType.includes('application/json')) {
        return response.json(); // Parse JSON if valid
      } else {
        throw new Error('Expected JSON response but got: ' + contentType);
      }
    })
    .then(data => {
      const resultsDiv = document.getElementById("search-results");
      resultsDiv.innerHTML = "";

      if (data.tasks && data.tasks.length > 0) {
        // Loop through tasks and display them
        data.tasks.forEach(task => {
          const li = document.createElement("li");
          li.innerHTML = `
            <span class="task-text">${task.title} - ${task.date}</span>
            <div class="task-buttons">
              <button style='color:#c4cad3' class="task__options" onclick="openEditPopup(${task.id})"><i class="fas fa-pencil-alt"></i></button>
              <button style='color:#c4cad3' class="task__options" onclick="deleteTask(${task.id})"><i class="fas fa-trash-alt"></i></button>
            </div>
          `;
          resultsDiv.appendChild(li);
        });
      } else {
        resultsDiv.innerHTML = "<li>No matching tasks found.</li>";
      }
    })
    .catch(error => {
      console.error('Error during fetch operation:', error);
      const resultsDiv = document.getElementById("search-results");
      resultsDiv.innerHTML = "<li>Error fetching data. Please try again later.</li>";
    });
});


  // function handleDragStart(e) {
  //   this.style.opacity = '0.1';
  //   this.style.border = '3px dashed #c4cad3';

  //   dragSrcEl = this;

  //   e.dataTransfer.effectAllowed = 'move';
  //   e.dataTransfer.setData('text/html', this.innerHTML);
  // }

  // function handleDragOver(e) {
  //   if (e.preventDefault) {
  //     e.preventDefault();
  //   }

  //   e.dataTransfer.dropEffect = 'move';

  //   return false;
  // }

  // function handleDragEnter(e) {
  //   this.classList.add('task-hover');
  // }

  // function handleDragLeave(e) {
  //   this.classList.remove('task-hover');
  // }

  // function handleDrop(e) {
  //   if (e.stopPropagation) {
  //     e.stopPropagation(); // stops the browser from redirecting.
  //   }

  //   if (dragSrcEl != this) {
  //     dragSrcEl.innerHTML = this.innerHTML;
  //     this.innerHTML = e.dataTransfer.getData('text/html');
  //   }

  //   return false;
  // }

  // function handleDragEnd(e) {
  //   this.style.opacity = '1';
  //   this.style.border = 0;

  //   items.forEach(function (item) {
  //     item.classList.remove('task-hover');
  //   });
  // }


  // let items = document.querySelectorAll('.task');
  // items.forEach(function (item) {
  //   item.addEventListener('dragstart', handleDragStart, false);
  //   item.addEventListener('dragenter', handleDragEnter, false);
  //   item.addEventListener('dragover', handleDragOver, false);
  //   item.addEventListener('dragleave', handleDragLeave, false);
  //   item.addEventListener('drop', handleDrop, false);
  //   item.addEventListener('dragend', handleDragEnd, false);
  // });
});

function moveTaskToDone(taskId) {
  // Find task element
  const taskElement = document.querySelector(`.task[data-task-id="${taskId}"]`);
  const doneSection = document.getElementById("done-tasks");

  if (!taskElement || !doneSection) {
      console.error("Error: Task element or 'Done' section not found.");
      return;
  }

  // Remove from its current section
  taskElement.remove();

  // Append to "Done" section
  doneSection.appendChild(taskElement);

  // Disable the checkbox to prevent further changes
  let checkbox = taskElement.querySelector(".task__checkbox");
  if (checkbox) checkbox.disabled = true;

  // Update backend
  updateTaskStatus(taskId, "completed");
}

function updateTaskStatus(taskId, status) {
  fetch("update_task_status.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id: taskId, status: status })
  })
  .then(response => response.json())
  .then(data => console.log("Task updated:", data))
  .catch(error => console.error("Error updating task:", error));
}
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
// Function to close the popup
function closePopup() {
  document.getElementById('eventPopup').style.display = 'none';
}

//form submit 
document.getElementById("eventForm").addEventListener("submit", async function (event) {
  event.preventDefault(); // Prevent default form submission

  const errorContainer = document.getElementById("errorContainer");
  errorContainer.innerHTML = ""; // Clear previous errors

  const eventData = {
    id: document.getElementById("taskId").value,
    title: document.getElementById("title").value,
    date: document.getElementById("date").value,
    description: document.getElementById("description").value,
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
      fetchTasks();
      document.getElementById("eventForm").reset(); // Reset form on success
      closePopup();

    }
  } catch (error) {
    console.error('Error:', error);
  }
});

async function fetchTasks() {
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

    const data = await response.json(); // Parse JSON response

    const tasks = data.tasks || []; // Extract only tasks from response
    const taskHistories = data.taskHistories || []; // Extract only tasks from response

    // Categorize tasks
    const categorizedTasks = categorizeTasks(tasks);

    // Render tasks in respective columns
    renderTasks(categorizedTasks);
    fetchHistories(taskHistories);
  } catch (error) {
    console.error('Error fetching tasks:', error);
    return []; // Return an empty array if there's an error
  }
}


function categorizeTasks(tasks) {

  const today = new Date().toISOString().split("T")[0]; // Get today's date (YYYY-MM-DD)

  const categorized = {
    today: [],
    upcoming: [],
    overdue: [],
    done: []
  };

  tasks.forEach(task => {
    const dueDate = task.date;
    let color = '';
    let status = '';

    if (task.is_completed === 1) {
      color = '#e8f1ec'; // Green
      status = 'completed';
      categorized.done.push({ ...task, color, status });
    } else if (dueDate === today) {
      color = '#e8f1ec';
      status = 'today'; // Blueish
      categorized.today.push({ ...task, color, status });
    } else if (dueDate > today) {
      color = 	'#e8f1ec';
      status = 'upcoming'; // Redish
      categorized.upcoming.push({ ...task, color, status });
    } else {
      color = '#e8f1ec'; 
      status = 'overdue'; // Blueish
      categorized.overdue.push({ ...task, color, status });
    }

    // You can update the task object directly here without reassigning `tasks`
    task.color = color;
    task.status = status;
  });

  return categorized;
}

function renderTasks(categorizedTasks) {
  const sections = {
    today: document.querySelector("#today-tasks"),
    upcoming: document.querySelector("#upcoming-tasks"),
    overdue: document.querySelector("#overdue-tasks"),
    done: document.querySelector("#done-tasks")
  };

  Object.keys(categorizedTasks).forEach(category => {
    sections[category].innerHTML = ""; // Clear previous content
    categorizedTasks[category].forEach(task => {
      sections[category].innerHTML += createTaskHTML(task);
    });
  });
}
function createTaskHTML(task) {
  return `
    <div class='task' draggable='true' data-task-id='${task.id}'>
      <div class='task__tags'>
          <span class='task__tag' style='background-color: ${task.color}'>${task.title}</span>
          <div class="task__options-container">
          ${task.status !== 'completed' ?`
              <button style='color:green' class='task__options' onclick="openEditPopup(${task.id})"><i class="fas fa-pencil"></i></button>
              <button style='color:green' class='task__options' onclick="deleteTask(${task.id})"><i class="fas fa-trash"></i></button>` : ''}
          </div>
      </div>
      <p>${task.description}</p>
      <div class='task__stats'>
          <span><time><i class="fas fa-flag"></i> ${task.date}</time></span>
          
      </div>
      ${task.status !== 'completed' ? `<input type="checkbox" class="task__checkbox">` : ''}
    </div>
  `;
}

// Function to open the popup for editing an event with pre-filled data
function openEditPopup(taskId) {
  // Fetch event data from the server (or from a global variable, etc.)
  fetchTaskDetails(taskId).then(event => {
    // Populate form fields with existing event data
    document.getElementById('taskId').value = event.id; // Set the hidden event ID field
    document.getElementById('title').value = event.title;

    document.getElementById('date').value = event.date;
    
    document.getElementById('description').value = event.description;

    // Change the popup title and button text for editing an event
    document.getElementById('popupTitle').textContent = 'Edit Task';
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

//<span><i class="fas fa-paperclip"></i>${task.attachments}</span>
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
        fetchTasks();
        showAlert(data.message);

      } else {
        showAlert(data.message || 'Failed to delete the event.');

      }
    })
    .catch(error => {
      console.error('Error deleting event:', error);
    });
}

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
function fetchHistories(taskHistories) { 
  const timeline = document.getElementById("timeline");

  if (!timeline) {
      console.error("Error: Element with ID 'timeline' not found.");
      return;
  }

  timeline.innerHTML = ""; // Clear existing items before adding new ones

  taskHistories.forEach(item => {
    let eventDiv = document.createElement("div");
    eventDiv.classList.add("event");
    let formattedDate = new Date(item.date).toLocaleDateString("en-US", { month: "2-digit", day: "2-digit" });
    eventDiv.innerHTML = `
        <span class="date">${formattedDate}</span>
        <div class="circle"></div>
        <div class="details">
            <strong>${item.title}</strong>
            <small>${item.description }</small>
        </div>
    `;

    timeline.appendChild(eventDiv);
});
}



function getIconClass(type) {
  switch (type) {
      case "attachment": return "fa-paperclip";
      case "Add Task": return "fa-floppy-disk";
      case "Update Task": return "fa-pencil-alt";
      default: return "fa-info-circle"; // Default icon
  }
}
function getIconTypeClass(action) {
  switch (action) {
      case "attachment": return "task-icon--attachment";
      case "Add Task": return "task-icon--comment";
      case "Update Task": return "task-icon--attachment";
      default: return "task-icon--default"; // Default class
  }
}
function formatDate(dateString) {
  const date = new Date(dateString.replace(" ", "T")); // Ensure correct parsing
  const options = { month: "short", day: "numeric", hour: "numeric", minute: "numeric", hour12: true };
  return date.toLocaleDateString("en-US", options);
}

