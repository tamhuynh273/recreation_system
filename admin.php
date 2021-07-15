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

    // Check connection
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }
    $database = mysqli_select_db($connection, DB_DATABASE);

    function getUser($connection){
      $countQuery = "SELECT * FROM USER_LOGIN";
      $result = mysqli_query( $connection, $countQuery);
      if(!$result) die ("<p>Error get users.</p>");
      return $result;
    }

    function getVendor($connection){
      $countQuery = "SELECT * from VENDOR_LOGIN";
      $result = mysqli_query($connection, $countQuery);
      if(!$result) die ("<p>Error get vendors.</p>");
      return $result;
    }

    function deleteUser($connection, $userId){
      $query = "DELETE FROM USER_LOGIN WHERE USER_ID = '$userId'";
      $result = mysqli_query($connection, $query);
      if(!$result) die ("<p>Error delete user.</p>");
    }

    function deleteVendor($connection, $vendorId){
      $query = "DELETE FROM VENDOR_LOGIN WHERE VENDOR_ID = '$vendorId'";
      $result = mysqli_query($connection, $query);
      if(!$result) die ("<p>Error delete vendor.</p>");
    }

    function blockUser($connection, $userId){
      $query = "UPDATE USER_LOGIN SET LOCK_ACCOUNT = 1 WHERE USER_ID = '$userId'";
      $result = mysqli_query($connection, $query);
      if(!$result) die ("<p>Error block user.</p>");
    }

    function unblockUser($connection, $userId){
      $query = "UPDATE USER_LOGIN SET LOCK_ACCOUNT = 0 WHERE USER_ID = '$userId'";
      $result = mysqli_query($connection, $query);
      if(!$result) die ("<p>Error unblock user.</p>");
    }

    function blockVendor($connection, $vendorId){
      $query = "UPDATE VENDOR_LOGIN SET LOCK_ACCOUNT = 1 WHERE VENDOR_ID = '$vendorId'";
      $result = mysqli_query($connection, $query);
      if(!$result) die ("<p>Error block vendor.</p>");
    }

    function unblockVendor($connection, $vendorId){
      $query = "UPDATE VENDOR_LOGIN SET LOCK_ACCOUNT = 0 WHERE VENDOR_ID = '$vendorId'";
      $result = mysqli_query($connection, $query);
      if(!$result) die ("<p>Error unblock vendor.</p>");
    }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Admin</title>

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
              <form method="POST" action="userWallet.php">
                  <input type="submit" name="logout" class = "btn btn-outline-secondary logout" value="Logout"/>
              </form>
              </li>
          </ul>
        </div>
        </nav>
      </div>
    </section>

    <section class="white-section" id="admin">
      <div class="container-fluid">
        <button type="button"  class="btn btn-secondary w-100 ticket-title">User Account:</button>
        <div class="ticket-content">
          <?php
          $userData = getUser($connection);
          $i = 0;
          if ($userData->num_rows > 1) {
              while ($row = $userData->fetch_array()) {
                if($row['USER_ID'] != 1){
                  $i++;
                  $userItem = "user-".$row['USER_ID'];
                  $deleteUser = "delete-user-".$row['USER_ID'];
                  $blockUser = "block-user-".$row['USER_ID'];
                  $unblockUser = "unblock-user-".$row['USER_ID'];
                  ?>
                  <div class="admin-item" id="<?php echo $userItem; ?>">
                    <div class="row">
                      <div class="col-2">
                        <h5> <?php echo $row['USER_ID']; ?></h6>
                      </div>
                      <div class="col-6">
                        <h6> <?php echo $row['UNAME']; ?></h6>
                      </div>
                      <div class="col-2">
                        <?php
                        if($row['LOCK_ACCOUNT'] == 1){
                          ?>
                          <form class="" action="" method="post">
                            <button type="submit" id="<?php echo $unblockUser; ?>"  name="<?php echo $unblockUser; ?>" class="btn btn-outline-primary w-75 ms-auto block-btn">Unblock</button>
                          </form>
                          <?php
                        }
                        else{
                          ?>
                          <form class="" action="" method="post">
                              <button type="submit" id="<?php echo $blockUser; ?>"  name="<?php echo $blockUser; ?>" class="btn btn-outline-primary w-75 ms-auto block-btn">Block</button>
                          </form>
                          <?php
                        }
                         ?>
                      </div>
                      <div class="col-2">
                        <form class="" action="" method="post">
                          <button type="submit" id="<?php echo $deleteUser; ?>"  name="<?php echo $deleteUser; ?>" class="btn btn-outline-primary w-75 ms-auto delete-btn">Delete</button>
                        </form>

                      </div>
                    </div>
                  </div>
                  <?php
                  if ($i != $userData->num_rows) {
            ?>
            <hr class="inside-hr">
            <?php
                  }
                  if(isset($_POST[$deleteUser])){
                    deleteUser($connection, $row['USER_ID']);
                    header("Location: admin.php");
                  }
                  if(isset($_POST[$blockUser])){
                    blockUser($connection, $row['USER_ID']);
                    header("Location: admin.php");
                  }
                  if(isset($_POST[$unblockUser])){
                    unblockUser($connection, $row['USER_ID']);
                    header("Location: admin.php");
                  }
                }
              }
          }
           ?>

        </div>

        <hr>
        <button type="button"  class="btn btn-secondary w-100 ticket-title">Vendor Account:</button>
        <div class="ticket-content">
          <?php
          $vendorData = getVendor($connection);
          $i = 0;
          if ($vendorData->num_rows > 0) {
              while ($row = $vendorData->fetch_array()) {
                $i++;
                $vendorItem = "vendor-".$row['VENDOR_ID'];
                $deleteVendor = "delete-vendor-".$row['VENDOR_ID'];
                $blockVendor = "block-vendor-".$row['VENDOR_ID'];
                $unblockVendor = "unblock-vendor-".$row['VENDOR_ID'];
                ?>
                <div class="admin-item" id="<?php echo $vendorItem; ?>">
                  <div class="row">
                    <div class="col-2">
                      <h6> <?php echo $row['VENDOR_ID']; ?></h6>
                    </div>
                    <div class="col-6">
                      <h6> <?php echo $row['UNAME']; ?></h6>
                    </div>
                    <div class="col-2">
                      <?php
                      if($row['LOCK_ACCOUNT'] == 1){
                        ?>
                        <form class="" action="" method="post">
                          <button type="submit" id="<?php echo $unblockVendor; ?>"  name="<?php echo $unblockVendor; ?>" class="btn btn-outline-primary w-75 ms-auto block-btn">Unblock</button>
                        </form>
                        <?php
                      }
                      else{
                        ?>
                        <form class="" action="" method="post">
                            <button type="submit" id="<?php echo $blockVendor; ?>"  name="<?php echo $blockVendor; ?>" class="btn btn-outline-primary w-75 ms-auto block-btn">Block</button>
                        </form>
                        <?php
                      }
                       ?>
                    </div>
                    <div class="col-2">
                      <form class="" action="admin.php" method="post">
                        <button type="submit" id="<?php echo $deleteVendor; ?>"  name="<?php echo $deleteVendor; ?>" class="btn btn-outline-primary w-75 ms-auto delete-btn">Delete</button>
                      </form>

                    </div>
                  </div>
                </div>
                <?php
                if ($i != $vendorData->num_rows) {
          ?>
          <hr class="inside-hr">
          <?php
                }
                if(isset($_POST[$deleteVendor])){
                  deleteVendor($connection, $row['VENDOR_ID']);
                  header("Location: admin.php");
                }
                if(isset($_POST[$blockVendor])){
                  blockVendor($connection, $row['VENDOR_ID']);
                  header("Location: admin.php");
                }
                if(isset($_POST[$unblockVendor])){
                  unblockVendor($connection, $row['VENDOR_ID']);
                  header("Location: admin.php");
                }

              }
          }
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
      $(".ticket-content").css("display","none");
      $(".delete-btn").css("display","none");
      $(".block-btn").css("display","none");

      $(".ticket-title").click(function(){
        $(this).toggleClass("drop");
        $(this).next().toggle();
      });

      $(".admin-item").hover(function(){
        $(this).toggleClass("selected-activity");
        $("#delete-"+this.id).toggle();
        $("#block-"+this.id).toggle();
        $("#unblock-"+this.id).toggle();
      });

    </script>
  </body>
</html>
