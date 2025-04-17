
document.getElementById('search-bar').addEventListener('input', function () {
    const searchQuery = this.value.toLowerCase();

    // Get all appointment rows
    const rows = document.querySelectorAll('#appointment-results tbody tr');

    rows.forEach(row => {
        const counselorName = row.children[1].textContent.toLowerCase();
        const appointmentDate = row.children[0].textContent.toLowerCase();

        // Show or hide rows based on the search query
        if (counselorName.includes(searchQuery) || appointmentDate.includes(searchQuery)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

