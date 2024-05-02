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
  $position = $userDetails['Position'];
}
//post details
$postquery = "SELECT p.*, u.* 
              FROM posts p 
              JOIN users u ON p.UserID = u.UserID ORDER BY p.PostedDate DESC";
$postresult = mysqli_query($conn, $postquery);


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

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>CONNECTO</title>
  <script src="https://kit.fontawesome.com/b7a08da434.js" crossorigin="anonymous"></script>
  <script src="https://kit.fontawesome.com/f4e815f78b.js" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/fontawesome.min.css">
  <link rel="icon" type="image/x-icon" href="../components/images/favicon.ico">
  <script>
    var chatting_user_id = 6;

    function search() {
      if (event.key === "Enter" || event.keyCode === 13) {
        var searchString = document.querySelector('.search').value.trim();
        if (searchString !== '') {
          $.ajax({
            url: './ajax.php?searchUsers',
            method: 'GET',
            dataType: 'json',
            data: {
              searchString: searchString
            },
            success: function(response) {
              // console.log(response);
              if (response.userlist.length > 0) {
                if (response.hasOwnProperty('userlist')) {
                  document.getElementById('searchResults').innerHTML = response.userlist;

                }
              } else {
                $('#searchResults').html('<p>No users found</p>');
              }

            }

          });
        }
      }
    }


    function synmsg() {
      $.ajax({
        url: './ajax.php?getMessages',
        method: 'GET',
        dataType: 'json',
        data: {
          chatter_id: chatting_user_id
        },
        success: function(response) {
          console.log(response.chat);
          $('#chatlist').html(response.chatlist);
          // $('#user_chat').html(response.chat);
        }
      });
    }
    // synmsg();
    setInterval(() => {
      synmsg();
    }, 1000);

    // for like
    function likes(PostID) {
      var button = this;
      $(button).attr('disabled', true);

      $.ajax({
        url: './ajax.php?like',
        method: 'post',
        dataType: 'json',
        data: {
          PostID: PostID
        },
        success: function(response) {
          console.log(response);
          if (response.status) {
            // Change the class to the filled heart icon

            $(button).attr('disabled', false);
            $(button).hide();
            $(button).siblings('.unlike_btn').show();
            location.reload(true);
          } else {
            $(button).attr('disabled', false);
            alert("Something is wrong");
          }
        }
      });
    }

    function unlikes(PostID) {
      var button = this;
      $(button).attr('disabled', true);

      $.ajax({
        url: './ajax.php?unlike',
        method: 'post',
        dataType: 'json',
        data: {
          PostID: PostID
        },
        success: function(response) {

          console.log(response);

          if (response.status) {
            // Change the class to the regular heart icon
            $(button).attr('disabled', false);
            $(button).hide();
            $(button).siblings('.like_btn').show();
            location.reload(true);

          } else {
            $(button).attr('disabled', false);
            alert("Something is wrong");
          }
        }
      });
    };

    // Function to get user's location
    function getUserLocation() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(sendLocationToServer);
      } else {
        alert("Geolocation is not supported by this browser.");
      }
    }

    // Function to send location to server
    function sendLocationToServer(position) {
      // Extract latitude and longitude from position object
      var latitude = position.coords.latitude;
      var longitude = position.coords.longitude;

      // Send latitude and longitude to server using AJAX
      $.ajax({
        url: 'store_location.php',
        method: 'POST',
        data: {
          latitude: latitude,
          longitude: longitude
        },
        success: function(response) {
          console.log(response); // Log response from server
        },
        error: function(xhr, status, error) {
          console.error(error); // Log any errors
        }
      });
    }

    // Call getUserLocation function when the page loads
    getUserLocation();
  </script>
  <link rel="stylesheet" href="../components/css/style.css">
</head>

<body>
  <header>
    <div class="container">
      <div class="logo">
        <img src="../components/images/logo.png" alt="">
      </div>
      <nav>
        <ul>

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
          <!-- <li class="hamburger"><a href="#"><i class="fa-solid fa-bars"></i></a></li> -->
        </ul>

      </nav>
      <div id="hamburger" class="hamburger" onclick="toggleNavbar()">
        <i class="fa-solid fa-bars ham"></i>
      </div>
      <script>
        // Function to toggle the right section and change the hamburger icon
        function toggleNavbar() {
          var leftSection = document.querySelector(".left");
          var hamburgerIcon = document.querySelector(".ham");

          if (
            leftSection.style.display === "none" ||
            leftSection.style.display === ""
          ) {
            leftSection.style.display = "block";
            // Change hamburger icon to X
            hamburgerIcon.classList.remove("fa-bars");
            hamburgerIcon.classList.add("fa-xmark");
          } else {
            leftSection.style.display = "none";
            // Change hamburger icon to bars
            hamburgerIcon.classList.remove("fa-xmark");
            hamburgerIcon.classList.add("fa-bars");
          }
        }
      </script>
      <!-- <li><img src="../components/images/menu.png" alt=""></li> -->
    </div>
  </header>
  <main class="container">
  <section class="left" style="display: none;">

      <div class="profile-options">
        <img src="./uploads/<?php echo $profileImage ?>" alt="Your Name" class="profile" />
        <h2>
          <?php echo $userDetails['FirstName'] . ' ' . $userDetails['LastName'] ?>
        </h2>
        <p><?php echo $position ?></p>
        <a href="./profile.php"><button>View Profile</button></a>
        <a class="profileEdit" href="./profileedit.php"><button>Edit Profile</button></a>
      </div>
      <div class="nav-second">
        <ul>
          <li><a href="home.php"><i class="fa-solid fa-house" style="font-size:30px; color: #000;"></i><a href="home.php">Home</a></a></li><hr>
          <li><a href="network.php"><i class="fa-solid fa-users" style="font-size:30px; color: #000;"></i><a href="home.php">My Network</a></a></li><hr>
          <li><a href="logout.php"><i class="fa-solid fa-right-from-bracket" style="font-size:30px; color: #000;"></i><a href="logout.php">Logout</a></a></li><hr>
          <li><a class="message_icon" href="users.php"><i class="fa-solid fa-message" style="font-size: 30px; color: #000;"></i><a href="users.php"></a>Messages</a></li>
        </ul>
      </div>
    </section>
  <section class="mid">
      <div class="box">
        <i class="fa-solid fa-magnifying-glass" style="color:#0718c4;font-size:18px;margin:13px"></i>
        <input type="text" name="search" class="search" onkeypress="search()" placeholder="Search for user">
      </div>
      <br>
      <div class="messaging-options" id="messaging-options">
        <h2>Messaging</h2>
        <div class="message" id="chatlist">

        </div>
      </div>
    </section>
    <section id="footer">

      <p>&copy; 2024 Your Network. All rights reserved.</p>
    </section>
  </main>

</body>

</html>