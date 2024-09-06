<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MP4 Video Background</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            overflow: hidden;
            background-color: #287bbf;
        }

        .video-background{
            margin: 0;
  position: absolute;
  top: 50%;
  left: 50%;
  -ms-transform: translate(-50%, -50%);
  transform: translate(-50%, -50%);
        }

    </style>
</head>
<body>
    <video class="video-background" autoplay loop muted playsinline>
        <source src="images/homepage.mp4" type="video/mp4">

    </video>


</body>
</html>
