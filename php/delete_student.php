<?php
session_start();

// Replace the following database credentials with your own
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "epita";

// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Create a new database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update the database with the new name and last name
    $email = $_POST["email"];
    $sql = "DELETE FROM students WHERE `students`.`student_epita_email` = '" . $email . "'";
    $stmt = $conn->prepare($sql);


    if ($stmt->execute()) {
        // Database update successful
        echo "Success " . $email;
    } else {
        // Database update failed
        echo "Error: " . $stmt->error;
    }
    $loginsql = "DELETE FROM student_login WHERE student_epita_email_ref = '" . $email . "'";
    $stmt2 = $conn->prepare($loginsql);
    if ($stmt2->execute()) {
        echo "Deleted From login";
    } else {
        echo "Error: " . $stmt2->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>