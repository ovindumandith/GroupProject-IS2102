let events = [];
window.onload = async function () {
    // Fetch events from the server
    const events = await fetchEvents();
    setToday()
    // Generate the calendar with the fetched events
    generateCalendar(events);
};
function setToday() {

    const today = new Date();

    const dayOfMonth = today.getDate();
    const month = today.getMonth(); // January is 0, December is 11
    const year = today.getFullYear();
    const dayName = today.toLocaleString('en-US', { weekday: 'short' }); // Gets the short day name (e.g., "Mon", "Tue")

    // Set the date and the full month-year format
    document.getElementById('current-day').textContent = dayOfMonth;
    document.getElementById('current-day-name').textContent = dayName;
    document.getElementById('current-month-year').textContent = `${new Intl.DateTimeFormat('en-US', { month: 'short' }).format(today)} ${year}`;

    // Highlight the current day in the week navigation
    const weekNav = document.getElementById('week-navigation');
    const days = weekNav.querySelectorAll('span');
    const daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

    // Remove previous active class
    days.forEach(day => day.classList.remove('active'));

    // Get the index for the current day and add the 'active' class
    const currentDayIndex = today.getDay(); // 0 = Sunday, 1 = Monday, ..., 6 = Saturday

    days[currentDayIndex].classList.add('active');

    fetchEventsForDay(today);
}
function fetchEventsForDay(date) {

    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0'); // Add 1 because getMonth() is zero-based
    const day = String(date.getDate()).padStart(2, '0');
    const formattedDate = `${year}-${month}-${day}`; // Combine components

    // API call to fetch events for the given day
    fetch(`../controller/ScheduleEventController.php?date=${formattedDate}`)
        .then(response => response.text()) // Read response as text first
        .then(data => {
            // Log raw response to see if it's HTML or an error message
            try {
                const jsonData = JSON.parse(data); // Parse the response as JSON
                events = jsonData;

                console.log('Events received:', jsonData);
                displayEvents(jsonData); // Process the data if it's valid JSON
            } catch (error) {
                console.error('Error parsing JSON:', error);
            }
        })
        .catch(error => {
            console.error('Error fetching events:', error);
        });
}
// Function to fetch events from the server
async function fetchEvents() {
    try {
        // Fetch all events from the backend
        const response = await fetch('../controller/ScheduleEventController.php', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const events = await response.json(); // Parse the response as JSON
        return events; // Return the fetched events
    } catch (error) {
        console.error('Error fetching events:', error);
        return []; // Return an empty array if there's an error
    }
}


// Assuming `events` is the array of events fetched from your server with 'date' as 'YYYY-MM-DD'

// Function to generate the calendar and highlight the dates with events
function generateCalendar(events) {
    const calendar = document.getElementById('calendar');
    const monthYear = document.getElementById('monthYear');
    let date = new Date(); // Initialize a new Date object

    // Ensure `date` is a valid Date object (just a sanity check)
    if (!(date instanceof Date) || isNaN(date)) {
        date = new Date(); // Fallback to the current date if invalid
    }

    // Set the current month and year
    const month = date.getMonth();
    const year = date.getFullYear();
    monthYear.innerText = `${date.toLocaleString('default', { month: 'long' })} ${year}`;

    // Get the first day of the month and the total days in the month
    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    // Clear the previous calendar
    calendar.innerHTML = '';

    // Add the week day names
    const weekDays = ['S', 'M', 'T', 'W', 'T', 'F', 'S'];
    const headerRow = document.createElement('tr');
    weekDays.forEach(day => {
        const headerCell = document.createElement('th');
        headerCell.innerText = day;
        headerRow.appendChild(headerCell);
    });
    calendar.appendChild(headerRow);

    // Create table rows for the calendar
    let row = document.createElement('tr');

    // Add empty cells for days before the first day of the month
    for (let i = 0; i < firstDay; i++) {
        const emptyCell = document.createElement('td');
        row.appendChild(emptyCell);
    }

    // Add cells for each day of the month
    for (let day = 1; day <= daysInMonth; day++) {
        const cell = document.createElement('td');
        cell.innerText = day;

        // Format the date as YYYY-MM-DD to compare with the events
        const formattedDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

        // Check if this day has any events
        const dayEvents = events.filter(event => event.date === formattedDate);

        // If there are events on this day, highlight it with a dot
        if (dayEvents.length > 0) {
            const dot = document.createElement('span');
            dot.classList.add('event-dot'); // Add a dot to indicate events
            cell.classList.add('has-event'); // Optionally style the cell
            cell.appendChild(dot);
        }

        // Add a data-date attribute for click handling
        cell.dataset.date = formattedDate;

        // Append the cell to the current row
        row.appendChild(cell);

        // Start a new row after Saturday (6th day)
        if ((firstDay + day) % 7 === 0) {
            calendar.appendChild(row);
            row = document.createElement('tr');
        }
    }

    // Append the last row if it's not complete
    if (row.children.length > 0) {
        calendar.appendChild(row);
    }
}






// Function to show the popup for adding a new event
function showPopup() {
    // Reset form to ensure no previous data is carried over
    document.getElementById('eventForm').reset();
    document.getElementById('eventId').value = ''; // Clear the hidden event ID field

    // Set the popup title and button text for adding a new event
    document.getElementById('popupTitle').textContent = 'Add Event';
    document.getElementById('formSubmitButton').textContent = 'Save Event';

    // Show the popup
    document.getElementById('eventPopup').style.display = 'block';
}

// Function to open the popup for editing an event with pre-filled data
function openEditPopup(eventId) {
    // Fetch event data from the server (or from a global variable, etc.)
    fetchEventDetails(eventId).then(event => {
        // Populate form fields with existing event data
        document.getElementById('eventId').value = event.id; // Set the hidden event ID field
        document.getElementById('title').value = event.title;
        document.getElementById('description').value = event.description;
        document.getElementById('date').value = event.date;
        document.getElementById('startTime').value = event.start_time;
        document.getElementById('endTime').value = event.end_time;

        // Change the popup title and button text for editing an event
        document.getElementById('popupTitle').textContent = 'Edit Event';
        document.getElementById('formSubmitButton').textContent = 'Update Event';

        // Show the popup
        document.getElementById('eventPopup').style.display = 'block';
    });
}

// Function to close the popup
function closePopup() {
    document.getElementById('eventPopup').style.display = 'none';
}

// Function to fetch event details by ID (you would implement this to get data 

function displayEvents(events) {
    const scheduleContainer = document.querySelector('.schedule');
    scheduleContainer.innerHTML = ''; // Clear previous events

    events.forEach(event => {
        const eventCard = document.createElement('div');
        eventCard.classList.add('course-card', 'math'); // Add appropriate classes

        eventCard.innerHTML = `
        <div class="time">${formatTime(event.start_time)} - ${formatTime(event.end_time)}</div>
        <div class="course-info">
            <h4>${event.title}</h4>
            <div class="details">${event.description}</div>
            <div class="button-container">
                <button class="edit-btn" onclick="openEditPopup(${event.id})">
                    <i class="fas fa-edit"></i> <!-- Font Awesome edit icon -->
                </button>
                <button class="delete-btn" onclick="deleteEvent(${event.id})">
                    <i class="fas fa-trash-alt"></i> <!-- Font Awesome trash icon -->
                </button>
            </div>
        </div>
    `;
    
    // Function to format time to HH:MM
    function formatTime(time) {
        // If time is a string in HH:MM:SS format (e.g., "12:12:00")
        return time.slice(0, 5);  // Extracts "HH:MM" from "HH:MM:SS"
    }
    

        scheduleContainer.appendChild(eventCard);
    });
}


async function fetchEventDetails(eventId) {
    try {
        const response = await fetch(`../controller/ScheduleEventController.php?id=${eventId}`);

        // Check if the response is not an error (i.e., status 2xx)
        if (!response.ok) {
            throw new Error('Event not found or server error');
        }

        // Parse the JSON response
        const data = await response.json();
        return data; // Return the event data
    } catch (error) {
        console.error('Error fetching event details:', error);
        return null; // Return null in case of error
    }
}

// // Event listener for form submission (optional, you can implement this for AJAX or form handling)
// document.getElementById('eventForm').addEventListener('submit', function(e) {
//     e.preventDefault();
//     // Process the form data here, such as sending it to the server
//     alert("Event Saved!");  // For demonstration
//     closePopup(); // Close the popup after saving
// });
// Function to set today's date and highlight the current day




// Call setToday when the page loads

document.getElementById('week-navigation').addEventListener('click', function (e) {
    if (e.target.tagName === 'SPAN') {
        // Remove active class from all days
        const days = document.querySelectorAll('#week-navigation span');
        days.forEach(day => day.classList.remove('active'));

        // Add active class to the clicked day
        e.target.classList.add('active');

        // Get the clicked day index and update the date header accordingly
        const clickedDayIndex = e.target.dataset.day;
        const today = new Date();

        // Calculate the day offset to navigate to the clicked day
        const dayOffset = clickedDayIndex - today.getDay(); // Get the offset to navigate to the clicked day

        const newDate = new Date(today);
        newDate.setDate(today.getDate() + dayOffset); // Set the new date

        // Update the date header
        const dayName = newDate.toLocaleString('default', { weekday: 'long' });
        const formattedDate = `${newDate.toLocaleString('default', { month: 'long' })} ${newDate.getDate()}, ${newDate.getFullYear()}`;
        document.getElementById('current-day').innerText = newDate.getDate();
        document.getElementById('current-day-name').innerText = dayName;
        document.getElementById('current-month-year').innerText = formattedDate;

        // Fetch events for the selected day
        fetchEventsForDay(newDate);
    }
});
//delete event
function deleteEvent(eventId) {
    if (!confirm("Are you sure you want to delete this event?")) {
        return; // Exit if the user cancels the confirmation
    }

    fetch('../controller/ScheduleEventController.php', {
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
                // Refresh or update the UI to reflect the deletion
                fetchEventsForDay(new Date()); // Reload events for the current day
            } else {
                showAlert(data.message || 'Failed to delete the event.');
            }
        })
        .catch(error => {
            console.error('Error deleting event:', error);
        });
}

