document.addEventListener('DOMContentLoaded', () => {
  const likeButtons = document.querySelectorAll('.like-btn');

  likeButtons.forEach(button => {
    button.addEventListener('click', () => {
      let likes = parseInt(button.textContent.match(/\d+/)[0]);
      button.textContent = `❤️ ${likes + 1} Likes`;
    });
  });

  // Comment functionality
  const commentInput = document.getElementById("comment-input");
  const addCommentButton = document.getElementById("add-comment-button");
  const commentsList = document.getElementById("comments-list");

  addCommentButton.addEventListener("click", () => {
    const commentText = commentInput.value.trim();
    if (commentText !== "") {
      const newComment = document.createElement("li");
      newComment.textContent = commentText;
      commentsList.appendChild(newComment);
      commentInput.value = ""; // Clear the input field
    } else {
      alert("Please write a comment before adding.");
    }
  });

  
});
