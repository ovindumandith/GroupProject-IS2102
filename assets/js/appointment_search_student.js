/**
 * Student Appointments Search and Animation Functionality
 */
document.addEventListener("DOMContentLoaded", function () {
  // References to elements
  const searchInput = document.getElementById("search-bar");
  const tableRows = document.querySelectorAll("#appointment-results tbody tr");

  // Add animation delay to table rows
  tableRows.forEach((row, index) => {
    row.style.setProperty("--row-index", index);
  });

  // Add status class to cells for styling
  addStatusClasses();

  // Apply search functionality
  if (searchInput) {
    searchInput.addEventListener("input", filterAppointments);

    // Add focus animation
    searchInput.addEventListener("focus", function () {
      this.placeholder = "Search by counselor, topic, or status...";
    });

    searchInput.addEventListener("blur", function () {
      this.placeholder = "🔍 Search...";
    });
  }

  /**
   * Filter appointments based on search input
   */
  function filterAppointments() {
    const searchTerm = searchInput.value.toLowerCase();
    let hasResults = false;

    tableRows.forEach((row) => {
      const appointmentDate = row.cells[0].textContent.toLowerCase();
      const counselor = row.cells[1].textContent.toLowerCase();
      const topic = row.cells[2].textContent.toLowerCase();
      const status = row.cells[3].textContent.toLowerCase();

      if (
        appointmentDate.includes(searchTerm) ||
        counselor.includes(searchTerm) ||
        topic.includes(searchTerm) ||
        status.includes(searchTerm)
      ) {
        row.style.display = "";
        hasResults = true;

        // Reset and re-apply animation
        row.style.animation = "none";
        row.offsetHeight; // Trigger reflow
        row.style.animation = null;
      } else {
        row.style.display = "none";
      }
    });

    // Show "no results" message if no matches
    const noResultsMessage = document.querySelector(".no-results-message");

    if (!hasResults && tableRows.length > 0) {
      if (!noResultsMessage) {
        const resultsContainer = document.getElementById("appointment-results");
        const table = resultsContainer.querySelector("table");

        if (table) {
          const message = document.createElement("p");
          message.className = "student-appointments no-results-message";
          message.textContent = "No appointments match your search criteria.";

          // Insert after table
          table.insertAdjacentElement("afterend", message);
        }
      } else {
        noResultsMessage.style.display = "block";
      }
    } else if (noResultsMessage) {
      noResultsMessage.style.display = "none";
    }
  }

  /**
   * Add status classes to cells for styling
   */
  function addStatusClasses() {
    tableRows.forEach((row) => {
      const statusCell = row.cells[3];
      const status = statusCell.textContent.trim();

      statusCell.classList.add(`status-${status.toLowerCase()}`);
    });
  }
});
