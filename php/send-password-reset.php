<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Other head elements -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
    </style>
</head>

<body>
    <!-- Your PHP and HTML content -->


    <?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $email = $_POST["email"];

    $token = bin2hex(random_bytes(16));

    $token_hash = hash("sha256", $token);

    $expiry = date("Y-m-d H:i:s", time() + 60 * 30);

    $mysqli = require __DIR__ . "/database.php";

    $sql = "UPDATE users
        SET reset_token_hash = ?,
            reset_token_expires_at = ?
        WHERE email = ?";

    $stmt = $mysqli->prepare($sql);

    $stmt->bind_param("sss", $token_hash, $expiry, $email);

    $stmt->execute();

    if ($mysqli->affected_rows) {

        $mail = require __DIR__ . "/mailer.php";

        $mail->setFrom("noreply@example.com");
        $mail->addAddress($email);
        $mail->Subject = "Password Reset";
        $mail->Body = <<<END
        <div style="max-width: 600px;
        margin: 50px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        text-align: left;">
        <div style="display: flex;
        align-items: center;">
            <h1 style="color: #0718c4;">CONNECTO</h1>
        </div>
        <hr />
        <h2>Password Reset</h2>
        <div style="text-align: left;">
            <p>Hi,User</p>
            <p>We have a request to reset your Connecto Account password. You can do this by clicking on the button below.</p>
            <br>
            <a
            class="btn"
            style="display: inline-block;
            padding: 15px 30px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;"
            href="http://localhost/job-finder/php/reset_password.php?token=$token"
            >Reset Password</a
            >
            <br>
            <p>If you did not request this change of password you may safely ignore this message.</p>
            <br>
            <hr>
            <br>
            Have a question? You can reach out to us via <a href="mailto:connecto@gmail.com">connecto@gmail.com</a>
        </div>
        </div>

        END;

        try {
            $mail->send();
            echo '<script>
                Swal.fire({
                    title: "Message",
                    text: "Sent to your mail Successfully!",
                    icon: "success"
                   
                });
              </script>';
        } catch (Exception $e) {
            echo '<script>
                    Swal.fire({
                        title: "Error!",
                        text: "Message could not be sent. Please try again later.",
                        icon: "error"
                    });
                </script>';
            // Log the error for debugging
            error_log("Message could not be sent. Mailer error: {$mail->ErrorInfo}");
        }
    }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>
