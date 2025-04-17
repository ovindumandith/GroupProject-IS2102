document.addEventListener("DOMContentLoaded", function () {
  const stressForm = document.getElementById("stress-assessment-form");

  // Validate form before submission
  stressForm.addEventListener("submit", function (event) {
    const section1Questions = [
      "section1_q1",
      "section1_q2",
      "section1_q3",
      "section1_q4",
      "section1_q5",
    ];

    const section2Questions = [
      "section2_q1",
      "section2_q2",
      "section2_q3",
      "section2_q4",
      "section2_q5",
    ];

    // Check if all questions in section 1 are answered
    const allSection1Answered = section1Questions.every((questionName) => {
      const radioButtons = document.querySelectorAll(
        `input[name="${questionName}"]:checked`
      );
      return radioButtons.length > 0;
    });

    // Check if all questions in section 2 are answered
    const allSection2Answered = section2Questions.every((questionName) => {
      const radioButtons = document.querySelectorAll(
        `input[name="${questionName}"]:checked`
      );
      return radioButtons.length > 0;
    });

    if (!allSection1Answered || !allSection2Answered) {
      event.preventDefault();

      // Create alert message
      const alertElement = document.createElement("div");
      alertElement.className = "alert alert-error";
      alertElement.textContent =
        "Please answer all questions before submitting the assessment.";

      // Insert alert at the top of the form
      const formContainer = document.querySelector(".container");
      const assessmentTitle = document.querySelector(".assessment-title");

      formContainer.insertBefore(alertElement, assessmentTitle.nextSibling);

      // Scroll to the top of the form
      window.scrollTo({
        top: formContainer.offsetTop - 100,
        behavior: "smooth",
      });

      // Highlight unanswered questions
      const allQuestions = [...section1Questions, ...section2Questions];

      allQuestions.forEach((questionName) => {
        const radioButtons = document.querySelectorAll(
          `input[name="${questionName}"]:checked`
        );

        if (radioButtons.length === 0) {
          const questionLabel =
            document.querySelector(`label[for="${questionName}"]`) ||
            document.querySelector(`label[for="${questionName}_0"]`);

          if (questionLabel) {
            const questionItem = questionLabel.closest(".question-item");
            if (questionItem) {
              questionItem.style.backgroundColor = "#ffebee";
              questionItem.style.padding = "10px";
              questionItem.style.borderRadius = "5px";
            }
          }
        }
      });

      // Remove highlighting after 5 seconds
      setTimeout(() => {
        allQuestions.forEach((questionName) => {
          const questionLabel =
            document.querySelector(`label[for="${questionName}"]`) ||
            document.querySelector(`label[for="${questionName}_0"]`);

          if (questionLabel) {
            const questionItem = questionLabel.closest(".question-item");
            if (questionItem) {
              questionItem.style.backgroundColor = "transparent";
              questionItem.style.padding = "0";
            }
          }
        });
      }, 5000);
    }
  });

  // Add smooth scrolling between sections
  const section1 = document.querySelector(".form-section:nth-child(1)");
  const section2 = document.querySelector(".form-section:nth-child(2)");

  if (section1 && section2) {
    // Create a "Next Section" button at the end of section 1
    const nextSectionBtn = document.createElement("button");
    nextSectionBtn.type = "button";
    nextSectionBtn.className = "submit-btn";
    nextSectionBtn.style.marginTop = "20px";
    nextSectionBtn.textContent = "Next Section →";

    section1.appendChild(nextSectionBtn);

    nextSectionBtn.addEventListener("click", function () {
      // Smooth scroll to section 2
      window.scrollTo({
        top: section2.offsetTop - 100,
        behavior: "smooth",
      });
    });
  }

  // Add visual feedback when selecting radio options
  const radioButtons = document.querySelectorAll('input[type="radio"]');

  radioButtons.forEach((radioButton) => {
    radioButton.addEventListener("change", function () {
      // Remove active class from all options in the same group
      const groupName = this.name;
      const sameGroupOptions = document.querySelectorAll(
        `input[name="${groupName}"]`
      );

      sameGroupOptions.forEach((option) => {
        const optionContainer = option.closest(".rating-option");
        if (optionContainer) {
          optionContainer.classList.remove("active");
        }
      });

      // Add active class to the selected option
      const selectedOptionContainer = this.closest(".rating-option");
      if (selectedOptionContainer) {
        selectedOptionContainer.classList.add("active");
      }
    });
  });

  // Add active class styling
  const styleElement = document.createElement("style");
  styleElement.textContent = `
        .rating-option.active {
            background-color: #e3f2fd;
            padding: 5px 10px;
            border-radius: 5px;
            border: 1px solid #bbdefb;
            transition: all 0.3s ease;
        }
    `;

  document.head.appendChild(styleElement);
});
