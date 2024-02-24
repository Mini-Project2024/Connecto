<?php
// include_once("./home.php");

require_once "config.php";
session_start();

//for getting id's of chat users

function getActiveChatUsersIds()
{
    global $conn;

    $current_user_id = $_SESSION['user']['UserID'];

    $query = "SELECT from_user_id,to_user_id FROM messages WHERE to_user_id = $current_user_id || from_user_id = $current_user_id ORDER BY id DESC";

    $run = mysqli_query($conn, $query);

    $data = mysqli_fetch_all($run, true);

    $ids = array();

    foreach ($data as $chat) {
        if ($chat['from_user_id'] != $current_user_id && !in_array($chat['from_user_id'], $ids)) {
            $ids[] = $chat['from_user_id'];
        }
        
        if ($chat['to_user_id'] != $current_user_id && !in_array($chat['to_user_id'], $ids)) {
            $ids[] = $chat['to_user_id'];
        }
    }
    
    
    return $ids;
    // return $current_user_id;
}

function getMessages($user_id){
    global $conn;

    $current_user_id = $_SESSION['user']['UserID'];

    $query = "SELECT * FROM messages WHERE (to_user_id = $current_user_id && from_user_id = $user_id) || (from_user_id = $current_user_id && to_user_id = $user_id) ORDER BY id DESC";

    $run = mysqli_query($conn, $query);

    return mysqli_fetch_all($run, true);
}

function getAllMessages(){
    $active_chat_ids = getActiveChatUsersIds();
    $conversation = array();
    foreach($active_chat_ids as $index=>$id){
        $conversation[$index]['user_id'] = $id;
        $conversation[$index]['messages'] = getMessages($id);
    }
    return $conversation;
}


function getUser($id){
    global $conn;

    $query = "SELECT * FROM users WHERE UserID=$id";

    $run = mysqli_query($conn, $query);

    return mysqli_fetch_assoc($run);
}

function sendMessage($user_id,$msg){
    global $conn;

    $current_user_id = $_SESSION['user']['UserID'];

    $query = "INSERT INTO messages (from_user_id,to_user_id,msg) VALUES($current_user_id,$user_id,'$msg')";

    return mysqli_query($conn, $query);

}
//-------------------------For getting all the users to follow ---------------------------
function getFollowSuggestions($user_id){
    global $conn;
    $current_user_id = $_SESSION['user']['UserID'];
    $query = "SELECT * FROM users WHERE UserID != $user_id AND UserID != $current_user_id";
    $run = mysqli_query($conn,$query);
    return mysqli_fetch_all($run,true);
}

//-------------------------For filtering the follow suggestion list ---------------------------

function filterFollowSuggestion($user_id){
    $list = getFollowSuggestions($user_id);
    $filter_list = array();
    foreach($list as $user){
        if(!checkFollowStatus($user['UserID'])){
            $filter_list[] = $user;
        }
    }
    return $filter_list;
}

//-------------------------For checking the user is followed by current user or not---------------------------

function checkFollowStatus($user_id){
    global $conn;
    $current_user_id = $_SESSION['user']['UserID'];
    $query = "SELECT COUNT(*) as row FROM connections WHERE connector_id = $current_user_id && user_id = $user_id";
    $run = mysqli_query($conn,$query);
    return mysqli_fetch_assoc($run)['row'];
}

function followUser($user_id){
    global $conn;
    $current_user_id = $_SESSION['user']['UserID'];
    $query = "INSERT INTO connections(connector_id,user_id) VALUES($current_user_id,$user_id)";
    return mysqli_query($conn,$query);
}

function searchUser($search) {
    global $conn;
    $query = "SELECT * FROM users WHERE FirstName LIKE '%$search%' OR LastName LIKE '%$search%'";
    $run = mysqli_query($conn,$query);
    return mysqli_fetch_all($run, MYSQLI_ASSOC);
}

//functions check like status
function checkLikeStatus($PostId){
    global $conn;
    $current_user_id = $_SESSION['user']['UserID'];
    $query = "SELECT COUNT(*) as row FROM likes WHERE UserId = $current_user_id && PostId = $PostId";
    $run = mysqli_query($conn,$query);
    return mysqli_fetch_assoc($run)['row'];
}

function like($PostID){
    global $conn;
    $current_user_id = $_SESSION['user']['UserID'];
    $query = "INSERT INTO likes(PostId,UserId) VALUES($PostID,$current_user_id)";
    return mysqli_query($conn,$query);
}
function unlike($PostID){
    global $conn;
    $current_user_id = $_SESSION['user']['UserID'];
    $query = "DELETE FROM likes WHERE UserId = $current_user_id && PostId = $PostID";
    return mysqli_query($conn,$query);
}
///getting like count
function getlikes($PostID){
    global $conn;
    $query = "SELECT * FROM likes WHERE  PostId = $PostID";
    $run = mysqli_query($conn,$query);
    return mysqli_fetch_all($run);
}

function disconnectUser($user_id){
    global $conn;
    $current_user_id = $_SESSION['user']['UserID'];
    $query = "DELETE FROM connections WHERE connector_id = $current_user_id AND user_id = $user_id";
    $result = mysqli_query($conn, $query);
    if ($result) {
        return true;
    } else {
        // Log the error
        error_log("Error disconnecting user: " . mysqli_error($conn));
        return false;
    }
}
