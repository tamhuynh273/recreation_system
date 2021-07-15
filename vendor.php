<?php
include "../inc/dbinfo.inc";
// Create connection
    $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);
    session_start();
    ini_set('sesion.use_only_cookies', 1);
    if(!isset($_SESSION['initiated'])) {
        session_regenerate_id();
        $_SESSION['initiated'] = 1;
    }
    ob_start();
    $vendor_id = $_SESSION['VendorID'];
    $fname = $_SESSION['FirstName'];

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}
    $database = mysqli_select_db($connection, DB_DATABASE);
    VerifyTableActivity($connection, DB_DATABASE);


    if( isset($_POST['addActivity'])) {
      $name = htmlentities($_POST['activityName']);
      $type = htmlentities($_POST['activityType']);
      $location = htmlentities($_POST['activityLocation']);
      $city = htmlentities($_POST['activityCity']);
      $age = htmlentities($_POST['activityAge']);
      $price = htmlentities($_POST['activityPrice']);
      $schedule = htmlentities($_POST['activitySchedule']);
      $sold_num = 0;
      addActivity($connection, $vendor_id,$name, $type, $location, $city, $age, $price, $schedule, $sold_num);
      header("Location: vendor.php");
    }

    function VerifyTableActivity($connection, $dbName) {
        if(!TableExists("ACTIVITY", $connection, $dbName)) {
            $query_activity = "CREATE TABLE ACTIVITY (
                activity_id INT NOT NULL AUTO_INCREMENT,
                vendor_id INT NOT NULL,
                sold_number INT NOT NULL,
                activity_type VARCHAR(100) NOT NULL,
                activity_name VARCHAR(100) NOT NULL,
                activity_location VARCHAR(100) NOT NULL,
                activity_city VARCHAR(100) NOT NULL,
                activity_minimum_age INT NOT NULL,
                activity_price DECIMAL(9,2) NOT NULL,
                activity_schedule VARCHAR(100) NOT NULL,
                PRIMARY KEY(activity_id),
                CONSTRAINT FK_Activity FOREIGN KEY (vendor_id)
                REFERENCES VENDOR_INFO(VENDOR_ID) ON DELETE CASCADE
              )";
            if(!mysqli_query($connection, $query_activity_time)) echo("<p>Error creating ativity table.</p>");
        }
    }

    function TableExists($tableName, $connection, $dbName) {
        $t = mysqli_real_escape_string($connection, $tableName);
        $d = mysqli_real_escape_string($connection, $dbName);

        $checktable = mysqli_query($connection,
         "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = '$t' AND TABLE_SCHEMA = '$d'");

        if(mysqli_num_rows($checktable) > 0) return true;

        return false;
    }

    function getRows($connection, $vendorId)
    {
      $countQuery = "SELECT * from ACTIVITY where vendor_id = '$vendorId'";
      $result = mysqli_query($connection, $countQuery);
      return $result;
    }

    function deleteActivity($connection, $id){
      $query = "DELETE FROM ACTIVITY WHERE activity_id = $id";
      if(!mysqli_query($connection, $query)) echo ("<p>Error delete activity data.</p>");
    }

    function updateActivity($connection,$id,$name, $type, $location, $city, $age, $price, $schedule){
      $query = "UPDATE ACTIVITY
                SET activity_name = '$name', activity_type = '$type', activity_location = '$location',activity_city = '$city', activity_minimum_age = '$age', activity_price = '$price', activity_schedule = '$schedule'
                WHERE activity_id = $id";
      if(!mysqli_query($connection, $query)) echo ("<p>Error update activity data.</p>");
    }

    function addActivity($connection, $vendor_id,$name, $type, $location, $city, $age, $price, $schedule, $sold_num){
      $query = "INSERT INTO ACTIVITY (
                vendor_id,
                sold_number,
                activity_type,
                activity_name,
                activity_location,
                activity_city,
                activity_minimum_age,
                activity_price,
                activity_schedule)
                VALUES('$vendor_id', '$sold_num', '$type','$name', '$location','$city', '$age', '$price','$schedule')";
      if(!mysqli_query($connection, $query)) echo ("<p>Error add activity data.</p>");
   }
?>

