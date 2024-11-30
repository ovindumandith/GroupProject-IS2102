// Form Handling
const academicForm = document.getElementById("academicForm");
const formInputs = document.querySelectorAll("input, select, textarea");

// Add floating label effect
formInputs.forEach((input) => {
  input.addEventListener("focus", () => {
    input.parentElement.classList.add("focused");
  });

  input.addEventListener("blur", () => {
    if (!input.value) {
      input.parentElement.classList.remove("focused");
    }
  });
});

// Form validation and submission
academicForm.addEventListener("submit", function (e) {
  e.preventDefault();

  // Get form values
  const formData = {
    indexNo: document.getElementById("indexNo").value,
    regNo: document.getElementById("regNo").value,
    fullName: document.getElementById("fullName").value,
    faculty: document.getElementById("faculty").value,
    telephone: document.getElementById("telephone").value,
    email: document.getElementById("email").value,
    question: document.getElementById("question").value,
  };

  // Validate phone number
  const phoneRegex = /^\+?[\d\s-]{10,}$/;
  if (!phoneRegex.test(formData.telephone)) {
    alert("Please enter a valid phone number");
    return;
  }

  // Validate email
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(formData.email)) {
    alert("Please enter a valid email address");
    return;
  }

  // Show success message with animation
  const successMessage = document.createElement("div");
  successMessage.className = "success-message";
  successMessage.textContent = "Your question has been submitted successfully!";
  academicForm.appendChild(successMessage);

  // Add fade-in animation
  successMessage.style.animation = "fadeIn 0.5s ease-out";

  // Reset form with animation
  setTimeout(() => {
    academicForm.reset();
    successMessage.style.animation = "fadeOut 0.5s ease-out";
    successMessage.addEventListener("animationend", () =>
      successMessage.remove()
    );
  }, 3000);
});

document.addEventListener("DOMContentLoaded", () => {
  const faqSearch = document.getElementById("faqSearch");
  const faqCards = document.querySelectorAll(".faq-card");

  // Search functionality
  faqSearch.addEventListener("input", (e) => {
    const searchTerm = e.target.value.toLowerCase();

    faqCards.forEach((card) => {
      const question = card
        .querySelector(".faq-question")
        .textContent.toLowerCase();
      const answer = card
        .querySelector(".faq-answer")
        .textContent.toLowerCase();

      if (question.includes(searchTerm) || answer.includes(searchTerm)) {
        card.style.display = "block";
        highlightText(card, searchTerm);
      } else {
        card.style.display = "none";
      }
    });
  });

  // Toggle FAQ answers
  faqCards.forEach((card) => {
    const question = card.querySelector(".faq-question");

    question.addEventListener("click", () => {
      const isActive = card.classList.contains("active");

      // Close other open cards
      faqCards.forEach((otherCard) => {
        if (otherCard !== card && otherCard.classList.contains("active")) {
          otherCard.classList.remove("active");
        }
      });

      // Toggle current card
      card.classList.toggle("active");

      // Smooth scroll into view if opening
      if (!isActive) {
        setTimeout(() => {
          card.scrollIntoView({
            behavior: "smooth",
            block: "nearest",
            inline: "nearest",
          });
        }, 300);
      }
    });
  });

  // Function to highlight matching text
  function highlightText(card, searchTerm) {
    if (!searchTerm) {
      // Reset highlights if search is empty
      card.querySelectorAll(".highlight").forEach((el) => {
        el.outerHTML = el.textContent;
      });
      return;
    }

    const questionEl = card.querySelector(".faq-question");
    const answerEl = card.querySelector(".faq-answer");

    [questionEl, answerEl].forEach((el) => {
      const originalText =
        el.getAttribute("data-original-text") || el.textContent;
      el.setAttribute("data-original-text", originalText);

      const regex = new RegExp(`(${searchTerm})`, "gi");
      const highlightedText = originalText.replace(
        regex,
        '<span class="highlight">$1</span>'
      );
      el.innerHTML = highlightedText;
    });
  }
});
