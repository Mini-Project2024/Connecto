<?php 

require_once "functions.php";

if(isset($_GET['getMessages'])){
  $chats = getAllMessaages();
  $chatlist = "";
  foreach ($chats as $chat) {
      $ch_user = getUser($chat['user_id']);
      $chatlist .= '<div class="msg">
                        <a href="#" onclick="showChatbox()">
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
  if(isset($_POST['chatter_id']) && $_POST['chatter_id'] != 0){
    $messages = getMessages($_POST['chatter_id']);
    $chatmsg = "";
    foreach($messages as $cm){
      $chatmsg.='<div class="chat1">'. $cm['msg'] .'</div>';
    }
    echo json_encode(['chat' => $chatmsg, 'chatlist' => $chatlist]); // Including 'chat' in the JSON response
  } else {
    echo json_encode(['chatlist' => $chatlist]); // Only include 'chatlist' if 'chatter_id' is not set
  }
}

