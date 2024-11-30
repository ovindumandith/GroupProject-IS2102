document.addEventListener("DOMContentLoaded", () => {
  const container = document.getElementById("container");
  const registerBtn = document.getElementById("register");
  const loginBtn = document.getElementById("login");

  // Toggle between login and registration forms
  registerBtn.addEventListener("click", () => {
    container.classList.add("active");
  });

  loginBtn.addEventListener("click", () => {
    container.classList.remove("active");
  });

  // Signup form validation and submission
  document.getElementById("signupForm").addEventListener("submit", (event) => {
    const name = document.getElementById("signupName").value.trim();
    const type = document.getElementById("signupType").value.trim();
    const specialization = document
      .getElementById("signupSpecialization")
      .value.trim();
    const email = document.getElementById("signupEmail").value.trim();
    const password = document.getElementById("signupPassword").value.trim();
    const errorDiv = document.getElementById("signupError");

    // Regular expressions for validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    // Client-side validation
    if (!name || !type || !email || !password) {
      event.preventDefault(); // Prevent form submission
      errorDiv.textContent = "All required fields must be filled!";
      return;
    }

    if (type === "Professional" && !specialization) {
      event.preventDefault();
      errorDiv.textContent =
        "Specialization is required for Professional counselors!";
      return;
    }

    if (!emailRegex.test(email)) {
      event.preventDefault();
      errorDiv.textContent = "Invalid email format!";
      return;
    }

    if (password.length < 6) {
      event.preventDefault();
      errorDiv.textContent = "Password must be at least 6 characters long!";
      return;
    }

    // Clear any previous errors and allow form submission
    errorDiv.textContent = "";
  });

  // Login form validation and submission
  document.getElementById("loginForm").addEventListener("submit", (event) => {
    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("loginPassword").value.trim();
    const errorDiv = document.getElementById("loginError");

    // Client-side validation
    if (!email || !password) {
      event.preventDefault(); // Prevent form submission
      errorDiv.textContent = "Both email and password are required!";
      return;
    }

    errorDiv.textContent = ""; // Clear error message if validation passes
  });
});
