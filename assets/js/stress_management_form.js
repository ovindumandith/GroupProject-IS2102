document.addEventListener("DOMContentLoaded", function () {
  const questions = document.querySelectorAll(".question-box");
  const nextButton = document.getElementById("next-question");
  const submitButton = document.getElementById("submit-questionnaire");
  const questionImage = document.getElementById("question-image");
  const progressBar = document.getElementById("progress-bar");
  const questionCountText = document.getElementById("question-count");

  const images = [
    "../../../assets/images/sleep.jpg", // Image for sleep question
    "../../../assets/images/exercise.jpg", // Image for exercise question
    "../../../assets/images/workload.jpg", // Image for workload question
    "../../../assets/images/mood.jpg", // Image for mood question
    
  ];
  const thankYouImage = "../../../assets/images/thankyou.jpg"; // Thank you image

  let currentQuestionIndex = 0;

  // Show the first question and progress
  questions[currentQuestionIndex].style.display = "flex";
  questionImage.src = images[currentQuestionIndex];
  updateProgress();

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

    const value = parseFloat(inputField.value);
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
      updateProgress();
    } else {
      // Show thank you message and image
      nextButton.style.display = "none"; // Hide the Next button
      submitButton.style.display = "inline-block"; // Show Submit button
      questionImage.src = thankYouImage; // Change to thank you image
      questionCountText.textContent =
        "Thank you for completing the questionnaire!";
      progressBar.style.width = "100%"; // Set progress bar to 100%
    }
  });

  // Update the progress bar and question count
  function updateProgress() {
    const progress = ((currentQuestionIndex + 1) / questions.length) * 100;
    progressBar.style.width = `${progress}%`;
    questionCountText.textContent = `Question ${currentQuestionIndex + 1} of ${
      questions.length
    }`;
  }
});
