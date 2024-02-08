<?php
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $dbname = "jobfinder";

    $conn = mysqli_connect($hostname, $username, $password, $dbname);

    if(!$conn)
    {
        echo "Database Connection Error".mysqli_connect_error();
    }


?>