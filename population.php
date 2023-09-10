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
        <div class="content">
            <!-- Student Pop Up -->
            <div class="add_student_popup" id="addStudentPopup">
                <div class="popupHeader" id="popupHeader">

                    <img id="add_student_popup_close_img" src="assets/closebut.png" alt="">
                </div>

                <form class="form" id="addStudentForm">
                    <p class="title">Add Student</p>
                    <div class="flex">
                        <label>
                            <input required="" type="text" name="firstname" placeholder="" class="input">
                            <span>Firstname</span>
                        </label>

                        <label>
                            <input required="" type="text" name="lastname" placeholder="" class="input">
                            <span>Lastname</span>
                        </label>
                    </div>

                    <label>
                        <input required="" type="email" placeholder="" class="input" name="student_email">
                        <span>Email</span>
                    </label>

                    <label>
                        <div class="birthdate-div">
                            <select class="select" name="birth_day" id="">
                                <option value="">Day</option>
                                <?php for ($i = 1; $i <= 31; $i++) {
                                    echo ("<option  value='" . $i . "'>" . $i . "</option>");
                                }
                                ?>
                            </select>
                            <select class="select" name="birth_month" id="">
                                <option value="">Month</option>
                                <?php for ($i = 1; $i <= 12; $i++) {
                                    echo ("<option value='" . $i . "'>" . $i . "</option>");
                                } ?>
                                <label for="month">month</label>
                            </select>
                            <select class="select" name="birth_year" id="">
                                <option value="">Year</option>
                                <?php for ($i = date('Y') - 18; $i >= date('Y') - 70; $i--) {
                                    echo ("<option value='" . $i . "'>" . $i . "</option>");
                                }
                                ;
                                ?>
                            </select>
                        </div>
                    </label>
                    <label>
                        <input required="" type="text" placeholder="" class="input" name="address">
                        <span>Address</span>
                    </label>
                    <label>
                        <input required="" placeholder="" type="text" class="input" name="city">
                        <span>City</span>
                    </label>
                    <label>
                        <select class="select_country" name="country" id="">
                            <option value="">Country</option>
                            <option value="Georgia">Georgia</option>
                            <option value="France">France</option>
                            <option value="USA">USA</option>
                            <option value="India">India</option>
                            <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                        </select>
                        <select class="select_country" name="enrollment_status" id="">
                            <option value="">Enrollment Status</option>
                            <option value="Confirmed">Confirmed</option>
                            <option value="Selected">Selected</option>
                        </select>
                    </label>
                    <button class="submit" type="submit">Add Student</button>

                </form>
            </div>

            <!-- Course Pop Up -->
            <div class="add_course_popup" id="addCoursePopup">
                <img id="add_course_popup_close_img" src="assets/closebut.png" alt="">
                <p class="title">Add Student</p>
                <div class="add-course-question" id="addCourseQuestionDiv">
                    <button id="addExistingCourse">add existing course</button>
                    <button id="addNewCourse">add new course</button>
                </div>
                <div class="add-existing-course" id="addExistingCourseDiv">
                    <form method="post" class="add_course_form" id="addExistingCourseForm">
                        <div class="add-courseforminput-wrapper">
                            <label for="coursename">Name</label>
                            <select class="select_coursename" name="coursename" id="">
                                <?php
                                require_once "php/connect.php";
                                $conn = conn();
                                $sql = "SELECT course_name FROM courses";
                                $result = $conn->query($sql);

                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row['course_name'] . "'>" . $row['course_name'] . "</option>";
                                }
                                ?>
                            </select>
                            <label for="sessioncount">Session Count</label>
                            <input type="number" name="sessioncount" value="0" max="30" min="0">
                            <label for="teacher">Teacher</label>
                            <select class="select_teacher" name="teacher" id="">
                                <?php
                                require_once "php/connect.php";
                                $conn = conn();
                                $sql = "SELECT teacher_epita_email FROM teachers;";
                                $result = $conn->query($sql);

                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row['teacher_epita_email'] . "'>" . $row['teacher_epita_email'] . "</option>";
                                }
                                ?>
                            </select>

                        </div>


                        <button class="submit" type="submit" id="existingCourseSubmitBtn">Add Course</button>
                    </form>
                </div>
                <div class="add-new-course" id="addNewCourseDiv">
                    <form class="add_course_form" id="addNewCourseForm">
                        <div class="add-courseforminput-wrapper">


                            <label for="new_coursename">Course Name</label>


                            <input type="text" name="new_coursename" placeholder="Relational Databases">
                            <label for="new_coursecode">Course Code</label>
                            <input type="text" name="new_coursecode" placeholder="PG_PYTHON">
                            <label for="teacher">Teacher</label>
                            <select class="select_teacher" name="teacher" id="">
                                <?php
                                require_once "php/connect.php";
                                $conn = conn();
                                $sql = "SELECT teacher_epita_email FROM teachers;";
                                $result = $conn->query($sql);

                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row['teacher_epita_email'] . "'>" . $row['teacher_epita_email'] . "</option>";
                                }
                                ?>
                            </select>
                            <label for="sessioncount">Session Count</label>
                            <input max="40" min="0" type="number" name="sessioncount" value="0">
                            <label for="duration">Duration</label>
                            <input max="50" min="0" type="number" name="duration" id="" value="0">
                            <label for="new_course_description">Course Description</label>
                            <textarea name="new_course_description" id="" cols="10" rows="5"></textarea>

                        </div>
                        <button class="submit" type="submit" id="newCourseSubmitBtn">Add Course</button>
                    </form>
                </div>
            </div>
            <div class="students">
                <div class="add_search_student_container">
                    <button title="Add" class="add_student">
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
                        $sql = "SELECT
                        s.STUDENT_EPITA_EMAIL,
                        c.CONTACT_FIRST_NAME,
                        c.CONTACT_LAST_NAME,
                        CONCAT(SUM(fgrades), '/', COUNT(fgrades)) AS grade
                    FROM
                        STUDENTS s
                        LEFT JOIN CONTACTS c ON s.STUDENT_CONTACT_REF = c.CONTACT_EMAIL
                        LEFT JOIN (
                            SELECT
                                g.GRADE_STUDENT_EPITA_EMAIL_REF,
                                e.EXAM_COURSE_CODE,
                                CASE WHEN SUM(g.GRADE_SCORE * e.EXAM_WEIGHT) / SUM(e.EXAM_WEIGHT) >= 10 THEN 1 ELSE 0 END AS fgrades
                            FROM
                                GRADES g
                                JOIN EXAMS e ON g.GRADE_COURSE_CODE_REF = e.EXAM_COURSE_CODE 
                            GROUP BY
                                g.GRADE_STUDENT_EPITA_EMAIL_REF, e.EXAM_COURSE_CODE
                        ) ag ON s.STUDENT_EPITA_EMAIL = ag.GRADE_STUDENT_EPITA_EMAIL_REF
                    WHERE
                        s.STUDENT_POPULATION_CODE_REF = '" . $code . "'
                        AND s.STUDENT_POPULATION_YEAR_REF = " . $year . "
                        AND s.STUDENT_POPULATION_PERIOD_REF LIKE '" . $intake . "'
                    GROUP BY
                        s.STUDENT_EPITA_EMAIL, c.CONTACT_FIRST_NAME, c.CONTACT_LAST_NAME
                    ORDER BY
                        s.STUDENT_EPITA_EMAIL;";
                        $result = $conn->query($sql);
                        $i = 1;
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td><a href='studentgrades.php?email=" . $row['STUDENT_EPITA_EMAIL'] . "'>" . $row['STUDENT_EPITA_EMAIL'] . "</a></td>";
                            echo "<td>" . $row['CONTACT_FIRST_NAME'] . "</td>";
                            echo "<td>" . $row['CONTACT_LAST_NAME'] . "</td>";
                            echo "<td>" . $row['grade'] . "</td>";
                            echo "<td><button class='edit-button' onclick=\"editNameAndLastName(this.parentNode.parentNode)\"><i class='fas fa-pen'>
                            </i></button><button class='trash-button' onclick=\"removeStudent(this.parentNode.parentNode)\" id='remove_student_" . $i . "'> 
                            <i class='fas fa-trash'></i></button></td>";
                            echo "</tr>";
                            $i += 1;
                        }

                        ?>
                    </tbody>
                </table>
            </div>
            <div class="courses">
                <div class="add_search_courses_container">
                    <button title="Add" class="add_course" id="addCourseButton">
                        <svg height="25" width="25" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M11 11V5h2v6h6v2h-6v6h-2v-6H5v-2z" fill="currentColor"></path>
                        </svg>
                        <span>Add</span>
                    </button>
                    <div class="search-input-wrapper">
                        <button class="search-icon">
                            <i class="fa-solid fa-magnifying-glass" id="search-glass-icon-courses"></i>
                        </button>
                        <input placeholder="search.." class="search-input" id="searchCourseInput" name="search"
                            type="text">
                    </div>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Coures ID</th>
                            <th>Course Name</th>
                            <th>Session Count</th>
                            <th>Teacher</th>
                        </tr>

                    </thead>
                    <tbody>
                        <?php
                        require_once "php/connect.php";
                        require_once "php/popcount.php";
                        $conn = conn();
                        $sql = "SELECT c.COURSE_CODE AS ID, c.course_name,c.COURSE_NAME , COUNT(s.SESSION_COURSE_REF),s.session_prof_ref as teacher 
                        FROM COURSES c JOIN PROGRAMS p ON c.COURSE_CODE = p.PROGRAM_COURSE_CODE_REF JOIN SESSIONS s ON s.SESSION_COURSE_REF = c.COURSE_CODE 
                        WHERE p.PROGRAM_ASSIGNMENT LIKE '" . $code . "' AND s.SESSION_POPULATION_YEAR  =" . $year . " AND s.SESSION_POPULATION_PERIOD LIKE '" . $intake . "'GROUP BY ID";
                        $result = $conn->query($sql);
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['ID'] . "</td>";
                            echo "<td><a href='grades.php?code=" . $code . "&year=" . $year . "&intake=" . $intake . "&course_name=" . $row['course_name'] . "'>" . $row['COURSE_NAME'] . "</a></td>";
                            echo "<td>" . $row['COUNT(s.SESSION_COURSE_REF)'] . "</td>";
                            echo "<td>" . $row['teacher'] . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        const popup = document.getElementById("addStudentPopup");
        const header = document.getElementById("popupHeader");
        let isDragging = false;
        let offsetX, offsetY;

        header.addEventListener("mousedown", (e) => {
            isDragging = true;
        });

        document.addEventListener("mousemove", (e) => {
            if (!isDragging) return;

            const left = e.clientX;
            const top = e.clientY;

            popup.style.left = left + "px";
            popup.style.top = top + "px";
        });

        document.addEventListener("mouseup", () => {
            isDragging = false;
        });
    </script>
    <?php $conn->close(); ?>
    <script src="JS/navbar.js"></script>
    <script src="JS/populations.js"></script>
    <script src="JS\addCourse.js"></script>

</body>

</html>