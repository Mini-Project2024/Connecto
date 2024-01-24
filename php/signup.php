<?php

include("config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // File upload handling
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["img"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if the file is an actual image
    $check = getimagesize($_FILES["img"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size (you can set your own limit)
    if ($_FILES["img"]["size"] > 50000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        // Check if the email already exists in the database
        $checkEmailQuery = "SELECT COUNT(*) as count FROM Users WHERE Email = ?";
        $stmtCheck = $conn->prepare($checkEmailQuery);
        $stmtCheck->bind_param('s', $email);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();
        $rowCheck = $resultCheck->fetch_assoc();

        if ($rowCheck['count'] > 0) {
            echo "$email already exists. Please use a different email.";
        } else {
            // The email does not exist, proceed with the insertion
            if (move_uploaded_file($_FILES["img"]["tmp_name"], $target_file)) {
                // Store the file name in a variable
                $fileName = basename($_FILES["img"]["name"]);
            
                $insertQuery = "INSERT INTO Users (FirstName, LastName, Email, Password, ProfileImage) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($insertQuery);
            
                // Use the file name variable for binding
                $stmt->bind_param('sssss', $firstName, $lastName, $email, $password, $fileName);
            
                if ($stmt->execute()) {
                    echo "Registration successful!";
                } else {
                    echo "Error in registration. Please try again.";
                }
                $stmt->close();
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }

        // Close the check email statement
        $stmtCheck->close();
    }

    $conn->close();
}
?>
