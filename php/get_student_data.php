<?php
// email needed to require this script
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "epita";


$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$student_sql = "SELECT * FROM students s JOIN contacts c 
ON s.student_contact_ref = c.contact_email
WHERE s.student_epita_email = ?";
$stmt_student = $conn->prepare($student_sql);
$stmt_student->bind_param('s', $email);

if ($stmt_student->execute()) {
    $student_result = $stmt_student->get_result();
    $student_row = $student_result->fetch_assoc();
} else {
    echo 'Error';
}

// Use the correct variable names here (e.g., $student_row instead of $s)
// $course_sql = "SELECT 
//  c.course_name 
// FROM COURSES c 
// JOIN PROGRAMS p 
// ON c.COURSE_CODE = p.PROGRAM_COURSE_CODE_REF 
// WHERE p.PROGRAM_ASSIGNMENT LIKE ? ";
$course_sql = "SELECT DISTINCT c.course_name 
FROM COURSES c 
JOIN PROGRAMS p 
ON c.COURSE_CODE = p.PROGRAM_COURSE_CODE_REF JOIN SESSIONS s 
ON s.SESSION_COURSE_REF = c.COURSE_CODE 
WHERE p.PROGRAM_ASSIGNMENT LIKE ? 
AND s.SESSION_POPULATION_YEAR  = ? 
AND s.SESSION_POPULATION_PERIOD LIKE ?";

$stmt_course = $conn->prepare($course_sql);
$stmt_course->bind_param(
    'sss',
    $student_row['student_population_code_ref'],
    $student_row['student_population_year_ref'],
    $student_row['student_population_period_ref']
);

if ($stmt_course->execute()) {
    $course_result = $stmt_course->get_result();

    // Check if there are rows returned
    if ($course_result->num_rows > 0) {

    } else {
        echo $student_row['student_population_code_ref'];
        echo $student_row['student_population_year_ref'];
        echo $student_row['student_population_period_ref'];
    }
} else {
    echo 'Error executing course query.';
}

$stmt_course->close();
$stmt_student->close();
$conn->close();

?>