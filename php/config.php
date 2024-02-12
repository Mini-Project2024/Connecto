<?php
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $dbname = "job_finder";

    $conn = mysqli_connect($hostname, $username, $password, $dbname);

    if(!$conn)
    {
        echo "Database Connection Error".mysqli_connect_error();
    }


?>