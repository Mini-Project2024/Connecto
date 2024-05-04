<?php

$token = $_GET["token"];

$token_hash = hash("sha256", $token);

$mysqli = require __DIR__ . "/database.php";

$sql = "SELECT * FROM users
        WHERE reset_token_hash = ?";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param("s", $token_hash);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if ($user === null) {
    die("token not found");
}

if (strtotime($user["reset_token_expires_at"]) <= time()) {
    die("token has expired");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" />
    <style>
        body{
            height: 100vh;
            display: flex;
            align-items:center;
            justify-content:center;
        }
        header{
         font-size:30px;
        }
        img{
            height: 400px;
            width: 400px;
        }
        section{
            display:flex;
        }
        .wrapper{
            border:1px solid lightgrey;
            border-radius:10px;
            height: 400px;
            width: 700px;
            padding: 10px;
        }
        form{
            display: flex;
            flex-direction:column;
            align-items:center;
            justify-content:center;             
        }
        header{
            font-size:35px;
            font-weight:600;
        }
        input[type=password]{
      width: 200px;
      outline:none;
      border:1px solid #a4a4a4;
      border-radius:5px;
      padding: 10px;
      margin: 10px;
    }
      input[type=submit]{
      background: #0718c4;
      color:#fff;
      border:none;
      outline:none;
      padding: 10px;
      inset:10px;
      border-radius:5px;
    }
    .field input{
        display: flex;
        align-items:center;
        justify-content:center;
    }
    @media screen and (max-width:480px)
    {
      section {
        display: flex;
        flex-direction: column;
      }
      .wrapper{
        height: 500px;
        width: 350px;
        display: flex;
        flex-direction: column;
      }
      img{
            height: 210px;
            width: 210px;
      }
    }
    </style>
</head>

<body>
    <div class="wrapper">
        <section class="form login">
            
            <center><img src="../components/images/reset.avif" alt=""></center>
            <form action="process-reset-password.php" method="POST" enctype="multipart/form-data" autocomplete="off">
            <header>Reset Password</header> <br><br>
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                <div class="field input">
                    <label for="password">New Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter New Password" required>
                </div>
                <div class="field input">
                    <label for="password_confirmation">Re-Type Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" required>
                </div>
                <div class="field button">
                    <input type="submit" name="submit" value="Reset Password">
                </div>
            </form>
        </section>
    </div>
</body>

</html>