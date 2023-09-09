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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>

  <div class="container_login">
    <nav class="login_nav">
      <a href="login.php">
        <img src="assets/EPITA School of Engineering and Computer Science_Logo.png" alt="" />
      </a>
      <div class="nav_btn">
        <button onclick="window.location.href = 'student_login.php'">Student portal</button>
      </div>
    </nav>
    <div class="login_box">
      <div class="welcome_login">
        <img src="assets/EPITA School of Engineering and Computer Science_Logo.png" alt="" />
      </div>
      <div class="welcome_text_login">
        <h2>Hello !</h2>
        <p>Enter in your admin panel</p>
      </div>

      <form class="login_form" method="post" id="loginForm" onsubmit="return validateForm();">
        <div class="login_input_email_wrapper">
          <input class="login_input_email" type="text" placeholder="Enter Your Email or Username" name="email"
            id="email" onchange="inputFields()"
            value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" />
        </div>
        <div class="login_input_pass_wrapper">
          <input class="login_input_pass" type="password" name="passwrd" id="passwrd" placeholder="*******"
            onchange="inputFields()" />
          <div class="show-password">
            <label for="passwrd">
              <i id="eyeIcon" class="fas fa-eye" onclick="togglePassword()"></i>
            </label>
          </div>
        </div>

        <div id="error_message"></div>
        <button name="login_form" type="submit">Log In</button>
      </form>
      <script>
        function togglePassword() {
          var passwrdInput = document.getElementById("passwrd");
          var eyeIcon = document.getElementById("eyeIcon");

          if (passwrdInput.type === "password") {
            passwrdInput.type = "text";
            eyeIcon.classList.remove("fa-eye");
            eyeIcon.classList.add("fa-eye-slash");
          } else {
            passwrdInput.type = "password";
            eyeIcon.classList.remove("fa-eye-slash");
            eyeIcon.classList.add("fa-eye");
          }
        }
      </script>
      <?php

      if (isset($_POST['login_form'])) {

        require_once "php/connect.php";
        $conn = conn();
        // $sql = "SELECT * FROM admins WHERE (admin_email ='" . $_POST["email"] . "' OR admin_username = '" . $_POST['email'] . "') and admin_password = '" . $_POST['passwrd'] . "'";
        $check_email = "SELECT * FROM admins WHERE admin_email ='" . $_POST["email"] . "' OR admin_username = '" . $_POST['email'] . "'";
        $result = $conn->query($check_email);

        if ($result->num_rows > 0) {
          $row = $result->fetch_assoc();
          if ($row['admin_password'] == $_POST['passwrd']) {
            $_SESSION['user_id'] = $row['admin_id']; //create session for each username
            $_SESSION['user_name'] = $row['admin_username']; //create session for each username
            setcookie('userID', $_SESSION['user_id'], 0, '/');
            header("Location: home.php");
            exit;
          } else {
            echo '<script> 
            document.getElementById("passwrd").classList.add("error-input");
            var errorMessageContainer = document.getElementById("error_message");
            errorMessageContainer.innerText = "Invalid password. Please try again.";
            errorMessageContainer.style.display = "block";
            </script>';
          }
        } else {
          echo '<script>
          document.querySelector("input[name=email]").classList.add("error-input");
          var errorMessageContainer = document.getElementById("error_message");
          errorMessageContainer.innerText = "Invalid Email or Username. Please try again.";
          errorMessageContainer.style.display = "block";
          </script>';
        }
        $conn->close();
      }
      ?>

      <script src="JS/login.js"></script>
</body>

</html>