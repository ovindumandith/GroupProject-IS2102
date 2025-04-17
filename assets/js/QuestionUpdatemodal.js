function openModal(questionId, questionText) {
  document.getElementById("question_id").value = questionId;
  document.getElementById("updated_question").value = questionText;
  document.getElementById("updateModal").style.display = "flex";
}

function closeModal() {
  document.getElementById("updateModal").style.display = "none";
}
