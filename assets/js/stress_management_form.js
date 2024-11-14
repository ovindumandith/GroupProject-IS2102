document.addEventListener("DOMContentLoaded", function () {
  const questions = document.querySelectorAll(".question-box");
  const nextButton = document.getElementById("next-question");
  const questionImage = document.getElementById("question-image");

  const images = [
    "../../assets/images/sleep.jpg", // Image for sleep question
    "../../assets/images/exercise.jpg", // Image for exercise question
    "../../assets/images/workload.jpg", // Image for workload question
  ];

  let currentQuestionIndex = 0;

  nextButton.addEventListener("click", () => {
    const currentQuestion = questions[currentQuestionIndex];
    const inputField = currentQuestion.querySelector("input");

    if (inputField.value.trim() === "") {
      alert("Please answer the question before proceeding.");
      return;
    }

    currentQuestion.style.display = "none";
    currentQuestionIndex++;

    if (currentQuestionIndex < questions.length) {
      questions[currentQuestionIndex].style.display = "flex";
      questionImage.src = images[currentQuestionIndex];
    } else {
      nextButton.textContent = "Submitted!";
      nextButton.disabled = true;
      alert("Thank you! Your responses have been recorded.");
    }
  });
});
