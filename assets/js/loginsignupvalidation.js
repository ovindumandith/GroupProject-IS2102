document.addEventListener("DOMContentLoaded", () => {
  const container = document.getElementById("container");
  const registerBtn = document.getElementById("register");
  const loginBtn = document.getElementById("login");

  registerBtn.addEventListener("click", () => {
    container.classList.add("active");
  });

  loginBtn.addEventListener("click", () => {
    container.classList.remove("active");
  });

  //signup for valiation and submission
  document.getElementById("signupForm").addEventListener("submit", (event) => {
    

    const username = document.getElementById("signupUsername").value.trim();
    const email = document.getElementById("signupEmail").value.trim();
    const phone = document.getElementById("signupPhone").value.trim();
    const year = document.getElementById("signupYear").value.trim();
    const password = document.getElementById("signupPassword").value.trim();
    const errorDiv = document.getElementById("signupError");

    // Regular expressions for validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const phoneRegex = /^\d{10}$/; // Assumes phone numbers are 10 digits long

    // Client-side validation
    if (!username || !email || !phone || !year || !password) {
      event.preventDefault(); // Prevent form submission
      errorDiv.textContent = "All fields are required!";
      return;
    }

    if (!emailRegex.test(email)) {
      errorDiv.textContent = "Invalid email format!";
      return;
    }

    if (!phoneRegex.test(phone)) {
      errorDiv.textContent = "Phone number must be 10 digits!";
      return;
    }

    if (password.length < 6) {
      errorDiv.textContent = "Password must be at least 6 characters!";
      return;
    }

    // Clear any previous errors
    errorDiv.textContent = "";

    document.getElementById("signupForm").submit();
  });

  // Login form validation and submission
  document.getElementById("loginForm").addEventListener("submit", (event) => {
   
    event.preventDefault();

    const username = document.getElementById("username").value.trim();
    const password = document.getElementById("loginPassword").value.trim();
    const errorDiv = document.getElementById("loginError");

    // Client-side validation
    if (!username || !password) {
      errorDiv.textContent = "Both username and password are required!";
      return; // Stop the form from submitting if validation fails
    }

    errorDiv.textContent = ""; // Clear error message if validation passes

    // Submit the form programmatically since validation passed
    document.getElementById("loginForm").submit();
  });
});
