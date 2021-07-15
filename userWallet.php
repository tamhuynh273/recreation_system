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
    $user_id = $_SESSION['UserID'];
    $fname = $_SESSION['FirstName'];

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}
    $database = mysqli_select_db($connection, DB_DATABASE);
    $balance = getBalance($connection, $user_id);

    if(isset($_POST['add'])) {
        $amount = $_POST['addBalance'];
        $new_balance = $amount + $balance;
        addBalance($connection, $user_id, $new_balance);
        $balance = getBalance($connection, $user_id);
        header("Location: userWallet.php");
    }

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

    function getBalance($connection, $userId){
      $query = "SELECT BALANCE FROM USER_WALLET WHERE USER_ID = '$userId'";
      $data = mysqli_query($connection, $query);
      if(!$data) echo("<p>Error getting balance.</p>");
      $result =  $data->fetch_array()[0] ?? '';
      return $result;
    }

    function getRows($connection, $userId)
    {
      $countQuery = "SELECT * from USER_HISTORY where user_id = '$userId'";
      $result = mysqli_query($connection, $countQuery);
      if(!$result) die ("<p>Error get rows.</p>");
      return $result;
    }

    function addBalance ($connection, $userid, $amount) {
        $query = "UPDATE USER_WALLET SET BALANCE = '$amount' WHERE USER_ID = '$userid'";
        $result = mysqli_query($connection, $query);
        if(!$result) die ("<p>Error add balance.</p>");
    }

    function useTicket ($connection, $userId, $activityId, $useNum){
      $query1 = "SELECT * FROM USER_HISTORY WHERE user_id = '$userId' AND activity_id = '$activityId'";
      $result1 = mysqli_query($connection, $query1);
      if(!$result1) die ("<p>Error select user history.</p>");

      if ($result1->num_rows > 0) {
        while ($row = $result1->fetch_array()) {
          $newOwnedNum = $row['owned_ticket'] - $useNum;
          $newUsedNum = $row['used_ticket'] + $useNum;
        }
      }

      $query2 = "UPDATE USER_HISTORY SET owned_ticket = '$newOwnedNum', used_ticket = '$newUsedNum' WHERE user_id = '$userId' AND activity_id = '$activityId'";
      $result2 = mysqli_query($connection, $query2);
      if(!$result2) die ("<p>Error update user history.</p>");
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
                        <a class="nav-link" href="userProfile.php"><?php echo $fname ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="home.php">Home</a>
                    </li>
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

    <!-- Wallet Section  -->
    <section class="white-section" id="wallet">
      <div class="container-fluid">
        <div class="balance-div">
          <div class="row">
            <div class="col-10">
              <h1>Balance:</h1>
            </div>

            <div class="col-2 right-col">
              <div class="balance-value">
                <p>$<?php echo $balance; ?></p>
              </div>

              <form class="" action="userWallet.php" method="post">
                <div class="dropdown balance-editor">
                  <button type="button" class="dropbtn btn btn-outline-secondary" name="button">•••</button>
                  <div class="dropdown-content">
                    <button class="dropdown-item addBalance" type="button" id="addBalance">Add Credit</button>
                  </div>
                </div>

                <div class="balance-input">
                  <input type="text" name="addBalance" class="form-control mb-2 w-50 ms-auto">
                  <button type="submit" name="add" class="btn btn-primary">Add</button>
                </div>
              </form>

            </div>
          </div>

        </div>

        <hr>
          <button type="button"  class="btn btn-secondary w-100 ticket-title">Tickets:</button>
          <div class="ticket-content">
          <?php
          $data = getRows($connection,$user_id);
          $i = 0;
          if ($data->num_rows > 0) {
              while ($row = $data->fetch_array()) {
                $i++;
                $name = getAcitivityName($connection,$row['activity_id']);
                $activityName = "name-".$row['activity_id'];
                $use = "use-".$row['activity_id'];
                $redeem = "redeem-".$row['activity_id'];
                $num = "num-".$row['activity_id'];
                ?>
                <form class="" action="userWallet.php" method="post">
                  <div class="activity-item"  id="<?php echo $row['activity_id']; ?>">
                    <div class="row">
                      <div class="col-8">
                        <h3 id="<?php echo $activityName; ?>"> <?php echo $name; ?></h3>
                      </div>

                      <div class="col-2">
                        <p >X<?php echo $row['owned_ticket']; ?></p>
                      </div>

                      <div class="col-2">
                        <div class="redeem-ticket" id="<?php echo $redeem; ?>">
                          <input type="number" id="<?php echo $num; ?>"  name="<?php echo $num; ?>" class="form-control mb-2 w-75 ms-auto" min="0" max="<?php echo $row['owned_ticket']; ?>">
                          <button type="submit" id="<?php echo $use; ?>"  name="<?php echo $use; ?>" class="btn btn-outline-primary w-75 ms-auto" onclick="generateQR(<?php echo $row['activity_id']; ?>, <?php echo $row['owned_ticket']; ?>);" >Use</button>
                        </div>
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
                if(isset($_POST[$use])){
                  $useNum = htmlentities($_POST[ $num]);
                  if($useNum <= $row['owned_ticket']){
                    useTicket ($connection, $user_id, $row['activity_id'], $useNum);
                    header("Location: userWallet.php");
                  }

                }
              }//end while
          }//end if
          ?>
          </div>

          <hr>
          <button type="button"  class="btn btn-secondary w-100 ticket-title">Used Tickets</button>
          <div class="ticket-content">
            <?php
            $data = getRows($connection,$user_id);
            $i = 0;
            if ($data->num_rows > 0) {
                while ($row = $data->fetch_array()) {
                  $i++;
                  $name = getAcitivityName($connection,$row['activity_id']);
                  if($row['used_ticket'] > 0)
                  {
                    ?>
                    <div class="activity-item">
                      <div class="row">
                        <div class="col-8">
                          <h3> <?php echo $name; ?></h3>
                        </div>

                        <div class="col-2">
                          <p >X<?php echo $row['used_ticket']; ?></p>
                        </div>

                      </div>
                    </div>
                    <?php
                    if ($i != $data->num_rows) {
                      ?>
                      <hr class="inside-hr">
                      <?php
                    }

                  }
                }//end while
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
    $(".balance-input").css("display","none");
    $(".redeem-ticket").css("display","none");
    $(".ticket-content").css("display","none");
    $(".balance-div").hover(function(){
      $(this).toggleClass("selected-activity");
    });

    $(".activity-item").hover(function(){
      $(this).toggleClass("selected-activity");
      $("#redeem-"+this.id).toggle();
    });

    $(".addBalance").click(function(){
      $(".balance-editor").css("display","none");
      $(".balance-input").css("display","block");
    });

    $(".ticket-title").click(function(){
      $(this).toggleClass("drop");
      $(this).next().toggle();
    });

    function generateQR(activityId, ownedTicket){
      if($("#num-"+activityId).val() <= ownedTicket){
          let finalURL ='https://chart.googleapis.com/chart?cht=qr&chl=' + $("#name-"+activityId).text()+' ticket '+$("#num-"+activityId).val() +'&chs=160x160&chld=L|0';
          var qrWindow = window.open(finalURL, "", "width=500,height=500");
      }
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
