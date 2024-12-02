document.addEventListener("DOMContentLoaded", function () {
  const positionSelect = document.getElementById("position");
  const hodFields = document.getElementById("hod-fields");
  const lecturerFields = document.getElementById("lecturer-fields");
  const form = document.getElementById("contactForm");

  positionSelect.addEventListener("change", function () {
    hodFields.classList.remove("active");
    lecturerFields.classList.remove("active");

    if (this.value === "hod") {
      hodFields.classList.add("active");
    } else if (this.value === "lecturer") {
      lecturerFields.classList.add("active");
    }
  });

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    // Basic form validation
    let isValid = true;
    const position = positionSelect.value;

    // Common fields validation
    if (!form.name.value.trim()) {
      showError("name", "Name is required");
      isValid = false;
    }

    if (!form.email.value.trim()) {
      showError("email", "Email is required");
      isValid = false;
    } else if (!isValidEmail(form.email.value)) {
      showError("email", "Please enter a valid email");
      isValid = false;
    }

    // Position specific validation
    if (position === "lecturer") {
      if (!form.phone.value.trim()) {
        showError("phone", "Phone number is required");
        isValid = false;
      }
      if (!form.module_code.value.trim()) {
        showError("module_code", "Module code is required");
        isValid = false;
      }
    }

    if (!form.faculty.value.trim()) {
      showError("faculty", "Faculty is required");
      isValid = false;
    }

    if (isValid) {
      form.submit();
    }
  });

  function showError(fieldId, message) {
    const field = document.getElementById(fieldId);
    const errorDiv = field.nextElementSibling;
    if (errorDiv && errorDiv.classList.contains("error")) {
      errorDiv.textContent = message;
    } else {
      const div = document.createElement("div");
      div.className = "error";
      div.textContent = message;
      field.parentNode.insertBefore(div, field.nextSibling);
    }
  }

  function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  }
});
