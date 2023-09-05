<?php
session_start();

// INSERT INTO `students` (`student_epita_email`, `student_contact_ref`, `student_enrollment_status`, `student_population_period_ref`, `student_population_year_ref`, `student_population_code_ref`) VALUES ('', '', '', '', '', '')
// INSERT INTO `contacts` (`contact_email`, `contact_first_name`, `contact_last_name`, `contact_address`, `contact_city`, `contact_country`, `contact_birthdate`) VALUES ('', NULL, NULL, NULL, NULL, NULL, NULL)
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


    $code = $_POST['code'];
    $year = $_POST['year'];
    $intake = $_POST['intake'];
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $email = $_POST["student_email"];
    $birth_day = $_POST["birth_day"];
    $birth_month = $_POST["birth_month"];
    $birth_year = $_POST["birth_year"];
    $address = $_POST["address"];
    $city = $_POST["city"];
    $country = $_POST["country"];
    $enrollment_status = $_POST["enrollment_status"];
    $epita_email = "" . $firstname . "." . $lastname . "@epita.fr";
    $_SESSION['epita_email'] = $epita_email;
    // Create a timestamp using mktime()
    $timestamp = mktime(0, 0, 0, $birth_month, $birth_day, $birth_year);
    // Format the timestamp as a MySQL-compatible date string
    $mysql_date_string = date('Y-m-d', $timestamp);



    // Inserting in the students table
    $sql_students = "INSERT INTO `students` (`student_epita_email`, `student_contact_ref`, `student_enrollment_status`, `student_population_period_ref`, `student_population_year_ref`, `student_population_code_ref`) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt_students = $conn->prepare($sql_students);
    $stmt_students->bind_param("ssssss", $epita_email, $email, $enrollment_status, $intake, $year, $code);


    // Inserting in the contacts table
    $sql_contacts = "INSERT INTO `contacts` (`contact_email`, `contact_first_name`, `contact_last_name`, `contact_address`, `contact_city`, `contact_country`, `contact_birthdate`) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt_contacts = $conn->prepare($sql_contacts);
    $stmt_contacts->bind_param("sssssss", $email, $firstname, $lastname, $address, $city, $country, $mysql_date_string);
    if ($stmt_contacts->execute() && $stmt_students->execute()) {
        // Both database updates successful
        echo "Success";

        // Now, you can include 'generate_pass_for_students.php'
        require 'generate_pass_for_students.php';
    } else {
        // At least one of the database updates failed
        echo "Error: " . $stmt_students->error . " or " . $stmt_contacts->error;
    }
    // Close the statement and connection
    $stmt_students->close();
    $stmt_contacts->close();
}

?>