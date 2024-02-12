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
    $chats = getAllMessaages();
    $chatlist = "";
    foreach ($chats as $chat) {
        $ch_user = getUser($chat['user_id']);
        // Modify the onclick event to pass the user_id
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
?>
