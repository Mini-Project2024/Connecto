<?php
session_start();
include("config.php");

if (isset($_POST['userid'])) {
    $userid = $_POST['userid'];
    $deleteQuery = "DELETE FROM users WHERE UserID = $userid";
    if (mysqli_query($conn, $deleteQuery)) {
        session_destroy(); 
        echo json_encode([
            'status' => 'success',
            'redirect' => '../components/pages/login.html' // Redirect URL
        ]);
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    // Handle case where userid is not set
}
?>
