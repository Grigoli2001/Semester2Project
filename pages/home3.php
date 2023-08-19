<!-- WITH avg_grades AS (
  SELECT s.STUDENT_EPITA_EMAIL, e.EXAM_COURSE_CODE, 
         (CASE WHEN SUM(g.GRADE_SCORE * e.EXAM_WEIGHT) / SUM(e.EXAM_WEIGHT) >= 10 THEN 1 ELSE 0 END) AS fgrades
  FROM STUDENTS s
  JOIN GRADES g ON s.STUDENT_EPITA_EMAIL = g.GRADE_STUDENT_EPITA_EMAIL_REF
  JOIN EXAMS e ON g.GRADE_COURSE_CODE_REF = e.EXAM_COURSE_CODE 
    GROUP BY s.STUDENT_EPITA_EMAIL, e.EXAM_COURSE_CODE
)
SELECT s.STUDENT_EPITA_EMAIL, c.CONTACT_FIRST_NAME, c.CONTACT_LAST_NAME,
       SUM(fgrades)  || '/' || COUNT(fgrades) 
FROM STUDENTS s
JOIN CONTACTS c ON s.STUDENT_CONTACT_REF = c.CONTACT_EMAIL
JOIN avg_grades ag ON s.STUDENT_EPITA_EMAIL = ag.STUDENT_EPITA_EMAIL
JOIN EXAMS e ON ag.EXAM_COURSE_CODE = e.EXAM_COURSE_CODE 
WHERE s.STUDENT_POPULATION_CODE_REF = '{course}' AND 
      s.STUDENT_POPULATION_YEAR_REF = {year} AND 
      s.STUDENT_POPULATION_PERIOD_REF LIKE '{intake}'
GROUP BY s.STUDENT_EPITA_EMAIL, c.CONTACT_FIRST_NAME, c.CONTACT_LAST_NAME
ORDER BY s.STUDENT_EPITA_EMAIL;
-->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="home.css?v=<?php echo time(); ?>">
    <title>Document</title>
</head>

<body>
    <nav>
        <button>Home</button>
        <div class="nav_btn">
            <a href="">
                <img src="EPITA School of Engineering and Computer Science_Logo.png" alt="" />
            </a>
        </div>
        <form action="" method="post">
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
    <div class="container_home">
        <!-- Welcome Text -->
        <div class="welcome">
            <p>Welcome,</p>
            <p style="font-weight: bolder;">
                <?php
                echo $_SESSION['user_id'];
                ?>
            </p>
        </div>
        <div class="first-part">
            <div class="program-list">
                <h1>Active Populations</h1>
                <?php
                require_once "connect.php";
                $conn = conn();
                $sql = "SELECT * From populations";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {

                    echo ("<ul><li>" . $row['population_code'] . "</li></ul>");
                }
                ;



                ?>
            </div>
            <div class="pie">
                <canvas id="pie"></canvas>
            </div>
        </div>
        <div class="second-part">
            <div class="att-list">
                <h1>Overall Attendance</h1>

                <ul>
                    <p style="list-style: none;">AIs - Artificial Intelligence Systems -
                        F2020({{att("AIs",2020,"FALL")}}%)
                    </p>
                </ul>
                <ul>
                    <p style="list-style: none;">AIs - Artificial Intelligence Systems -
                        S2021({{att("AIs",2021,"SPRING")}}%)</p>
                </ul>
                <ul>
                    <p style="list-style: none;">CS - Computer Science - F2020({{att("CS",2020,"FALL")}}%)</p>
                </ul>
                <ul>
                    <p style="list-style: none;">CS - Computer Science - S2021({{att("CS",2021,"SPRING")}}%)</p>
                </ul>
                <ul>
                    <p style="list-style: none;">DSA - Data Structures and Algorithms -
                        F2020({{att("DSA",2020,"FALL")}}%)
                    </p>
                </ul>
                <ul>
                    <p style="list-style: none;">DSA - Data Structures and Algorithms -
                        S2021({{att("DSA",2021,"SPRING")}}%)
                    </p>
                </ul>
                <ul>
                    <p style="list-style: none;">ISM - Information Systems Management -
                        F2020({{att("ISM",2020,"FALL")}}%)
                    </p>
                </ul>
                <ul>
                    <p style="list-style: none;">ISM - Information Systems Management -
                        S2021({{att("ISM",2021,"SPRING")}}%)
                    </p>
                </ul>
                <ul>
                    <p style="list-style: none;">SE - Software Engeneering - F2020({{att("SE",2020,"FALL")}}%)</p>
                </ul>
                <ul>
                    <p style="list-style: none;">SE - Software Engeneering - S2021({{att("SE",2021,"SPRING")}}%)</p>
                </ul>
            </div>
            <div class="bar">
                <canvas id="bar"></canvas>
            </div>
        </div>
    </div>
    <p id="generated-time"></p>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>const ctx = document.getElementById('pie').getContext("2d");

        var piegraph = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['AIs-F2020', 'AIs-S2021', 'CS-F2020', 'CS-S2021', 'DSA-F2020', 'DSA-S2021', 'ISM-F2020', 'ISM-S2021', 'SE-F2020', 'SE-S2021'],
                datasets: [{
                    label: 'Average Count',
                    data: {{ count_list }},
            borderWidth: 1
        }]
          },
        options: {
            responsive: false
        }
        });
        const ctx2 = document.getElementById('bar').getContext("2d");

        var piegraph = new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: ['AIs-F2020', 'AIs-S2021', 'CS-F2020', 'CS-S2021', 'DSA-F2020', 'DSA-S2021', 'ISM-F2020', 'ISM-S2021', 'SE-F2020', 'SE-S2021'],
                datasets: [{
                    label: 'Attendance Rate',
                    data: {{ att_list }},
            borderWidth: 1
        }]
          },
        options: {
            responsive: false
        }
        });

        // Get the height of the navbar
        const navbarHeight = document.getElementById("navbar").offsetHeight;

        // Set the top margin of the .container_home class
        const containerHome = document.querySelector(".container_home");
        containerHome.style.marginTop = `${navbarHeight}px`;
    </script>


    </div>
</body>

</html>