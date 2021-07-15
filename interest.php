<?php
    ob_start();

    echo <<<_END
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Interest</title>

    <!-- CSS Stylesheets -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
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
                <a class="nav-link" href="index.php">Login</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">About</a>
              </li>
            </ul>
          </div>
        </nav>
      </div>
    </section>

    <section class="white-section" id="interest">
      <div class="container-fluid">
        <div class="select">
          <h1 class="text-dark mb-5">Select Activities You're Interested In</h1>
        </div>

        <div class="row">
          <div class="activities-column col-lg-4 col-md-6 mb-3">
            <div class="card">
              <img src="https://images.pexels.com/photos/4393021/pexels-photo-4393021.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940" class="card-img-top" alt="Food-img">
              <div class="card-body">
                <h5>Food</h5>
              </div>
            </div>
          </div>

          <div class="activities-column col-lg-4 col-md-6  mb-3">
            <div class="card">
              <img src="https://images.pexels.com/photos/701016/pexels-photo-701016.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940" class="card-img-top" alt="Outdoors-img">
              <div class="card-body">
                <h5>Outdoors</h5>
              </div>
            </div>
          </div>

          <div class="activities-column col-lg-4 col-md-6  mb-3">
            <div class="card">
              <img src="https://images.pexels.com/photos/2123337/pexels-photo-2123337.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940" class="card-img-top" alt="Art-img">
              <div class="card-body">
                <h5>Art</h5>
              </div>
            </div>
          </div>

          <div class="activities-column col-lg-4 col-md-6  mb-3">
            <div class="card">
              <img src="https://images.pexels.com/photos/5088017/pexels-photo-5088017.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940" class="card-img-top" alt="Education-img">
              <div class="card-body">
                <h5>Education</h5>
              </div>
            </div>
          </div>

          <div class="activities-column col-lg-4 col-md-6  mb-3">
            <div class="card">
              <img src="https://images.pexels.com/photos/1105666/pexels-photo-1105666.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940" class="card-img-top" alt="Festival-img">
              <div class="card-body">
                <h5>Festival</h5>
              </div>
            </div>
          </div>

          <div class="activities-column col-lg-4 col-md-6  mb-3">
            <div class="card">
              <img src="https://images.pexels.com/photos/1618200/pexels-photo-1618200.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940" class="card-img-top" alt="Sports-img">
              <div class="card-body">
                <h5>Sport</h5>
              </div>
            </div>
          </div>

        </div>

        <form class="ms-auto col-4 mt-5" action="interest.php" method="POST">
          <input type="submit" class="btn btn-primary w-100" name="Next" value ="Next"/>
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

    <script type="text/javascript">
      $(".card").click(function(){
        $(this).toggleClass("selected");
        counter();
      })

      //number of selected
      function counter(){
        var selectedNumber = $(".selected").length;
        console.log(selectedNumber);
      }
    </script>

  </body>
</html>

_END;

    if(isset($_POST['Next'])) {
        header("Location: index.php");
    }
    ob_end_flush();

?>
