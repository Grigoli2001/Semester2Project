<?php
session_start();

// Replace the following database credentials with your own
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "epita";
print_r($_POST);
// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $courseName = $_POST["courseName"];
    $email = $_POST["email"];
    // Validate the data if necessary

    // Create a new database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $course_sql = "SELECT course_code,course_rev FROM courses WHERE course_name = ?";
    $stmt_course = $conn->prepare($course_sql);
    $stmt_course->bind_param('s', $courseName);

    if ($stmt_course->execute()) {
        $course_result = $stmt_course->get_result();
        // Fetch the row and extract the count value
        $course_row = $course_result->fetch_assoc();
        $course_code = $course_row['course_code'];
        $course_rev = $course_row['course_rev'];
    }
    $grade_sql = "SELECT grade_exam_type_ref FROM grades WHERE grade_student_epita_email_ref = ?";
    $stmt_grade = $conn->prepare($grade_sql);
    $stmt_grade->bind_param('s', $email);
    if ($stmt_grade->execute()) {
        $grade_result = $stmt_grade->get_result();
        $grade_row = $grade_result->fetch_assoc();
        $grade_exam_type_ref = $grade_row['grade_exam_type_ref'];
    }

    // Update the database with the new name and last name
    $sql = "DELETE FROM grades 
    WHERE `grades`.`grade_student_epita_email_ref` = ? 
    AND `grades`.`grade_course_code_ref` = ?
    AND `grades`.`grade_course_rev_ref` = ?
    AND `grades`.`grade_exam_type_ref` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssss",
        $email,
        $course_code,
        $course_rev,
        $grade_exam_type_ref
    );

    if ($stmt->execute()) {
        // Database update successful
        echo "Success";
    } else {
        // Database update failed
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt_course->close();
    $stmt_grade->close();
    $stmt->close();
    $conn->close();
}
?>