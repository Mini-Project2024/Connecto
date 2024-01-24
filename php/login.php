<?php

    include("config.php");

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        if(!empty($email) && !empty($password)){
            $sql = mysqli_query($conn, "SELECT * FROM Users WHERE email = '{$email}'");

            if(mysqli_num_rows($sql) > 0){
                $row = mysqli_fetch_assoc($sql);

                if(password_verify($password, $row['Password'])){
                    header("Location: ../components/pages/profile.html");
                } else {
                    echo "Email or Password is Incorrect";
                }
            } else {
                echo "$email this email does not exits";
            }
        } else {
            echo "Please Enter the all the details";
        }
    }