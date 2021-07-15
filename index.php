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
    <title>KeyCity</title>

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
    <!-- Login Section -->
  <section class="colored-section" id="login">

    <!-- Navigation Bar -->
    <div class="container-fluid">
      <nav class="navbar navbar-expand-lg navbar-dark">
        <span class="navbar-brand">KeyCity</span>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <form method="POST" action="index.php">
                <button type="submit" class="btn btn-outline-secondary logout" name="anonym">Anonymous user</button>
              <form>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Login</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">About</a>
            </li>
          </ul>
        </div>
      </nav>

      <div class="row">
        <div class="col-lg-6 col-md-12">
          <h1 class="big-heading">Search Interesting Activities Near You.</h1>
          <button type="button" class="btn btn-light btn-lg download-btn"><i class="fab fa-apple"></i> Download</button>
          <button type="button" class="btn btn-outline-light btn-lg download-btn"><i class="fab fa-google-play"></i> Download</button>
        </div>


        <div class="col-lg-6 col-md-12">
         <form class="" method="POST">
           <div class="btn-group w-100 mb-3" role="group">
             <button type="submit" name="signup-btn" class="btn btn-outline-primary signup-btn">Sign up</button>
             <button type="submit" name="login-btn"  class="btn btn-outline-primary login-btn">Login</button>
           </div>
         </form>


          <!-- Login form -->
        <form action="index.php" method="POST"><div>
        <div class="login-form">
            <div class="mb-3">
            <label for="loginRole" class="form-label text-light">Role</label>
            <select class="form-select" id="roleInput" name="role" required>
                <option selected>Choose...</option>
                <option value="user">User</option>
                <option value="vendor">Vendor</option>
            </select>
            </div>

            <div class="mb-3">
            <label for="emailInput" class="form-label text-light">Email address</label>
            <input type="email" class="form-control" id="loginEmailInput" placeholder="name@example.com" name="LoginEmail"required>
            </div>
            <div class="mb-3">
            <label for="passwordInput" class="form-label text-light">Password</label>
            <input type="password" class="form-control" id="loginPasswordInput" name="LoginPassword" required>
            </div>

            <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" onchange="showPassword(this)" name="show-password" value="Yes" id="showPasswordCheck">
            <label class="form-check-label text-light" for="showPasswordCheck">
                Show password
            </label>
            </div>
            <a href="searchAccount.php">Forgot Password? </a>
            <input class="login-submit btn btn-primary w-100" type="submit" name ="LoginSubmit" value="Log In"/>
        </div>
        </div></form>

         <!-- Sign Up form-->
        <form action="index.php" method="POST"><div>
        <div class="signup-form">
            <div class="mb-3">
            <label for="loginRole" class="form-label text-light">Role</label>
            <select class="form-select" id="role"  name="role" required>
                <option selected required>Choose...</option>
                <option value="user">User</option>
                <option value="vendor">Vendor</option>
            </select>
            </div>
            <div class="mb-3">
            <label for="emailInput" class="form-label text-light">Email address</label>
            <div>
            <input type="email" class="form-control" id="SignupEmail" placeholder="name@example.com" name="SignupEmail" required>
            <div id="uname_response" ></div>
            </div>
            </div>
            <div class="mb-3">
            <label for="signupPasswordInput" class="form-label text-light">Set Password</label>
            <input type="password" class="form-control" id="signupPasswordInput" name="Password" onkeyup="check()" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number, one uppercase, one lowercase letter, and be at least 8 or more characters" required>
            </div>
            <div class="mb-3">
            <label for="confirmPasswordInput" class="form-label text-light">Confirm Password</label>
            <input type="password" class="form-control" id="signupConfirmPasswordInput" name="Confirm_Password" onkeyup="check()" required>
            <span id='message'></span>
            </div>
            <!-- Show Password Check -->
            <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" onchange="showPassword(this)" name="show-password" value="Yes" id="showPasswordCheck">
            <label class="form-check-label text-light" for="showPasswordCheck">
              Show password
            </label>
            </div>

            <!-- Submit button -->
            <input class="signup-submit btn btn-primary w-100" type="submit" name ="SignupSubmit" value="Sign Up"/>
        </div>
        </div></form>

        </div>
      </div>
    </div>

  </section>

  <!-- Features Section -->
  <section class="white-section" id="features">
    <div class="container-fluid">
      <div class="row">
        <div class="feature-box col-lg-4">
          <i class="icon fas fa-map-marker-alt fa-4x"></i>
          <h3 class="feature-title">Efficient to Search</h3>
          <p class="feature-text">Efficient local map for you to search the activities you are looking for.</p>
        </div>

        <div class="feature-box col-lg-4">
          <i class="icon fas fa-wallet fa-4x"></i>
          <h3 class="feature-title">Convenient to Purchases</h3>
          <p class="feature-text">Purchase electronic pass for activities by using online wallet.</p>
        </div>

        <div class="feature-box col-lg-4">
          <i class="icon fas fa-heart fa-4x"></i>
          <h3 class="feature-title">Easy to Use</h3>
          <p class="feature-text">The system is simple, operates quickly, has scalable functionalities, and is user-friendly.</p>
        </div>
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
  $(".signup-form").css("display","none");
  $(".signup-submit").css("display","none");
  $(".subscribe-check").css("display","none");
  </script>
