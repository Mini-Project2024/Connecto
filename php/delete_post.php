<?php
include("config.php");

if(isset($_POST['postId'])) {
    $postId = $_POST['postId'];
    
    // Delete the post from the database
    $delete_query = "DELETE FROM `posts` WHERE PostId = $postId";
    $result = mysqli_query($conn, $delete_query);
    
    if($result) {
        echo "Post deleted successfully";
        echo "
<script>
  window.location.reload(); // Reload the page
</script>
";
    } else {
        echo "Failed to delete post";
    }
} else {
    echo "Post ID not provided";
}
?>