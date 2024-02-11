<?php
// session_start();
include_once("./functions.php");
include("config.php");

// Check if user is logged in
if (!isset($_SESSION['user'])) {
  // Redirect to login page if user is not logged in
  header("Location: ../../../components/pages/login.html");
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
  $coverimage = $userDetails['CoverPhotoURL'];
  $bio = $userDetails['Bio'];
  $company = $userDetails['CompanyName'];
  $position = $userDetails['Position'];
  $institution = $userDetails['Institution'];
  $degree = $userDetails['Degree'];
  $field_of_study = $userDetails['FieldOfStudy'];
  $graduationyear = $userDetails['GraduationYear'];
  $proficiency = $userDetails['Proficiency'];
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
  <script>
    function synmsg() {
      // console.log("hello");
      $.ajax({
          url: './ajax.php?getMessages',
          method: 'GET',
          dataType: 'json',
          success: function(response){
              $('#chatlist').html(response.chatlist);
          }
      });
    }
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
        <img src="./uploads/<?php echo $profileImage ?>" alt="Your Name" />
        <h2>
          <?php echo $user['FirstName']; ?>
        </h2>
        <p>Job Title</p>
        <button><a href="./profile.php">View Profile</a></button>
        <button><a href="./profileedit.php">Edit Profile</a></button>
      </div>
    </section>
    <section class="middle_s">
      <div class="new_post">
        <img src="./uploads/<?php echo $profileImage ?>" alt="Your Name" />
        <h2> start a new post</h2>
        <button>post</button>
      </div>
      <div class="feeds">
        <!-- ---feedstart---------- -->
        <div class="feed">
          <div class="feed-top">
            <div class="user">
              <div class="profile-picture">
                <img src="./uploads/<?php echo $profileImage ?>" alt="">
              </div>
              <div class="info">
                <h3>
                  <?php echo $user['FirstName']; ?>
                </h3>
              </div>
            </div>
          </div>
          <div class="feed-image">
            <img src="./uploads/<?php echo $profileImage ?>"="../images/profile.jpg" alt="">
          </div>
          <div class="action-button">
            <div class="interaction-button">
              <span><i class="fa-regular fa-heart"></i></span>
              <span><i class="fa-regular fa-comment"></i></span>
              <span><i class="fa-regular fa-bookmark"></i></span>
            </div>
            <div class="bookmark">
              <i class="fa-regular fa-bookmark"></i>
            </div>
          </div>
          <div class="caption">
            <p><b>Ananya</b>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Adipisci laudantium quo iusto quibusdam tempora neque eveniet reprehenderit quas sed commodi! <span>#Lifestyle</span></p>
          </div>
          <div class="comments text-grey">View all comments</div>
        </div>
      </div>

      <div class="feeds">
        <!-- ---feedstart---------- -->
        <div class="feed">
          <div class="feed-top">
            <div class="user">
              <div class="profile-picture">
                <img src="./uploads/<?php echo $profileImage ?>" alt="">
              </div>
              <div class="info">
                <h3>Ananya</h3>
              </div>
            </div>
          </div>
          <div class="feed-image">
            <img src="./uploads/<?php echo $profileImage ?>" alt="">
          </div>
          <div class="action-button">
            <div class="interaction-button">
              <span><i class="fa-regular fa-heart"></i></span>
              <span><i class="fa-regular fa-comment"></i></span>
              <span><i class="fa-regular fa-bookmark"></i></span>
            </div>
            <div class="bookmark">
              <i class="fa-regular fa-bookmark"></i>
            </div>
          </div>
          <div class="caption">
            <p><b>Ananya</b>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Adipisci laudantium quo iusto quibusdam tempora neque eveniet reprehenderit quas sed commodi! <span>#Lifestyle</span></p>
          </div>
          <div class="comments text-grey">View all comments</div>
        </div>
      </div>
      <div class="feeds">
        <!-- ---feedstart---------- -->
        <div class="feed">
          <div class="feed-top">
            <div class="user">
              <div class="profile-picture">
                <img src="./uploads/<?php echo $profileImage ?>" alt="">
              </div>
              <div class="info">
                <h3>Ananya</h3>
              </div>
            </div>
          </div>
          <div class="feed-image">
            <img src="./uploads/<?php echo $profileImage ?>" alt="">
          </div>
          <div class="action-button">
            <div class="interaction-button">
              <span><i class="fa-regular fa-heart"></i></span>
              <span><i class="fa-regular fa-comment"></i></span>
              <span><i class="fa-regular fa-bookmark"></i></span>
            </div>
            <div class="bookmark">
              <i class="fa-regular fa-bookmark"></i>
            </div>
          </div>
          <div class="caption">
            <p><b>Ananya</b>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Adipisci laudantium quo iusto quibusdam tempora neque eveniet reprehenderit quas sed commodi! <span>#Lifestyle</span></p>
          </div>
          <div class="comments text-grey">View all comments</div>
        </div>
      </div>
      <span><i class="fa-solid fa-arrow-up"></i></span>
    </section>
    <section class="right">
<<<<<<< HEAD
      <div class="messaging-options">
        <?php
          if(isset($_GET['test'])){
            $current_user = getActiveChatUsersIds();
            echo "<pre>";
            print_r(getMessages(5));
          }
=======
      <div class="chatbox" id="chatbox">
        <div class="chathead">
          <a href="#"><i class="fa-solid fa-arrow-left"></i></a>
          <img class="chat-img" src="./uploads/<?php echo $profileImage?>" alt="">
          <p>Nishal</p>
        </div>
        <div class="chatarea">
>>>>>>> a7027a348e883f13290cf4e18d4fb1170003690c

        </div>
        <div class="chatbottom">
          <input type="text" class="chat-msg">
          <button class="sendbtn"><i class="fa-solid fa-paper-plane"></i></button>
        </div>
      </div>
      <div class="messaging-options">
        <h2>Messaging</h2>
        <div class="message" id="chatlist">
          
        </div>
      </div>
    </section>
  </main>
  <footer>
    <div class="container">
      <p>&copy; 2024 Your Network. All rights reserved.</p>
    </div>
  </footer>
</body>

</html>