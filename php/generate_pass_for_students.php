<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "epita";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);


// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if (isset($_SESSION['epita_email'])) {
    // Generate a random 8-character password
    $randomPassword = generateRandomPassword(8);
    $_SESSION['randompassword'] = $randomPassword;
    // Prepare the SQL statement for inserting into student_login
    $insertSql = "INSERT INTO student_login (student_epita_email_ref, student_password) VALUES ('" . $_SESSION['epita_email'] . "', '" . $randomPassword . "')";

    // Execute the insert query
    if ($conn->query($insertSql) === TRUE) {
        echo "Password inserted for: " . $_SESSION['epita_email'] . "<br>";
        require 'send_passwords.php';
    } else {
        echo "Error inserting password: " . $conn->error . "<br>";
    }
}


// Close the database connection
$conn->close();

// Function to generate a random password
function generateRandomPassword($length)
{
    $characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $password = "";
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $password;
}
?>