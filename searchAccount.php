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
    $fname = $_SESSION['FirstName'];
    
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
    <form action="searchAccount.php" method="POST">
        <div class="login-form col-lg-6 col-md-12 findAccount">
            <div>
                <h4 class="find-account-message"> Find Your Account </h4>
            </div>
            <div class="mb-3">
                <label for="emailInput" class="form-label">Please enter your email address</label>
                <input type="text" name="inputEmail" placeholder="Email Address" class="form-control">
            </div>
            
            <div class="mb-3">
                <label for="loginRole" class="form-label">Role</label>
                <select class="form-select" id="roleInput" name="role" required>
                    <option selected>Choose...</option>
                    <option value="user">User</option>
                    <option value="vendor">Vendor</option>
                </select>
            </div>

            <div class="mb-3">
                <div>
                    <a class="btn btn-outline-secondary cancel" role="button" href="index.php">Cancel</a>
                    <button type="submit" class="btn btn-outline-secondary search" name="search">Search</button>
                </div>
            </div>
        </div>
    </form>
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

  </body>
</html>
    
_END;


  if(isset($_POST['search'])) {
      $email = mysqli_real_escape_string($connection, $_POST['inputEmail']);
      if($_POST['role'] == 'user') {
          $_SESSION['role'] = "user";
          $query = "SELECT * FROM USER_LOGIN WHERE UNAME = '$email'";
      } elseif ($_POST['role'] == 'vendor') {
          $_SESSION['role'] = "vendor";
          $query = "SELECT * FROM VENDOR_LOGIN WHERE UNAME = '$email'";
      }
      $result = mysqli_query($connection, $query);
      if(!$result) die ("Something went wrong");
      elseif(mysqli_num_rows($result) == 0) {
          header("Location: invalidEmail.php");
      } else {
          $_SESSION['email'] = $email;
          header("Location: resetpw.php");
      }
  }
    
    ob_end_flush();
    mysqli_close($connection);

?>
