<?php
session_start();

if (empty($_SESSION['user_id'])) {
    // If the user is not logged in, check for the "remember_token" cookie
    if (isset($_COOKIE['remember_token'])) {
        $token = $_COOKIE['remember_token'];

        // Look up the token in the database (optional)
        // Retrieve the user's ID associated with the token.
        // Here I tried to use cookies but I had to change database as well to add token column in admin table because value of the token should be a string which is unique

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
    <link rel="stylesheet" href="style/grades.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>

<body>
    <nav id="navbar">
        <div class="back-btn">

            <button onclick="window.history.back()">BACK</button>
        </div>
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
        <div class="add_student_popup" id="addGradePopup">
            <img id="add_student_popup_close_img" src="assets/closebut.png" alt="">
            <p class="title">Add Grade</p>
            <form method="post" class="add_grade_form form" id="addGradeForm">
                <label for="epitaEmail">Select Student</label>
                <select class="select" name="epitaEmail" id="">

                    <?php
                    require_once "php/connect.php";
                    $conn = conn();
                    $sql = "SELECT student_epita_email FROM students s WHERE s.STUDENT_POPULATION_CODE_REF = '" . $code . "' AND s.STUDENT_POPULATION_YEAR_REF = " . $year . " 
                    AND s.STUDENT_POPULATION_PERIOD_REF LIKE '" . $intake . "'
                    ORDER BY student_epita_email"
                    ;
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        echo ("<option value='" . $row['student_epita_email'] . "'>" . $row['student_epita_email'] . "</option>");
                    }
                    ?>
                </select>
                <label for="examType">Exam Type</label>
                <select class="select" name="examType" id="">
                    <option value="Project">Project</option>
                    <option value="Lab">Lab</option>
                    <option value="Quiz">Quiz</option>
                </select>
                <label for="examWeight">Exam Weight</label>
                <input type="number" max="4" min="1" name="examWeight" id="">
                <label for="studentGrade">Grade(20)</label>
                <input type="number" max="20" min="0" name="studentGrade" id="">
                <button class="submit" type="submit">Add Grade</button>
            </form>
        </div>
        <div class="add_search_student_container">
            <button title="Add" class="add_student" id="addGradeButton">
                <svg height="25" width="25" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M11 11V5h2v6h6v2h-6v6h-2v-6H5v-2z" fill="currentColor"></path>
                </svg>
                <span>Add</span>
            </button>
            <div class="search-input-wrapper">
                <button class="search-icon">
                    <i class="fa-solid fa-magnifying-glass" id="search-glass-icon"></i>
                </button>
                <input placeholder="search.." class="search-input" id="searchInput" name="search" type="text">
            </div>
        </div>
        <div class="all-grades">
            <h1>All the Grades</h1>
            <table>
                <thead>
                    <tr>
                        <th>EMAIL</th>
                        <th>FIrst Name</th>
                        <th>Last Name</th>
                        <th>Course</th>
                        <th>Exam Type</th>
                        <th>Exam Weight</th>
                        <th>Grade(20)</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    require_once "php/connect.php";
                    $conn = conn();
                    $sql = "SELECT s.student_epita_email, c.contact_first_name,c.contact_last_name,
                c2.course_name,g.grade_exam_type_ref,e.exam_weight,g.grade_score 
                FROM grades g 
                JOIN students s ON g.grade_student_epita_email_ref = s.student_epita_email 
                JOIN contacts c ON s.student_contact_ref = c.contact_email 
                JOIN courses c2 ON g.grade_course_code_ref = c2.course_code 
                JOIN exams e ON g.grade_course_code_ref = e.exam_course_code AND g.grade_exam_type_ref = e.exam_type
                WHERE c2.course_name = '" . $course_name . "' 
                AND s.student_population_code_ref = '" . $code . "' 
                AND s.student_population_year_ref = " . $year . " 
                AND s.student_population_period_ref = '" . $intake . "'
                order by student_epita_email";
                    $result = $conn->query($sql);
                    $i = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['student_epita_email'] . "</td>";
                        echo "<td>" . $row['contact_first_name'] . "</td>";
                        echo "<td>" . $row['contact_last_name'] . "</td>";
                        echo "<td>" . $row['course_name'] . "</td>";
                        echo "<td>" . $row['grade_exam_type_ref'] . "</td>";
                        echo "<td>" . $row['exam_weight'] . "</td>";
                        echo "<td>" . $row['grade_score'] . "</td>";
                        echo "<td><button class='edit-button' onclick=\"editGrade(this.parentNode.parentNode)\"><i class='fas fa-pen'></i></button><button onclick=\"removeGrade(this.parentNode.parentNode)\" class='trash-button'  id='remove_student_" . $i . "'> <i class='fas fa-trash'></i></button></td>";
                        echo "</tr>";
                        $i += 1;
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="final-grades">
            <h1>Final Grades</h1>
        </div>
    </div>

    <?php $conn->close(); ?>

    <script src="JS/navbar.js"></script>
    <script src="JS/editGrades.js"></script>
    <script src="JS/removeGrades.js"></script>
    <script src="JS/grades.js"></script>
    <script src="JS/addGrade.js"></script>
</body>

</html>