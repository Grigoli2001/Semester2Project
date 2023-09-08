<?php
session_start();

// Replace the following database credentials with your own
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "epita";

// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    // Create a new database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // get student contact Email, So I can delete from contacts table as well
    // I can't determine epita email using only first name and last name because there might be more than 1 people with same name.
    $sql_get_student_email = "SELECT student_contact_ref FROM students WHERE student_epita_email = ?";
    $stmt1 = $conn->prepare($sql_get_student_email);
    $stmt1->bind_param('s', $email);
    if ($stmt1->execute()) {

        $result = $stmt1->get_result();
        $row = $result->fetch_assoc();
        $contact_email = $row['student_contact_ref'];
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

    $sql_delete_contact = "DELETE FROM contacts WHERE contact_email = ?";
    $stmt3 = $conn->prepare($sql_delete_contact);
    $stmt3->bind_param('s', $contact_email);
    if ($stmt3->execute()) {
        echo 'Contact Deleted';
    } else {
        echo 'Error : ' . $stmt3->error;
    }


    // Close the statement and connection

    $stmt->close();
    $stmt1->close();
    $stmt2->close();
    $stmt3->close();
    $conn->close();
}
?>