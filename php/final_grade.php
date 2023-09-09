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
$grade_sql = "SELECT
c.course_name,
round(SUM(e.exam_weight * g.grade_score) / SUM(e.exam_weight),2) AS final_grade
FROM
grades g
JOIN
exams e ON e.exam_course_code = g.grade_course_code_ref AND e.exam_type = g.grade_exam_type_ref
JOIN
courses c ON c.course_code = g.grade_course_code_ref
WHERE
g.grade_student_epita_email_ref = ?
GROUP BY
c.course_name;";

$stmt_grade = $conn->prepare($grade_sql);
$stmt_grade->bind_param('s', $email);
if ($stmt_grade->execute()) {
    $grade_result = $stmt_grade->get_result();
    $course_names = array(); // Initialize an empty PHP array
    $final_grades = array(); // Initialize an empty PHP array

    while ($grade_row = $grade_result->fetch_assoc()) {
        $course_names[] = $grade_row["course_name"];
        $final_grades[] = (float) $grade_row["final_grade"];
    }
    $course_names_json = json_encode($course_names);
    $final_grades_json = json_encode($final_grades);
} else {
    echo 'Error';
}
$stmt_grade->close();
$conn->close();

?>