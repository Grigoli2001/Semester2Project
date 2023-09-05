<!-- to do -->

$sql_insert_programs = "INSERT INTO `programs` (`program_course_code_ref`,
`program_course_rev_ref`, `program_assignment`)
VALUES (?, ?, ?)";
$stmt_insert_programs = $conn->prepare($sql_insert_programs);
$stmt_insert_programs->bind_param(
"sss",
$new_coursecode,
$course_rev,
$code
);
$stmt_insert_programs->execute();



$stmt_insert_programs->close();