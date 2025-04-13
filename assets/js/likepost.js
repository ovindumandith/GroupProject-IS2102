document.addEventListener('DOMContentLoaded', () => {
  // Get all buttons whose id starts with "likeButton"
  const buttons = document.querySelectorAll('[id^="likeButton"]'); 

  buttons.forEach(button => {
    // Get the unique ID for the button (likeButton1, likeButton2, etc.)
    const buttonId = button.id;

    // Check if there's a saved like count in localStorage for this button
    const savedLikes = localStorage.getItem(buttonId);
    if (savedLikes) {
      button.textContent = `❤️ ${savedLikes} Likes`; // Update the button with the saved count
    } else {
      button.textContent = `❤️ 0 Likes`; // Default to 0 likes if nothing is saved
    }

    // Set initial liked status to false
    button.dataset.liked = "false"; 

    // Add event listener for the like button
    button.addEventListener('click', () => {
      if (button.dataset.liked === "false") {
        // Extract the current like count from the button text
        let likes = parseInt(button.textContent.replace(/\D/g, '')); // Remove non-digit characters

        // Increment the like count
        likes += 1;
        button.textContent = `❤️ ${likes} Likes`;

        // Save the updated like count in localStorage
        localStorage.setItem(buttonId, likes);

        // Mark the post as liked to prevent multiple likes
        button.dataset.liked = "true"; 
      } else {
        alert("You already liked this.");
      }
    });
  });
});
