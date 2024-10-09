import {
  getAuth,
  createUserWithEmailAndPassword,
} from "https://www.gstatic.com/firebasejs/10.14.0/firebase-auth.js";

const auth = getAuth();

document
  .getElementById("registerForm")
  .addEventListener("submit", async (e) => {
    e.preventDefault();
    const fullName = document.getElementById("fullName").value;
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirmPassword").value;
    const agreeTerms = document.getElementById("agreeTerms").checked;
    const errorMessage = document.getElementById("errorMessage");

    errorMessage.style.display = "none";

    if (password !== confirmPassword) {
      errorMessage.textContent = "Passwords do not match.";
      errorMessage.style.display = "block";
      return;
    }

    if (!agreeTerms) {
      errorMessage.textContent =
        "You must agree to the Terms of Service and Privacy Policy.";
      errorMessage.style.display = "block";
      return;
    }

    try {
      const userCredential = await createUserWithEmailAndPassword(
        auth,
        email,
        password
      );
      const user = userCredential.user;
      console.log("User registered successfully:", user);
      window.location.href = "dashboard.html";
    } catch (error) {
      errorMessage.textContent = error.message;
      errorMessage.style.display = "block";
    }
  });
