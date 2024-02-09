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

    // Check if a profile image was uploaded
    if ($_FILES['img']['error'] === UPLOAD_ERR_OK) {
        $profileImage = 'uploads/' . uniqid() . '_' . $_FILES['img']['name'];
        move_uploaded_file($_FILES['img']['tmp_name'], $profileImage);
    } else {
        // If no new image uploaded, keep the existing one
        $profileImage = $_POST['current_profile_image'];
    }

    // Check if a cover image was uploaded
    if ($_FILES['coverimg']['error'] === UPLOAD_ERR_OK) {
        $coverimage = 'uploads/' . uniqid() . '_' . $_FILES['coverimg']['name'];
        move_uploaded_file($_FILES['coverimg']['tmp_name'], $coverimage);
    } else {
        // If no new image uploaded, keep the existing one
        $coverimage = $_POST['current_cover_image'];
    }

    $query = "UPDATE users SET FirstName='$first_name', LastName='$last_name', Email='$email', ProfileImage='$profileImage', CoverPhotoURL='$coverimage', Bio='$bio', CompanyName='$company', Position='$position', Institution='$institution', Degree='$degree', FieldOfStudy='$field_of_study', GraduationYear='$graduationyear', Proficiency='$proficiency' WHERE UserID=" . $user['UserID'];

    if (mysqli_query($conn, $query)) {
        echo "Record updated successfully";
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
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <input type="text" name="first_name" placeholder="Name" value="<?php echo $firstName?>" required>
        <input type="text" name="last_name" placeholder="LastName" value="<?php echo $lastName?>" required>
        <input type="email" name="email" placeholder="email" value="<?php echo $email?>" required>
        <label for="upload_profile_image">Upload New Profile Image: </label> 
        <input type="file" name="img" id="upload_profile_image">
        <input type="hidden" name="current_profile_image" value="<?php echo $profileImage ?>">
        <label for="upload_cover_image">Upload New Cover Photo: </label> 
        <input type="file" name="coverimg" id="upload_cover_image">
        <input type="hidden" name="current_cover_image" value="<?php echo $coverimage ?>">
        <textarea name="bio" id="bio" cols="30" rows="10" placeholder="Bio"><?php echo $bio; ?></textarea>
        <input type="text" name="company" placeholder="Company name" value="<?php echo $company?>">
        <input type="text" name="position" placeholder="Position" value="<?php echo $position?>">
        <input type="text" name="institution" placeholder="Institution" value="<?php echo $institution?>">
        <input type="text" name="degree" placeholder="Degree" value="<?php echo $degree?>">
        <input type="text" name="fieldofstudy" placeholder="Field of study" value="<?php echo $field_of_study?>">
        <input type="number" name="graduationyear" placeholder="Graduation year" value="<?php echo $graduationyear?>">
        <select name="proficiency">
            <option value="Beginner" <?php if ($proficiency == 'Beginner') echo 'selected'; ?>>Beginner</option>
            <option value="Intermediate" <?php if ($proficiency == 'Intermediate') echo 'selected'; ?>>Intermediate</option>
            <option value="Advanced" <?php if ($proficiency == 'Advanced') echo 'selected'; ?>>Advanced</option>
        </select>

        <input type="submit" class="button" value="Submit"><br>
    </form>
</body>
</html>
