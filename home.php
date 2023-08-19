<?php
session_start();

if (empty($_SESSION['user_id'])) {

    // Redirect to the login page if the user is not logged in and no "remember_token" cookie is found
    header("Location: login.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style/home.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="style/nav.css?v=<?php echo time(); ?>">

</head>

<body>
    <nav id="navbar">
        <button>Home</button>
        <div class="nav_logo">
            <a href="">
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
        <div class="welcome">
            <p>Welcome, </p>
            <p style="font-weight: bolder;">
                <?php
                echo $_SESSION['user_id'];
                ?>
            </p>
        </div>
        <div class="program-list">
            <h1>Active Populations</h1>
            <?php
            require_once "php/connect.php";
            require_once "php/popcount.php";
            $conn = conn();
            $sql = "SELECT * FROM populations";
            $result = $conn->query($sql);

            while ($row = $result->fetch_assoc()) {
                $popcount = pop_count($row['population_year'], $row['population_code'], $row['population_period'], $conn);
                echo ("<a href='population.php?code=" . $row['population_code'] .
                    "&year=" . $row['population_year'] .
                    "&intake=" . $row['population_period'] .
                    "'><p>" . $row['population_code'] . " " . $row['population_year'] . " " . $row['population_period'] . " (" . $popcount . ")</p></a>");
            }


            ?>
        </div>
        <div class="attendance">
            <h1>Overall Attendance</h1>
            <?php
            require_once "php/connect.php";
            require_once "php/attendance.php";
            $conn = conn();
            $sql = "SELECT * FROM populations";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                $attentance = attendance($row['population_year'], $row['population_code'], $row['population_period'], $conn);
                echo ("<p>" . $row['population_code'] . " " . $row['population_year'] . " " . $row['population_period'] . " (" . $attentance . "%)</p>");
            }
            ?>
        </div>
    </div>




    <?php $conn->close() ?>
    <script>
        const navbarHeight = document.getElementById("navbar").offsetHeight;

        // Set the top margin of the .container_home class
        const containerHome = document.querySelector(".container_home");
        containerHome.style.marginTop = `${navbarHeight + 10}px`;
    </script>
</body>

</html>