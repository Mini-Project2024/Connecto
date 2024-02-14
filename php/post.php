<?php
// session_start();
include("config.php");
include_once("./functions.php");
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
  
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../components/css/style.css"> 
    <script src="https://kit.fontawesome.com/f4e815f78b.js" crossorigin="anonymous"></script>
    <title>Post picture</title>
</head>
<body>
    
    <div class="postcontainer">
        <div class="myself">
        <img class='postprofile' src="./uploads/<?php echo $profileImage ?>" alt="Your Name" />
        <h4 class='postprofilename'>
         Post your thoughts..
        </h4>
        </div>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <div class="postmessage">
        <textarea name="posttext" id="posttext" maxlength="1000" cols="100" rows="17" placeholder="Share your thoughts...."></textarea>
        </div>
        <div class="flex" style="justify-content:center">
        

        <input type="file" name="fileToUpload" id="fileToUpload"/></div><br>
        <div class="flex" style="justify-content:center">
        <input type="submit"  class="postbutton" value="Post"></input></div>
        </form>
    </div>
    
</body>


</script>
</html>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $descript = $_POST['posttext'];
    // File upload handling
    $target_dir = "posts/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    // Check if the file is an actual image
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size (you can set your own limit)
    if ($_FILES["fileToUpload"]["size"] > 50000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }
    skip_upload:
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            // Store the file name in a variable
            $fileName = basename($_FILES["fileToUpload"]["name"]);

            // Get the current date and time
            $postedDate = date('Y-m-d H:i:s');

            $insertQuery = "INSERT INTO posts (UserID, Content, ContentPhoto, PostedDate) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);

            // Bind parameters
            $stmt->bind_param('isss', $user['UserID'], $descript, $fileName, $postedDate);

            if ($stmt->execute()) {
                echo "Post successfully added!";
                // Redirect to some page after successful post submission
                header("Location: ./home.php");
                exit(); // Stop execution of the script
            } else {
                echo "Error in adding post. Please try again.";
            }
            $stmt->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>
