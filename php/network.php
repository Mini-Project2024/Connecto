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
$current_user_id = $_SESSION['user']['UserID'];

$query = "SELECT u.* FROM connections c JOIN users u ON c.user_id = u.UserID WHERE c.connector_id = $current_user_id";
$result = mysqli_query($conn, $query);

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
          <li><a href="./network.php">My Network</a></li>
          <li><a href="#">Jobs</a></li>
        </ul>
      </nav>
    </div>
  </header>
  <br><br><br><br><br>
  <center>
    <h1>Your Connections</h1>
  </center>
  <script>
    function redirectToProfile(userID) {
      var profileUrl = "./profile.php?userID=" + userID;
      window.location.href = profileUrl;
    }
  </script>

  <div class="network-container">
    <?php
    while ($userDetails = mysqli_fetch_assoc($result)) {
      $coverImage = $userDetails['CoverPhotoURL'];
      $profileImage = $userDetails['ProfileImage'];
      $position = $userDetails['Position'];
      $firstName = $userDetails['FirstName'];
      $lastName = $userDetails['LastName'];
      $company = $userDetails['CompanyName'];
      $userId = $userDetails['UserID'];

      $no_connections = "SELECT COUNT(*) AS connection_count FROM connections WHERE (connector_id = {$userDetails['UserID']} OR user_id = {$userDetails['UserID']})";
      $res = mysqli_query($conn, $no_connections);
      $no = mysqli_fetch_array($res);

    ?>
      <div class="cards">
        <img src="./uploads/<?php echo $coverImage ?>" alt="Your Name" class="connect-cover" />
        <img src="./uploads/<?php echo $profileImage ?>" alt="Your Name" class="connect-profile" />
        <div class="connect-content">
          <center>
          <h3><?php echo $firstName . ' ' . $lastName ?></h3><center>
          <p><?php echo $position ?> at <?php echo $company ?></p>
          <p><?php echo $no['connection_count'] ?> connections</p></p>
          <div> <button onclick="redirectToProfile(<?php echo $userId ?>)" class="view">View Profile</button></div>
        </div>
      </div>
    <?php } ?>
  </div>
</body>

</html>