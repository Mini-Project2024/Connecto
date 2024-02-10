<?php
session_start();
include_once("./functions.php");
// Check if user is logged in
if (!isset($_SESSION['user'])) {
  // Redirect to login page if user is not logged in
  header("Location: ../../../components/pages/login.html");
  exit(); // Stop execution of the script
}

// Access the user data from session
$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>CONNECTO</title>
  <script src="https://kit.fontawesome.com/b7a08da434.js" crossorigin="anonymous"></script>
  <script src="https://kit.fontawesome.com/f4e815f78b.js" crossorigin="anonymous"></script>
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
        <img src="./uploads/<?php echo $user['ProfileImage'] ?>" alt="Your Name" />
        <h2>
          <h2>
            <?php
            echo $user['FirstName'];
            ?>
          </h2>
        </h2>
        <p>Job Title</p>
        <button><a href="./profile.php">View Profile</a></button>
        <button><a href="./profileedit.php">Edit Profile</a></button>
      </div>
    </section>
    <section class="middle_s">
      <div class="new_post">
        <img src="./uploads/<?php echo $user['ProfileImage'] ?>" alt="Your Name" />
        <h2> start a new post</h2>
        <button>post</button>
      </div>
      <div class="feeds">
        <!-- ---feedstart---------- -->
        <div class="feed">
          <div class="feed-top">
            <div class="user">
              <div class="profile-picture">
                <img src="./uploads/<?php echo $user['ProfileImage'] ?>" alt="">
              </div>
              <div class="info">
                <h3>
                  <!-- Adding the name from the database -->
                  <?php
                  echo $user['FirstName'];
                  ?>
                </h3>
              </div>
            </div>
          </div>
          <div class="feed-image">
            <img src="./uploads/<?php echo $user['ProfileImage'] ?>"="../images/profile.jpg" alt="">
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
                <img src="./uploads/<?php echo $user['ProfileImage'] ?>" alt="">
              </div>
              <div class="info">
                <h3>Ananya</h3>
              </div>
            </div>
          </div>
          <div class="feed-image">
            <img src="./uploads/<?php echo $user['ProfileImage'] ?>" alt="">
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
                <img src="./uploads/<?php echo $user['ProfileImage'] ?>" alt="">
              </div>
              <div class="info">
                <h3>Ananya</h3>
              </div>
            </div>
          </div>
          <div class="feed-image">
            <img src="./uploads/<?php echo $user['ProfileImage'] ?>" alt="">
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
      <!-- <section class="middle">
       
      
        
       
        <div class="post-options">
          <h2>Posts</h2>
          <img src="./uploads/" alt="Post Image" />
          <p>
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Culpa
            deserunt placeat iste architecto dolorem iusto laboriosam atque
            commodi magnam, adipisci quo veniam voluptatibus possimus sint
            nesciunt modi eos aliquam. In animi unde labore hic debitis, ullam
            aspernatur culpa quibusdam corrupti blanditiis explicabo quis vel
            modi ad mollitia eius? Earum illum ut accusamus.
          </p>
        </div>
      </section> -->
      <span><i class="fa-solid fa-arrow-up"></i></span>
    </section>
    <section class="right">

      <?php
      if (isset($_GET['test'])) {
        $active_chat_id = getActiveChatUsersIds();
        echo"<pre>";
        print_r(getMessages(7));
      }
      ?>


      <div class="messaging-options">
        <h2>Messaging</h2>
        <div class="message">
          <p>John Doe: Hi there!</p>
        </div>
        <div class="message">
          <p>Jane Doe: How are you?</p>
        </div>
        <div class="message">
          <p>John Doe: Doing well, thanks!</p>
        </div>
      </div>
      <!-- <button>+</button> -->
    </section>



  </main>
  <footer>
    <div class="container">
      <p>&copy; 2024 Your Network. All rights reserved.</p>
    </div>
  </footer>
</body>

</html>