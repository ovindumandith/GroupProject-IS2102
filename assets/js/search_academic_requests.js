
function searchTable() {
    // Get the search query
    const input = document.getElementById("searchInput");
    const filter = input.value.toLowerCase();
    
    // Get the table and its rows
    const table = document.getElementById("requestTable");
    const rows = table.getElementsByTagName("tr");

    // Loop through the rows and hide those that don't match the search query
    for (let i = 1; i < rows.length; i++) { // Start from 1 to skip the header row
        const row = rows[i];
        const cells = row.getElementsByTagName("td");
        
        let match = false;
        for (let j = 0; j < cells.length; j++) {
            const cell = cells[j];
            if (cell) {
                if (cell.innerText.toLowerCase().indexOf(filter) > -1) {
                    match = true;
                    break; // No need to check other cells if one matches
                }
            }
        }
        
        // Display row if it matches, otherwise hide it
        if (match) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    }
}

