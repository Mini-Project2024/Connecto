<?php
require_once "functions.php";

if(isset($_GET['sendmessage'])){
    if(sendMessage($_POST['user_id'],$_POST['msg'])){
        $response['status']=true;
    }else{
        $response['status']=false;
    }
    echo json_encode($response);
}

if(isset($_GET['getMessages'])){
    $chats = getAllMessages();
    $chatlist = "";
    foreach ($chats as $chat) {
        $ch_user = getUser($chat['user_id']);
        $chatlist .= '<div class="msg">
                            <a href="./messages.php?chatter_id=' . $ch_user['UserID'] . '" class="chatlist_item">
                            <div class="msg-main">
                                <img class="msg-img" src="./uploads/' . $ch_user['ProfileImage'] . '" alt="img">
                                <div class="msg-content">
                                <p class="msg-name">' . $ch_user['FirstName'] . '</p>
                                <p>' . $chat['messages'][0]['msg'] . '</p>
                                </div>
                            </div>
                            </a>
                        </div><hr>';
    }
    $json['chatlist'] = $chatlist;

    if(isset($_GET['chatter_id']) && $_GET['chatter_id'] != 0){
        $messages = getMessages($_GET['chatter_id']);
        $chatmsg = "";
        foreach($messages as $cm){
            if($cm['from_user_id'] == $_SESSION['user']['UserID']){
                $cl1 = 'chat2';
            }else{
                $cl1 = 'chat1';
            }
            $chatmsg.='<div class="' . $cl1 . '">'. $cm['msg'] .'</div>';
        }
        $json['chat']['msgs'] = $chatmsg;
        $json['chat']['userdata'] = getUser($_GET['chatter_id']);
    }else{
        $json['chat']['msgs'] = '<div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
        </div>';
    }
    echo json_encode($json);
}

if(isset($_GET['connect'])){
    $user_id = $_POST['user_id'];
    $current_user_id = $_SESSION['user']['UserID'];

    // Check if the users are already connected
    if(checkFollowStatus($user_id)){
        // If they are connected, disconnect them
        if(disconnectUser($user_id)){
            $response['status'] = false; // Set status to false indicating disconnection
        }else{
            $response['status'] = true; // Set status to true indicating connection
        }
    } else {
        // If they are not connected, connect them
        if(followUser($user_id)){
            $response['status'] = true; // Set status to true indicating connection
        }else{
            $response['status'] = false; // Set status to false indicating disconnection
        }
    }

    echo json_encode($response);
}

if(isset($_GET['searchUsers'])){
    $str = $_GET['searchString'];
    $search_results = searchUser($str);
    $userlist = "";
    foreach ($search_results as $user) {
        $userlist .= '<div class="msg">
                        <a href="./profile.php?userID=' . $user['UserID'] . '" class="chatlist_item">
                            <div class="msg-main">
                                <img class="msg-img" src="./uploads/' . $user['ProfileImage'] . '" alt="img">
                                <p class="msg-name">' . $user['FirstName'] . ' ' . $user['LastName'] .'</p>
                            </div>
                        </a>
                    </div><hr>';
    }
    $json['userlist'] = $userlist;
    echo json_encode($json);
}

if(isset($_GET['like'])){
    $PostID= $_POST['PostID'];
    if(!checkLikeStatus($PostID)){
        if(like($PostID)){
            $response['status'] = true;
        }else{
            $response['status'] = false;
        }
        echo json_encode($response);
    }
}

if(isset($_GET['unlike'])){
    $PostID= $_POST['PostID'];
    if(checkLikeStatus($PostID)){
        if(unlike($PostID)){
            $response['status'] = true;
        }else{
            $response['status'] = false;
        }
        echo json_encode($response);
    }
}

if(isset($_GET['disconnect'])){
    $user_id = $_POST['user_id']; // Ensure this is properly set
    if(disconnectUser($user_id)){
        $response['status'] = true;
    }else{
        $response['status'] = false;
    }
    echo json_encode($response);
}

?>
