// Form handling
const academicForm = document.getElementById("academicForm");

academicForm.addEventListener("submit", function (e) {
  // Get form values
  const formData = {
    telephone: document.getElementById("telephone").value.trim(),
    email: document.getElementById("email").value.trim(),
  };

  // Validation regex patterns
  const phoneRegex = /^\+?[\d\s-]{10,}$/; // Validates phone numbers (e.g., international or standard)
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Basic email validation pattern

  // Phone number validation
  if (!phoneRegex.test(formData.telephone)) {
    alert("Please enter a valid phone number (at least 10 digits).");
    e.preventDefault(); // Prevent form submission if validation fails
    return;
  }

  // Email validation
  if (!emailRegex.test(formData.email)) {
    alert("Please enter a valid email address.");
    e.preventDefault(); // Prevent form submission if validation fails
    return;
  }

  // If validation passes, allow form submission
  // The form will submit to the action URL specified in the form tag
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