function searchEvents() {
    const searchQuery = document.getElementById('searchInput').value.toLowerCase();

    // Construct the URL with query parameters
    const url = `../controller/ScheduleEventController.php?search=${encodeURIComponent(searchQuery)}`;

    // Fetch all events from the backend
    fetch(url, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => response.json())
        .then(events => {
            displayEvents(events);
        })
        .catch(error => {
            console.error('Error fetching events:', error);
        });
}


document.getElementById('calendar').addEventListener('click', (event) => {
    const clickedCell = event.target.closest('td');

    if (clickedCell && clickedCell.dataset.date) {
        const selectedDate = new Date(clickedCell.dataset.date + 'T00:00:00'); // Add time to ensure it's treated as local time
        fetchEventsForDay(selectedDate);
        syncWeekNavigationWithDate(selectedDate);

    }
});
function syncWeekNavigationWithDate(date) {
    // Get the clicked day index (0-6 for Sunday to Saturday)
    const clickedDayIndex = date.getDay();

    // Remove active class from all days
    const days = document.querySelectorAll('#week-navigation span');
    days.forEach(day => day.classList.remove('active'));

    // Add active class to the corresponding day in the week navigation
    const targetDay = document.querySelector(`#week-navigation span[data-day="${clickedDayIndex}"]`);
    if (targetDay) {
        targetDay.classList.add('active');
    }

    // Update the date header
    const dayName = date.toLocaleString('default', { weekday: 'long' });
    const formattedDate = `${date.toLocaleString('default', { month: 'long' })} ${date.getDate()}, ${date.getFullYear()}`;
    document.getElementById('current-day').innerText = date.getDate();
    document.getElementById('current-day-name').innerText = dayName;
    document.getElementById('current-month-year').innerText = formattedDate;
}
document.getElementById("eventForm").addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent default form submission

    const errorContainer = document.getElementById("errorContainer");
    errorContainer.innerHTML = ""; // Clear previous errors

    const eventData = {
        id: document.getElementById("eventId").value,
        title: document.getElementById("title").value,
        description: document.getElementById("description").value,
        date: document.getElementById("date").value,
        startTime: document.getElementById("startTime").value,
        endTime: document.getElementById("endTime").value,
    };

    const url = `../controller/ScheduleEventController.php`;

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams(eventData), // Format data for POST
    })
        .then((response) => response.json())
        .then((data) => {
            // Handle errors if present
            if (data.errors) {
                displayErrors(data.errors);
            } else if (data.message) {
                // Only show success alert if there are no errors
                showAlert(data.message);
                document.getElementById("eventForm").reset(); // Reset form on success
            }
        })
        .catch((error) => {
            console.error('Error:', error);
        });
});

function displayErrors(errors) {
    const errorContainer = document.getElementById("errorContainer");

    errors.forEach((error) => {
        const errorMessage = `<p>${error}</p>`;
        errorContainer.innerHTML += errorMessage; // Display errors
    });
}


// Function to format time to HH:MM
function formatTime(time) {
    // If time is a string in HH:MM:SS format (e.g., "12:12:00")
    return time.slice(0, 5);  // Extracts "HH:MM" from "HH:MM:SS"
}
function displayErrors(errors) {
    const errorContainer = document.getElementById("errorContainer");

    // Loop through each error and display them
    for (const [field, message] of Object.entries(errors)) {
        const errorMessage = `<p><strong>${message}</p>`;
        errorContainer.innerHTML += errorMessage; // Append error messages to container
    }
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
