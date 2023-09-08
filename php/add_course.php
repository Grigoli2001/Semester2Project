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
    $coursename = trim($_POST['coursename']);
    $sessioncount = $_POST['sessioncount'];
    $teacher = $_POST['teacher'];

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
        $sql_insert_sessions = "INSERT INTO `sessions` (`session_course_ref`, 
        `session_course_rev_ref`, `session_prof_ref`, `session_date`, 
        `session_start_time`, `session_end_time`, `session_type`, 
        `session_population_year`, `session_population_period`, `session_room`) 
        VALUES (?, ?, ?, ?, ?, ?, NULL, ?, ?, NULL)";
        $stmt_insert_sessions = $conn->prepare($sql_insert_sessions);
        $stmt_insert_sessions->bind_param(
            "ssssssss",
            $course_row['course_code'],
            $course_row['course_rev'],
            $teacher,
            $currentDate,
            $currentTime,
            $currentTime,
            $year,
            $intake,
        );
        // Execute the insert statement multiple times
        for ($i = 0; $i < $sessioncount; $i++) {
            $stmt_insert_sessions->execute();

            // Increment the date by one day for the next insertion
            $currentDate = date("Y-m-d", strtotime($currentDate . " +1 day"));

            // Update the bound parameter for the next iteration
            $stmt_insert_sessions->bind_param("ssssssss", $course_row['course_code'], $course_row['course_rev'], $teacher, $currentDate, $currentTime, $currentTime, $year, $intake);
        }
        $sql_insert_programs = "INSERT INTO `programs` (`program_course_code_ref`, 
        `program_course_rev_ref`, `program_assignment`) 
        VALUES (?, ?, ?)";
        $stmt_insert_programs = $conn->prepare($sql_insert_programs);
        $stmt_insert_programs->bind_param(
            "sss",
            $course_row['course_code'],
            $course_row['course_rev'],
            $code
        );
        $stmt_insert_programs->execute();
        echo "success";

    }



    // Close the statement and connection
    $stmt_courses->close();
    $stmt_insert_sessions->close();
    $stmt_insert_programs->close();
    $conn->close();
}

?>