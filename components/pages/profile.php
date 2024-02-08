<?php
  session_start();
  $user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>CONNECTO</title>
    <script src="https://kit.fontawesome.com/b7a08da434.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/style.css">
  </head>
  <body>
    <header>
      <div class="container">
        <div class="logo">
          <img src="../images/logo.png" alt="">
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
          <img
            src="../images/profile.jpg"
            alt="Your Name"
          />
          <h2>Your Name</h2>
          <p>Job Title</p>
          <button>View Profile</button>
          <button>Edit Profile</button>
        </div>
      </section>
     <section class="middle_s">
      <div class="new_post">
        <img
        src="../images/profile.jpg"
        alt="Your Name"
      />
    <h2> start a new post</h2>
    <button>post</button>
      </div>
        <div class="feeds">
          <!-- ---feedstart---------- -->
          <div class="feed">
            <div class="feed-top">
              <div class="user">
                <div class="profile-picture">
                  <img src="../images/profile.jpg" alt="">
                </div>
                <div class="info"><h3>Ananya</h3></div>
              </div>
            </div>
             <div class="feed-image">
              <img src="../images/profile.jpg" alt="">
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
                  <img src="../images/profile.jpg" alt="">
                </div>
                <div class="info"><h3>Ananya</h3></div>
              </div>
            </div>
             <div class="feed-image">
              <img src="../images/profile.jpg" alt="">
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
                  <img src="../images/profile.jpg" alt="">
                </div>
                <div class="info"><h3>Ananya</h3></div>
              </div>
            </div>
             <div class="feed-image">
              <img src="../images/profile.jpg" alt="">
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
          <img src="../images/profile.jpg" alt="Post Image" />
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
    </section>
      <section class="right">
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
        <button>+</button>
      </section>
   

    </main>
    <footer>
      <div class="container">
        <p>&copy; 2024 Your Network. All rights reserved.</p>
      </div>
    </footer>
  </body>
</html>
