<?php
    session_start();
    include("config.php");

    if (!isset($_SESSION['user'])) {
        // Redirect to login page if user is not logged in
        header("Location: ../../../components/pages/login.html");
        exit(); // Stop execution of the script
    }


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
        $bio = $userDetails['Bio'];
        $company = $userDetails['CompanyName'];
        $position = $userDetails['Position'];
        $institution = $userDetails['Institution'];
        $degree = $userDetails['Degree'];
        $field_of_study = $userDetails['FieldOfStudy'];
        $graduationyear = $userDetails['GraduationYear'];
        $coverimage = $userDetails['CoverPhotoURL'];
    }  
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<img src="./uploads/<?php echo $coverimage?>" alt="Your Name" />
<img src="./uploads/<?php echo $profileImage?>" alt="Your Name" />
<h1><?php echo $firstName . ' ' . $lastName; ?></h1>
<?php echo $bio ?> <br>
<?php echo $email ?> <br>
<?php echo $company ?> <br>
<?php echo $position ?> <br>
<?php echo $institution ?> <br>
<?php echo $field_of_study ?> <br>
<?php echo $degree ?> <br>
<?php echo $graduationyear ?> <br>

</body>
</html>

