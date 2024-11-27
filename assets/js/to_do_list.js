document.addEventListener("DOMContentLoaded", () => {
  const filterButtons = document.querySelectorAll(".filter-button");
  const eventCards = document.querySelectorAll(".my-tasks-list-card");

  // Default: Show "Today" events
  filterEvents("today");

  filterButtons.forEach((button) => {
    button.addEventListener("click", () => {
      // Remove "active" class from all buttons
      filterButtons.forEach((btn) => btn.classList.remove("active"));

      // Add "active" class to the clicked button
      button.classList.add("active");

      // Get the filter category
      const filter = button.getAttribute("data-filter");

      // Filter events based on the category
      filterEvents(filter);
    });
  });

  function filterEvents(category) {
    eventCards.forEach((card) => {
      // Show cards matching the category, hide others
      if (card.getAttribute("data-category") === category) {
        card.style.display = "block";
      } else {
        card.style.display = "none";
      }
    });
  }
});


// Function to open the popup when the page loads (if not already visible)
window.onload = function() {
  const popup = document.getElementById("task-popup");
  if (popup) {
      popup.style.display = "block"; // Show the popup when the page loads
  }

  // Close the popup when the "X" button is clicked
  const closeButton = document.getElementById("close-btn");
  if (closeButton) {
      closeButton.addEventListener("click", function() {
        window.location.href = "list1.html"; 
      });
  }

  // Close the popup if clicked outside the popup content
  window.addEventListener("click", function(event) {
      if (event.target === popup) {
          popup.style.display = "none";
      }
  });
};

// Function to handle form submission
const form = document.getElementById("add-task-form");
if (form) {
  form.addEventListener("submit", function(event) {
      event.preventDefault();

      // Get form values
      const taskTitle = document.getElementById("task-title").value;
      const taskDescription = document.getElementById("task-description").value;
      const taskDueDate = document.getElementById("task-due-date").value;
      const taskPriority = document.getElementById("task-priority").value;
      const taskCategory = document.getElementById("task-category").value;
      const taskStatus = document.getElementById("task-status").value;

      // Log form data (replace this with an actual API call or form processing)
      console.log({
          taskTitle,
          taskDescription,
          taskDueDate,
          taskPriority,
          taskCategory,
          taskStatus
      });

      // Optionally, close the popup and reset form
      const popup = document.getElementById("task-popup");
      if (popup) {
          popup.style.display = "none";
      }

      form.reset(); // Reset the form fields
  });
}
