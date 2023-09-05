<?php
session_start();

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
    $coursename = $_POST['courseName'];
    $epitaEmail = $_POST['epitaEmail'];
    $examType = $_POST['examType'];
    $examWeight = $_POST['examWeight'];
    $studentGrade = $_POST['studentGrade'];

    $currentDate = date("Y-m-d"); // Format for date (MySQL format)
    $currentTime = "00:00:00"; // Format for time (HH:MM:SS)


    // Selecting courses
    $sql_courses = "SELECT * FROM courses WHERE course_name = ?";
    $stmt_courses = $conn->prepare($sql_courses);
    $stmt_courses->bind_param("s", $coursename);
    $stmt_courses->execute();
    // Get the result
    $result = $stmt_courses->get_result();
    // Fetch the row and extract the count value
    $course_row = $result->fetch_assoc();
    if ($course_row !== null) {
        $sql_insert_exams = "INSERT INTO `exams`
        ( `exam_course_code`, `exam_course_rev`, `exam_weight`, `exam_type`) 
        VALUES (?,?,?,?)";
        $stmt_insert_exams = $conn->prepare($sql_insert_exams);
        $stmt_insert_exams->bind_param(
            "ssss",
            $course_row['course_code'],
            $course_row['course_rev'],
            $examWeight,
            $examType,
        );
        $stmt_insert_exams->execute();
        // inserting in grades table
        $sql_insert_grades = "INSERT INTO `grades`
        (`grade_student_epita_email_ref`, `grade_course_code_ref`, 
        `grade_course_rev_ref`, `grade_exam_type_ref`, `grade_score`) 
        VALUES (?,?,?,?,?)";
        $stmt_insert_grades = $conn->prepare($sql_insert_grades);
        $stmt_insert_grades->bind_param(
            "sssss",
            $epitaEmail,
            $course_row['course_code'],
            $course_row['course_rev'],
            $examType,
            $studentGrade
        );
        $stmt_insert_grades->execute();
        echo "success";

    }



    // Close the statement and connection
    $stmt_courses->close();
    $stmt_insert_exams->close();
    $stmt_insert_grades->close();
    $conn->close();
}

?>