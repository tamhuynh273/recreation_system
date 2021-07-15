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

    //retrieve info
    $fname = $_SESSION['FirstName'];
    $lname = $_SESSION['LastName'];
    $age = $_SESSION['Age'];
    $dob = $_SESSION['DoB'];
    $phone = $_SESSION['Phone'];
    $city = $_SESSION['City'];
    $balance = $_SESSION['Balance'];

    echo <<<_END
        <html lang="en" dir="ltr">
        <head>
        <meta charset="utf-8">
        <title>Profile</title>

        <!-- CSS Stylesheets -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous"/>
        <link rel="stylesheet" href="css/styles.css"/>

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />

        <!-- Google font -->
        <link rel="preconnect" href="https://fonts.gstatic.com"/>
        <link
         href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap"
         rel="stylesheet"/>
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
                        <a class="nav-link" href="vendor.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="vendorWallet.php">Wallet</a>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="home.php">
                            <input type="submit" name="logout" class = "btn btn-outline-secondary logout" value="Logout"/>
                        </form>
                    </li>
                    </ul>
                </div>
                </nav>
            </div>
        </section>
    
        <section class="white-section" id="information">
        <div class = "container-fluid" >
            <form action="info.php" method="POST">
            <div class="mb-5">
                <h3> $fname $lname </h3>
                <p> $age years old </p>
                <p> Date of birth: $dob </p>
                <p> Phone number: $phone </p>
                <p> City: $city </p>
                <p> Balance: $$balance </p>
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

    </body>
    </html>
_END;

    if(isset($_POST['logout'])) {
        destroy_session_and_data();
        header("Location: index.php");
    }
    mysqli_close($connection);
    ob_end_flush();
    
    function destroy_session_and_data() {
        $_SESSION = array();
        setcookie(session_name(), '', time() - 2592000, '/');
        session_destroy();
    }
?>

