<?php
    include "../inc/dbinfo.inc";
    $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);
    if (mysqli_connect_errno()) echo ("Something went wrong");
    $database = mysqli_select_db($connection, DB_DATABASE);
    session_start();
    ob_start();

    echo <<<_END
        <html lang="en" dir="ltr">
        <head>
        <meta charset="utf-8">
        <title>Information</title>

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

        <section class="white-section" id="information">
            <div class = "container-fluid" >
                <div class="mb-5">
                <h1 >Let's know more about you!</h1>
                </div>
            <form action="info.php" method="POST">
            <div>
                <div class="mb-3 input-group">
                <span class="input-group-text text-light bg-secondary info-input">First Name</span>
                <input type="text" label="First name" class="form-control" name="FirstName" required>
                </div>

                <div class="mb-3 input-group">
                <span class="input-group-text text-light bg-secondary info-input">Last Name</span>
                <input type="text" label="Last name" class="form-control" name="LastName" required>
                </div>

                <div class="mb-3 input-group">
                <span class="input-group-text text-light bg-secondary info-input">Phone Number</span>
                <input type="tel" label="Phone number" class="form-control" name="PhoneNumber"required>
                </div>

                <div class="mb-3 input-group">
                <span class="input-group-text text-light bg-secondary info-input">Birth Date</span>
                <input type="date" label="Birth date" class="form-control" name="BirthDate" required>
                </div>

                <div class="input-group mb-3">
                <label class="input-group-text text-light bg-secondary info-input" for="inputGenderSelect">Gender</label>
                <select class="form-select" id="inputGenderSelect" name="Gender" required>
                    <option selected>Choose...</option>
                    <option value="female">Female</option>
                    <option value="male">Male</option>
                </select>
                </div>
                <div class="mb-5 input-group">
                <span class="input-group-text text-light bg-secondary info-input">City</span>
                <input type="text" label="City" class="form-control" name ="City" required>
                </div>

                <div class="ms-auto col-4 mt-3">
                <input type="submit" class="btn btn-primary w-100" name="Next" value="Next"/>
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

        </body>
        </html>
_END;

    VerifyTable($connection, DB_DATABASE);

    if( isset($_POST['Next']) && isset($_SESSION['Email']) ) {
        $un = $_SESSION['Email'];
        $fn = htmlentities($_POST['FirstName']);
        $ln = htmlentities($_POST['LastName']);
        $phone = htmlentities($_POST['PhoneNumber']);
        $birthdate = htmlentities($_POST['BirthDate']);
        $sex = htmlentities($_POST['Gender']);
        $ct = htmlentities($_POST['City']);
        echo $_SESSION['role'];
        if($_SESSION['role'] == "user") {
            $query1 = "SELECT USER_ID FROM USER_LOGIN WHERE UNAME='$un'";
            $result1 = mysqli_query($connection, $query1);
            if(!$result1) die ("Something went wrong");
            else {
                $result_row1 = mysqli_fetch_array($result1, MYSQLI_NUM);
                $result1->close();
                AddUserInfo($connection, $result_row1[0], $un, $fn, $ln, $phone, $birthdate, $sex, $ct);
                header("Location: interest.php");
            }
        } elseif($_SESSION['role'] == "vendor") {
            $query2 = "SELECT VENDOR_ID FROM VENDOR_LOGIN WHERE UNAME='$un'";
            $result2 = mysqli_query($connection, $query2);
            if(!$result2) die ("Something went wrong");
            else {
                $result_row2 = mysqli_fetch_array($result2, MYSQLI_NUM);
                $result2->close();
                AddVendorInfo($connection, $result_row2[0], $un, $fn, $ln, $phone, $birthdate, $sex, $ct);
                header("Location: index.php");
            }
        }
    }
    ob_end_flush();
    mysqli_close($connection);


    function AddUserInfo($connection, $uid, $un, $fname, $lname, $phoneno, $dob, $gender, $city) {
        $fn = mysqli_real_escape_string($connection, $fname);
        $ln = mysqli_real_escape_string($connection, $lname);
        $phone = mysqli_real_escape_string($connection, $phoneno);
        $birthdate = mysqli_real_escape_string($connection, $dob);
        $sex = mysqli_real_escape_string($connection, $gender);
        $ct = mysqli_real_escape_string($connection, $city);

        $query = "INSERT INTO USER_INFO (USER_ID, UNAME, FNAME, LNAME, PHONENO, DOB, SEX, CITY) VALUES ('$uid', '$un','$fn', '$ln', '$phone', '$birthdate', '$sex', '$ct');";
        if(!mysqli_query($connection, $query)) echo ("<p>Error adding user info data.</p>");
    }

    function AddVendorInfo($connection, $vid, $un, $fname, $lname, $phoneno, $dob, $gender, $city) {
        $fn = mysqli_real_escape_string($connection, $fname);
        $ln = mysqli_real_escape_string($connection, $lname);
        $phone = mysqli_real_escape_string($connection, $phoneno);
        $birthdate = mysqli_real_escape_string($connection, $dob);
        $sex = mysqli_real_escape_string($connection, $gender);
        $ct = mysqli_real_escape_string($connection, $city);

        $query = "INSERT INTO VENDOR_INFO (VENDOR_ID, UNAME, FNAME, LNAME, PHONENO, DOB, SEX, CITY) VALUES ('$vid', '$un','$fn', '$ln', '$phone', '$birthdate', '$sex', '$ct');";
        if(!mysqli_query($connection, $query)) echo ("<p>Error adding user info data.</p>");
    }

  /*Check if table exists, if not create table USER_INFO*/
    function VerifyTable($connection, $dbName) {
        if(!TableExists("USER_INFO", $connection, $dbName)) {
            $query = "CREATE TABLE USER_INFO (
              USER_ID INT NOT NULL PRIMARY KEY,
              UNAME VARCHAR(64) NOT NULL,
              FNAME VARCHAR(45) NOT NULL,
              LNAME VARCHAR(45) NOT NULL,
              PHONENO VARCHAR(12) NOT NULL,
              DOB DATE NOT NULL,
              SEX VARCHAR(10) NOT NULL,
              CITY VARCHAR(45) NOT NULL,
              CONSTRAINT FK_UserInfo
              FOREIGN KEY (USER_ID) REFERENCES USER_LOGIN(USER_ID)
              ON DELETE CASCADE
              )";
            if(!mysqli_query($connection, $query)) echo("<p>Error creating user_info table.</p>");
        }

        if(!TableExists("VENDOR_INFO", $connection, $dbName)) {
            $query1 = "CREATE TABLE VENDOR_INFO (
              VENDOR_ID INT NOT NULL PRIMARY KEY,
              UNAME VARCHAR(64) NOT NULL,
              FNAME VARCHAR(45) NOT NULL,
              LNAME VARCHAR(45) NOT NULL,
              PHONENO VARCHAR(12) NOT NULL,
              DOB DATE NOT NULL,
              SEX VARCHAR(10) NOT NULL,
              CITY VARCHAR(45) NOT NULL,
              CONSTRAINT FK_VendorInfo
              FOREIGN KEY (VENDOR_ID) REFERENCES VENDOR_LOGIN(VENDOR_ID)
              ON DELETE CASCADE
              )";
            if(!mysqli_query($connection, $query1)) echo("<p>Error creating vendor_info table.</p>");
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
?>
