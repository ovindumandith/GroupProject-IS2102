// JavaScript for student appointment search functionality

document.addEventListener("DOMContentLoaded", function () {
  // Get the search input
  const searchInput = document.getElementById("search-bar");

  // Initialize search on input
  if (searchInput) {
    searchInput.addEventListener("input", performSearch);
  }

  // Function to perform the search
  function performSearch() {
    const searchTerm = searchInput.value.toLowerCase();
    const table = document.querySelector("table");

    // If there's no table, exit the function
    if (!table) return;

    const rows = table.querySelectorAll("tbody tr");

    // Loop through all table rows, and hide those that don't match the search query
    rows.forEach((row) => {
      let found = false;
      const cells = row.querySelectorAll("td");

      cells.forEach((cell) => {
        if (cell.textContent.toLowerCase().includes(searchTerm)) {
          found = true;
        }
      });

      // Toggle row visibility based on search match
      if (found) {
        row.style.display = "";
      } else {
        row.style.display = "none";
      }
    });

    // Show a message when no results are found
    const noResultsMsg = document.getElementById("no-results-message");
    const visibleRows = Array.from(rows).filter(
      (row) => row.style.display !== "none"
    );

    if (visibleRows.length === 0 && searchTerm !== "") {
      // Create a "no results" message if it doesn't exist
      if (!noResultsMsg) {
        const resultsContainer = document.getElementById("appointment-results");
        const message = document.createElement("p");
        message.id = "no-results-message";
        message.className = "no-results";
        message.textContent = "No appointments match your search.";

        // Insert before or after the table depending on the structure
        if (table.parentNode === resultsContainer) {
          resultsContainer.appendChild(message);
        } else {
          resultsContainer.insertBefore(message, table.nextSibling);
        }
      } else {
        noResultsMsg.style.display = "block";
      }
    } else if (noResultsMsg) {
      // Hide the "no results" message if there are results or the search is empty
      noResultsMsg.style.display = "none";
    }
  }

  // Add data-label attributes to table cells for responsive design
  function addDataLabels() {
    const table = document.querySelector("table");
    if (!table) return;

    const headerCells = table.querySelectorAll("thead th");
    const headerLabels = Array.from(headerCells).map((th) =>
      th.textContent.trim()
    );

    const bodyRows = table.querySelectorAll("tbody tr");

    bodyRows.forEach((row) => {
      const cells = row.querySelectorAll("td");
      cells.forEach((cell, index) => {
        if (index < headerLabels.length) {
          cell.setAttribute("data-label", headerLabels[index]);
        }
      });
    });
  }

  // Call the function to add data labels
  addDataLabels();

  // Show success or error messages and auto-hide after a few seconds
  function handleMessages() {
    const successMsg = document.querySelector(".success-message");
    const errorMsg = document.querySelector(".error-message");

    if (successMsg || errorMsg) {
      setTimeout(() => {
        if (successMsg) successMsg.style.display = "none";
        if (errorMsg) errorMsg.style.display = "none";
      }, 5000); // Hide after 5 seconds
    }
  }

  // Call the function to handle messages
  handleMessages();
});
