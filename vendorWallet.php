<?php
include "../inc/dbinfo.inc";
// Create connection
    $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);
    ini_set('sesion.use_only_cookies', 1);
    if(!isset($_SESSION['initiated'])) {
        session_regenerate_id();
        $_SESSION['initiated'] = 1;
    }
    ob_start();
    session_start();
    $vendor_id = $_SESSION['VendorID'];
    $fname = $_SESSION['FirstName'];

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}
    $database = mysqli_select_db($connection, DB_DATABASE);
    $total = 0;


    function TableExists($tableName, $connection, $dbName) {
        $t = mysqli_real_escape_string($connection, $tableName);
        $d = mysqli_real_escape_string($connection, $dbName);

        $checktable = mysqli_query($connection,
         "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = '$t' AND TABLE_SCHEMA = '$d'");

        if(mysqli_num_rows($checktable) > 0) return true;

        return false;
    }

    function getAcitivityName($connection, $activityId){
      $query = "SELECT activity_name FROM ACTIVITY WHERE activity_id = '$activityId'";
      $data = mysqli_query($connection, $query);
      if(!$data) echo("<p>Error getting activity name.</p>");
      $result =  $data->fetch_array()[0] ?? '';
      return $result;
    }


    function getRows($connection, $vendorId)
    {
      $countQuery = "SELECT * from ACTIVITY where vendor_id = '$vendorId'";
      $result = mysqli_query($connection, $countQuery);
      return $result;
    }

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Wallet</title>

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
                        <a class="nav-link" href="profile.php"><?php echo $fname ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="vendor.php">Home</a>
                    </li>
                    <li class="nav-item">
                    <form method="POST" action="index.php">
                        <input type="submit" name="logout" class = "btn btn-outline-secondary logout" value="Logout"/>
                    </form>
                    </li>
                </ul>
              </div>
              </nav>
            </div>
        </section>

    <!-- Wallet Section  -->
    <section class="white-section" id="wallet">
      <div class="container-fluid">
        <div class="row activity-item">
          <div class="col-8">
            <h1>Name</h1>
          </div>

          <div class="col-2">
            <h1>Sold</h1>
          </div>

          <div class="col-2">
            <h1>Earn</h1>
          </div>
        </div>

        <hr>
        <div class="activity-item">
          <?php
          $data = getRows($connection,$vendor_id);
          $i = 0;
          if ($data->num_rows > 0) {
              while ($row = $data->fetch_array()) {
                $i++;
                ?>
                <div class="row wallet-item" style="border-radius: 10px;">
                  <div class="col-8">
                    <h3> <?php echo $row['activity_name']; ?></h3>
                  </div>

                  <div class="col-2">
                    <p> X<?php echo $row['sold_number']; ?></p>
                  </div>

                  <div class="col-2">
                    <p>$<?php echo $row['sold_number'] * $row['activity_price']; ?></p>
                  </div>
                </div>
                <?php
                $total += $row['sold_number'] * $row['activity_price'];
                if ($i != $data->num_rows) {
                  ?>
                  <hr class="inside-hr">
                  <?php
                }
              }//end while
              ?>
              <hr>
              <div class="row">
                <div class="col-8">
                  <h1>Total:</h1>
                </div>

                <div class="col-2">

                </div>

                <div class="col-2">
                  <h6>$<?php echo $total; ?></h6>
                </div>
              </div>
              <?php
          }//end if
          ?>
        </div>

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
      $(".wallet-item").hover(function(){
        $(this).toggleClass("selected-activity");
      });

    </script>
  </body>
</html>

<?php
    if(isset($_POST['logout'])) {
        destroy_session_and_data();
        header("Location: index.php");
    }

    ob_end_flush();

    function destroy_session_and_data() {
        $_SESSION = array();
        setcookie(session_name(), '', time() - 2592000, '/');
        session_destroy();
    }

  mysqli_close($connection);
 ?>
