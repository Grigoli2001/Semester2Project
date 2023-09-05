function calculateMeanFinalGrades() {
  // Get all the rows in the table body
  const tableBody = document.querySelector("tbody");
  const rows = tableBody.querySelectorAll("tr");

  // Create an object to store the total scores and count for each unique combination of email and course
  const scoreSumMap = new Map();
  const scoreCountMap = new Map();

  // Iterate through each row and calculate the mean final grade
  rows.forEach((row) => {
    const email = row.querySelector("td:nth-child(1)").textContent;
    const course = row.querySelector("td:nth-child(4)").textContent;
    const finalGrade = parseFloat(
      row.querySelector("td:nth-child(7)").textContent
    );
    const examWeight = parseFloat(
      row.querySelector("td:nth-child(6)").textContent
    );

    // Update the total score and count for the combination of email and course
    if (!scoreSumMap.has(email)) {
      scoreSumMap.set(email, new Map());
      scoreCountMap.set(email, new Map());
    }
    if (!scoreSumMap.get(email).has(course)) {
      scoreSumMap.get(email).set(course, 0);
      scoreCountMap.get(email).set(course, 0);
    }

    scoreSumMap
      .get(email)
      .set(
        course,
        scoreSumMap.get(email).get(course) + finalGrade * examWeight
      );
    scoreCountMap
      .get(email)
      .set(course, scoreCountMap.get(email).get(course) + examWeight);
  });

  // Create a new table structure
  const newTable = document.createElement("table");
  const newTableHeader = document.createElement("thead");
  const newTableBody = document.createElement("tbody");

  // Create header row for the new table
  const headerRow = document.createElement("tr");
  const emailHeader = document.createElement("th");
  emailHeader.textContent = "Email";
  const courseHeader = document.createElement("th");
  courseHeader.textContent = "Course";
  const meanFinalGradeHeader = document.createElement("th");
  meanFinalGradeHeader.textContent = "Final Grade";
  headerRow.appendChild(emailHeader);
  headerRow.appendChild(courseHeader);
  headerRow.appendChild(meanFinalGradeHeader);
  newTableHeader.appendChild(headerRow);

  // Append the new table structure to the document
  newTable.appendChild(newTableHeader);
  newTable.appendChild(newTableBody);
  // Append the new table structure to the div with class name "final-grades"
  const finalGradesDiv = document.querySelector(".final-grades");
  finalGradesDiv.appendChild(newTable);

  // Iterate through the map and calculate the mean final grade for each combination
  scoreSumMap.forEach((courseMap, email) => {
    courseMap.forEach((scoreSum, course) => {
      const count = scoreCountMap.get(email).get(course);
      const meanFinalGrade = scoreSum / count;

      // Create a new row for the new table
      const newRow = document.createElement("tr");
      const emailCell = document.createElement("td");
      emailCell.textContent = email;
      const courseCell = document.createElement("td");
      courseCell.textContent = course;
      const meanFinalGradeCell = document.createElement("td");
      meanFinalGradeCell.textContent = meanFinalGrade.toFixed(2); // Adjust decimal places

      // Append cells to the new row
      newRow.appendChild(emailCell);
      newRow.appendChild(courseCell);
      newRow.appendChild(meanFinalGradeCell);

      // Append the new row to the new table body
      newTableBody.appendChild(newRow);
    });
  });
}

// Call the function to calculate mean final grades and create the new table when the page loads
window.addEventListener("load", calculateMeanFinalGrades);

const addGradePopup = document.getElementById("addGradePopup");
// Get the "Add" button element
const addGradeButton = document.getElementById("addGradeButton");
const add_student_popup_close_img = document.getElementById(
  "add_student_popup_close_img"
);
console.log(addGradeButton);
addGradeButton.addEventListener("click", () => {
  // Display the popup by adding a CSS class
  if (addGradePopup.classList.contains("show")) {
    addGradePopup.classList.remove("show");
  }
  addGradePopup.classList.add("show");
});

// Close the popup when the user clicks outside the popup
window.addEventListener("click", (event) => {
  if (event.target === add_student_popup_close_img) {
    addGradePopup.classList.remove("show");
  }
});

// search
document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.getElementById("searchInput");
  const rows = document.querySelectorAll(".all-grades tbody tr");

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
