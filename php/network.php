
<?php
// session_start();
include_once("./functions.php");
include("config.php");

// Check if user is logged in
if (!isset($_SESSION['user'])) {
  // Redirect to login page if user is not logged in
  header("Location: ../components/pages/login.html");
  exit(); // Stop execution of the script
}

// Access the user data from session
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

$postquery = "SELECT p.*, u.* 
              FROM posts p 
              JOIN users u ON p.UserID = u.UserID ORDER BY p.PostedDate DESC";
$postresult = mysqli_query($conn, $postquery);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Network</title>
    <link rel="stylesheet" href="../components/css/style.css">
<style>
   
 </style>


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
          <li><a href="#">My Network</a></li>
          <li><a href="#">Jobs</a></li>
          <li><a href="#">Messaging</a></li>
        </ul>
      </nav>
    </div>
  </header>
  <br><br><br><br><br>
  <center> <h1>Your Connections</h1></center>
 
  <div class="cards">
  <img src="./uploads/<?php echo $coverimage ?>" alt="Your Name" class="connect-cover" />
      <img src="./uploads/<?php echo $profileImage ?>" alt="Your Name" class="connect-profile" />
      <div class="connect-content">
      <p><?php echo $position ?> at <?php echo $company ?></p>
      <div > <button>View Profile</button></div>
      </div>
  </div>
</body>
</html>