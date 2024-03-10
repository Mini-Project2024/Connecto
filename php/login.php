<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php

session_start();
include("config.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    if(!empty($email) && !empty($password)){
        $sql = mysqli_query($conn, "SELECT * FROM Users WHERE email = '{$email}'");

        if(mysqli_num_rows($sql) > 0){
            $row = mysqli_fetch_assoc($sql);

            if(password_verify($password, $row['Password'])){
                $_SESSION['user'] = $row;
                // echo $_SESSION['user'];
                header("Location: ../php/home.php");
                exit();
            } else { 
                echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
                echo '<script>';
                echo 'document.addEventListener("DOMContentLoaded", function() {';
                echo 'Swal.fire({';
                echo 'title: "Oh no!",';
                echo 'text: "Email or Password doesn\'t match",';
                echo 'icon: "error",';
                echo 'confirmButtonText: "OK"';
                echo '}).then(function() {';
                echo 'window.location.href = "../components/pages/login.html";';
                echo '});';
                echo '});';
                echo '</script>';

                
            }
        } else {
            echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
            echo '<script>';
            echo 'document.addEventListener("DOMContentLoaded", function() {';
            echo 'Swal.fire({';
            echo 'title: "Oh no!",';
            echo 'text: "Email  doesn\'t exists",';
            echo 'icon: "warning",';
            echo 'confirmButtonText: "OK"';
            echo '}).then(function() {';
            echo 'window.location.href = "../components/pages/login.html";';
            echo '});';
            echo '});';
            echo '</script>';
        }
    } else {
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
        echo '<script>';
        echo 'document.addEventListener("DOMContentLoaded", function() {';
        echo 'Swal.fire({';
        echo 'title: "Oh no!",';
        echo 'text: "please enter all the details",';
        echo 'icon: "error",';
        echo 'confirmButtonText: "OK"';
        echo '}).then(function() {';
        echo 'window.location.href = "../components/pages/login.html";';
        echo '});';
        echo '});';
        echo '</script>';
    }
}
?>

</body>
</html>
