var emailInput = document.getElementById("email");
var passwrdInput = document.getElementById("passwrd");

const inputFields = () => {
  if (emailInput.value != "") {
    emailInput.classList.remove("error-input");
  }
  if (passwrdInput.value != "") {
    passwrdInput.classList.remove("error-input");
  }
};
function validateForm() {
  // For example, check if email and password are not empty

  // If validation fails, add the error-input class to the fields and return false

  if (!emailInput.value.trim()) {
    emailInput.classList.add("error-input");
    return false;
  }

  if (!passwrdInput.value.trim()) {
    passwrdInput.classList.add("error-input");
    return false;
  }

  // If validation passes, submit the form
  return true;
}
function displayErrorMessage(message) {
  var errorMessageContainer = document.getElementById("error_message");
  errorMessageContainer.innerText = message;
  errorMessageContainer.style.display = "block";
}
