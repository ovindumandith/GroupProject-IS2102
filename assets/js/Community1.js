document.addEventListener('DOMContentLoaded', () => {
  const likeButtons = document.querySelectorAll('.like-btn');

  likeButtons.forEach(button => {
    button.addEventListener('click', () => {
      let likes = parseInt(button.textContent.match(/\d+/)[0]);
      button.textContent = `❤️ ${likes + 1} Likes`;
    });
  });

  // Add more event listeners for comments and shares if needed
});
