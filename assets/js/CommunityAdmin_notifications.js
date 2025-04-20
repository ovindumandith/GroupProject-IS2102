
    const searchInput = document.getElementById("searchInput");
    const table = document.getElementById("notificationsTable").getElementsByTagName('tbody')[0];

    searchInput.addEventListener("keyup", function() {
      const filter = this.value.toLowerCase();
      const rows = table.getElementsByTagName("tr");

      for (let i = 0; i < rows.length; i++) {
        const text = rows[i].innerText.toLowerCase();
        rows[i].style.display = text.includes(filter) ? "" : "none";
      }
    });