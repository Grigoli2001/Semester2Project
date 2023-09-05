<?php


function attendance($year, $course, $intake, $conn)
{
    // Prepare the SQL query with placeholders for the parameters
    $sql = "SELECT round((Sum(a.ATTENDANCE_PRESENCE) * 100)/count(a.ATTENDANCE_PRESENCE)) as attendance FROM STUDENTS s JOIN ATTENDANCE a ON s.STUDENT_EPITA_EMAIL = a.ATTENDANCE_STUDENT_REF
    WHERE s.STUDENT_POPULATION_YEAR_REF = ? 
    AND s.STUDENT_POPULATION_CODE_REF LIKE ?
    AND s.STUDENT_POPULATION_PERIOD_REF LIKE ? 
    AND s.STUDENT_ENROLLMENT_STATUS LIKE 'completed'";



    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters to the statement
    $stmt->bind_param("sss", $year, $course, $intake);

    // Execute the statement
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Fetch the row and extract the count value
    $row = $result->fetch_assoc();
    $att = $row['attendance'];

    // Close the statement
    $stmt->close();

    // Return the count value
    return $att;
}
?>