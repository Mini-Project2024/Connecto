<?php 

require_once "functions.php";

if(isset($_GET['getMessages'])){
    $chats = getAllMessaages();
    $chatlist = "";
    foreach ($chats as $chat) {
        $ch_user = getUser($chat['user_id']);
        $chatlist .= '<div class="msg">
                          <a href="#">
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
    echo json_encode(['chatlist' => $chatlist]);
}
