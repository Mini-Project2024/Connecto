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

if (isset($_GET['connect'])) {
    $user_id = $_POST['user_id'];
    $current_user_id = $_SESSION['user']['UserID'];

    if (checkPendingRequest($current_user_id, $user_id)) {
        $response['message'] = 'Request already pending';
    } else {
        if (createConnectionRequest($current_user_id, $user_id)) {
            $response['status'] = true;
            $response['message'] = 'Request sent';
        } else {
            $response['message'] = 'Request failed';
        }
    }

    echo json_encode($response);
    exit();
}

if (isset($_GET['disconnect'])) {
    $user_id = $_POST['user_id'];
    $current_user_id = $_SESSION['user']['UserID'];

    if (disconnectUser($user_id, $current_user_id)) {
        $response['status'] = true;
    } else {
        $response['message'] = 'Disconnection failed';
    }
    echo json_encode($response);
    exit();
}



if (isset($_GET['acceptRequest'])) {
    $request_id = $_POST['request_id'];

    // Get the from_user_id and to_user_id from the connection_requests table
    $query = "SELECT from_user_id, to_user_id FROM connection_requests WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $request_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        $from_user_id = $row['from_user_id'];
        $to_user_id = $row['to_user_id'];

        // Insert the connection into the connections table
        $insert_query = "INSERT INTO connections (user_id, connector_id) VALUES (?, ?), (?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("iiii", $from_user_id, $to_user_id, $to_user_id, $from_user_id);
        if ($stmt->execute()) {
            // Update the status in the connection_requests table
            $update_query = "UPDATE connection_requests SET status = 'accepted' WHERE id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("i", $request_id);
            if ($stmt->execute()) {
                echo json_encode(['status' => true]);
                exit();
            }
        }
    }

    echo json_encode(['status' => false]);
    exit();
}

if (isset($_GET['rejectRequest'])) {
    $request_id = $_POST['request_id'];
    $query = "UPDATE connection_requests SET status = 'rejected' WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $request_id);
    if ($stmt->execute()) {
        echo json_encode(['status' => true]);
    } else {
        echo json_encode(['status' => false]);
    }
    exit();
}
?>

