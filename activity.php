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
    $fname = $_SESSION['FirstName'];
    $user_id = $_SESSION['UserID'];
    $type = $_SESSION['Type'];
    $city = $_SESSION['City'];


    // Check connection
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }
    $database = mysqli_select_db($connection, DB_DATABASE);
    VerifyTableActivity($connection, DB_DATABASE);

    if (isset($_POST['buy-ticket'])){
        $totalCost = htmlentities($_POST['totalCost']);
        $balance = getBalance($connection, $user_id);

        if(!isset($_SESSION['UserID'])) {
            echo "<script>
            alert('Please log in to purchase activity');
            window.location.href='index.php';
            </script>";
        } else {
            if($totalCost > $balance){
                echo '<script>alert("You do not have enough balance.")</script>';
            }
            else{
                $newBalance = $balance - $totalCost;
                updateBalance($connection, $user_id, $newBalance);
                $data = getRows($connection,$type,$city);
                if ($data->num_rows > 0) {
                    while ($row = $data->fetch_array()) {
                        $purchaseNum = "purchaseNum-".$row['activity_id'];
                        $num = htmlentities($_POST[$purchaseNum]);
                        updateUserHistory($connection, $user_id, $num, $row['activity_id']);
                        updateActivity($connection, $row['activity_id'], $num);
                    }//end while
                }// end if
                echo '<script>alert("You sucessfully buy the tickets.")</script>';
            }//end else
        }
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
            if(!mysqli_query($connection, $query_activity)) echo("<p>Error creating ativity table.</p>");
        }

        if(!TableExists("USER_HISTORY", $connection, $dbName)) {
          $query_user_history = "CREATE TABLE USER_HISTORY (
              user_id INT NOT NULL,
              activity_id INT NOT NULL,
              owned_ticket INT NOT NULL,
              used_ticket INT NOT NULL,
              CONSTRAINT FK_ActivityId
              FOREIGN KEY (activity_id) REFERENCES ACTIVITY(activity_id)
              ON DELETE CASCADE,
              CONSTRAINT FK_UserHistory
              FOREIGN KEY (user_id) REFERENCES USER_INFO(USER_ID)
              ON DELETE CASCADE
            )";
            if(!mysqli_query($connection, $query_user_history)) echo("<p>Error creating user history table.</p>");
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

    function updateActivity($connection, $activityId, $buyNumber){
      $selectQuery = "SELECT sold_number FROM ACTIVITY WHERE activity_id = '$activityId'";
      $data1 = mysqli_query($connection, $selectQuery);
      if(!$data1) echo("<p>Error getting sold ticket number.</p>");
      $soldTicket = $data1->fetch_array()[0] ?? '';
      $newSoldNumber = $soldTicket + $buyNumber;
      $updateQuery = "UPDATE ACTIVITY SET sold_number = '$newSoldNumber' WHERE activity_id = '$activityId'";
      $data2 = mysqli_query($connection, $updateQuery);
      if(!$data2) echo("<p>Error update sold number for activity.</p>");
    }

    function updateUserHistory($connection, $userId, $buyNumber, $activityId){
      $countQuery = "SELECT * FROM USER_HISTORY WHERE activity_id = '$activityId' and user_id = '$userId'";
      $count = mysqli_query($connection, $countQuery);
      if ($count->num_rows > 0){
        $ticketQuery = "SELECT owned_ticket FROM USER_HISTORY WHERE activity_id = '$activityId' and user_id = '$userId'";
        $data1 = mysqli_query($connection, $ticketQuery);
        if(!$data1) echo("<p>Error getting owned ticket number.</p>");
        $ownedTicket = $data1->fetch_array()[0] ?? '';
        $updateTicketNum = $ownedTicket + $buyNumber;
        $updateQuery = "UPDATE USER_HISTORY SET owned_ticket = '$updateTicketNum' WHERE activity_id = '$activityId' and user_id = '$userId'";
        $data2 = mysqli_query($connection, $updateQuery);
        if(!$data2) echo("<p>Error update user history.</p>");
      }
      else{
        if($buyNumber>0){
          $insertQuery = "INSERT INTO USER_HISTORY (user_id, activity_id, owned_ticket, used_ticket)
                          VALUES ('$userId', '$activityId', '$buyNumber', '0')";
          $data3 = mysqli_query($connection, $insertQuery);
          if(!$data3) echo("<p>Error insert into user history.</p>");
        }
      }
    }

    function updateBalance($connection, $userId, $newBalance){
      $query = "UPDATE USER_WALLET SET BALANCE = '$newBalance' WHERE USER_ID = '$userId'";
      if(!mysqli_query($connection, $query)) echo ("<p>Error update balance.</p>");
    }

    function getBalance($connection, $userId){
      $query = "SELECT BALANCE FROM USER_WALLET WHERE USER_ID = '$userId'";
      $data = mysqli_query($connection, $query);
      if(!$data) echo("<p>Error getting balance.</p>");
      $result =  $data->fetch_array()[0] ?? '';
      return $result;
    }

    function getRows($connection, $type, $city)
    {
      if(empty($city)){
        $countQuery = "SELECT * from ACTIVITY where activity_type = '$type'";
      }
      else {

        $countQuery = "SELECT * from ACTIVITY where activity_type = '$type' and activity_city = '$city'";
      }
      $result = mysqli_query($connection, $countQuery);
      return $result;
    }

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Activity</title>

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
                      <?php
                      if(empty($user_id)){
                        ?>
                        <li class="nav-item">
                          <form method="POST" action="home.php">
                              <input type="submit" name="logout" class = "btn btn-outline-secondary logout" value="signUp"/>
                          </form>
                        </li>
                        <?php
                      }
                      else{
                        ?>
                        <li class="nav-item">
                          <a class="nav-link" href="userProfile.php"><?php echo $fname ?></a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="userWallet.php">Wallet</a>
                        </li>
                        <li class="nav-item">
                          <form method="POST" action="home.php">
                              <input type="submit" name="logout" class = "btn btn-outline-secondary logout" value="Logout"/>
                          </form>
                        </li>
                        <?php
                      }
                       ?>
                    </ul>
                </div>
                </nav>
            </div>
      </section>

      <!-- Activity Section -->
      <section class="white-section" id="activity">
        <div class="container-fluid">
          <?php
          $data = getRows($connection,$type, $city);
          $i = 0;
          if ($data->num_rows > 0) {
              while ($row = $data->fetch_array()) {
                  $i++;
                  $purchaseNum = "purchaseNum-".$row['activity_id'];
                   ?>

              <form class="" action="activity.php" method="post">
                <div class="row activity-item">
                  <div class="col-10">
                    <h2> <?php echo $row['activity_name']; ?></h2>
                    <p><span class="info-title">Location</span>: <?php echo $row['activity_location']; ?></p>
                    <p><button class="btn btn-primary" name="<?php echo $row['activity_location'];?>" onclick="direction(name)">Get Direction</button></p>
                    <p><span class="info-title">Type</span>: <?php echo $row['activity_type']; ?></p>
                    <p><span class="info-title">Minimum Age</span>: <?php echo $row['activity_minimum_age']; ?>+</p>
                    <p><span class="info-title">Price per person</span>: $<?php echo $row['activity_price']; ?></p>
                    <p><span class="info-title">Schedule</span>: <?php echo $row['activity_schedule']; ?></p>
                  </div>

                  <div class="col-2">
                    <input type="number" name="<?php echo $purchaseNum;?>" class="btn btn-outline-secondary" oninput="updateNum(<?php echo $i;?>,value, <?php echo $row['activity_price'];?>)" min="0" max="100">
                    <p id ="<?php echo $i;?>" value="0" style="display: none;"></p>
                  </div>
                </div>

              <?php
                    if ($i != $data->num_rows) {
                      ?>
                      <hr class="inside-hr">
                      <?php
                    }
                }
                ?>
                <hr>
                <div class="row cal-total">
                  <div class="col-11">
                    <h2>Total:</h2>
                  </div>

                  <div class="col-1 right-col">
                    <p id = "total">$0</p>
                    <input type="number" style="display: none;" id="totalCost" name="totalCost">
                  </div>
                </div>

                <div class="ms-auto col-4 mt-3">
                  <button type="submit" name="buy-ticket" class="btn btn-primary w-100">Buy</button>
                </div>
              </form>

                <?php
          } else {?>
            <div class="empty">
              <h1>No Events</h1>
            </div>
          <?php
          }
           ?>
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
         let total = 0;
         var destination;
         $(".activity-item").hover(function(){
           $(this).toggleClass("selected-activity");
         });

         function updateNum(id,num, price){
           let cost = Number($("#"+id).text());
           if (cost > 0){
             total -= cost;
           }

           total += num*price;
           $("#total").html("$"+total);
           $("#totalCost").val(total);
           $("#"+id).html(num*price);
         }

        function direction(name) {
            destination = name;
            sessionStorage.setItem("destination", name);
            window.open('getdirection.php');
        }
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
