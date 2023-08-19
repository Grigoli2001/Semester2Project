<?php
session_start();

if (empty($_SESSION['user_id'])) {

    // Redirect to the login page if the user is not logged in and no "remember_token" cookie is found
    header("Location: login.php");
    exit;
}
;
if (isset($_GET['code']) && isset($_GET['year']) && isset($_GET['intake'])) {
    $code = $_GET['code'];
    $year = $_GET['year'];
    $intake = $_GET['intake'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style/nav.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="style/population.css?v=<?php echo time(); ?>">
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
        <div class="content">
            <div class="students">
                <table>
                    <thead>
                        <tr>
                            <th>EMAIL</th>
                            <th>FIrst Name</th>
                            <th>Last Name</th>
                            <th>Passed</th>
                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        require_once "php/connect.php";
                        require_once "php/popcount.php";
                        $conn = conn();
                        $sql = "WITH avg_grades AS (
                SELECT s.STUDENT_EPITA_EMAIL, e.EXAM_COURSE_CODE, 
                    (CASE WHEN SUM(g.GRADE_SCORE * e.EXAM_WEIGHT) / SUM(e.EXAM_WEIGHT) >= 10 THEN 1 ELSE 0 END) AS fgrades
                FROM STUDENTS s
                JOIN GRADES g ON s.STUDENT_EPITA_EMAIL = g.GRADE_STUDENT_EPITA_EMAIL_REF
                JOIN EXAMS e ON g.GRADE_COURSE_CODE_REF = e.EXAM_COURSE_CODE 
                GROUP BY s.STUDENT_EPITA_EMAIL, e.EXAM_COURSE_CODE
            )
            SELECT s.STUDENT_EPITA_EMAIL, c.CONTACT_FIRST_NAME, c.CONTACT_LAST_NAME,
                    CONCAT(SUM(fgrades), '/', COUNT(fgrades)) AS grade
            FROM STUDENTS s
            JOIN CONTACTS c ON s.STUDENT_CONTACT_REF = c.CONTACT_EMAIL
            JOIN avg_grades ag ON s.STUDENT_EPITA_EMAIL = ag.STUDENT_EPITA_EMAIL
            JOIN EXAMS e ON ag.EXAM_COURSE_CODE = e.EXAM_COURSE_CODE 
            WHERE s.STUDENT_POPULATION_CODE_REF = '" . $code . "' AND 
                    s.STUDENT_POPULATION_YEAR_REF = " . $year . " AND 
                    s.STUDENT_POPULATION_PERIOD_REF LIKE '" . $intake . "'
            GROUP BY s.STUDENT_EPITA_EMAIL, c.CONTACT_FIRST_NAME, c.CONTACT_LAST_NAME
            ORDER BY s.STUDENT_EPITA_EMAIL";
                        $result = $conn->query($sql);
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['STUDENT_EPITA_EMAIL'] . "</td>";
                            echo "<td>" . $row['CONTACT_FIRST_NAME'] . "</td>";
                            echo "<td>" . $row['CONTACT_LAST_NAME'] . "</td>";
                            echo "<td>" . $row['grade'] . "</td>";
                            echo "<td><button onclick=\"editNameAndLastName(this.parentNode.parentNode)\">Edit</button></td>";
                            echo "</tr>";
                        }

                        ?>
                    </tbody>
                </table>
            </div>
            <div class="courses">
                <table>
                    <thead>
                        <tr>
                            <th>Coures ID</th>
                            <th>Course Name</th>
                            <th>Session Count</th>
                        </tr>

                    </thead>
                    <tbody>
                        <?php
                        require_once "php/connect.php";
                        require_once "php/popcount.php";
                        $conn = conn();
                        $sql = "SELECT c.COURSE_CODE AS ID,c.COURSE_NAME , COUNT(s.SESSION_COURSE_REF)  
                        FROM COURSES c JOIN PROGRAMS p ON c.COURSE_CODE = p.PROGRAM_COURSE_CODE_REF JOIN SESSIONS s ON s.SESSION_COURSE_REF = c.COURSE_CODE 
                        WHERE p.PROGRAM_ASSIGNMENT LIKE '" . $code . "' AND s.SESSION_POPULATION_YEAR  =" . $year . " AND s.SESSION_POPULATION_PERIOD LIKE '" . $intake . "'GROUP BY ID";
                        $result = $conn->query($sql);
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['ID'] . "</td>";
                            echo "<td><a href='grades.php?code=" . $code . "&year=" . $year . "&intake=" . $intake . "&course_name=" . $row['ID'] . "'>" . $row['COURSE_NAME'] . "</a></td>";
                            echo "<td>" . $row['COUNT(s.SESSION_COURSE_REF)'] . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php $conn->close(); ?>
    <script src="JS/navbar.js"></script>
    <script src="JS/populations.js"></script>

</body>

</html>