<?php


function pop_count($year, $course, $intake, $conn)
{
    // Prepare the SQL query with placeholders for the parameters
    $sql = "SELECT COUNT(STUDENT_EPITA_EMAIL) as count 
            FROM STUDENTS s 
            WHERE STUDENT_POPULATION_YEAR_REF = ? 
            AND STUDENT_POPULATION_CODE_REF LIKE ? 
            AND s.STUDENT_POPULATION_PERIOD_REF LIKE ?";

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
    $count = $row['count'];

    // Close the statement
    $stmt->close();

    // Return the count value
    return $count;
}
?>