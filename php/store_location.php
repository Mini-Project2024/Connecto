<?php
// Include the database configuration file
include("config.php");

// Start session if not already started
session_start();

// Check if user is logged in
if (!isset($_SESSION['user'])) {
  // Redirect to login page if user is not logged in
  header("Location: ../components/pages/login.html");
  exit(); // Stop execution of the script
}

// Access the user data from session
$user = $_SESSION['user'];

// Check if latitude and longitude are sent from the client
if (isset($_POST['latitude']) && isset($_POST['longitude'])) {
  // Store latitude and longitude in variables
  $latitude = $_POST['latitude'];
  $longitude = $_POST['longitude'];

  // Store latitude and longitude in the database
  $userID = $user['UserID'];
  $query = "UPDATE users SET Latitude = '$latitude', Longitude = '$longitude' WHERE UserID = '$userID'";
  $result = mysqli_query($conn, $query);

  if ($result) {
    echo "Location stored successfully.";
  } else {
    echo "Error storing location: " . mysqli_error($conn);
  }
} else {
  echo "Latitude and longitude not received.";
}
?>
