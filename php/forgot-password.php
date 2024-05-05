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
  <style>
    body{
      display: flex;
      height: 100vh;
      align-items:center;
      justify-content:center;
    }
    .wrapper{
      width: 600px;
      height: 350px;
      display: flex;
      align-items:center;
      justify-content:center;
      border:1px solid #ddd;
      padding: 10px;
    }
    .wrapper header{
      font-size:40px;
      font-weight:600;
    }
    .question{
      height: 300px;
      width: 300px;
    }
    section{
      display: flex;
    }
    form{
      display: flex;
      flex-direction:column;
      justify-content:center;
      align-items:center;
    }
    .field input{
      display: flex;
      flex-direction:column;
    }
    input[type=text]{
      width: 250px;
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
   
    @media screen and (max-width:480px)
    {
      section {
        display: flex;
        flex-direction: column;
      }
      .wrapper{
        height: 450px;
        width: 350px;
        display: flex;
        flex-direction: column;
      }
    }
  </style>
</head>

<body>
  <div class="wrapper">
  <section class="form login">
    <div>
     
     
      <img src="../components/images/bg1.jpg" alt="" class="question"></div>
      <form action="./send-password-reset.php" method="POST" enctype="multipart/form-data">
      <header>Forgot Password</header>
        <!-- <div class="error-text"></div> -->
       
        <div class="field input">
          <!-- <label for="email">Email Address</label> -->
          <input type="text" name="email" placeholder="Enter your email" required>
        </div>
        <div class="field button">
          <input type="submit" name="submit" value="Send Verification">
        </div>
      </form>
    </section>
  </div>
</body>