<?php
// session_start();
include("config.php");
include_once("functions.php");

if (!isset($_SESSION['user'])) {
    header("Location: ../../../components/pages/login.html");
    exit();
}

$current_user_id = $_SESSION['user']['UserID'];

// Get pending connection requests
$query = "SELECT cr.id AS request_id, u.UserID, u.FirstName, u.LastName, u.ProfileImage 
          FROM connection_requests cr
          JOIN users u ON cr.from_user_id = u.UserID
          WHERE cr.to_user_id = ? AND cr.status = 'pending'";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $current_user_id);
$stmt->execute();
$result = $stmt->get_result();
$requests = $result->fetch_all(MYSQLI_ASSOC);

$querynum = "SELECT COUNT(*) AS num_requests 
          FROM connection_requests cr
          WHERE cr.to_user_id = ? AND cr.status = 'pending'";
$stmt = $conn->prepare($querynum);
$stmt->bind_param("i", $current_user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$num_requests = $row['num_requests'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link rel="stylesheet" href="../components/css/style.css">
    <script src="https://kit.fontawesome.com/b7a08da434.js" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/f4e815f78b.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>

<body>
    <header>
        <div class="container">
            <div class="logo">
                <img src="../components/images/logo.png" alt="">
            </div>
            <nav>
                <ul>
                    <div id="searchResults" style="text-align: center;"></div>
                    <li><a href="home.php"><i class="fa-solid fa-house" style="font-size:30px;margin-bottom:12px;"></i><a href="home.php">Home</a></a></li>
                    <li><a href="network.php"><svg fill="#ffff" height="28px" width="28px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 490 490" xml:space="preserve" stroke="#ffff">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <g>
                                        <ellipse cx="245.639" cy="154.693" rx="50.324" ry="51.162"></ellipse>
                                        <path d="M158.728,306.884h173.823c0-48.799-38.912-88.359-86.912-88.359S158.728,258.085,158.728,306.884z"></path>
                                        <path d="M49.729,99.439c4.652,0,9.151-0.652,13.424-1.846l52.215,60.854l23.238-19.949L88.831,80.488 c6.652-8.413,10.635-19.007,10.635-30.516c0-27.291-22.311-49.482-49.736-49.482C22.311,0.49,0,22.681,0,49.972 C0,77.248,22.311,99.439,49.729,99.439z M49.729,31.115c10.535,0,19.111,8.464,19.111,18.857c0,10.393-8.576,18.842-19.111,18.842 s-19.104-8.449-19.104-18.842C30.625,39.579,39.194,31.115,49.729,31.115z"></path>
                                        <path d="M49.729,489.51c27.425,0,49.736-22.191,49.736-49.482c0-11.515-3.983-22.114-10.636-30.529l49.778-58.012l-23.238-19.948 l-52.216,60.854c-4.274-1.194-8.773-1.846-13.424-1.846C22.311,390.546,0,412.737,0,440.028S22.311,489.51,49.729,489.51z M49.729,421.171c10.535,0,19.111,8.464,19.111,18.857c0,10.393-8.576,18.857-19.111,18.857s-19.104-8.464-19.104-18.857 C30.625,429.635,39.194,421.171,49.729,421.171z"></path>
                                        <path d="M374.631,158.447l52.22-60.852c4.27,1.192,8.765,1.844,13.412,1.844c27.425,0,49.737-22.191,49.737-49.467 c0-27.291-22.311-49.482-49.737-49.482c-27.41,0-49.721,22.191-49.721,49.482c0,11.508,3.983,22.103,10.634,30.516l-49.783,58.011 L374.631,158.447z M440.263,31.115c10.543,0,19.111,8.464,19.111,18.857c0,10.393-8.569,18.842-19.111,18.842 c-10.527,0-19.096-8.449-19.096-18.842C421.167,39.579,429.736,31.115,440.263,31.115z"></path>
                                        <path d="M401.177,409.499c-6.652,8.416-10.635,19.014-10.635,30.529c0,27.291,22.311,49.482,49.721,49.482 c27.425,0,49.737-22.191,49.737-49.482s-22.311-49.482-49.737-49.482c-4.647,0-9.142,0.652-13.412,1.844l-52.221-60.852 l-23.238,19.948L401.177,409.499z M459.375,440.028c0,10.393-8.569,18.857-19.111,18.857c-10.527,0-19.096-8.464-19.096-18.857 c0-10.393,8.569-18.857,19.096-18.857C450.806,421.171,459.375,429.635,459.375,440.028z"></path>
                                    </g>
                                </g>
                            </svg><a href="network.php">My Network</a></li></a>
                    <li>
                        <a href="notification.php">
                            <i class="fa-solid fa-bell" style="font-size:28px;margin-bottom:12px;"></i>
                            <a href="notification.php">Notification</a>
                            <?php if ($num_requests > 0) : ?>
                                <span class="badge" style="position: absolute;top: 1px; right: 180px; background-color: red; border-radius: 50%; width: 20px; text-align: center;"><?php echo $num_requests; ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li><a href="logout.php"><i class="fa-solid fa-right-from-bracket" style="font-size:28px;margin-bottom:12px;"></i><a href="logout.php">Logout</a></a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="main-profile">
        <?php if (empty($requests)) : ?>
            <h3>No Notifications Currently</h3>
        <?php else : ?>
            <?php foreach ($requests as $request) : ?>
                <div class="cards notification-item" data-request-id="<?php echo $request['request_id']; ?>">
                    <img src="./uploads/<?php echo $request['ProfileImage']; ?>" alt="img" class="connect-profile">
                    <div class="connect-content">
                        <p><?php echo $request['FirstName'] . ' ' . $request['LastName']; ?></p>
                        <button class="accept-request connect1" data-request-id="<?php echo $request['request_id']; ?>">Accept</button>
                        <button class="reject-request connect1" data-request-id="<?php echo $request['request_id']; ?>">Reject</button>
                    </div>
                </div>
                <hr>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <script>
        setTimeout(function() {
            location.reload();
        }, 6000);
        $(document).on("click", ".accept-request", function() {
            var button = $(this);
            var request_id = button.data('request-id');

            $.ajax({
                url: './ajax.php?acceptRequest',
                method: 'POST',
                dataType: 'json',
                data: {
                    request_id: request_id
                },
                success: function(response) {
                    if (response.status) {
                        button.closest('.notification-item').remove();
                    } else {
                        alert("Failed to accept request");
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alert("Something went wrong");
                }
            });
        });

        $(document).on("click", ".reject-request", function() {
            var button = $(this);
            var request_id = button.data('request-id');

            $.ajax({
                url: './ajax.php?rejectRequest',
                method: 'POST',
                dataType: 'json',
                data: {
                    request_id: request_id
                },
                success: function(response) {
                    if (response.status) {
                        button.closest('.notification-item').remove();
                    } else {
                        alert("Failed to reject request");
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alert("Something went wrong");
                }
            });
        });
    </script>
</body>

</html>