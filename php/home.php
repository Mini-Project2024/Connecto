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
      echo "Error adding comment: " . mysqli_error($conn) ;
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
        data: { PostID: PostID },
        success: function (response) {
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
        data: { PostID: PostID },
        success: function (response) {
          
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
          <input type="text" name="search" class="search" onkeypress="search()" placeholder="Search for user">
          <div id="searchResults" style="text-align: center;"></div>
          <li><a href="#">Home</a></li>
          <li><a href="network.php">My Network</a></li>
          <li><a href="logout.php">Logout</a></li>
        </ul>
      </nav>
    </div>
  </header>
  <main class="container">
    <section class="left">
      <div class="profile-options">
        <img src="./uploads/<?php echo $profileImage ?>" alt="Your Name" class="profile" />
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
        <img src="./uploads/<?php echo $profileImage ?>" alt="Your Name" class="profile" />
        <h2> start a new post</h2>
        <button><a style="text-decoration:none; color:#fff; font-weight:600;" href="./post.php">Post</a></button>
      </div>
      <div class="feeds">
        <script>
          function redirectToProfile(userID) {
            var profileUrl = "./profile.php?userID=" + userID;
            window.location.href = profileUrl;
          }
        </script>
        <?php while ($postDetails = mysqli_fetch_assoc($postresult)) { 
          $likes =getlikes($postDetails['PostID']);?>
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
            <span>
             <?php
             if(checkLikeStatus($postDetails['PostID'])){
              $like_btn_display='none';
              $unlike_btn_display=''; 
             }
              else{
                $like_btn_display='';
              $unlike_btn_display='none'; 
              }
              ?>
               <script>
                    
                    function toggleCommentSection(commentIcon) {
                        var feed = commentIcon.closest('.feed');
                        var commentSection = feed.querySelector('.commentsection');
                        if (commentSection.style.display === 'none' || commentSection.style.display === '') {
                            commentSection.style.display = 'block';
                        } else {
                            commentSection.style.display = 'none';
                        }
                    }
                    function toggleReply(replyButton, commentID, postID) {
                        var comment = replyButton.closest('.comment'); // Get the parent comment element
                        var inputform = comment.querySelector('.inputform'); // Find the input form within the comment
                        var parentCommentIDInput = inputform.querySelector('.parentCommentID');
                        parentCommentIDInput.value = commentID; // Set the ParentCommentID input field
                        var postIDInput = inputform.querySelector('.postID');
                        postIDInput.value = postID; // Set the PostID input field
                        if (inputform.style.display === 'none' || inputform.style.display === '') {
                            inputform.style.display = 'block';
                            comment.appendChild(inputform); // Move the input form to the end of the comment
                        } else {
                            inputform.style.display = 'none';
                        }
                    }


        </script>
          <?php
              // Comment section php code
       //Parent comments
        $postID = $postDetails['PostID'];
        $commentQuery = "SELECT u.*,c.*
                         FROM comments c JOIN users u
                         ON c.UserID = u.UserID  AND PostID = $postID AND ParentCommentID=0";
        $commentResult = mysqli_query($conn, $commentQuery);
      

          

  ?>
              <div class="flex">
              
              <i class="fa-regular fa-heart like_btn "style="font-size:24px;cursor:pointer;display:<?=$like_btn_display?>" onclick="likes(<?php echo $postDetails['PostID']; ?>)"></i> 
              
            <i class="fa-solid fa-heart unlike_btn" style="font-size:24px;cursor:pointer;color:red;display:<?=$unlike_btn_display?>" onclick="unlikes(<?php echo $postDetails['PostID'];?>)"></i> 
            </span>
            
            <!-- <?php echo $postDetails['PostID']?> -->
            <i class="fa-regular fa-comment " style=" font-size: 24px;" onclick="toggleCommentSection(this)"></i>

            </div>
            <br>
            <?=count($likes)?> likes
            <div class="commentsection" style="display: none;">
                 <?php  while ($comment = mysqli_fetch_assoc($commentResult)) {?>
                <div class="comment">
                  <div class="onlycomment">
                      <div class="profile-picture">
                        <img src="./uploads/<?php echo $comment['ProfileImage']; ?>" alt="" class="profile">
                      </div>
                      <div class="nameandcomment">
                        <h5><?php echo $comment['FirstName'].' '.$comment['LastName']; ?></h5>
                        <p class="commenttext"><?php echo $comment['Comment'] ?></p>
                      </div>
                      <button class="replybtn" onclick="toggleReply(this, <?php echo $comment['CommentID']; ?>, <?php echo $postID; ?>)">Reply</button>

                  </div>
                    
                    <div class="inputform" style="display: none;">
                      <form id="commentForm<?php echo $postID; ?>" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" enctype="multipart/form-data">
                        <input type="text" name="newcomment" class="replynewcomment" placeholder="Add your reply...">
                        <input type="hidden" name="PostID" class="postID" value="<?php echo $postDetails['PostID']; ?>">
                        <input type="hidden" name="UserID" class="userID" value="<?php echo $user['UserID']; ?>">
                        <input type="hidden" name="ParentCommentID" class="parentCommentID" value="<?php echo $comment['CommentID']; ?>">
                        <button type="submit" class="commentpost">Post</button>
                      </form>
                    </div>
                </div>


                <?php 
                  $replyCommentQuery = "SELECT u.*,c.*
                                        FROM comments c JOIN users u
                                        ON c.UserID = u.UserID  
                                        AND PostID = $postID AND ParentCommentID = {$comment['CommentID']}";
                  $replyCommentResult = mysqli_query($conn, $replyCommentQuery);
                  while ($replyComment = mysqli_fetch_assoc($replyCommentResult)) {
                ?>
                  <div class="replycomment">
                    <div class="profile-picture">
                      <img src="./uploads/<?php echo $replyComment['ProfileImage']; ?>" alt="" class="profile">
                    </div>
                    <div class="nameandcomment">
                      <h5><?php echo $replyComment['FirstName'].' '.$replyComment['LastName']; ?></h5>
                      <p class="commenttext"><?php echo "@".$comment['FirstName'].' '.$comment['LastName']; ?> <?php echo $replyComment['Comment'] ?></p>
                    </div>
                    
                  </div>
                <?php } ?>
            
                <?php }?>
                
                <form id="commentForm<?php echo $postID; ?>" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" enctype="multipart/form-data">
                  <input type="text" name="newcomment" class="newcomment" placeholder="Add your comment...">
                  <input type="hidden" name="PostID" class="postID" value="<?php echo $postDetails['PostID']; ?>">
                  <input type="hidden" name="UserID" class="userID" value="<?php echo $user['UserID']; ?>">
                  <input type="hidden" name="ParentCommentID" class="parentCommentID" value="NULL">
                  <button type="submit" class="commentpost">Post</button>

                </form>
                
            </div>
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