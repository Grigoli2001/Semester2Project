// Get the current date and time
const currentDateAndTime = new Date();

// Format the date and time as a string
const formattedDateAndTime = currentDateAndTime.toLocaleString();

// Create a new <p> element
const paragraphElement = document.createElement("span");

// Set the text content of the <p> element
paragraphElement.textContent = `Website has been generated at ${formattedDateAndTime}`;

// Find the footer element by its ID
const footerElement = document.getElementById("footer");

// Append the <p> element to the footer
footerElement.appendChild(paragraphElement);
