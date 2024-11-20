document.addEventListener("DOMContentLoaded", function () {
  const questions = document.querySelectorAll(".question-box");
  const nextButton = document.getElementById("next-question");
  const submitButton = document.getElementById("submit-questionnaire");
  const questionImage = document.getElementById("question-image");
  const progressBar = document.getElementById("progress-bar");
  const questionCountText = document.getElementById("question-count");

  const images = [
    "../../../assets/images/sleep.jpg",
    "../../../assets/images/exercise.jpg",
    "../../../assets/images/workload.jpg",
    "../../../assets/images/mood.jpg",
  ];

  let currentQuestionIndex = 0;

  nextButton.addEventListener("click", () => {
    const currentQuestion = questions[currentQuestionIndex];
    const inputField = currentQuestion.querySelector("input");
    const minValue = parseInt(inputField.min, 10);
    const maxValue = parseInt(inputField.max, 10);

    // Validation
    if (inputField.value.trim() === "") {
      alert("This field is required. Please enter a value.");
      return;
    }

    const value = parseInt(inputField.value, 10);
    if (isNaN(value) || value < minValue || value > maxValue) {
      alert(`Please enter a valid value between ${minValue} and ${maxValue}.`);
      return;
    }

    // Hide current question and move to the next
    currentQuestion.style.display = "none";
    currentQuestionIndex++;

    if (currentQuestionIndex < questions.length) {
      questions[currentQuestionIndex].style.display = "flex";
      questionImage.src = images[currentQuestionIndex];

      // Update progress
      const progress = ((currentQuestionIndex + 1) / questions.length) * 100;
      progressBar.style.width = `${progress}%`;
      questionCountText.textContent = `Question ${
        currentQuestionIndex + 1
      } of ${questions.length}`;
    } else {
      // All questions answered, show submit button
      nextButton.style.display = "none";
      submitButton.style.display = "block";
      questionCountText.textContent = "Ready to submit your answers.";
      progressBar.style.width = "100%";
    }
  });
});
