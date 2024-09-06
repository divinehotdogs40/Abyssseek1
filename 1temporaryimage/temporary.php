<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Background Page</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
        }

        .background-container {
            background-image: url('/abyssseek/1temporaryimage/a1.jpg'); /* Path to your image */
            background-size: cover;
            background-position: center;
            height: 100vh; /* Full height of the viewport */
            display: flex;
            justify-content: center;
            align-items: center;
            color: white; /* Text color on top of the background image */
            font-size: 24px; /* Adjust font size as needed */
            font-family: Arial, sans-serif; /* Adjust font family as needed */
            text-align: center;
        }
    </style>
</head>
<body>

<div class="background-container">
</div>

</body>
<?php include_once "footer.php"; ?>
</html>
