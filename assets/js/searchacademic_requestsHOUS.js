// Function to filter table rows based on search input
function filterTable() {
  const searchBar = document.getElementById("searchBar");
  const filter = searchBar.value.toLowerCase(); // Get the search term and convert to lowercase
  const tableRows = document.querySelectorAll(".questions-table tbody tr");

  tableRows.forEach((row) => {
    const studentName = row
      .querySelector(".student-name")
      .textContent.toLowerCase(); // Get the student name from the row
    if (studentName.includes(filter)) {
      row.style.display = ""; // Show the row if it matches the search term
    } else {
      row.style.display = "none"; // Hide the row if it doesn't match
    }
  });
}

// Add event listener to the search bar
document.getElementById("searchBar").addEventListener("input", filterTable);

// Add event listener to the search icon
document.querySelector(".search-icon").addEventListener("click", () => {
  filterTable(); // Trigger search when the icon is clicked
});
