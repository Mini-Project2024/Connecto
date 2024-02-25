<?php

$hostname = "localhost";
$username = "root";
$password = "";
$dbname = "finder";

$mysqli = new mysqli(
    hostname: $hostname,
    username: $username,
    password: $password,
    database: $dbname
);

if ($mysqli->connect_errno) {
    die("Connection error: " . $mysqli->connect_error);
}

return $mysqli;
