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
if (isset($_GET['email'])) {
    $email = $_GET['email'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style/nav.css">
    <link rel="stylesheet" href="style/studentgrade.css">
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
        <div class="leftside">
            <div class="profile-pic">
                <img width="200px" src="assets\avatar_student.webp" alt="">
            </div>
            <div class="student-courses">
                <span>Course List</span>
                <ul>
                    <?php
                    require 'php/get_student_data.php';
                    while ($course_row = $course_result->fetch_assoc()) {
                        echo "<li>" . $course_row['course_name'] . "</li>";
                    }
                    ?>
                </ul>
            </div>
        </div>
        <div class="rightside">
            <div class="name">

                <?php
                // Split the email address by dot ('.')
                // Use regular expression to match the first and last name
                if (preg_match('/^([^\.]+)\.([^@]+)@/', $email, $matches)) {
                    $firstName = $matches[1];
                    $lastName = $matches[2];

                    echo "<h2>" . $firstName . " " . $lastName . "</h2>";

                }
                ?>
                <p>AIs 2020 FALL</p>
            </div>
            <div class="above-content-wrapper">
                <div class="above-content-leftside-wrapper">
                    <div class="average-grade">
                        <span>Average Grade</span>
                        <p>18.5</p>
                    </div>
                    <div class="send-message">
                        <button class="bt" id="bt">
                            <span class="msg" id="msg"></span>
                            SEND EMAIL
                        </button>
                    </div>
                </div>
                <div class="chart">
                    <canvas id="courseChart"></canvas>
                </div>
            </div>
            <div class="about-text">
                <i class="fa-solid fa-user"></i>
                <span>About</span>
            </div>
            <div class="about">
                <div class="contact-information">
                    <p>Contact Information</p>
                    <ul>
                        <li>Phone</li>
                        <li>Address</li>
                        <li>E-mail</li>
                        <li>name</li>
                    </ul>
                </div>
                <div class="basic-information">
                    <p>Basic Information</p>
                    <ul>
                        <li>Birthday</li>
                        <li>Gender</li>
                    </ul>
                </div>
            </div>
        </div>















    </div>
    <div class="all-grades">
        <h3>Grades For</h3>
        <?php
        // Split the email address by dot ('.')
        // Use regular expression to match the first and last name
        if (preg_match('/^([^\.]+)\.([^@]+)@/', $email, $matches)) {
            $firstName = $matches[1];
            $lastName = $matches[2];

            echo "<h1>" . $firstName . " " . $lastName . "</h1>";

        }
        ?>
        <table>
            <thead>
                <tr>
                    <th>Course</th>
                    <th>Exam Type</th>
                    <th>Exam Weight</th>
                    <th>Grade(20)</th>

                </tr>
            </thead>
            <tbody>
                <?php
                require_once "php/connect.php";
                $conn = conn();
                $sql = "SELECT c.course_name, `grade_exam_type_ref`,e.exam_weight,e.exam_type, `grade_score` FROM `grades` g
                JOIN exams e ON e.exam_course_code = g.grade_course_code_ref AND e.exam_type = g.grade_exam_type_ref
                JOIN courses c ON c.course_code = g.grade_course_code_ref
                WHERE grade_student_epita_email_ref = '" . $email . "';";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['course_name'] . "</td>";
                    echo "<td>" . $row['grade_exam_type_ref'] . "</td>";
                    echo "<td>" . $row['exam_weight'] . "</td>";
                    echo "<td>" . $row['grade_score'] . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php $conn->close(); ?>
    <script src="JS/navbar.js"></script>
    <script src="JS/editGrades.js"></script>
    <script src="JS/removeGrades.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('courseChart');

        // Get the population data from PHP and format it as an array
        const data = [
            3, 10, 17
        ];

        // Create the doughnut chart
        new Chart(ctx, {
            type: 'polarArea',
            data: {
                labels: [
                    'Database',
                    'Python',
                    'Algo'
                ],
                datasets: [{
                    label: 'Course Grade',
                    data: data,
                    backgroundColor: [
                        'rgb(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                    ],
                    borderWidth: 1,
                }],
            },
            options: {
                responsive: false,

            },
        });
    </script>
</body>

</html>