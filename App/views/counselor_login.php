<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
  />
  <link rel="stylesheet" href="../../assets/css/loginsignup.css">
  <title>Counselor Login & Registration</title>
  <script src="../../assets/js/counselor_loginsignupvalidation.js"></script>
</head>
<body>
  <div class="container" id="container">
    <div class="form-container sign-up">
      <form id="signupForm" action="../controller/CounselorRegisterController.php" method="POST">
        <h1><b>Create Counselor Account</b></h1>

        <input
          type="text"
          id="signupName"
          name="name"
          placeholder="Full Name"
          required
        />
          <input
          type="text"
          id="username"
          name="username"
          placeholder="Username"
          required
        />

        <select name="type" id="signupType" required>
          <option value="" disabled selected>Select Counselor Type</option>
          <option value="Professional">Professional</option>
          <option value="Student">Student</option>
        </select>

        <input
          type="text"
          id="signupSpecialization"
          name="specialization"
          placeholder="Specialization"
        />

        <input
          type="email"
          id="signupEmail"
          name="email"
          placeholder="Email"
          required
        />

        <input
          type="password"
          id="signupPassword"
          name="password"
          placeholder="Password"
          required
        />

        <button type="submit">Sign Up</button>
        <div class="error" id="signupError">
          <?php
          if (isset($_SESSION['signup_error'])) {
              echo '<p style="color:red;">' . $_SESSION['signup_error'] . '</p>';
              unset($_SESSION['signup_error']); // Clear message after displaying
          } elseif (isset($_SESSION['signup_success'])) {
              echo '<p style="color:green;">' . $_SESSION['signup_success'] . '</p>';
              unset($_SESSION['signup_success']); // Clear message after displaying
          }
          ?>
        </div>
      </form>
    </div>

    <div class="form-container sign-in">
      <form
        id="loginForm"
        action="../controller/CounselorLoginController.php"
        method="POST"
      >
        <h1>Counselor Login</h1>

  <input
    type="text"
    id="loginusername"
    name="loginusername"
    placeholder="Username"
    required
  />
        <input
          type="password"
          id="loginPassword"
          name="loginPassword"
          placeholder="Password"
          required
        />
        <a href="#">Forget Your Password?</a>
        <button type="submit">Sign In</button>
        <div class="error" id="loginError">
          <?php
          if (isset($_SESSION['login_error'])) {
              echo '<p style="color:red;">' . $_SESSION['login_error'] . '</p>';
              unset($_SESSION['login_error']); // Clear message after displaying
          }
          ?>
        </div>
      </form>
    </div>

    <div class="toggle-container">
      <div class="toggle">
        <div class="toggle-panel toggle-left">
          <h1>Welcome Back!</h1>
          <p>Log in to access your counselor dashboard.</p>
          <button class="hidden" id="login">Sign In</button>
        </div>
        <div class="toggle-panel toggle-right">
          <h1>Hello!</h1>
          <p>
            Register as a counselor and start helping students improve their well-being!
          </p>
          <button class="hidden" id="register">Sign Up</button>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
