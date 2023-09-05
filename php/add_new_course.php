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
    $coursename = $_POST['new_coursename'];
    $new_coursecode = $_POST['new_coursecode'];
    $duration = $_POST['duration'];
    $new_course_description = $_POST['new_course_description'];
    $course_rev = 1;
    $course_last_rev = date("Y");
    $teacher = $_POST['teacher'];
    $sessioncount = $_POST['sessioncount'];

    $currentDate = date("Y-m-d"); // Format for date (MySQL format)
    $currentTime = "00:00:00"; // Format for time (HH:MM:SS)


    // Selecting courses
    $sql_courses = "INSERT INTO `courses` (`course_code`, `course_rev`, `duration`, 
    `course_last_rev`, `course_name`, `course_description`) 
    VALUES (?, ?, ?, ?, ?, ?)";
    $stmt_courses = $conn->prepare($sql_courses);
    $stmt_courses->bind_param(
        "ssssss",
        $new_coursecode,
        $course_rev,
        $duration,
        $course_last_rev,
        $coursename,
        $new_course_description
    );
    $stmt_courses->execute();
    $sql_insert_courses = "INSERT INTO `sessions` (`session_course_ref`, 
        `session_course_rev_ref`, `session_prof_ref`, `session_date`, 
        `session_start_time`, `session_end_time`, `session_type`, 
        `session_population_year`, `session_population_period`, `session_room`) 
        VALUES (?, ?, ?, ?, ?, ?, NULL, ?, ?, NULL)";
    $stmt_insert_courses = $conn->prepare($sql_insert_courses);
    $stmt_insert_courses->bind_param(
        "ssssssss",
        $new_coursecode,
        $course_rev,
        $teacher,
        $currentDate,
        $currentTime,
        $currentTime,
        $year,
        $intake,
    );
    // Execute the insert statement multiple times
    for ($i = 0; $i < $sessioncount; $i++) {
        $stmt_insert_courses->execute();

        // Increment the date by one day for the next insertion
        $currentDate = date("Y-m-d", strtotime($currentDate . " +1 day"));

        // Update the bound parameter for the next iteration
        $stmt_insert_courses->bind_param(
            "ssssssss",
            $new_coursecode,
            $course_rev,
            $teacher,
            $currentDate,
            $currentTime,
            $currentTime,
            $year,
            $intake
        );
    }
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
    echo "success";




    $stmt_courses->close();
    $stmt_insert_courses->close();
    $stmt_insert_programs->close();
    $conn->close();
}

?>