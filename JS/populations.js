// Get the popup element
const addCoursePopup = document.getElementById("addCoursePopup");
// Get the "Add" button element
const addCourseButton = document.getElementById("addCourseButton");
const add_Course_popup_close_img = document.getElementById(
  "add_course_popup_close_img"
);
// Get the popup element
const addStudentPopup = document.getElementById("addStudentPopup");
// Get the "Add" button element
const addStudentButton = document.querySelector(".add_student");
const add_student_popup_close_img = document.getElementById(
  "add_student_popup_close_img"
);
function editNameAndLastName(row) {
  const nameCell = row.cells[1];
  const lastNameCell = row.cells[2];
  const emailCell = row.cells[0];
  // Extract the email address from the <a> element within the email cell
  const emailAnchor = emailCell.querySelector("a");
  const email = emailAnchor ? emailAnchor.textContent : "";

  // Get the current values
  const currentName = nameCell.innerText;
  const currentLastName = lastNameCell.innerText;

  // Create input elements
  const nameInput = document.createElement("input");
  nameInput.type = "text";
  nameInput.value = currentName;

  const lastNameInput = document.createElement("input");
  lastNameInput.type = "text";
  lastNameInput.value = currentLastName;

  // Create the submit button
  const submitButton = document.createElement("button");
  submitButton.textContent = "Submit";
  submitButton.classList.add("edit-button");
  // Save the original content of the "Action" cell
  const originalActionContent = row.cells[4].innerHTML;

  // Replace the cells content with input elements and submit button
  nameCell.innerHTML = "";
  nameCell.appendChild(nameInput);

  lastNameCell.innerHTML = "";
  lastNameCell.appendChild(lastNameInput);

  // Clear the "Action" cell and append the submit button
  row.cells[4].innerHTML = "";
  row.cells[4].appendChild(submitButton);

  // Focus the input elements
  nameInput.focus();
  lastNameInput.focus();

  // Handle the submit button click event
  submitButton.addEventListener("click", function () {
    const newName = nameInput.value || currentName;
    const newLastName = lastNameInput.value || currentLastName;

    // Send the updated values to the server using AJAX
    const xhr = new XMLHttpRequest();
    const url = "php/update_student_name.php"; // Replace with your PHP script URL
    const data = `name=${encodeURIComponent(
      newName
    )}&last_name=${encodeURIComponent(newLastName)}&email=${encodeURIComponent(
      email
    )}`;

    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
      if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
        // If the request is successful, update the table cell content
        nameCell.innerText = newName;
        lastNameCell.innerText = newLastName;

        // Restore the original content of the "Action" cell
        row.cells[4].innerHTML = originalActionContent;
      }
    };
    xhr.send(data);
  });
}
// JavaScript function to remove student
function removeStudent(row) {
  const emailCell = row.cells[0];
  // Find the <a> element within the email cell
  const emailAnchor = emailCell.querySelector("a");

  // Extract the text (email address) from the <a> element
  const email = emailAnchor ? emailAnchor.textContent : "";
  const firstname = row.cells[1].innerHTML;
  const lastname = row.cells[2].innerHTML;
  console.log(email);
  // Create the submit button
  const actionRow = row.cells[4].innerHTML;
  console.log(actionRow);
  // Create an i element for the Font Awesome icon
  const confirmIcon = document.createElement("i");
  confirmIcon.className = "fa-solid fa-check"; // Add Font Awesome classes

  // Create the confirm button
  const confirmButton = document.createElement("button");
  confirmButton.appendChild(confirmIcon); // Append the icon to the button
  confirmButton.classList.add("edit-button");
  const returnIcon = document.createElement("i");
  returnIcon.className = "fa-solid fa-xmark";

  const returnButton = document.createElement("button");
  returnButton.appendChild(returnIcon);
  returnButton.classList.add("trash-button");
  row.cells[4].innerHTML = "";
  row.cells[4].appendChild(confirmButton);
  row.cells[4].appendChild(returnButton);

  confirmButton.addEventListener("click", () => {
    // Send the updated values to the server using AJAX
    const xhr = new XMLHttpRequest();
    const url = "php/delete_student.php"; // Replace with your PHP script URL
    const data = `email=${encodeURIComponent(
      email
    )}&firstname=${encodeURIComponent(firstname)}&lastname=${encodeURIComponent(
      lastname
    )}`;

    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
      if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
        // If the request is successful, update the table cell content
        console.log("Success");
        location.reload();
      }
    };
    xhr.send(data);
  });
  returnButton.addEventListener("click", () => {
    row.cells[4].innerHTML = actionRow;
  });
}

// Add an event listener to the "Add" button
addStudentButton.addEventListener("click", () => {
  // Display the popup by adding a CSS class
  if (addCoursePopup.classList.contains("show")) {
    addCoursePopup.classList.remove("show");
  }
  addStudentPopup.classList.add("show");
});

// Close the popup when the user clicks outside the popup
window.addEventListener("click", (event) => {
  if (event.target === add_student_popup_close_img) {
    addStudentPopup.classList.remove("show");
  }
});

// Add Student