<!DOCTYPE html>
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
                    <a class="nav-link" href="vendorProfile.php"><?php echo $fname ?></a>
              </li>
              <li class="nav-item">
                  <a class="nav-link" href="vendorWallet.php">Wallet</a>
              </li>
              <li class="nav-item">
                <form method="POST" action="vendor.php">
                    <input type="submit" name="logout" class = "btn btn-outline-secondary logout" value="Logout"/>
                </form>
              </li>
            </ul>
          </div>
        </nav>
      </div>
    </section>

    <section class="white-section" id="event">
      <div class="container-fluid">
        <!-- Show Activities -->
        <?php
        $data = getRows($connection, $vendor_id);
        $i = 0;
        if ($data->num_rows > 0) {
            while ($row = $data->fetch_array()) {
                $i++;
                $showItem = "showItem-".$row['activity_id'];
                $editItem = "editItem-".$row['activity_id'];
                $delete = "delete-".$row['activity_id'];
                $done = "done-".$row['activity_id'];
                 ?>

          <div class="activity-item" id="<?php echo $showItem; ?>">
            <div class="row " >
              <div class="col-11">
                <h2> <?php echo $row['activity_name']; ?></h2>
                <p><span class="info-title">Address</span>: <?php echo $row['activity_location']; ?></p>
                <p><span class="info-title">Type</span>: <?php echo $row['activity_type']; ?></p>
                <p><span class="info-title">Minimum Age</span>: <?php echo $row['activity_minimum_age']; ?>+</p>
                <p><span class="info-title">Price per person</span>: $<?php echo $row['activity_price']; ?></p>
                <p><span class="info-title">Schedule</span>: <?php echo $row['activity_schedule']; ?></p>
              </div>

              <div class="col-1" >
                <form class="" action="vendor.php" method="post">
                  <div class="dropdown editor">
                    <button class="dropbtn btn btn-outline-secondary" type="button">•••</button>
                    <div class="dropdown-content">
                      <button class="dropdown-item" type="submit" name="<?php echo $delete; ?>" >Delete</button>
                      <button class="dropdown-item" type="button" name="<?php echo $row['activity_id']; ?>"
                      onclick="invisible(name)">Edit</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <form class="" action="vendor.php" method="post">
            <div class="acitivity-item" id="<?php echo $editItem; ?>" style="display: none;">
              <div class="input-group">
                <span class="input-group-text text-light bg-secondary info-input">Name</span>
                <input type="text" label="Name" class="form-control" name="editActivityName"
                value="<?php echo $row['activity_name']; ?>" required>
              </div>

              <div class="input-group">
                <span class="input-group-text text-light bg-secondary info-input">Type</span>
                <input type="text"  label="Type" class="form-control" name="editActivityType"  autocomplete="off"
                value="<?php echo $row['activity_type']; ?>" list="datalistOptions" id="activitiesDataList"  required>
                <datalist id="datalistOptions">
                  <option value="Restaurant" class="w-100"></option>
                  <option value="Outdoor" class="w-100"></option>
                  <option value="Festival" class="w-100"></option>
                  <option value="Art" class="w-100"></option>
                  <option value="Sport" class="w-100"></option>
                </datalist>
              </div>

              <div class="input-group">
                <span class="input-group-text text-light bg-secondary info-input">Address</span>
                <input type="text" label="Location" class="form-control"
                 value="<?php echo $row['activity_location']; ?>" name="editActivityLocation" required>
              </div>

              <div class="input-group">
                <span class="input-group-text text-light bg-secondary info-input">City</span>
                <input type="text" label="City" class="form-control"
                 value="<?php echo $row['activity_city']; ?>" name="editActivityCity" required>
              </div>

              <div class="input-group">
                <span class="input-group-text text-light bg-secondary info-input">Minimum Age</span>
                <input type="text" label="Age" class="form-control"
                value="<?php echo $row['activity_minimum_age']; ?>" name="editActivityAge" required>
              </div>

              <div class="input-group">
                <span class="input-group-text text-light bg-secondary info-input">Price</span>
                <input type="text" label="Price" class="form-control"
                value="<?php echo $row['activity_price']; ?>" name="editActivityPrice" required>
              </div>

              <div class="input-group">
                <label class="input-group-text text-light bg-secondary info-input" for="Schedule">Schedule</label>
                <input type="text" class="form-control" name="editActivitySchedule"
                value="<?php echo $row['activity_schedule']; ?>" required >
              </div>

              <div class="row mt-3 edit-btn">
                <div class="col-8">

                </div>
                <div class="col-2">
                  <button type="submit" class="btn btn-outline-primary w-75" name="<?php echo $row['activity_id']; ?>"
                    onclick="visible(name)">Cancel</button>
                </div>
                <div class="col-2">
                  <button type="submit" name="<?php echo $done; ?>" class="btn btn-primary w-75">Done</button>
                </div>
              </div>
            </div>
          </form>

          <?php
              if ($i != $data->num_rows) {
          ?>
          <hr class="inside-hr">
          <?php
                }
                if(isset($_POST[$delete])) {
                  deleteActivity($connection,$row['activity_id']);
                  header("Location: vendor.php");
                }
                if(isset($_POST[$done])) {
                  $name = htmlentities($_POST['editActivityName']);
                  $type = htmlentities($_POST['editActivityType']);
                  $location = htmlentities($_POST['editActivityLocation']);
                  $city = htmlentities($_POST['editActivityCity']);
                  $age = htmlentities($_POST['editActivityAge']);
                  $price = htmlentities($_POST['editActivityPrice']);
                  $schedule = htmlentities($_POST['editActivitySchedule']);
                  updateActivity($connection,$row['activity_id'],$name, $type, $location,$city, $age, $price, $schedule);
                  header("Location: vendor.php");
                }
        }//end while
        } else {?>
          <div class="empty">
            <h1>No Events</h1>
          </div>
        <?php
      }
       ?>

       <hr>
       <!-- Add Activity -->
       <form class="" method="post">
         <div class="mb-3 plus">
           <button type="button" name="plus-btn" class="add">+</button>
         </div>

         <div class="mb-3 minus">
           <button type="button" name="minus-btn" class="add">-</button>
         </div>
       </form>

       <form method="post">
         <div class="activity-info">
           <div class="mb-3 input-group">
             <span class="input-group-text text-light bg-secondary info-input">Name</span>
             <input type="text" label="Name" class="form-control" name="activityName" required>
           </div>

           <div class="mb-3 input-group">
             <span class="input-group-text text-light bg-secondary info-input">Type</span>
             <input type="text"  label="Type" class="form-control" name="activityType"  autocomplete="off" list="datalistOptions" id="activitiesDataList"  required>
             <datalist id="datalistOptions">
               <option value="Restaurant" class="w-100"></option>
               <option value="Outdoor" class="w-100"></option>
               <option value="Festival" class="w-100"></option>
               <option value="Art" class="w-100"></option>
               <option value="Sport" class="w-100"></option>
             </datalist>
           </div>

           <div class="mb-3 input-group">
             <span class="input-group-text text-light bg-secondary info-input">Address</span>
             <input type="text" label="Location" class="form-control" name="activityLocation" required>
           </div>

           <div class="mb-3 input-group">
             <span class="input-group-text text-light bg-secondary info-input">City</span>
             <input type="text" label="City" class="form-control" name="activityCity" required>
           </div>

           <div class="mb-3 input-group">
             <span class="input-group-text text-light bg-secondary info-input">Minimum Age</span>
             <input type="text" label="Age" class="form-control" name="activityAge" required>
           </div>

           <div class="mb-3 input-group">
             <span class="input-group-text text-light bg-secondary info-input">Price</span>
             <input type="text" label="Price" class="form-control" name="activityPrice" required>
           </div>

           <div class="mb-3 input-group">
             <label class="input-group-text text-light bg-secondary info-input" for="Schedule">Schedule</label>
             <textarea id ="Schedule" class="form-control" name="activitySchedule" required rows="5"></textarea>
           </div>

           <div class="ms-auto col-4 mt-3">
             <input type="submit" class="btn btn-primary w-100" name="addActivity" value="ADD"/>
           </div>
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

    <script type="text/javascript">
      $(".activity-item").hover(function(){
        $(this).toggleClass("selected-activity");
      });

      $(".activity-info").css("display","none");
      $(".minus").css("display","none");

      function invisible(id){
        $("#showItem-"+id).css("display","none");
        $("#editItem-"+id).css("display","block");
      }

      function visible(id) {
        $("#showItem-"+id).css("display","block");
        $("#editItem-"+id).css("display","none");
      }

      $(".plus").click(function(){
        $(".activity-info").css("display","block");
        $(".minus").css("display","block");
        $(this).css("display","none");
      });

      $(".minus").click(function(){
        $(".activity-info").css("display","none");
        $(".plus").css("display","block");
        $(this).css("display","none");
      });
    </script>

  </body>
</html>

<?php
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
