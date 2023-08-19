<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="style/login.css">
  <link rel="stylesheet" href="style/nav.css">
</head>

<body>

  <div class="container_login">
    <nav class="login_nav">
      <a href="/">
        <img src="EPITA School of Engineering and Computer Science_Logo.png" alt="" />
      </a>
      <div class="nav_btn">
        <button>About </button>
        <button>Student portal</button>
        <button>Admin Portal</button>
      </div>
    </nav>
    <div class="login_box">
      <div class="welcome_login">
        <img src="EPITA School of Engineering and Computer Science_Logo.png" alt="" />
      </div>
      <div class="welcome_text_login">
        <h2>Hello !</h2>
        <p>Connect to discover the functionality</p>
      </div>

      <form class="login_form" method="post">
        <input type="text" placeholder="Enter Your Email" name="email" />
        <input type="password" name="passwrd" id="passwrd" placeholder="*******" />
        <div class="below_input">
          <input type="checkbox" name="rememberMe" id="" />
          <span>Remember Me</span>
          <a href="/"> Forgot my password</a>
        </div>
        <button name="login_form" type="submit">Log In</button>
      </form>

    </div>
  </div>
  </div>
  <?php
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "epita";

  if (isset($_POST['login_form'])) {

    require_once "php/connect.php";
    $conn = conn();
    $sql = "SELECT * FROM admins WHERE (admin_email ='" . $_POST["email"] . "' OR admin_username = '" . $_POST['email'] . "') and admin_password = '" . $_POST['passwrd'] . "'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $_SESSION['user_id'] = $row['First_name']; //create session for each username
      header("Location: home.php");
      exit;
    } else {
      $_SESSION['login_error'] = "Invalid Credentials";
      echo '<div id="error" style="background-color: rgba(255, 0, 0, 0.49);color:white;font-weight:bolder;position:absolute;top:0;width:100%;height:5%;display:flex;flex-direction:row;"><div id="errortext" style="flex:10;text-align:center;line-height:2.5;margin-left:100px">' . $_SESSION['login_error'] . '</div><div id="close" style="flex:1;line-height:3;text-align:right;"><img id="closebut" src="assets/closebut.png" height=20px width=20px></div></div>';
      // echo $_SESSION['login_error'];//
      unset($_SESSION['login_error']);
    }
    $conn->close();
  }
  ?>

  <script src="login.js"></script>
</body>

</html>