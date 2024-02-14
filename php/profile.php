<?php
session_start();
include("config.php");

if (!isset($_SESSION['user'])) {
  // Redirect to login page if user is not logged in
  header("Location: ../../../components/pages/login.html");
  exit(); // Stop execution of the script
}

$user = $_SESSION['user'];
$query = "SELECT * FROM users WHERE UserID = " . $user['UserID']; // Assuming user_details table stores additional user information
$result = mysqli_query($conn, $query);
$userDetails = mysqli_fetch_assoc($result);

// Check if user details exist
if ($userDetails) {
  $profileImage = $userDetails['ProfileImage'];
  $firstName = $userDetails['FirstName'];
  $lastName = $userDetails['LastName'];
  $email = $userDetails['Email'];
  $bio = $userDetails['Bio'];
  $company = $userDetails['CompanyName'];
  $position = $userDetails['Position'];
  $institution = $userDetails['Institution'];
  $degree = $userDetails['Degree'];
  $field_of_study = $userDetails['FieldOfStudy'];
  $graduationyear = $userDetails['GraduationYear'];
  $coverimage = $userDetails['CoverPhotoURL'];
  $NativePlace = $userDetails['NativePlace'];
}
$viewingOwnProfile = ($_SESSION['user']['UserID'] === $userDetails['UserID']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="../components/css/style.css">
  <script src="https://kit.fontawesome.com/f4e815f78b.js" crossorigin="anonymous"></script>
</head>

<body>
  <header>
    <div class="container">
      <div class="logo">
        <img src="../components/images/logo.png" alt="">
      </div>
      <nav>
        <ul>
          <li><a href="#">Home</a></li>
          <li><a href="#">My Network</a></li>
          <li><a href="#">Jobs</a></li>
          <li><a href="#">Messaging</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <div class="full-profile">
    <div class="main-profile">
      <img src="./uploads/<?php echo $coverimage ?>" alt="Your Name" class="cover-photo" />
      <img src="./uploads/<?php echo $profileImage ?>" alt="Your Name" class="profile-image" />
      <div class="username">
        <h1><?php echo $firstName . ' ' . $lastName; ?></h1>

        <p> From <?php echo $NativePlace ?></p>
        <p>1 connections</p>
        <br>
        <?php if (!$viewingOwnProfile) { ?>
          <button class="connect" id="connect"><i class="fa-solid fa-user-plus"></i> Connect</button>
          <a href="./messages.php" class="message_btn" id="message_btn"><i class="fa-solid fa-paper-plane"></i> Message</a>
        <?php } ?>
        <?php if ($viewingOwnProfile) { ?>
          <a href="#" class="view-connect" id="view-connect"><i class="fa-solid fa-user-plus"></i> View Connections</a>
        <?php } ?>
      </div>

      <br><br><br>
      <hr>
      <br>

      <h4><?php echo $bio ?></h4><br>
      <hr>
      <div class="flex">
        <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24">
          <path fill="currentColor" d="M20 6h-4V4c0-1.11-.89-2-2-2h-4c-1.11 0-2 .89-2 2v2H4c-1.11 0-1.99.89-1.99 2L2 19c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2m-6 0h-4V4h4z" />
        </svg>
        <p><?php echo $position ?> at <?php echo $company ?></p>


        <br>
      </div>
      <hr>
      <div class="flex">
        <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 48 48">
          <g fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="4">
            <path d="M44 24V9H24H4V24V39H24" />
            <path d="M44 34L30 34" />
            <path d="M39 29L44 34L39 39" />
            <path d="M4 9L24 24L44 9" />
          </g>
        </svg>
        <?php echo $email ?> <br>
      </div>
      <hr>
      <div class="flex">
        <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 640 512">
          <path fill="currentColor" d="M337.8 5.4c-10.8-7.2-24.8-7.2-35.6 0L166.3 96H48c-26.5 0-48 21.5-48 48v320c0 26.5 21.5 48 48 48h208v-96c0-35.3 28.7-64 64-64s64 28.7 64 64v96h208c26.5 0 48-21.5 48-48V144c0-26.5-21.5-48-48-48H473.7zM96 192h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H96c-8.8 0-16-7.2-16-16v-64c0-8.8 7.2-16 16-16m400 16c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16h-32c-8.8 0-16-7.2-16-16zM96 320h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H96c-8.8 0-16-7.2-16-16v-64c0-8.8 7.2-16 16-16m400 16c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16h-32c-8.8 0-16-7.2-16-16zM232 176a88 88 0 1 1 176 0a88 88 0 1 1-176 0m88-48c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16s-7.2-16-16-16h-16v-16c0-8.8-7.2-16-16-16" />
        </svg>
        <?php echo $institution ?> <br>
      </div>
      <hr>
      <div class="flex">
        <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24">
          <path fill="currentColor" d="M5 13.18v2.81c0 .73.4 1.41 1.04 1.76l5 2.73c.6.33 1.32.33 1.92 0l5-2.73c.64-.35 1.04-1.03 1.04-1.76v-2.81l-6.04 3.3c-.6.33-1.32.33-1.92 0zm6.04-9.66l-8.43 4.6c-.69.38-.69 1.38 0 1.76l8.43 4.6c.6.33 1.32.33 1.92 0L21 10.09V16c0 .55.45 1 1 1s1-.45 1-1V9.59c0-.37-.2-.7-.52-.88l-9.52-5.19a2.04 2.04 0 0 0-1.92 0" />
        </svg>
        <p><?php echo $degree ?> in <?php echo $field_of_study ?> completed in the year <?php echo $graduationyear ?> <br> </p>
        <br>
      </div>
    </div>
    <!-- <button class="connect" id="connect"><i class="fa-solid fa-user-plus"></i>  Connect</button> -->
    <!-- <a href="./messages.php" class="message_btn" id="message_btn"><i class="fa-solid fa-paper-plane"></i> Message</a> -->



    <div class="side-profile">
      <div class="follow-section">
        <img src="../components/images/profile.jpg" alt="" class="follow-profile">
        <p>username</p>
        <button>connect</button>
      </div>
    </div>
  </div>
</body>

</html>