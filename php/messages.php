<?php
// session_start();
include_once("./functions.php");
include("config.php");

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    // Redirect to login page if user is not logged in
    header("Location: ../components/pages/login.html");
    exit(); // Stop execution of the script
}

// Access the user data from session
$user = $_SESSION['user'];
$query = "SELECT * FROM users WHERE UserID = " . $user['UserID']; // Assuming user_details table stores additional user information
$result = mysqli_query($conn, $query);
$userDetails = mysqli_fetch_assoc($result);

// Check if user details exist
if ($userDetails) {
    $profileImage = $userDetails['ProfileImage'];

    $firstName = $userDetails['FirstName'];
    $lastName = $userDetails['LastName'];
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>CONNECTO</title>
    <script src="https://kit.fontawesome.com/b7a08da434.js" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/f4e815f78b.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="../components/css/style.css">
    <style>
        .msg-name {
            font-size: 20px !important;
        }

        .uploaded-image {
            max-width: 100px;
            max-height: 100px;
            margin-top: 5px;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 10px 0;
            text-align: center;
        }

        .container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        nav ul li {
            display: inline;
            margin-right: 20px;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
        }

        .chat_box {
            max-width: 800px;
            height: 650px;
            max-height: 650px;
            margin: 20px auto;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 20px;
        }

        .chat_header {
            background-color: #836FFF;
            color: #fff;
            padding: 10px;
            display: flex;
            align-items: center;
            gap: 20px;
            border-radius: 10px 10px 0 0;
        }

        .chat_body {
            max-height: 500px;
            height: 500px;
            overflow-y: auto;
            padding: 10px;
            background-color: #fff;
            display: flex;
           
            flex-direction: column-reverse;
        }

        .chat_body::-webkit-scrollbar {
            display: none;
        }

        .chat_bottom {
            background-color: #f0f0f0;
            padding: 10px;
        }

        .chat_input {
            width: calc(100% - 60px);
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .send_button {
            width: 50px;
            padding: 8px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .back-arrow {
            font-size: 20px;
            color: #fff;
        }
    </style>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script> -->
</head>

<body>
    <script>
        function updateDataUserId(chatter_id) {
            $("#sendmsg").attr('data-user-id', chatter_id);
        }

        $(document).on('click', '.sendbtn', function() {
            // console.log("Send button clicked");
            sendMessage();
        });

        $(document).on('click', '#mediaEmoji', function() {
            // Trigger click event on file input
            $('#fileToUpload').click();
        });

        // Function to handle file input change event
        $('#fileToUpload').change(function() {
            var file = this.files[0];
            if (file) {
                // Display the uploaded image
                displayImage(file);
            }
        });

        // Function to display the uploaded image
        function displayImage(file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                console.log(e.target.result);
                var imageHtml = '<img src="' + e.target.result + '" class="uploaded-image" alt="Uploaded Image">';
                $('#chat_body').append(imageHtml);
            };
            reader.readAsDataURL(file);
        }

        function send() {
            if (event.key === "Enter" || event.keyCode === 13) {
                event.preventDefault();
                sendMessage();
            }
        }


        function sendMessage() {
            var user_id = chatting_user_id;
            var msg = $("#userinput").val();
            if (!msg) return;

            $.ajax({
                url: './ajax.php?sendmessage',
                method: 'POST',
                dataType: 'json',
                data: {
                    user_id: user_id,
                    msg: msg
                },
                success: function(response) {
                    if (response.status) {
                        $("#userinput").val('');
                    } else {
                        alert('Failed to send message. Please try again later.');
                    }
                },
                error: function(xhr, status, error) {
                    alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                }
            });
        }


        function synmsg() {
            $.ajax({
                url: './ajax.php?getMessages&chatter_id=' + chatting_user_id,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    $('#chatlist').html(response.chatlist);
                    $('#chat_body').html(response.chat.msgs);
                    if (chatting_user_id != 0) {
                        $('#chatter_name').text(response.chat.userdata.FirstName + ' ' + response.chat.userdata.LastName);
                        $('#chatter_img').attr('src', './uploads/' + response.chat.userdata.ProfileImage);
                    }
                }
            });
        }

        var chatting_user_id = <?php echo isset($_GET['chatter_id']) ? $_GET['chatter_id'] : 0; ?>;

        synmsg();

        setInterval(synmsg, 1000);

        $(document).ready(function() {
            updateDataUserId(chatting_user_id);
        });

        $(window).on('popstate', function() {
            var new_chatter_id = <?php echo isset($_GET['chatter_id']) ? $_GET['chatter_id'] : 0; ?>;
            chatting_user_id = new_chatter_id;
            updateDataUserId(chatting_user_id);
        });
    </script>

    <div class="chat_box">
        <div class="chat_header">
            <a class="back-arrow" href="./home.php"><i class="fa-solid fa-arrow-left"></i></a>
            <img class="chat-img" id="chatter_img" src="" alt="">
            <h3 class="msg-name">
                <span id="chatter_name"></span>
            </h3>
        </div>
        <div class="chat_body" id="chat_body">

        </div>
        <div class="chat_bottom">
            <input type="text" class="chat_input" id="userinput" onkeypress="send()" placeholder="Type your message...">
            <!-- <i class="fa-regular fa-image" id="mediaEmoji"></i>
            <input type="file" name="fileToUpload" id="fileToUpload" style="display: none;"> -->
            <button class="sendbtn" data-user-id="0" id="sendmsg"><i class="fa-solid fa-paper-plane"></i></button>
        </div>
    </div>


</body>

</html>