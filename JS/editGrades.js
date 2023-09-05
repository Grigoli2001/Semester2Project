function editGrade(row) {
  const email = row.cells[0].textContent;
  // Extract the email address from the <a> element within the email cell

  const courseName = row.cells[3].textContent;
  const examType = row.cells[4].textContent;
  const gradeCell = row.cells[6];
  // Get the current values
  const currentGrade = gradeCell.innerText;
  // Create input elements
  const gradeInput = document.createElement("input");
  gradeInput.type = "number";
  gradeInput.min = 0;
  gradeInput.max = 20;
  gradeInput.value = currentGrade;

  // Create the submit button
  const submitButton = document.createElement("button");
  submitButton.textContent = "Submit";
  submitButton.classList.add("edit-button");

  // Save the original content of the "Action" cell
  const originalActionContent = row.cells[7].innerHTML;

  // Replace the cells content with input elements and submit button
  gradeCell.innerHTML = "";
  gradeCell.appendChild(gradeInput);

  // Clear the "Action" cell and append the submit button
  row.cells[7].innerHTML = "";
  row.cells[7].appendChild(submitButton);

  // Focus the input elements
  gradeInput.focus();

  // Handle the submit button click event
  submitButton.addEventListener("click", function () {
    let newGrade = gradeInput.value || currentGrade;
    if (newGrade > 20) {
      newGrade = 20;
    }

    // Send the updated values to the server using AJAX
    const xhr = new XMLHttpRequest();
    const url = "php/edit_grades.php"; // Replace with your PHP script URL
    const data = `email=${encodeURIComponent(
      email
    )}&newGrade=${encodeURIComponent(newGrade)}&courseName=${encodeURIComponent(
      courseName
    )}&examType=${encodeURIComponent(examType)}`;
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
      if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
        // If the request is successful, update the table cell content
        gradeCell.innerText = newGrade;

        // Restore the original content of the "Action" cell
        row.cells[7].innerHTML = originalActionContent;
        window.location.reload();
      }
    };
    xhr.send(data);
  });
}
