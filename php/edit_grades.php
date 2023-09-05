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
    $newGrade = $_POST["newGrade"];
    $courseName = $_POST["courseName"];
    $email = $_POST["email"];
    $examType = $_POST["examType"];
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
        echo ($course_code);
        echo ($course_rev);
    }

    // Update the database with the new name and last name
    $sql = "UPDATE `grades` SET `grade_score` = ? 
    WHERE `grade_student_epita_email_ref` = ? 
    AND `grade_course_code_ref` = ? 
    AND `grade_course_rev_ref` = ?
    AND `grade_exam_type_ref` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sssss",
        $newGrade,
        $email,
        $course_code,
        $course_rev,
        $examType
    );
    echo ($examType);
    if ($stmt->execute()) {
        // Database update successful
        echo "Success";
    } else {
        // Database update failed
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt_course->close();
    $stmt->close();
    $conn->close();
}
?>