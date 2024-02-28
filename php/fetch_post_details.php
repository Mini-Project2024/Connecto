<?php

include 'config.php';


if(isset($_GET['Postid'])) {

    $Postid = mysqli_real_escape_string($conn, $_GET['Postid']);


    $postquery = "SELECT p.*, u.* 
              FROM posts p 
              JOIN users u ON p.UserID = u.UserID 
              WHERE p.PostID = $Postid 
              ORDER BY p.PostedDate DESC";
$postresult = mysqli_query($conn, $postquery);


    if($postresult && mysqli_num_rows($postresult) > 0) {

        $postDetails = mysqli_fetch_assoc($postresult);

       
        header('Content-Type: application/json');
        echo json_encode($postDetails);
        exit; 
    } else {
        
        echo "No post found for the provided Postid";
    }
} else {
   
    echo "Postid parameter is missing in the request";
}
?>
