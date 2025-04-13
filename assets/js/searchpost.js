
    document.addEventListener("DOMContentLoaded", function () {
      const searchInput = document.getElementById("searchBox");
      const posts = document.querySelectorAll(".post");

      searchInput.addEventListener("input", function () {
        const query = searchInput.value.trim().toLowerCase();

        posts.forEach(post => {
          const title = post.querySelector("h3").textContent.toLowerCase();
          if (title.includes(query)) {
            post.style.display = "block";
          } else {
            post.style.display = "none";
          }
        });
      });
    });
