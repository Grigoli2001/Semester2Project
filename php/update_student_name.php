<?php
session_start();

// Replace the following database credentials with your own
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "epita";

// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the new name and last name from the POST data
    $newName = $_POST["name"];
    $newLastName = $_POST["last_name"];

    // Validate the data if necessary

    // Create a new database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update the database with the new name and last name
    $email = $_POST["email"]; // Assuming you send the email along with the name and last name
    $sql = "UPDATE contacts c JOIN students s ON c.contact_email = s.student_contact_ref SET contact_first_name = ? , contact_last_name = ? WHERE s.student_epita_email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $newName, $newLastName, $email);

    if ($stmt->execute()) {
        // Database update successful
        echo "Success";
    } else {
        // Database update failed
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>