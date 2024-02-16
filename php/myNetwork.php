<?php
// session_start();
include("config.php");
include_once("functions.php");

if (!isset($_SESSION['user'])) {
    // Redirect to login page if user is not logged in
    header("Location: ../../../components/pages/login.html");
    exit(); // Stop execution of the script
}
$current_user_id = $_SESSION['user']['UserID'];

$query = "SELECT u.* FROM connections c JOIN users u ON c.user_id = u.UserID WHERE c.connector_id = $current_user_id";
$result = mysqli_query($conn, $query);
$userDetails = mysqli_fetch_assoc($result);

if ($userDetails) {
    $profileImage = $userDetails['ProfileImage'];
    $firstName = $userDetails['FirstName'];
    $lastName = $userDetails['LastName'];
    $email = $userDetails['Email'];
    $userid = $userDetails['UserID'];
}




?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../components/css/style.css">
    <script src="https://kit.fontawesome.com/f4e815f78b.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>

<body>
    <header>
        <div class="container">
            <div class="logo">
                <img src="../components/images/logo.png" alt="">
            </div>
            <nav>
                <ul>
                    <li><a href="./home.php">Home</a></li>
                    <li><a href="./myNetwork.php">My Network</a></li>
                    <li><a href="#">Jobs</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="myNetwork_main">

    </div>
</body>

</html>