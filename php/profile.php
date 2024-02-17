<?php
// session_start();
include("config.php");
include_once("functions.php");

if (!isset($_SESSION['user'])) {
  // Redirect to login page if user is not logged in
  header("Location: ../../../components/pages/login.html");
  exit(); // Stop execution of the script
}


if (isset($_GET['userID'])) {
  $userID = $_GET['userID'];
  $follow_suggestions = filterFollowSuggestion($userID);
  $query = "SELECT * FROM users WHERE UserID = $userID";
  $result = mysqli_query($conn, $query);
  $userDetails = mysqli_fetch_assoc($result);
} else {
  $user = $_SESSION['user'];
  $follow_suggestions = filterFollowSuggestion($user['UserID']);
  $query = "SELECT * FROM users WHERE UserID = " . $user['UserID']; // Assuming user_details table stores additional user information
  $result = mysqli_query($conn, $query);
  $userDetails = mysqli_fetch_assoc($result);
}

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
  $graduationYear = $userDetails['GraduationYear'];
  $coverImage = $userDetails['CoverPhotoURL'];
  $NativePlace = $userDetails['NativePlace'];
}

$viewingOwnProfile = ($_SESSION['user']['UserID'] === $userDetails['UserID']);
// Check if the viewing user is already connected to the profile user
$connected = false;
if (!$viewingOwnProfile) {
  $connected = checkFollowStatus($userDetails['UserID']);
}

$no_connections = "SELECT COUNT(*) AS connection_count FROM connections WHERE connector_id = {$userDetails['UserID']}";
$res = mysqli_query($conn, $no_connections);
$no = mysqli_fetch_array($res);

$postquery = "SELECT p.*, u.* 
              FROM posts p 
              JOIN users u ON p.UserID = u.UserID and u.UserID = " . $user['UserID']. "
              ORDER BY p.PostedDate DESC";
$postresult = mysqli_query($conn, $postquery);

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
  <script>
    //for following the user
    $(document).on("click", ".connect", function() {
      var user_id_connect = $(this).data('user-id');
      var button = this;
      $(button).attr('disabled', true);
      $.ajax({
        url: './ajax.php?connect',
        method: 'POST',
        dataType: 'json',
        data: {
          user_id: user_id_connect
        },
        success: function(response) {
          if (response.status) {
            $(button).data('user-id', 0);
            $(button).html('<i class="fa-solid fa-user-check"></i> Connected');
          } else {
            $(button).attr('disabled', false);
            alert("Something went wrong");
          }
        }
      })
    });
    $(document).on("click", ".cnt", function() {
      var user_id_connect = $(this).data('user-id');
      var button = this;
      $(button).attr('disabled', true);
      $.ajax({
        url: './ajax.php?connect',
        method: 'POST',
        dataType: 'json',
        data: {
          user_id: user_id_connect
        },
        success: function(response) {
          if (response.status) {
            $(button).data('user-id', 0);
            $(button).html('<i class="fa-solid fa-user-check"></i> Connected');
          } else {
            $(button).attr('disabled', false);
            alert("Something went wrong");
          }
        }
      })
    });
  </script>
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

  <div class="full-profile">
    <div class="main-profile">

      <img src="./uploads/<?php echo $coverImage ?>" alt="Your Name" class="cover-photo" />
      <img src="./uploads/<?php echo $profileImage ?>" alt="Your Name" class="profile-image" />
      <div class="username">
        <h1><?php echo $firstName . ' ' . $lastName; ?></h1>

        <p> From <?php echo $NativePlace ?></p>
        <p><?php echo $no['connection_count'] ?> connections</p>
        <br>
        <script>
          function redirectToMessages(userID) {
            var profileUrl = "./messages.php?chatter_id=" + userID;
            window.location.href = profileUrl;
          }
        </script>
        <?php if (!$viewingOwnProfile) { ?>
          <?php if ($connected) { ?>
            <button class="connect" id="connect"><i class="fa-solid fa-user-check"></i> Connected</button>
          <?php } else { ?>
            <button class="connect" id="connect" data-user-id="<?php echo $userDetails['UserID'] ?>"><i class="fa-solid fa-user-plus"></i> Connect</button>
          <?php } ?>
          <button onclick="redirectToMessages(<?php echo $userDetails['UserID']; ?>)" class="message_btn" id="message_btn"><i class="fa-solid fa-paper-plane"></i> Message</button>
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
        <p><?php echo $degree ?> in <?php echo $field_of_study ?> completed in the year <?php echo $graduationYear ?> <br> </p>
        <br>
      </div>

      

    </div>
    <!-- <button class="connect" id="connect"><i class="fa-solid fa-user-plus"></i>  Connect</button> -->
    <!-- <a href="./messages.php" class="message_btn" id="message_btn"><i class="fa-solid fa-paper-plane"></i> Message</a> -->



    <div class="side-profile">
      <?php foreach ($follow_suggestions as $suser) : ?>
        <div class="follow-section">
          <img src="./uploads/<?php echo $suser['ProfileImage'] ?>" alt="" class="follow-profile">
          <p><?php echo $suser['FirstName'] . ' ' . $suser['LastName']; ?></p>
          <button class="connect" id="connect" data-user-id="<?php echo $suser['UserID'] ?>"><i class="fa-solid fa-user-plus"></i> Connect</button>
        </div>
      <?php endforeach; ?>
      <?php
      if (count($follow_suggestions) < 1) {
        echo "<h6>Currently no suggestions for You</h6>";
      }
      ?>
    </div>


  </div>
  <div class="feeds">
    <h1>Post by <?php echo $userDetails['FirstName'] . ' ' . $userDetails['LastName'] ?></h1>
        <script>
          function redirectToProfile(userID) {
            var profileUrl = "./profile.php?userID=" + userID;
            window.location.href = profileUrl;
          }
        </script>
        <?php while ($postDetails = mysqli_fetch_assoc($postresult)) { ?>
          <div class="feed">
            <div class="feed-top">
              <div class="user" onclick="redirectToProfile(<?php echo $postDetails['UserID']; ?>)">
                <div class="profile-picture">
                  <img src="./uploads/<?php echo $postDetails['ProfileImage']; ?>" alt="" class="profile">
                </div>
                <div class="info">
                  <h3><?php echo $postDetails['FirstName'] . ' ' . $postDetails['LastName'] ?></h3>
                </div>
              </div>
            </div>
            <div class="caption">
              <?php echo $postDetails['Content']; ?>
            </div>
            <div class="feed-image">
              <img src="./posts/<?php echo $postDetails['ContentPhoto']; ?>" alt="">
            </div>
            <div class="action-button">
              <!-- Your action buttons here -->
            </div>
            <div class="flex">
            <i class="fa-regular fa-heart style="font-size: 24px;"></i>
            <i class="fa-regular fa-comment "></i></div>
            <div class="comments text-grey">View all comments</div>
          </div>
        <?php } ?>
      </div>
</body>

</html>