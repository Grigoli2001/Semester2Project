// JavaScript function to handle the name and last name editing
function editNameAndLastName(row) {
  const nameCell = row.cells[1];
  const lastNameCell = row.cells[2];
  const email = row.cells[0].innerHTML;

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
    const url = "php/update_database.php"; // Replace with your PHP script URL
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
