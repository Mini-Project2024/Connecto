<?php
require_once("./config.php");
include_once("./home.php");


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

    $query = "SELECT * FROM messages WHERE (to_user_id = $current_user_id && from_user_id = $user_id) || (from_user_id = $current_user_id && to_user_id = $user_id)";

    $run = mysqli_query($conn, $query);

    return mysqli_fetch_all($run, true);
}


