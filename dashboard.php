<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <style>
@font-face {
    font-family: 'main';
    src: url('files/assets/fonts/JetBrainsMonoNL-ExtraLight.ttf') format('truetype');
  }

body {
    resize: none;
    font-family: main, sans-serif;
    background-image: url('files/assets/bg.jpg');
    background-size: 100% 100%; 
    background-position: center; 
    background-repeat: no-repeat;
    margin: 0;
    padding: 0;
    display: flex;

 
    height: 100vh; 
    }
    .button {
      font-family: main, sans-serif;
      display: inline-block;
      padding: 10px 20px;
      background-color: #007bff;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s ease;
      position: fixed;
      top: 100px;
      left: 500px;
    }
    </style>
</head>
<body>
    <h2 style="position: fixed; top: 425px; left: 790px;">Welcome, </h2> <h2 style="color: cyan; position: fixed; top: 425px; left: 920px;"><?php echo $_SESSION['username']; ?></h2>
    <button class="button" onclick="window.location.href='logout.php';" style="position: fixed; top: 485px; left: 790px;">Logout</button>
    <button class="button" onclick="window.location.href='index-webcrawler.php';" style="position: fixed; top: 485px; left: 890px;">Abyssseek Webcrawler</button>
</body>
</html>
