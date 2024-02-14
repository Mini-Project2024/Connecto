<?php
session_start();
include("config.php");
if (!isset($_SESSION['user'])) {
    // Redirect to login page if user is not logged in
    header("Location: ../../../components/pages/login.html");
    exit(); // Stop execution of the script
}
$user = $_SESSION['user'];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $bio = $_POST['bio'];
    $company = $_POST['company'];
    $position = $_POST['position'];
    $institution = $_POST['institution'];
    $degree = $_POST['degree'];
    $field_of_study = $_POST['fieldofstudy'];
    $graduationyear = $_POST['graduationyear'];
    $proficiency = $_POST['proficiency'];
    $nativeplace = $_POST['nativeplace'];

    // Define the upload directory
    $uploadDirectory = 'uploads/';
    
    // Check if a profile image was uploaded
    if ($_FILES['img']['error'] === UPLOAD_ERR_OK) {
        $profileImage = $_FILES['img']['name'];
        $profileImageFilePath = $uploadDirectory . $profileImage;
        move_uploaded_file($_FILES['img']['tmp_name'], $profileImageFilePath);
    } else {
        // If no new image uploaded, keep the existing one
        $profileImage = $_POST['current_profile_image'];
    }

    // Check if a cover image was uploaded
    if ($_FILES['coverimg']['error'] === UPLOAD_ERR_OK) {
        $coverimage = $_FILES['coverimg']['name'];
        $coverImageFilePath = $uploadDirectory . $coverimage;
        move_uploaded_file($_FILES['coverimg']['tmp_name'], $coverImageFilePath);
    } else {
        // If no new image uploaded, keep the existing one
            $coverimage = $_POST['current_cover_image'];
    }

    // Extract only the filenames without the path
    $profileImageFileName = basename($profileImage);
    $coverImageFileName = basename($coverimage);

    // Update the database with only the filenames
    $query = "UPDATE users SET FirstName='$first_name', LastName='$last_name', Email='$email', ProfileImage='$profileImageFileName', CoverPhotoURL='$coverImageFileName', Bio='$bio', NativePlace='$nativeplace', CompanyName='$company', Position='$position', Institution='$institution', Degree='$degree', FieldOfStudy='$field_of_study', GraduationYear='$graduationyear', Proficiency='$proficiency' WHERE UserID=" . $user['UserID'];

    if (mysqli_query($conn, $query)) {
        echo "Record updated successfully";
        header("Location: ./home.php");
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}

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
    $nativeplace = $userDetails['NativePlace'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="../components/css/style.css">
</head>

<body>
    <div class="full">
    <div class="left1">
    <h1>Profile <br> details</h1>
        <img src="../components/images/application.png" alt="">
    </div> 

    <div class="right1">   
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" class="edit">
        <h1>Edit Your Profile</h1>
        <fieldset>
            <legend>Personal details</legend>
        <div class="one">
            <label>Name:</label>
        <input type="text" name="first_name" placeholder="Name" class="fname" value="<?php echo $firstName ?>" required>
        <input type="text" name="last_name" placeholder="LastName" class="lname" value="<?php echo $lastName ?>" required>
      </div>
       
        <div class="one"> 
            <label>Email:</label><input type="email" name="email" placeholder="email" value="<?php echo $email ?>" required>
        </div>
        <div class="one">
        <label for="upload_profile_image">Upload New Profile Image: </label>
        <input type="file" name="img" id="upload_profile_image">
        <input type="hidden" name="current_profile_image" value="<?php echo $profileImage ?>">
        </div>
        <div class="one"><label for="upload_cover_image">Upload New Cover Photo: </label>
        <input type="file" name="coverimg" id="upload_cover_image">
        </div>
        <input type="hidden" name="current_cover_image" value="<?php echo $coverimage ?>">
       <label>Bio:</label><textarea name="bio" id="bio" cols="30" rows="10" placeholder="Bio"><?php echo $bio; ?></textarea>
       <input type="text" name="nativeplace" placeholder="Native Place" class="nname" value="<?php echo $nativeplace ?>" >
    </fieldset>
    <fieldset>
        <legend>Company Details</legend>
        <label>Company:</label><input type="text" name="company" placeholder="Company name" value="<?php echo $company ?>">
        <label>Position:</label><input type="text" name="position" placeholder="Position" value="<?php echo $position ?>">
        </fieldset>
        <Fieldset> 
            <legend>Institutional details</legend>
            <div class="one"> <label>Institution:</label> <input type="text" name="institution" placeholder="Institution" value="<?php echo $institution ?>"></div>
            <div class="one"><label>Degree:</label><input type="text" name="degree" placeholder="Degree" value="<?php echo $degree ?>"></div>
            <div class="one"><label>Field of study:</label><input type="text" name="fieldofstudy" placeholder="Field of study" value="<?php echo $field_of_study ?>"></div>
            <div class="one"> <label for="">graduation year:</label><input type="number" name="graduationyear" placeholder="Graduation year" value="<?php echo $graduationyear ?>">
          
            <div class="one">
            <label for="">Proficiency:</label>
        <select name="proficiency">
            <option value="Beginner" <?php if ($proficiency == 'Beginner') echo 'selected'; ?>>Beginner</option>
            <option value="Intermediate" <?php if ($proficiency == 'Intermediate') echo 'selected'; ?>>Intermediate</option>
            <option value="Advanced" <?php if ($proficiency == 'Advanced') echo 'selected'; ?>>Advanced</option>
        </select>
</div>
</Fieldset>
        <input type="submit" class="button" value="Save"><br>
    </form>
    </div></div>
</body>

</html>