_END;

  if (array_key_exists('signup-btn', $_POST)) {
echo <<<_END
    <script type="text/javascript">
    $(".login-submit").css("display","none");
    $(".login-form").css("display","none");
    $(".signup-form").css("display","block");
    $(".signup-submit").css("display","block");
    $(".subscribe-check").css("display","block");
    </script>
_END;
  } elseif (array_key_exists('login-btn', $_POST)) {
echo <<<_END
    <script type="text/javascript">
    $(".login-submit").css("display","block");
    $(".login-form").css("display","block");
    $(".signup-form").css("display","none");
    $(".signup-submit").css("display","none");
    $(".subscribe-check").css("display","none");
    </script>
_END;

  }
echo <<<_END

  <script>
  function showPassword(x) {
  var checkbox = x.checked;
  if(checkbox){
    $("#loginPasswordInput").attr("type","text");
    $("#signupPasswordInput").attr("type","text");
    $("#signupConfirmPasswordInput").attr("type","text");
  }
  else{
    $("#loginPasswordInput").attr("type","password");
    $("#signupPasswordInput").attr("type","password");
    $("#signupConfirmPasswordInput").attr("type","password");
  }
  }
  </script>

    <!-- check matching password -->
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

    <!-- check existed user/vendor -->
    <script>
        $(document).ready(function(){
        $("#SignupEmail").keyup(function(){
        var username = document.getElementById("SignupEmail").value;
        if(document.getElementById("role").value == "user") {
            var role="user";
        } else {
            var role="vendor";
        }
        if(username != ''){

        $.ajax({
           url: 'checkdata.php',
           type: 'POST',
           data: {username:username, role:role},
           success: function(response){

              $("#uname_response").html(response);
            }
        });
        }else{
        $("#uname_response").html("");
        }

        });

    });
    </script>

  </body>
