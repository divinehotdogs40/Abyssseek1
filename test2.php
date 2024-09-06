<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Picture Circle</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }

        .profile-picture-container {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
            border: 3px solid #4CAF50; /* Optional: Add a border for better visibility */
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #fff; /* Optional: Background color if the image has transparency */
        }

        .profile-picture {
            width: 100%;
            height: 100%;
            object-fit: cover; /* Ensures the image covers the container without distortion */
        }
    </style>
</head>
<body>
    <div class="profile-picture-container">
        <img src="profile-picture.jpg" alt="User Profile Picture" class="profile-picture">
    </div>
</body>
</html>
