function removeGrade(row) {
  const email = row.cells[0].textContent;
  const courseName = row.cells[3].textContent;

  // Create the submit button
  const actionRow = row.cells[7].innerHTML;
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
  row.cells[7].innerHTML = "";
  row.cells[7].appendChild(confirmButton);
  row.cells[7].appendChild(returnButton);

  confirmButton.addEventListener("click", () => {
    // Send the updated values to the server using AJAX
    const xhr = new XMLHttpRequest();
    const url = "php/remove_grades.php"; // Replace with your PHP script URL
    const data = `email=${encodeURIComponent(
      email
    )}&courseName=${encodeURIComponent(courseName)}`;

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
    row.cells[7].innerHTML = actionRow;
  });
}
