<?php
    include "../inc/dbinfo.inc";
    $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);
    if (mysqli_connect_errno()) echo ("Something went wrong");
    $database = mysqli_select_db($connection, DB_DATABASE);
    session_start();
    ini_set('sesion.use_only_cookies', 1);
    if(!isset($_SESSION['initiated'])) {
        session_regenerate_id();
        $_SESSION['initiated'] = 1;
    }
    ob_start();

    echo <<<_END
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Home</title>

    <!-- CSS Stylesheets -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/styles.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />

    <!-- Google font -->
   <link rel="preconnect" href="https://fonts.gstatic.com">
   <link
         href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap"
         rel="stylesheet">

  </head>
  <body>
    <section class="colored-section" id="header">
      <!-- Navigation Bar -->
      <div class="container-fluid">
        <nav class="navbar navbar-expand-lg navbar-dark">
          <span class="navbar-brand">KeyCity</span>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarTogglerDemo">
            <ul class="navbar-nav ms-auto">
              <li class="nav-item">
                <form method="POST" action="home.php">
                    <input type="submit" name="logout" class = "btn btn-outline-secondary logout" value="Log In"/>
                </form>
              </li>
            </ul>
          </div>
        </nav>
      </div>
    </section>

    <section class="white-section" id="content">
    <div class = "content-div">
    <form method="POST" action="resetpw.php">
        <div class="login-form col-lg-6 col-md-12 findAccount">
            <div class="mb-3">
                <label for="passwordInput" class="form-label">Set Password</label>
                <input type="password" class="form-control" id="signupPasswordInput" name="Password" onkeyup="check()" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number, one uppercase, one lowercase letter, and be at least 8 or more characters" required/>
            </div>
            <div class="mb-3">
                <label for="confirmPasswordInput" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="signupConfirmPasswordInput" name="Confirm_Password" onkeyup="check()" required>
            </div>
            <input type="submit" name="reset" class="btn btn-outline-secondary search" value="Reset"/>
        </div>
    </form>
    </div>

    </section>

    <!-- Footer Section -->
    <footer class="colored-section" id="footer">
      <div class="container-fluid">
        <i class="social-icon fab fa-twitter"></i>
        <i class="social-icon fab fa-facebook-f"></i>
        <i class="social-icon fab fa-instagram"></i>
        <i class="social-icon fas fa-envelope"></i>
        <p>© 2021 Team Four</p>
      </div>
    </footer>

    <!-- Bootstrap Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script>
    var check = function() {
        if (document.getElementById('signupPasswordInput').value ==
            document.getElementById('signupConfirmPasswordInput').value) {
            document.getElementById('message').style.color = 'green';
            document.getElementById('message').innerHTML = 'Matching';
        } else {
            document.getElementById('message').style.color = 'red';
            document.getElementById('message').innerHTML = 'Not Matching';
        }
    }
    </script>

  </body>
</html>

_END;


  if(isset($_POST['reset'])) {
      $uname = $_SESSION['email'];
      $newpassword = mysqli_real_escape_string($connection, $_POST['Password']);

      $salt1 = rand();
      $salt2 = rand();
      $token = hash('ripemd128', "$salt1$newpassword$salt2");
      if($_SESSION['role'] == "user") {
          $query1 = "UPDATE USER_LOGIN SET PASSWORD_HASHED='$token', SALT1='$salt1', SALT2='$salt2' WHERE UNAME='$uname'";
          if(!mysqli_query($connection, $query1)) echo ("<p>Error renewing user password.</p>");
          else header("Location: succeed.php");
      } elseif($_SESSION['role'] == "vendor") {
          $query2 = "UPDATE VENDOR_LOGIN SET PASSWORD_HASHED='$token', SALT1='$salt1', SALT2='$salt2' WHERE UNAME='$uname'";
          if(!mysqli_query($connection, $query2)) echo ("<p>Error renewing vendor password.</p>");
          else header("Location: succeed.php");
      }
  }

    ob_end_flush();
    mysqli_close($connection);

?>
<<<<<<< HEAD

=======
>>>>>>> 78dd2d0d8bb79513984b92b72b6f5e579a52cbb0