</html>
_END;

    VerifyTable($connection, DB_DATABASE);
    $init_balance_user = number_format(200,2);
    $init_balance_vendor = number_format(0,2);

    //if signup is submitted

    if(isset($_POST['SignupSubmit'])) {
        $un = htmlentities($_POST['SignupEmail']);
        $pw = htmlentities($_POST['Password']);
        //sign up as user
        if($_POST['role'] == "user") {
            $role = "user";
            $finduser = "SELECT UNAME FROM USER_LOGIN WHERE UNAME = '$un'";
            $user_result = mysqli_query($connection, $finduser);
            if(!$user_result) die ("Something went wrong");
            elseif(mysqli_num_rows($user_result) > 0) header("Location: index.php");
            else {
                CreateUserAccount($connection, $un, $pw);
                //retrieve user_id and use as data to create user_wallet table
                $find_uid = "SELECT USER_ID FROM USER_LOGIN WHERE UNAME = '$un'";
                $find_uid_result = mysqli_query($connection, $find_uid);
                if(!$find_uid_result) die ("Something went wrong");
                else {
                    $uid_result = mysqli_fetch_array($find_uid_result, MYSQLI_NUM);
                    $find_uid_result->close();
                    CreateUserWallet($connection, $uid_result[0], $init_balance_user);
                    $_SESSION['Email'] = $un;
                    $_SESSION['role'] = $role;
                    header("Location: info.php");
                }
            }
        }
        //sign up as vendor
        elseif($_POST['role'] == "vendor") {
            $role = "vendor";
            $findvendor = "SELECT UNAME FROM VENDOR_LOGIN WHERE UNAME = '$un'";
            $vendor_result = mysqli_query($connection, $findvendor);
            if(!$vendor_result) die ("Something went wrong");
            elseif(mysqli_num_rows($vendor_result) > 0) header("Location: index.php");
            else {
                CreateVendorAccount($connection, $un, $pw);
                //retrieve vendor_id and use as data to create vendor_wallet table
                $find_vid = "SELECT VENDOR_ID FROM VENDOR_LOGIN WHERE UNAME = '$un'";
                $find_vid_result = mysqli_query($connection, $find_vid);
                if(!$find_vid_result) die ("Something went wrong");
                else {
                    $vid_result = mysqli_fetch_array($find_vid_result, MYSQLI_NUM);
                    $find_vid_result->close();
                    CreateVendorWallet($connection, $vid_result[0], $init_balance_vendor);
                    $_SESSION['Email'] = $un;
                    $_SESSION['role'] = $role;
                    header("Location: info.php");
                }
            }
        }
    }

    //if login is submitted
    if(isset($_POST['LoginSubmit'])) {
        $li_email = mysqli_real_escape_string($connection, $_POST['LoginEmail']);
        $li_password = mysqli_real_escape_string($connection, $_POST['LoginPassword']);
        $today = new Datetime(date('m.d.y'));

        //log in as user
        if($_POST['role'] == "user") {
          // check LOCK
          $userQuery = "SELECT * FROM USER_LOGIN WHERE UNAME = '$li_email'";
          $userResult = mysqli_query($connection, $userQuery );
          if(!userResult) die ("Something went wrong");
          elseif($userRow = $userResult->fetch_array()){
            if($userRow['LOCK_ACCOUNT'] == 1){
              echo '<script>alert("Your account is locked by admin. Please contact the admin")</script>';
            }
            else{
              //save user info into session
              $query1 = "SELECT * FROM USER_INFO WHERE UNAME = '$li_email'";
              $result1 = mysqli_query($connection, $query1);
              if(!result1) die ("Something went wrong");
              elseif (mysqli_num_rows($result1)) {
                  $row_result1 = mysqli_fetch_array($result1, MYSQLI_NUM);
                  $result1->close();
                  $_SESSION['UserID'] = $row_result1[0];
                  $_SESSION['FirstName'] = $row_result1[2];
                  $_SESSION['LastName'] = $row_result1[3];
                  $_SESSION['DoB'] = $row_result1[5];
                  $_SESSION['Phone'] = $row_result1[4];
                  $_SESSION['City'] = $row_result1[7];
                  $dob = new DateTime($row_result1[5]);
                  $diff = $today->diff($dob);
                  $age = $diff->y;
                  $_SESSION['Age'] = $age;
                  $_SESSION['Balance'] = $init_balance_user;
              }
              //query to check password
              $query2 = "SELECT * FROM USER_LOGIN WHERE UNAME = '$li_email';";
              $result2 = mysqli_query($connection, $query2);

              if(!result2) die ("Something went wrong");
              elseif (mysqli_num_rows($result2)) {
                  $row_result2 = mysqli_fetch_array($result2, MYSQLI_NUM);
                  $result2->close();

                  $salt1 = $row_result2[3];
                  $salt2 = $row_result2[4];
                  $token_user = hash('ripemd128', "$salt1$li_password$salt2");

                  if (($token_user == $row_result2[2]) && ($li_email != 'admin@gmail.com')) {
                     header("Location: home.php");
                  } elseif (($token_user == $row_result2[2]) && ($li_email == 'admin@gmail.com')) {
                      header("Location: admin.php");
                  } else {
                      header("Location: invalidLogin.php");
                  }
              } else {
                  header("Location: invalidLogin.php");
              }
            }
          }

        //log in as vendor
        } elseif ($_POST['role'] == "vendor") {
          $vendorQuery = "SELECT * FROM VENDOR_LOGIN WHERE UNAME = '$li_email'";
          $vendorResult = mysqli_query($connection, $vendorQuery );
          if(!vendorResult) die ("Something went wrong");
          elseif($vendorRow = $vendorResult->fetch_array()){
            if($vendorRow['LOCK_ACCOUNT'] == 1){
              echo '<script>alert("Your account is locked by admin. Please contact the admin")</script>';
            }
            else{
              //save vendor info into session
              $query4 = "SELECT * FROM VENDOR_INFO WHERE UNAME = '$li_email'";
              $result4 = mysqli_query($connection, $query4);
              if(!result4) die ("Something went wrong");
              elseif (mysqli_num_rows($result4)) {
                  $row_result4 = mysqli_fetch_array($result4, MYSQLI_NUM);
                  $result4->close();
                  $_SESSION['VendorID'] = $row_result4[0];
                  $_SESSION['FirstName'] = $row_result4[2];
                  $_SESSION['LastName'] = $row_result4[3];
                  $_SESSION['DoB'] = $row_result4[5];
                  $_SESSION['Phone'] = $row_result4[4];
                  $_SESSION['City'] = $row_result4[7];
                  $dob = new DateTime($row_result4[5]);
                  $diff = $today->diff($dob);
                  $age = $diff->y;
                  $_SESSION['Age'] = $age;
                  $_SESSION['Balance'] = $init_balance_vendor;
              }
              //query to check password
              $query3 = "SELECT * FROM VENDOR_LOGIN WHERE UNAME = '$li_email';";
              $result3 = mysqli_query($connection, $query3);

              if(!result3) die ("Something went wrong");
              elseif (mysqli_num_rows($result3)) {
                  $row_result3 = mysqli_fetch_array($result3, MYSQLI_NUM);
                  $result3->close();
                  $salt1 = $row_result3[3];
                  $salt2 = $row_result3[4];
                  $token_vendor = hash('ripemd128', "$salt1$li_password$salt2");

                  if($token_vendor == $row_result3[2]) {
                      header("Location: vendor.php");
                  } else {
                      header("Location: invalidLogin.php");
                  }
              } else {
                  header("Location: invalidLogin.php");
              }
            }
          }

        }


    }

    //annonymous user
    if(isset($_POST['anonym'])) {
        destroy_session_and_data();
        header("Location: home.php");
    }

    //Create Account (including provider and user)
    function CreateUserAccount($connection, $uname, $password) {
        $un = mysqli_real_escape_string($connection, $uname);
        $pw = mysqli_real_escape_string($connection, $password);
        $salt1 = rand();
        $salt2 = rand();
        $token = hash('ripemd128', "$salt1$pw$salt2");
        $query = "INSERT INTO USER_LOGIN (UNAME, PASSWORD_HASHED, SALT1, SALT2, LOCK_ACCOUNT) VALUES ('$un', '$token', '$salt1', '$salt2', '0')";
        if(!mysqli_query($connection, $query)) echo ("<p>Error creating account data.</p>");
    }

    function CreateVendorAccount($connection, $uname, $password) {
        $un = mysqli_real_escape_string($connection, $uname);
        $pw = mysqli_real_escape_string($connection, $password);
        $salt1 = rand();
        $salt2 = rand();
        $token = hash('ripemd128', "$salt1$pw$salt2");
        $query = "INSERT INTO VENDOR_LOGIN (UNAME, PASSWORD_HASHED, SALT1, SALT2, LOCK_ACCOUNT) VALUES ('$un', '$token', '$salt1', '$salt2', '0')";
        if(!mysqli_query($connection, $query)) echo ("<p>Error creating account data.</p>");
    }

    function CreateUserWallet($connection, $user_id, $init_amount_user) {
        $query = "INSERT INTO USER_WALLET (USER_ID, BALANCE) VALUES ('$user_id', '$init_amount_user');";
        if(!mysqli_query($connection, $query)) echo ("<p>Error creating userwallet data.</p>");
    }

    function CreateVendorWallet($connection, $vendor_id, $init_amount_vendor) {
        $query = "INSERT INTO VENDOR_WALLET (VENDOR_ID, BALANCE) VALUES ('$vendor_id', '$init_amount_vendor');";
        if(!mysqli_query($connection, $query)) echo ("<p>Error creating userwallet data.</p>");
    }


      /*Check if tables exist, if not create table USER_LOGIN and PROVIDER_LOGIN*/
    //Create table LOG_IN
    function VerifyTable($connection, $dbName) {
        if(!TableExists("USER_LOGIN", $connection, $dbName)) {
            $query1 = "CREATE TABLE USER_LOGIN (
            USER_ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            UNAME VARCHAR(64) NOT NULL,
            PASSWORD_HASHED VARCHAR(64) NOT NULL,
            SALT1 VARCHAR(64) NOT NULL,
            SALT2 VARCHAR(64) NOT NULL,
            LOCK_ACCOUNT INT NOT NULL
            )";
            if(!mysqli_query($connection, $query1)) echo("<p>Error creating user_login table.</p>");
            $adminUm = "admin@gmail.com";
            $adminPw = "KeyCityCS160";
            $salt1 = rand();
            $salt2 = rand();
            $token = hash('ripemd128', "$salt1$adminPw$salt2");
            $query = "INSERT INTO USER_LOGIN (UNAME, PASSWORD_HASHED, SALT1, SALT2, LOCK_ACCOUNT) VALUES ('$adminUm', '$token', '$salt1', '$salt2', '0')";
            if(!mysqli_query($connection, $query)) echo ("<p>Error creating admin account.</p>");
        }
        if(!TableExists("VENDOR_LOGIN", $connection, $dbName)) {
            $query2 = "CREATE TABLE VENDOR_LOGIN (
            VENDOR_ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            UNAME VARCHAR(64) NOT NULL,
            PASSWORD_HASHED VARCHAR(64) NOT NULL,
            SALT1 VARCHAR(64) NOT NULL,
            SALT2 VARCHAR(64) NOT NULL,
            LOCK_ACCOUNT INT NOT NULL
            )";
            if(!mysqli_query($connection, $query2)) echo("<p>Error creating vendor_login table.</p>");
        }
        if(!TableExists("USER_WALLET", $connection, $dbName)) {
            $query3 = "CREATE TABLE USER_WALLET (
            USER_ID INT NOT NULL PRIMARY KEY,
            BALANCE DECIMAL(9,2) NOT NULL,
            CONSTRAINT FK_UserWallet
            FOREIGN KEY (USER_ID) REFERENCES USER_LOGIN(USER_ID)
            ON DELETE CASCADE
            )";
            if(!mysqli_query($connection, $query3)) echo("<p>Error creating userwallet table.</p>");

        }
        if(!TableExists("VENDOR_WALLET", $connection, $dbName)) {
            $query4 = "CREATE TABLE VENDOR_WALLET (
            VENDOR_ID INT NOT NULL PRIMARY KEY,
            BALANCE DECIMAL(9,2) NOT NULL,
            CONSTRAINT FK_VendorWallet
            FOREIGN KEY (VENDOR_ID) REFERENCES VENDOR_LOGIN(VENDOR_ID)
            ON DELETE CASCADE
            )";
            if(!mysqli_query($connection, $query4)) echo("<p>Error creating vendorwallet table.</p>");
        }
    }

        /*Check for existance of table*/
    function TableExists($tableName, $connection, $dbName) {
        $t = mysqli_real_escape_string($connection, $tableName);
        $d = mysqli_real_escape_string($connection, $dbName);

        $checktable = mysqli_query($connection,
            "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = '$t' AND TABLE_SCHEMA = '$d'");

        if(mysqli_num_rows($checktable) > 0) return true;

        return false;
    }

    function destroy_session_and_data() {
        $_SESSION = array();
        setcookie(session_name(), '', time() - 2592000, '/');
        session_destroy();
    }

    ob_end_flush();
    mysqli_close($connection);
?>
