<?php

include("config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);


    $defaultImage = "./uploads/default.png"; 

    // File upload handling
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["img"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if a file is uploaded
    if ($_FILES["img"]["size"] > 0) {
        // If uploaded, proceed with validation
        $check = getimagesize($_FILES["img"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check file size
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
    } else {
        $fileName = basename($defaultImage);
    }
    

    $defaultbanner =basename('./uploads/defaultbanner.png');

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
            if ($_FILES["img"]["size"] > 0) {
                if (move_uploaded_file($_FILES["img"]["tmp_name"], $target_file)) {
                    // If uploaded successfully, store the file name in a variable
                    $fileName = basename($_FILES["img"]["name"]);
                     echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
                    echo '<script>';
                    echo 'document.addEventListener("DOMContentLoaded", function() {';
                    echo 'Swal.fire({';
                    echo 'title: "Oh no!",';
                    echo 'text: "Sorry, there was an error uploading your file",';
                    echo 'icon: "error",';
                    echo 'confirmButtonText: "OK"';
                    echo '}).then(function() {';
                    echo 'window.location.href = "../components/pages/index.html";';
                    echo '});';
                    echo '});';
                    echo '</script>';
                    $uploadOk = 0;
                }
            }
            
            $insertQuery = "INSERT INTO Users (FirstName, LastName, Email, Password, ProfileImage,CoverPhotoURL) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);

            // Use the file name variable for binding
            $stmt->bind_param('ssssss', $firstName, $lastName, $email, $password, $fileName,$defaultbanner);

            if ($stmt->execute()) {
                echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
                echo '<script>';
                echo 'document.addEventListener("DOMContentLoaded", function() {';
                echo 'Swal.fire({';
            
                echo 'text: "Registration successful!",';
                echo 'icon: "success",';
                echo '}).then(function() {';
                echo 'window.location.href = "../components/pages/login.html";';
                echo '});';
                echo '});';
                echo '</script>';
                
            } else {
                echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
                echo '<script>';
                echo 'document.addEventListener("DOMContentLoaded", function() {';
                echo 'Swal.fire({';
                echo 'title: "Oh no!",';
                echo 'text: "Error in Registeration!",';
                echo 'icon: "error",';
                echo 'confirmButtonText: "OK"';
                echo '}).then(function() {';
                echo 'window.location.href = "../components/pages/index.html";';
                echo '});';
                echo '});';
                echo '</script>';
            }
            $stmt->close();
        }

        // Close the check email statement
        $stmtCheck->close();
    }

    $conn->close();
}
?>
