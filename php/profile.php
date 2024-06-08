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
  $current_user = $userDetails['UserID'];
}

$viewingOwnProfile = ($_SESSION['user']['UserID'] === $userDetails['UserID']);
// Check if the viewing user is already connected to the profile user
$connected = false;
if (!$viewingOwnProfile) {
  $connected = checkFollowStatus($userDetails['UserID']);
}

$no_connections = "SELECT COUNT(*) AS connection_count FROM connections WHERE (connector_id = {$userDetails['UserID']} OR user_id = {$userDetails['UserID']})";
$res = mysqli_query($conn, $no_connections);
$no = mysqli_fetch_array($res);

$user = $_SESSION['user'];

$postquery = "SELECT p.*, u.* 
              FROM posts p 
              JOIN users u ON p.UserID = u.UserID and u.UserID = " . $userDetails['UserID'] . "
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
  <script src="https://kit.fontawesome.com/b7a08da434.js" crossorigin="anonymous"></script>

  <script src="https://kit.fontawesome.com/f4e815f78b.js" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script>
    $(document).on("click", ".connect1", function() {
      var button = $(this);

      var user_id_connect = button.data('user-id');

      var connectStatus = button.text().trim();
      var ajaxURL = './ajax.php?connect';
      var action = 'connect';


      if (connectStatus === 'Connected') {
        ajaxURL = './ajax.php?disconnect';
        action = 'disconnect';
      }

      $.ajax({
        url: ajaxURL,
        method: 'POST',
        dataType: 'json',
        data: {
          user_id: user_id_connect
        },
        success: function(response) {
          // console.log(response.status);
          if (response.status) {
            if (action === 'connect') {
              button.html('<i class="fa-solid fa-user-check"></i> Connected');
              setTimeout(function() {
                location.reload();
              }, 500);
            } else {
              button.html('<i class="fa-solid fa-user-plus"></i> Connect');
              setTimeout(function() {
                location.reload();
              }, 500);
            }
          } else {
            alert("Something went wrong");
          }
        },
        error: function(xhr, status, error) {
          console.error(xhr.responseText);
          alert("Something went wrong");
        }
      });
    });






    // Acc deleting
    function deleteacc(userid, firstname, lastname) {
      var userInput = prompt("To delete your account, type your username here:");
      if (userInput != firstname + " " + lastname) {
        alert("Username confirmation failed.");
        return;
      }
      $.ajax({
        url: './delete_acc.php',
        method: 'POST',
        data: {
          userid: userid,
          firstname: firstname,
          lastname: lastname
        },
        dataType: 'json', // Specify JSON dataType for parsing response
        success: function(response) {

          // Exit function if confirmation fails


          if (response.status === 'success') {
            // Redirect to the provided URL
            window.location.href = response.redirect;

          } else {
            console.error("Error deleting account");
          }
        },
        error: function(xhr, status, error) {
          console.error(xhr.responseText);
        }
      });
    }
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
          <!-- <div class="box">
          <i class="fa-solid fa-magnifying-glass" style="color:#0718c4;font-size:18px"></i>
          <input type="text" name="search" class="search" onkeypress="search()" placeholder="Search for user">
          </div> -->
          <div id="searchResults" style="text-align: center;"></div>
          <li><a href="home.php"><i class="fa-solid fa-house" style="font-size:30px;margin-bottom:12px;"></i><a href="home.php">Home</a></a></li>
          <li><a href="network.php"><svg fill="#ffff" height="28px" width="28px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 490 490" xml:space="preserve" stroke="#ffff">
                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                <g id="SVGRepo_iconCarrier">
                  <g>
                    <ellipse cx="245.639" cy="154.693" rx="50.324" ry="51.162"></ellipse>
                    <path d="M158.728,306.884h173.823c0-48.799-38.912-88.359-86.912-88.359S158.728,258.085,158.728,306.884z"></path>
                    <path d="M49.729,99.439c4.652,0,9.151-0.652,13.424-1.846l52.215,60.854l23.238-19.949L88.831,80.488 c6.652-8.413,10.635-19.007,10.635-30.516c0-27.291-22.311-49.482-49.736-49.482C22.311,0.49,0,22.681,0,49.972 C0,77.248,22.311,99.439,49.729,99.439z M49.729,31.115c10.535,0,19.111,8.464,19.111,18.857c0,10.393-8.576,18.842-19.111,18.842 s-19.104-8.449-19.104-18.842C30.625,39.579,39.194,31.115,49.729,31.115z"></path>
                    <path d="M49.729,489.51c27.425,0,49.736-22.191,49.736-49.482c0-11.515-3.983-22.114-10.636-30.529l49.778-58.012l-23.238-19.948 l-52.216,60.854c-4.274-1.194-8.773-1.846-13.424-1.846C22.311,390.546,0,412.737,0,440.028S22.311,489.51,49.729,489.51z M49.729,421.171c10.535,0,19.111,8.464,19.111,18.857c0,10.393-8.576,18.857-19.111,18.857s-19.104-8.464-19.104-18.857 C30.625,429.635,39.194,421.171,49.729,421.171z"></path>
                    <path d="M374.631,158.447l52.22-60.852c4.27,1.192,8.765,1.844,13.412,1.844c27.425,0,49.737-22.191,49.737-49.467 c0-27.291-22.311-49.482-49.737-49.482c-27.41,0-49.721,22.191-49.721,49.482c0,11.508,3.983,22.103,10.634,30.516l-49.783,58.011 L374.631,158.447z M440.263,31.115c10.543,0,19.111,8.464,19.111,18.857c0,10.393-8.569,18.842-19.111,18.842 c-10.527,0-19.096-8.449-19.096-18.842C421.167,39.579,429.736,31.115,440.263,31.115z"></path>
                    <path d="M401.177,409.499c-6.652,8.416-10.635,19.014-10.635,30.529c0,27.291,22.311,49.482,49.721,49.482 c27.425,0,49.737-22.191,49.737-49.482s-22.311-49.482-49.737-49.482c-4.647,0-9.142,0.652-13.412,1.844l-52.221-60.852 l-23.238,19.948L401.177,409.499z M459.375,440.028c0,10.393-8.569,18.857-19.111,18.857c-10.527,0-19.096-8.464-19.096-18.857 c0-10.393,8.569-18.857,19.096-18.857C450.806,421.171,459.375,429.635,459.375,440.028z"></path>
                  </g>
                </g>
              </svg><a href="network.php">My Network</a></li></a>
          <li><a href="logout.php"><i class="fa-solid fa-right-from-bracket" style="font-size:28px;margin-bottom:12px;"></i><a href="logout.php">Logout</a></a></li>
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
        <div class="flex">
          <img src="../components/images/location.svg" class="svg">
          <p> <?php echo ' From ' . $NativePlace ?></p>
        </div>
        <p style="font-size:15px;margin-left:14px;"><?php echo $no['connection_count'] ?> Connections</p>

        <br>
        <script>
          function redirectToMessages(userID) {
            var profileUrl = "./messages.php?chatter_id=" + userID;
            window.location.href = profileUrl;
          }
        </script>
        <?php if (!$viewingOwnProfile) { ?>
          <?php if ($connected) { ?>
            <button class="connect1" id="connect" data-user-id="<?php echo $userDetails['UserID'] ?>"><i class="fa-solid fa-user-check"></i> Connected</button>
          <?php } else { ?>
            <button class="connect1" id="connect" data-user-id="<?php echo $userDetails['UserID'] ?>"><i class="fa-solid fa-user-plus"></i> Connect</button>
          <?php } ?>
          <button onclick="redirectToMessages(<?php echo $userDetails['UserID']; ?>)" class="message_btn" id="message_btn"><i class="fa-solid fa-paper-plane"></i> Message</button>
        <?php } ?>
        <?php if ($viewingOwnProfile) { ?>
          <a href="./network.php" class="view-connect" id="view-connect"><i class="fa-solid fa-user-plus"></i> View Connections</a>
          <button onclick="deleteacc(<?php echo $userDetails['UserID']; ?>,'<?php echo $userDetails['FirstName']; ?>','<?php echo $userDetails['LastName']; ?>')" class='deleteacc'><i class="fa-regular fa-trash-can" style="color:black;font-size:20px"></i></button>

          <a href="./profileedit.php" class="edit-connect">Edit Profile <i class="fa-solid fa-pen"></i></a>
        <?php } ?>


      </div>
      <br>
      <br>
      <hr>
      <br>

      <h4 style="margin:10px"><?php echo $bio ?></h4><br>
      <hr>
      <div class="flex">
        <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24">
          <path fill="currentColor" d="M20 6h-4V4c0-1.11-.89-2-2-2h-4c-1.11 0-2 .89-2 2v2H4c-1.11 0-1.99.89-1.99 2L2 19c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2m-6 0h-4V4h4z" />
        </svg>
        <p><?php echo $position . " at " . $company ?></p>


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
        <a href="https://mail.google.com/mail/u/0/?fs=1&to=<?php echo $email ?>&su=&body=&tf=cm"><?php echo $email ?></a> <br>
      </div>
      <hr>
      <div class="flex">
        <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 640 512">
          <path fill="currentColor" d="M337.8 5.4c-10.8-7.2-24.8-7.2-35.6 0L166.3 96H48c-26.5 0-48 21.5-48 48v320c0 26.5 21.5 48 48 48h208v-96c0-35.3 28.7-64 64-64s64 28.7 64 64v96h208c26.5 0 48-21.5 48-48V144c0-26.5-21.5-48-48-48H473.7zM96 192h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H96c-8.8 0-16-7.2-16-16v-64c0-8.8 7.2-16 16-16m400 16c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16h-32c-8.8 0-16-7.2-16-16zM96 320h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H96c-8.8 0-16-7.2-16-16v-64c0-8.8 7.2-16 16-16m400 16c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16h-32c-8.8 0-16-7.2-16-16zM232 176a88 88 0 1 1 176 0a88 88 0 1 1-176 0m88-48c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16s-7.2-16-16-16h-16v-16c0-8.8-7.2-16-16-16" />
        </svg>
        <p><?php echo $institution ?></p> <br>
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

    <!-- <---------------------------- NIshals  work -------------------------->

    <?php
    // Assuming $current_user_id contains the ID of the logged-in user
    $current_user_id = $current_user; // Example user ID

    // Fetch the latitude and longitude of the logged-in user from the database
    $query = "SELECT Latitude, Longitude FROM users WHERE UserID = $current_user_id";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $current_user_lat = $row['Latitude'];
    $current_user_lon = $row['Longitude'];

    // Define the calculateDistance function
    function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
      $earth_radius = 6371; // Radius of the earth in km
      $dLat = deg2rad($lat2 - $lat1);
      $dLon = deg2rad($lon2 - $lon1);
      $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
      $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
      $distance = $earth_radius * $c; // Distance in km
      return $distance;
    }

    $threshold_distance = 10; // Threshold distance in km

    ?>

    <!-- <div class="side-profile">
      <h2>Suggestions<i class="fa-solid fa-user-plus"></i> </h2><br>
      < class="follow">
      <?php foreach ($follow_suggestions as $suser) : ?>
        <div class="follow-section">
          <img src="./uploads/<?php echo $suser['ProfileImage'] ?>" alt="" class="follow-profile">
          <p><?php echo $suser['FirstName'] . ' ' . $suser['LastName']; ?></p>
          <button class="connect1 suggestion-connect" id="fconnect" data-user-id="<?php echo $suser['UserID'] ?>">Connect</button>
        </div>
      <?php endforeach; ?>
      <?php
      if (count($follow_suggestions) < 1) {
        echo "<h6>Currently no suggestions for You</h6>";
      }
      ?>
    </div> -->

    <style>
      
    </style>

  <div class="side-profile">
      <div class="sug">
      <h2>Suggestions<i class="fa-solid fa-user-plus"></i> </h2>
       <!-- <div><i class="fa-solid fa-xmark" onclick="closesug()"></i></div><br> -->
      </div>
   
       <div class="myside">
     
      <div class="follow">
      <?php foreach ($follow_suggestions as $suser) : ?>
        <div class="follow-section">
          <img src="./uploads/<?php echo $suser['ProfileImage'] ?>" alt="" class="follow-profile">
          <p><?php echo $suser['FirstName'] . ' ' . $suser['LastName']; ?></p>
          <button class="connect1 suggestion-connect" id="fconnect" data-user-id="<?php echo $suser['UserID'] ?>">Connect</button>
        </div>
      <?php endforeach; ?>
      <?php
      if (count($follow_suggestions) < 1) {
        echo "<h6>Currently no suggestions for You</h6>";
      }
      ?>
    </div></div></div>

    <!-- <----------------------- Nishals works end ---------------------------->

  </div>
  <center>
    <h1>Posts</h1>
  </center>
  <div class="feeds1">

    <?php
    $row = mysqli_num_rows($postresult);
    ?><?php
      if ($row != 0) {
      ?> <?php
        } else {
          ?> <h1>No Posts Yet</h1><?php
                                }
                                  ?>
  <script>
    function redirectToProfile(userID) {
      var profileUrl = "./profile.php?userID=" + userID;
      window.location.href = profileUrl;
    }
  </script>
  <script>
    function deletePost(postId) {
      $.ajax({
        url: './delete_post.php',
        method: 'POST',
        data: {
          postId: postId
        },
        success: function(response) {
          console.log(response);
          $("#post_" + postId).remove(); // Log the response for debugging
        },
        error: function(xhr, status, error) {
          console.error(xhr.responseText);
        }
      });
    }
  </script>


  <?php

  while ($postDetails = mysqli_fetch_assoc($postresult)) {

  ?>
    <div class="feed">
      <div class="feed-top">
        <div class="user" onclick="redirectToProfile(<?php echo $postDetails['UserID']; ?>)">
          <!-- <div class="profile-picture">
            <img src="./uploads/<?php echo $postDetails['ProfileImage']; ?>" alt="" class="profile">
            <?php $likes = getlikes($postDetails['PostID']); ?>
          </div> -->
          <!-- <div class="info">
            <h3><?php echo $postDetails['FirstName'] . ' ' . $postDetails['LastName'] ?></h3>
          </div> -->

        </div>
      </div>

      <div class="feed-image">
        <img src="./posts/<?php echo $postDetails['ContentPhoto']; ?>" alt="">
      </div>
      <!-- <div class="caption">
        <?php echo $postDetails['Content']; ?>
        <br>
      </div> -->
      <div class="action-button">
        <h2 style="margin-left:10px"> <?= count($likes) ?> <i class="fa-solid fa-heart unlike_btn" style="font-size:24px;"></i>
        </h2>
      </div>
      <!-- <div class="flex">
                    <i class="fa-regular fa-heart style=" font-size: 24px;"></i>
                    <i class="fa-regular fa-comment "></i>
                </div>
                <div class="comments text-grey">View all comments</div> -->
    </div>
  <?php
  } ?>
  </div>

  <script>
    function openPopup(Postid) {
      var popup = document.getElementById("popup");
      popup.style.display = "block";
    }

    function closePopup() {
      var popup = document.getElementById("popup");
      popup.style.display = "none";
    }
  </script>
  <!-- Pop-up Posts -->
  <?php
  $postquery = "SELECT p.*, u.* 
                  FROM posts p 
                  JOIN users u ON p.UserID = u.UserID WHERE u.UserID = " . $userDetails['UserID'] . " 
                  ORDER BY p.PostedDate DESC";
  $postresult = mysqli_query($conn, $postquery);
  $postDetails = mysqli_fetch_assoc($postresult);

  //insert comment
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    include("config.php");

    $newComment = $_POST['newcomment'];
    $postID = $_POST['PostID'];
    $userID = $_POST['UserID'];
    $parentCommentId = $_POST['ParentCommentID'];
    $commentdate = date('Y-m-d H:i:s');

    $commentinsertQuery = "INSERT INTO comments (Comment, UserID, PostID, CommentDate,ParentCommentID) VALUES ('$newComment', '$userID', '$postID','$commentdate','$parentCommentId')";
    $commentinsertValue = mysqli_query($conn, $commentinsertQuery);


    if ($commentinsertValue) {
      // echo "Successfull comment insertion";
    } else {
      echo "Error adding comment: " . mysqli_error($conn);
    }
  }
  ?>





</body>

</html>