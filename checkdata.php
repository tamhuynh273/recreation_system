<?php
    include "../inc/dbinfo.inc";
    $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);
    if (mysqli_connect_errno()) echo ("Something went wrong");
    $database = mysqli_select_db($connection, DB_DATABASE);


    if(isset($_POST['username']) && isset($_POST['role'])){
        $username = mysqli_real_escape_string($connection,$_POST['username']);
        $role = mysqli_real_escape_string($connection, $_POST['role']);
        if($role == "user") {
            $query = "SELECT * FROM USER_LOGIN WHERE UNAME ='$username'";
        } else {
            $query = "SELECT * FROM VENDOR_LOGIN WHERE UNAME ='$username'";
        }
        $result = mysqli_query($connection, $query);
        $response = "<span style='color: green;'>Available.</span>";
        if(mysqli_num_rows($result) > 0 ){
            $response = "<span style='color: red;'>Email has already been used.</span>";
        }
        
        echo $response;
        die;
    }
    mysqli_close($connection);

?>



