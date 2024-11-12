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
    <title>Login</title>
    <script src="../../assets/js/loginsignupvalidation.js"></script>
    
  </head>
  <body>

    <div class="container" id="container">
      <div class="form-container sign-up">
        <form id="signupForm" action="../controller/RegisterController.php" method="POST">
          <h1><b>Create Account</b></h1>

          <input
            type="text"
            id="signupUsername"
            name="username"
            placeholder="Username"
            required
          />

          <input
            type="email"
            id="signupEmail"
            name="email"
            placeholder="Email"
            required
          />

          <input
            type="text"
            id="signupPhone"
            name="phone"
            placeholder="Phone No"
            required
          />

          <select name="year" id="signupYear" required>
            <option value="" disabled selected>Select Study Year</option>
            <option value="1">1st Year</option>
            <option value="2">2nd Year</option>
            <option value="3">3rd Year</option>
            <option value="4">4th Year</option>
          </select>

          <input
            type="password"
            id="signupPassword"
            name="password"
            placeholder="Password"
            required
          />

          <button type="submit">Sign Up</button>
                           <div class="error" id="signupError"> <?php
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
          action="../controller/LoginController.php"
          method="POST"
        >
          <h1>Sign In</h1>

          <input
            type="text"
            id="username"
            name="username"
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
          <div class="error" id="loginError"></div>
        </form>
      </div>

      <div class="toggle-container">
        <div class="toggle">
          <div class="toggle-panel toggle-left">
            <h1>Welcome Back!</h1>
            <p>Enter your personal details to use all of site features</p>
            <button class="hidden" id="login">Sign In</button>
          </div>
          <div class="toggle-panel toggle-right">
            <h1>Hello!</h1>
            <p>
              Register with us and start improving your academic well-beign!
            </p>
            <button class="hidden" id="register">Sign Up</button>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
