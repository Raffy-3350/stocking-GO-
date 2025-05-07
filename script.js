document.addEventListener("DOMContentLoaded", function () {
  // Show SignUp Form
  function showSignUp() {
    document.getElementById("loginForm").style.display = "none";
    document.getElementById("signupForm").style.display = "block";
  }

  // Show Login Form
  function showLogin() {
    document.getElementById("signupForm").style.display = "none";
    document.getElementById("loginForm").style.display = "block";
  }

  // Make showLogin and showSignUp globally accessible (for buttons)
  window.showLogin = showLogin;
  window.showSignUp = showSignUp;

  // Handle Login Form Submission
  const loginForm = document.getElementById("loginForm");
  if (loginForm) {
    loginForm.addEventListener("submit", (e) => {
      e.preventDefault();
      // Perform login actions here
      console.log("Login form submitted");
    });
  }

  // Handle Sign-Up Form Submission
  const signupForm = document.getElementById("signupForm");
  if (signupForm) {
    signupForm.addEventListener("submit", (e) => {
      e.preventDefault();
      // Perform signup actions here
      console.log("Signup form submitted");
    });
  }
});
