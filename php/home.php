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
  $position =$userDetails['Position'];
}

$postquery = "SELECT p.*, u.* 
              FROM posts p 
              JOIN users u ON p.UserID = u.UserID ORDER BY p.PostedDate DESC";
$postresult = mysqli_query($conn, $postquery);

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
  <script>
    var chatting_user_id = 6;

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
          <li><a href="#">Home</a></li>
          <li><a href="#">My Network</a></li>
          <li><a href="#">Jobs</a></li>
          <li><a href="#">Messaging</a></li>
        </ul>
      </nav>
    </div>
  </header>
  <main class="container">
    <section class="left">
      <div class="profile-options">
        <img src="./uploads/<?php echo $profileImage ?>" alt="Your Name" class="profile"/>
        <h2>
          <?php echo $userDetails['FirstName'] . ' ' . $userDetails['LastName'] ?>
        </h2>
        <p><?php echo $position ?>
        <button><a href="./profile.php">View Profile</a></button>
        <button><a href="./profileedit.php">Edit Profile</a></button>
      </div>
    </section>
    <section class="middle_s">
      <div class="new_post">
        <img src="./uploads/<?php echo $profileImage ?>" alt="Your Name" class="profile"/>
        <h2> start a new post</h2>
        <button><a style="text-decoration:none; color:#fff; font-weight:600;" href="./post.php">Post</a></button>
      </div>
      <script>
        // Wait for the DOM to be fully loaded
        document.addEventListener("DOMContentLoaded", function() {
          var users = document.querySelectorAll('.user');

          users.forEach(function(user) {
            user.addEventListener('click', function() {
              var userId = this.getAttribute('data-userid');
              window.location.href = './profile.php?user_id=' + userId;
            });
          });
        });
      </script>
      <div class="feeds">
        <?php while ($postDetails = mysqli_fetch_assoc($postresult)) { ?>
          <div class="feed">
            <div class="feed-top">
              <div class="user" id="user" data-userid="<?php echo $postDetails['UserID']; ?>">
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
            <div class="comments text-grey">View all comments</div>
          </div>
        <?php } ?>
      </div>
      <footer>
  
  <p>&copy; 2024 Your Network. All rights reserved.</p>

</footer>
    
    </section>
    <section class="right">
      <!-- <script>
        // Function to show chatbox and hide messaging options
        function showChatbox() {
          document.getElementById('chatbox').style.display = 'block';
          document.getElementById('messaging-options').style.display = 'none';
        }

        // Function to hide chatbox and show messaging options
        function hideChatbox() {
          document.getElementById('chatbox').style.display = 'none';
          document.getElementById('messaging-options').style.display = 'block';
        }
      </script> -->


      <div class="messaging-options" id="messaging-options">
        <h2>Messaging</h2>
        <div class="message" id="chatlist">

        </div>
      </div>
    </section>
   
  </main>

</body>

</html>