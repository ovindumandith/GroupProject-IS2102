/**
 * Student Academic Questions Search and Animation Functionality
 */
document.addEventListener("DOMContentLoaded", function () {
  // References to elements
  const searchInput = document.getElementById("search-bar");
  const tableRows = document.querySelectorAll("tbody tr");

  // Add animation delay to table rows
  tableRows.forEach((row, index) => {
    row.style.setProperty("--row-index", index);
  });

  // Apply status colors
  applyStatusColors();

  // Apply search functionality
  if (searchInput) {
    searchInput.addEventListener("input", filterQuestions);

    // Add focus animation
    searchInput.addEventListener("focus", function () {
      this.placeholder = "Search by name, index, question, or status...";
    });

    searchInput.addEventListener("blur", function () {
      this.placeholder = "🔍 Search...";
    });
  } else {
    console.error("Search input element not found");
  }

  /**
   * Filter questions based on search input
   */
  function filterQuestions() {
    const searchTerm = searchInput.value.toLowerCase();
    let hasResults = false;

    tableRows.forEach((row) => {
      // Check if row has enough cells
      if (row.cells.length >= 6) {
        const indexNo = row.cells[0].textContent.toLowerCase();
        const regNo = row.cells[1].textContent.toLowerCase();
        const fullName = row.cells[2].textContent.toLowerCase();
        const faculty = row.cells[3].textContent.toLowerCase();
        const question = row.cells[4].textContent.toLowerCase();
        const status = row.cells[5].textContent.toLowerCase();

        if (
          indexNo.includes(searchTerm) ||
          regNo.includes(searchTerm) ||
          fullName.includes(searchTerm) ||
          faculty.includes(searchTerm) ||
          question.includes(searchTerm) ||
          status.includes(searchTerm)
        ) {
          row.style.display = "";
          hasResults = true;
        } else {
          row.style.display = "none";
        }
      }
    });

    // Show "no results" message if no matches
    checkNoResults(hasResults);
  }

  /**
   * Check if no results are found and display a message
   */
  function checkNoResults(hasResults) {
    let noResultsMessage = document.querySelector(".no-results-message");

    if (!hasResults && tableRows.length > 0) {
      if (!noResultsMessage) {
        const table = document.querySelector("table");

        if (table) {
          noResultsMessage = document.createElement("p");
          noResultsMessage.className = "no-results-message";
          noResultsMessage.textContent =
            "No questions match your search criteria.";

          // Insert after table
          table.insertAdjacentElement("afterend", noResultsMessage);
        }
      } else {
        noResultsMessage.style.display = "block";
      }
    } else if (noResultsMessage) {
      noResultsMessage.style.display = "none";
    }
  }

  /**
   * Apply color styling to status cells based on their content
   */
  function applyStatusColors() {
    const statusCells = document.querySelectorAll("td:nth-child(6)");

    statusCells.forEach((cell) => {
      const status = cell.textContent.trim().toLowerCase();

      // Remove any existing classes
      cell.classList.remove(
        "status-pending",
        "status-progress",
        "status-resolved",
        "status-rejected"
      );

      // Add appropriate class and color
      if (status.includes("pending")) {
        cell.classList.add("status-pending");
        cell.style.color = "#f39c12"; // Orange
      } else if (status.includes("progress")) {
        cell.classList.add("status-progress");
        cell.style.color = "#3498db"; // Blue
      } else if (status.includes("resolved")) {
        cell.classList.add("status-resolved");
        cell.style.color = "#27ae60"; // Green
      } else if (status.includes("rejected")) {
        cell.classList.add("status-rejected");
        cell.style.color = "#e53935"; // Red
      }
    });
  }
});