document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("addStudentForm");

  form.addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent the default form submission

    const formData = new FormData(form);
    const xhr = new XMLHttpRequest();
    const url = "php/add_student.php"; // Replace with your PHP script URL

    // Validate the form fields before submitting
    const birthDay = formData.get("birth_day");
    const birthMonth = formData.get("birth_month");
    const birthYear = formData.get("birth_year");
    const enrollmentStatus = formData.get("enrollment_status");
    const country = formData.get("country");

    if (!birthDay || !birthMonth || !birthYear) {
      alert("Please select a valid birthdate.");
      return;
    }

    if (enrollmentStatus === "") {
      alert("Please select an enrollment status.");
      return;
    }

    if (country === "") {
      alert("Please select a country.");
      return;
    }
    const urlObject = new URL(window.location.href);

    // Get the query parameters as a URLSearchParams object
    const searchParams = new URLSearchParams(urlObject.search);

    // Extract individual query parameters
    const code = searchParams.get("code");
    const year = searchParams.get("year");
    const intake = searchParams.get("intake");
    formData.append("code", code);
    formData.append("year", year);
    formData.append("intake", intake);

    console.log(formData);
    xhr.open("POST", url, true);
    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4) {
        if (xhr.status === 200) {
          // Handle success response
          console.log(xhr.responseText);
          window.location.reload();
          console.log("Im working ");
          // You can update the page content or show a success message here
        } else if (
          xhr.responseText.includes("Duplicate") &&
          xhr.responseText.includes("contacts")
        ) {
          console.log("it is a duplicate");
          alert("Student already exists with this email");
        } else {
          // Handle error response
          console.error(xhr.responseText);

          // You can show an error message here
        }
      }
    };

    xhr.send(formData);
  });
});

// Add an event listener to the "Add" button
addCourseButton.addEventListener("click", () => {
  // Display the popup by adding a CSS class
  if (addStudentPopup.classList.contains("show")) {
    addStudentPopup.classList.remove("show");
  }
  addCoursePopup.classList.add("show");
});

// Close the popup when the user clicks outside the popup
window.addEventListener("click", (event) => {
  if (event.target === add_Course_popup_close_img) {
    addCoursePopup.classList.remove("show");
  }
});

// search
document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.getElementById("searchInput");
  const rows = document.querySelectorAll(".students tbody tr");

  // Add an event listener to the search input field
  searchInput.addEventListener("input", function () {
    const searchText = searchInput.value.toLowerCase();
    const searchTerms = searchText.trim().split(" ");
    console.log(searchTerms);
    // Loop through all table rows and hide/show based on search text
    rows.forEach(function (row) {
      const email = row
        .querySelector("td:first-child")
        .textContent.toLowerCase();
      const firstName = row
        .querySelector("td:nth-child(2)")
        .textContent.toLowerCase();
      const lastName = row
        .querySelector("td:nth-child(3)")
        .textContent.toLowerCase();

      const isMatchingEmail = email.includes(searchText.trim());
      const isMatchingFirstName = firstName.includes(searchText.trim());
      const isMatchingLastName = lastName.includes(searchText.trim());

      // Check if both first name and last name match
      const isMatchingFullName =
        searchTerms.length === 2 &&
        firstName.includes(searchTerms[0]) &&
        lastName.includes(searchTerms[1]);

      if (
        isMatchingEmail ||
        isMatchingFirstName ||
        isMatchingLastName ||
        isMatchingFullName
      ) {
        row.style.display = "table-row";
      } else {
        row.style.display = "none";
      }
    });
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.getElementById("searchInput");
  const searchGlassIcon = document.getElementById("search-glass-icon");
  searchInput.addEventListener("input", function () {
    // Check if the input has content
    if (searchInput.value.trim() !== "") {
      searchInput.classList.add("focused");
      searchGlassIcon.style.color = "black";
    } else {
      searchGlassIcon.style.color = "white";
      searchInput.classList.remove("focused");
    }
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.getElementById("searchCourseInput");
  const rows = document.querySelectorAll(".courses tbody tr");

  // Add an event listener to the search input field
  searchInput.addEventListener("input", function () {
    const searchText = searchInput.value.toLowerCase();
    const searchTerms = searchText.trim().split(" ");
    console.log(searchTerms);
    // Loop through all table rows and hide/show based on search text
    rows.forEach(function (row) {
      const courseId = row
        .querySelector("td:first-child")
        .textContent.toLowerCase();
      const courseName = row
        .querySelector("td:nth-child(2)")
        .textContent.toLowerCase();
      const sessionCount = row
        .querySelector("td:nth-child(3)")
        .textContent.toLowerCase();

      const isMatchingCourseId = courseId.includes(searchText.trim());
      const isMatchingcourseName = courseName.includes(searchText.trim());
      const isMatchingSessionCount = sessionCount.includes(searchText.trim());

      if (
        isMatchingCourseId ||
        isMatchingcourseName ||
        isMatchingSessionCount
      ) {
        row.style.display = "table-row";
      } else {
        row.style.display = "none";
      }
    });
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.getElementById("searchCourseInput");
  const searchGlassIcon = document.getElementById("search-glass-icon-courses");
  searchInput.addEventListener("input", function () {
    // Check if the input has content
    if (searchInput.value.trim() !== "") {
      searchInput.classList.add("focused");
      searchGlassIcon.style.color = "black";
    } else {
      searchGlassIcon.style.color = "white";
      searchInput.classList.remove("focused");
    }
  });
});
// add course
// Toggle course divs

const addExistingCourse = document.getElementById("addExistingCourse");
const addNewCourse = document.getElementById("addNewCourse");
const addCourseQuestionDiv = document.getElementById("addCourseQuestionDiv");
const addExistingCourseDiv = document.getElementById("addExistingCourseDiv");
const addNewCourseDiv = document.getElementById("addNewCourseDiv");
addExistingCourse.addEventListener("click", () => {
  addCourseQuestionDiv.style.display = "none";
  addExistingCourseDiv.style.display = "flex";
});
addNewCourse.addEventListener("click", () => {
  addCourseQuestionDiv.style.display = "none";
  addNewCourseDiv.style.display = "flex";
});
window.addEventListener("click", () => {
  if (!addCoursePopup.classList.contains("show")) {
    addNewCourseDiv.style.display = "none";
    addExistingCourseDiv.style.display = "none";
    addCourseQuestionDiv.style.display = "flex";
  }
});
