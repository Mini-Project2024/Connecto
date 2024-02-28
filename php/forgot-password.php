<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Connecto</title>
  <link rel="stylesheet" href="style.css">
  <link rel="icon" type="image/png" href="/logo.png"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
</head>
<body>
  <div class="wrapper">
  <section class="form login">
      <header>Forgot Password</header>
      <form action="./send-password-reset.php" method="POST" enctype="multipart/form-data">
        <!-- <div class="error-text"></div> -->
        <div class="field input">
          <label for="email">Email Address</label>
          <input type="text" name="email" placeholder="Enter your email" required>
        </div>
        <div class="field button">
          <input type="submit" name="submit" value="Send Verification">
        </div>
      </form>
    </section>
  </div>
</body>