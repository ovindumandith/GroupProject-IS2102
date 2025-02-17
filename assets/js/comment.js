document.getElementById('toggleComments').addEventListener('click', function () {
  const commentSection = document.getElementById('commentSection');
  commentSection.classList.toggle('hidden');
});

document.getElementById('addComment').addEventListener('click', function () {
  const commentText = document.getElementById('newComment').value;
  if (commentText.trim()) {
    const commentsList = document.querySelector('.comments');
    const newComment = document.createElement('li');
    newComment.innerHTML = `<strong>You:</strong> ${commentText}`;
    commentsList.appendChild(newComment);
    document.getElementById('newComment').value = ''; // Clear the textarea
  } else {
    alert('Please enter a comment!');
  }
});
