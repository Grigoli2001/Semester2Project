<?php
session_start();

if (empty($_SESSION['user_id'])) {
    // If the user is not logged in, check for the "remember_token" cookie
    if (isset($_COOKIE['remember_token'])) {
        $token = $_COOKIE['remember_token'];

        // Look up the token in the database (optional)
        // Retrieve the user's ID associated with the token.

        // Log in the user automatically
        // For example, you can set session variables or create a logged-in session.
        $_SESSION['user_id'] = $user_id; // Set the user ID based on the token lookup
    } else {
        // Redirect to the login page if the user is not logged in and no "remember_token" cookie is found
        header("Location: login.php");
        exit;
    }
}
;
if (isset($_GET['code']) && isset($_GET['year']) && isset($_GET['intake'])) {
    $code = $_GET['code'];
    $year = $_GET['year'];
    $intake = $_GET['intake'];
    $course_name = $_GET['course_name'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style/nav.css">
    <link rel="stylesheet" href="style/grades.css">
</head>

<body>
    <nav id="navbar">
        <button>Home</button>
        <div class="nav_logo">
            <a href="home.php">
                <img src="assets/EPITA School of Engineering and Computer Science_Logo.png" alt="" />
            </a>
        </div>
        <form action="php/logout.php" method="post">
            <button class="Btn">

                <div class="sign"><svg viewBox="0 0 512 512">
                        <path
                            d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z">
                        </path>
                    </svg></div>

                <div class="text">Logout</div>
            </button>
        </form>
    </nav>
    <div class="container">
        <table>
            <thead>
                <tr>
                    <td>EMAIL</td>
                    <td>FIrst Name</td>
                    <td>Last Name</td>
                    <td>Course</td>
                    <td>Grade(20)</td>
                </tr>
            </thead>
            <tbody>
                <?php
                require_once "php/connect.php";
                $conn = conn();
                $sql = "SELECT s.STUDENT_EPITA_EMAIL ,c.CONTACT_FIRST_NAME ,c.CONTACT_LAST_NAME ,c2.COURSE_NAME ,round(SUM(g.GRADE_SCORE*e.EXAM_WEIGHT)/SUM(e.EXAM_WEIGHT),2) as grade
                FROM STUDENTS s JOIN CONTACTS c ON s.STUDENT_CONTACT_REF = c.CONTACT_EMAIL 
                JOIN GRADES g ON s.STUDENT_EPITA_EMAIL = g.GRADE_STUDENT_EPITA_EMAIL_REF 
                JOIN EXAMS e ON g.GRADE_COURSE_CODE_REF = e.EXAM_COURSE_CODE 
                JOIN COURSES c2 ON e.EXAM_COURSE_CODE = c2.COURSE_CODE 
                WHERE s.STUDENT_POPULATION_CODE_REF = '" . $code . "' AND s.STUDENT_POPULATION_YEAR_REF = " . $year . " 
                AND s.STUDENT_POPULATION_PERIOD_REF LIKE  '" . $intake . "' AND e.EXAM_COURSE_CODE LIKE '" . $course_name . "' 
                GROUP BY STUDENT_EPITA_EMAIL, e.EXAM_COURSE_CODE";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['STUDENT_EPITA_EMAIL'] . "</td>";
                    echo "<td>" . $row['CONTACT_FIRST_NAME'] . "</td>";
                    echo "<td>" . $row['CONTACT_LAST_NAME'] . "</td>";
                    echo "<td>" . $row['COURSE_NAME'] . "</td>";
                    echo "<td>" . $row['grade'] . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php $conn->close(); ?>

    <script src="JS/navbar.js"></script>
</body>

</html>