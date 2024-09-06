
<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "abyssseek";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start the session
session_start();

// Check if the logout form is submitted
if (isset($_POST['logout'])) {
    // Get the email from the session
    $email = $_SESSION['email'];

    // Set the timezone to Philippine time
    date_default_timezone_set('Asia/Manila');

    // Get current date and time
    $logout_time = date('Y-m-d H:i:s');

    // Update the logout time in the database using prepared statement
    $sql = "UPDATE login SET logout_time = ? WHERE email = ? AND logout_time IS NULL";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $logout_time, $email);
    
    if ($stmt->execute()) {
        // Change user status to 'Offline' in user_status table using prepared statement
        $sql_update_status = "UPDATE user_status SET status = 'Offline' WHERE email = ?";
        $stmt_update_status = $conn->prepare($sql_update_status);
        $stmt_update_status->bind_param("s", $email);
        
        if ($stmt_update_status->execute()) {
            echo "User time-out recorded to database successfully.";
        } else {
            echo "Error updating user status: " . $stmt_update_status->error;
        }
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    // Destroy the session
    session_destroy();

    // Redirect to the login page
    header("Location: login.php");
    exit;
}


// Fetch user's first name and last name from created_account table
$email = $_SESSION['email']; // Assuming email is stored in session
$sql_fetch_user = "SELECT First_Name, Last_Name FROM created_account WHERE email = ?";
$stmt_fetch_user = $conn->prepare($sql_fetch_user);
$stmt_fetch_user->bind_param("s", $email); 
$stmt_fetch_user->execute();
$result = $stmt_fetch_user->get_result();

if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        $first_name = $row["First_Name"];
        $last_name = $row["Last_Name"];
    }
} else {
    echo "0 results";
}



?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Abyssseek</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-..." crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="assets/js/jquery-1.10.2.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="assets/js/custom.js"></script>
    <style>
        @font-face{
            font-family: 'main';
            src: url('files/assets/fonts/Rubik-Light.ttf');
        }

        body {
            color: #ffff;
            font-family: main, sans-serif, monospace;
            margin-bottom: 200px;
            background: radial-gradient(circle, #3b8898 5%, #284a6f 25%, #151b3d 50%, #0a0a24 90%, #000000 100%);
            background-size: contain;
            font-size: 18px;
            height: 100%;
            background-position: center; /* Align the background image to the center */
        }

        .navbar {
            background-color: rgba(0, 0, 0, 1);
            color: #45acab; /* Green text color */
        }

        .navbar-nav li a  {
            color: #45acab !important; /* Green text color */
        }

        .navbar-brand {
            position: absolute;
            left: 95px; /* Adjust this value to move the text more to the left */
            color: #45acab !important;
        }

        .navbar-nav li a:hover {
            color: gray !important; /* Green text color on hover */
            text-decoration: none !important; /* Remove underline on hover */
        }

        .navbar-brand:hover {
            color: #45acab !important; /* Green text color on hover */
            text-decoration: none !important; /* Remove underline on hover */
        }

        .input-style {
            padding: 10px;
            border: 2px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            color: #555;
            outline: none;
            width: 500px;
        }

        .input-style:focus {
            border-color: #45acab;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .toggle-switch {
            position: relative;
            width: 100px;
            height: 50px;
            --light: #d8dbe0;
            --dark: #28292c;
            --link: rgb(27, 129, 112);
            --link-hover: rgb(24, 94, 82);
        }

        .switch-label {
            position: absolute;
            width: 100%;
            height: 50px;
            background-color: var(--dark);
            border-radius: 25px;
            cursor: pointer;
            border: 3px solid var(--dark);
        }

        .footer {
            background-color: rgba(0, 0, 0, 1);
            padding: 10px;
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            color: #45acab;
            z-index: 9999; /* Ensure the footer stays on top */
            font-size: 14px;
        }

        .iframe-container {
            position: absolute;
            top: 0;
            left: 50px; /* Adjust the left value to position the iframe beside the sidebar */
            width: 100%; /* Set the width to fill the remaining space */
            height: 100%;
            display: none; /* Initially hide the iframe */
            overflow: hidden;
            transition: width 0.3s ease;
            z-index: -10;
        }


        .typing-box {
            border: 2px solid #ccc;
            padding: 20px;
        }

        .typing-box p {
            border-right: 2px solid #000;
            white-space: nowrap;
            overflow: hidden;
            width: 0;
        }

        @keyframes typing {
            from { width: 0 }
            to { width: 100% }
        }

        .button {
            cursor: pointer;
            --c: #0ea5e9;
            padding: 12px 28px;
            margin: 1em auto;
            position: relative;
            min-width: 12em;
            background: transparent;
            font-size: 12px;
            font-weight: bold;
            color: #ffffff;
            text-align: center;
            text-transform: uppercase;
            font-family: sans-serif;
            letter-spacing: 0.1em;
            border: 2px solid #45acab;
            border-radius: 8px;
            overflow: hidden;
            z-index: 1;
            transition: 0.5s;
        }

        .button span {
            position: absolute;
            width: 25%;
            height: 100%;
            background-color: #45acab !important;
            transform: translateY(150%);
            border-radius: 50%;
            left: calc((var(--n) - 1) * 25%);
            transition: 0.5s;
            transition-delay: calc((var(--n) - 1) * 0.1s);
            z-index: -1;
        }

        .button:hover {
            color: black;
        }

        .button:hover span {
            transform: translateY(0) scale(2);
        }

        .button span:nth-child(1) {
            --n: 1;
        }

        .button span:nth-child(2) {
            --n: 2;
        }

        .button span:nth-child(3) {
            --n: 3;
        }

        .button span:nth-child(4) {
            --n: 4;
        }

        .in {
            font-size: 23px;
        }

        .display-4 {
            border-bottom: 1px solid #45acab;
        }

        .button2 {
            padding: 1.5em 5em;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 2.5px;
            font-weight: 500;
            color: #000;
            background-color: #45acab;
            border: none;
            border-radius: 45px;
            box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease 0s;
            cursor: pointer;
            outline: none;
        }

        .button2:hover {
            background-color: #41C9E2;
            box-shadow: 0px 15px 20px rgba(46, 229, 157, 0.4);
            color: #fff;
            transform: translateY(-7px);
        }

        .button2:active {
            transform: translateY(-1px);
        }

    

        .sidebar ul {
            padding: 0;
            margin: 0;
            list-style: none;
            flex-grow: 1;
        }
        
        .bottom-links {
            position: absolute;
            bottom: 30px;
            width: 100%
        }

        .sidebar ul li {
            padding: 10px;
            text-align: center;
        }

        .sidebar ul li a {
            color: #ffffff;
            text-decoration: none;
        }

        .sidebar ul li a:hover {
            color: #45acab;
        }

        .logout-button {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px;
            text-align: center;
        }

        .logout-button:hover {
            background-color: #45acab;
        }

    .dropdown-menu {
    min-width: 100%; /* Set the minimum width to 100% */
    max-width: 100%; /* Set the maximum width to 100% */
    background-color: #808080; /* Adjust background color */
    border: none; /* Remove border */
    border-radius: 3px;
    box-shadow: 0 3px 5px rgba(0, 0, 0, 0.125);
    margin-right: -20px; /* Remove the right margin */
    left: 0; /* Align with the left edge of the dropdown button */
    top: 70%; /* Align below the dropdown button */
    text-align: center;
}

        

        .sidebar:hover{
            transform: translateX(80%); /* Move the sidebar out of the viewport to the left */
        } 

        .dropdown-menu li {
            padding: 5px 0;
        }
        
        .dropdown-menu li a {
            color: #ffffff;
            text-decoration: none;
            
        }
        
        /* Show the dropdown menu when the dropdown button is hovered */
        .dropdown:hover .dropdown-menu {
            display: block;
        }
        .iframe-container {
            position: absolute;
            top: 0;
            left: 50px; /* Adjust the left value to position the iframe beside the sidebar */
            width: 100%; /* Set the width to fill the remaining space */
            height: 100%;
            display: none; /* Initially hide the iframe */
            overflow: hidden;
            transition: width 0.3s ease;
            z-index: -10;
        }


        .iframe-container iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        .iframe-container-closed {
            width: 100%;
            left: 0;
        }


        .button-with-icon {
        display: inline-flex;
        align-items: center;
        background: radial-gradient(circle, #3b8898 5%, #284a6f 25%, #151b3d 50%, #0a0a24 90%, #000000 100%);
        }

        .button-with-icon img {
            margin-left: 5px; /* Adjust the margin as needed */
        }

        .sidebar.sidebar-closed {
    transform: translateX(-250px); /* Move the sidebar out of the viewport to the left */
}
        body {
            background-image: url('images/homepagelogo.gif');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center top; /* Adjusted to position the image at the bottom center */
        }

        .additional-button {
            position: absolute;
            top: 28.5%; /* Adjust the vertical position as needed */
            left: 250px; /* Adjust the horizontal position as needed */
            transform: translateY(-50%);
        }

        .additional-button img {
            /* Adjust the positioning as needed */
            margin-left: 24px; /* Example: Moves the button 10px to the right */
            margin-top: 24px; /* Example: Moves the button 5px down */
        }

        
        
        .user-profile-picture {
            width: 43px; /* Set the width of the profile picture */
            height: 43px; /* Set the height of the profile picture */
            position: absolute;
            top: 4px; /* Adjust the top position as needed */
            right: 200px; /* Adjust the right position as needed */
        }

            .user-container {
            position: absolute;
            top: 50%;
            right: 10px; /* Adjust the right position as needed */
            transform: translateY(-50%);
        }

        .user-name {
    color: white; /* Set the color of the user's name */
    font-size: 21px; /* Adjust the font size of the user's name */
    position: relative; /* Change position to relative */
    padding-left: 60px; /* Add padding to accommodate the imageContainer */
}

.imageContainer {
    transform: translateY(-50%); /* Adjusted translateY value */
    width: 40px; /* Adjust width */
    height: 40px; /* Adjust height */
    border: 1px solid #ccc;
    overflow: hidden;
    border-radius: 50%; /* Adjusted border-radius value */
    background-size: cover;
    background-position: center; /* Center the background image */
    background-repeat: no-repeat; /* Ensure the background image does not repeat */
    position: absolute;
    top: 50%; /* Adjust the top position to 50% */
    left: 10px; /* Adjust the left position as needed */
}




.dropdown-toggle.dropdown::after {
    display: none !important; /* Hide the arrow down icon */
}

.dropdown {
    color: #45acab;
    position: absolute;
    display: block;
    padding: 10px;
}

.dropdown-toggle {
    color: white;
    cursor: pointer;
    background-color: transparent;
    padding: 8px 50px; /* Adjust padding */
    font-size: 15px; /* Adjust font size */
    margin-top: 17px;
    height: auto; /* Adjust height */
    line-height: 2; /* Reset line height */
    margin-bottom: 17px;
    border: none; /* Remove border */
}

.dropdown-menu {
    min-width: 95%;
    background-color: gray; /* Adjust background color */
    border: none; /* Remove border */
    border-radius: 3px;
    box-shadow: 0 3px 5px rgba(0, 0, 0, 0.125);
    margin-right: 0; /* Remove the right margin */
    right: 5%; /* Align with the right edge of the dropdown button */
    top: 70%; /* Align below the dropdown button */
    text-align: center;
}

.dropdown-item {
    font-size: 20px;
    color: black;
    display: block;
    width: auto;
    clear: both;
    text-align: inherit;
    white-space: nowrap;
    background-color: transparent;
    border: 0;
    padding: 10px 10px;
    font-size: 12px;
    line-height: 1.42857143;
}

.dropdown-item:hover {
    background-color: #fff;
    box-shadow: 0px 15px 20px rgba(46, 229, 157, 0.4);
    color: #000;
}

.btn.btn-default.btn-sm.dropdown-toggle::after {
    content: none !important; /* Remove the arrow down icon */
}

        .sidebar ul li a.button-with-icon {
            background-color: #808080; /* Add background color */
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px;
            border-radius: 20px;
            margin-bottom: 5px; /* Add margin between buttons */
        }
        .dropdown-menu {
            background-color: #45acab; /* Add background color */
            padding: 10px;
            border-radius: 5px;

        }
        .dropdown-menu li {
            position: center;
            padding-right: 30px;
            font-size: 10px;
            padding: 5px 0;
        }

        .sidebar {
            position: fixed; /* Fix the sidebar position */
            top: 0; /* Align the top of the sidebar with the top of the screen */
            left: 0%; /* Align the sidebar to the left side of the screen */
            width: 300px; /* Set the width of the sidebar */
            height: 100%; /* Make the sidebar full height */
            background-color: black; /* Adjust background color as needed */
            padding-top: 50px; /* Add padding to the top of the sidebar */
            transition: transform 0.3s ease; /* Add transition effect */
        }

        .sidebar.sidebar-closed {
            transform: translateX(-82%); /* Move the sidebar out of the viewport to the left */
        }

        .toggle-sidebar-btn {
            position: absolute;
            top: 10px;
            left: 200px;
            background-color: transparent;
            border: none;
            outline: none;
            cursor: pointer;
            width: 40px;
            height: 40px;
            display: flex; /* Add display flex */
            justify-content: center; /* Center the content horizontally */
            align-items: center; /* Center the content vertically */
        }

    .dropdown-menu {
    min-width: 100%;
    background-color: #808080; /* Adjust background color */
    border: none; /* Remove border */
    border-radius: 3px;
    box-shadow: 0 3px 5px rgba(0, 0, 0, 0.125);
    margin-right: -20px; /* Remove the right margin */
    left: 10px; /* Adjust the position from the right */
    top: 70%; /* Align below the dropdown button */
    text-align: center;
}



/* Hide default HTML checkbox */
input[type=checkbox] {
  height: 0;
  width: 0;
  visibility: hidden;
}

/* Switch label */
label {
  cursor: pointer;
  text-indent: -9999px;
  width: 50px; /* Width of the switch button */
  height: 25px; /* Height of the switch button */
  background: grey;
  display: block;
  border-radius: 50px;
  position: relative;
  top: -10px;
  left: 2350%; /* Move the switch button to the left */
}

/* Slider */
label:after {
  content: '';
  position: absolute;
  top: 5px;
  left: 5px; /* Move the slider a little to the right */
  width: 15px; /* Size of the slider */
  height: 15px; /* Size of the slider */
  background: #fff;
  border-radius: 90px;
  transition: 0.3s;
}

/* Change background color when checked */
input:checked + label {
  background: blue;
}

/* Move slider to the right when checked */
input:checked + label:after {
  left: calc(100% - 5px);
  transform: translateX(-100%);
}

/* Increase size of slider when active */
label:active:after {
  width: 50px;
}


#vpn-text {
    position: absolute;
    top: 11px; /* Adjust top position as needed */
    right: 340px; /* Adjust right position as needed */
    color: #ffffff; /* Text color */
}



.notification-box {
  position: fixed;
  z-index: 99;
  top: 20px;
  right: 10px;
  width: 30px;
  height: 30px;
  text-align: center;   
}
.notification-bell {
  animation: bell 1s 1s both infinite;
}
.notification-bell * {
  display: block;
  margin: 0 auto;
  background-color: #fff;
  box-shadow: 0px 0px 15px #fff;
}
.bell-top {
  width: 3px;
  height: 3px;
  border-radius: 3px 3px 0 0;
}
.bell-middle {
  width: 12px;
  height: 12px;
  margin-top: -1px;
  border-radius: 12.5px 12.5px 0 0;
}
.bell-bottom {
  position: relative;
  z-index: 0;
  width: 15px;
  height: 2px;
}
.bell-bottom::before,
.bell-bottom::after {
  content: '';
  position: absolute;
  top: -4px;
}
.bell-bottom::before {
  left: 1px;
  border-bottom: 4px solid #fff;
  border-right: 0 solid transparent;
  border-left: 4px solid transparent;
}
.bell-bottom::after {
  right: 1px;
  border-bottom: 4px solid #fff;
  border-right: 4px solid transparent;
  border-left: 0 solid transparent;
}

.bell-rad {
  width: 6px;
  height: 4px;
  margin-top: 1px;
  border-radius: 0 0 4px 4px;
  animation: rad 1s 2s both infinite;
}
.notification-count {
  position: absolute;
  z-index: 1;
  top: -4px;
  right: -6px;
  width: 15px;
  height: 15px;
  line-height: 15px;
  font-size: 15px;
  border-radius: 50%;
  background-color: #ff4927;
  color: #fff;
  animation: zoom 3s 3s both infinite;
}
@keyframes bell {
  0% { transform: rotate(0); }
  10% { transform: rotate(30deg); }
  20% { transform: rotate(0); }
  80% { transform: rotate(0); }
  90% { transform: rotate(-30deg); }
  100% { transform: rotate(0); }
}
@keyframes rad {
  0% { transform: translateX(0); }
  10% { transform: translateX(6px); }
  20% { transform: translateX(0); }
  80% { transform: translateX(0); }
  90% { transform: translateX(-6px); }
  100% { transform: translateX(0); }
}
@keyframes zoom {
  0% { opacity: 0; transform: scale(0); }
  10% { opacity: 1; transform: scale(1); }
  50% { opacity: 1; }
  51% { opacity: 0; }
  100% { opacity: 0; }
}
@keyframes moon-moving {
  0% {
    transform: translate(-200%, 600%);
  }
  100% {
    transform: translate(800%, -200%);
  }
}

</style>

<script>

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('fileToUpload').addEventListener('change', function(event) {
        var file = event.target.files[0];
        var reader = new FileReader();

        reader.onload = function(e) {
            var imageData = e.target.result;
            var imageContainer = document.querySelector('.imageContainer');
            imageContainer.style.backgroundImage = "url('" + imageData + "')";
        };

        reader.readAsDataURL(file);
    });
});


</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>

document.addEventListener('DOMContentLoaded', function() {
    // Load profile picture from database on page load
    loadProfilePicture();

    document.getElementById('fileToUpload').addEventListener('change', function(event) {
        var file = event.target.files[0];
        var formData = new FormData();
        formData.append('file', file);

    
        fetch('upload_profile_picture.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
          
                loadProfilePicture();
            } else {
                console.error('Error uploading profile picture:', data.message);
            }
        })
        .catch(error => {
            console.error('Error uploading profile picture:', error);
        });
    });
});


// Function to load profile picture
function loadProfilePicture() {
    fetch('get_profile_picture.php')
    .then(response => {
        if (!response.ok) {
            throw new Error('Failed to fetch profile picture');
        }
        return response.json();
    })
    .then(data => {
        if (data.success && data.imageUrl) {
            var imageContainer = document.querySelector('.imageContainer');
            imageContainer.style.backgroundImage = "url('" + data.imageUrl + "')";
        } else {
            var imageContainer = document.querySelector('.imageContainer');
            imageContainer.style.backgroundImage = "url('default_profile_picture.webp')"; // Default profile picture
        }
    })
    .catch(error => {
        console.error('Error loading profile picture:', error);
    });
}



</script>

    <script>
        // JavaScript function to load page into iframe
        function loadPage(url) {
            var logo = document.getElementById('logo-container-main');
            var iframeContainer = document.getElementById('iframeContainer');
            var iframe = iframeContainer.querySelector('iframe');
            iframe.src = url;
            iframeContainer.style.display = 'block'; // Show the iframe container
            logo.style.display = 'none';
            

        }
    </script>

        <script>
            // JavaScript to toggle dropdown menu
            document.addEventListener("DOMContentLoaded", function() {
                var dropdownToggle = document.querySelector(".dropdown-toggle");
                dropdownToggle.addEventListener("click", function() {
                    var dropdownMenu = this.nextElementSibling;
                    dropdownMenu.classList.toggle("show");
                });
            });
        </script>

<script>
    // Initialize notification count
    let notificationCount = 3; // Set default count to 3

    // Function to update notification count
    function updateNotificationCount(count) {
        const notificationCountElement = document.getElementById("notificationCount");
        notificationCountElement.textContent = count;
        notificationCountElement.style.display = count > 0 ? "block" : "none";
    }

    // Simulate receiving a notification
    function receiveNotification() {
        notificationCount++;
        updateNotificationCount(notificationCount);
    }

    // Call receiveNotification function to simulate initial notification count
    updateNotificationCount(notificationCount);

    // Example: Simulate receiving a notification after 3 seconds
    setTimeout(receiveNotification, 3000);
</script>


<script>
    // Function to toggle the sidebar
    function toggleSidebar() {
        var sidebar = document.querySelector('.sidebar');
        sidebar.classList.toggle('sidebar-closed'); // Toggle the class for closing
    }

    // Add event listener to the additional button
    var additionalButton = document.querySelector('.additional-button');
    additionalButton.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent the default behavior of the link
        toggleSidebar(); // Toggle the sidebar
    });
</script>


</head>
<body>






<nav class="navbar navbar-inverse navbar-fixed-top" id="custom-navbar">
    <div class="container">
        <div class="navbar-header">

        <a href="" class="additional-button" onclick="toggleSidebar(); return false;">
                    <img src="images/menu.png" alt="Additional Button Image" class="additional-button-img">


            <a class="navbar-brand" href="index_in.php">ABYSSSEEK</a>

            


            <h4 id="vpn-text">ABYSSSEEK VPN</h4>
            <div class="notification-box">

    <span class="notification-count">6</span>
    <div class="notification-bell">
      <span class="bell-top"></span>
      <span class="bell-middle"></span>
      <span class="bell-bottom"></span>
      <span class="bell-rad"></span>
    </div>
  </div>

  
    

            
            <input type="checkbox" id="switch" />
        <label for="switch"></label>





     <div class="dropdown" style="position: absolute; bottom: -25px; right: -10px;">
<button class="btn btn-default btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <!-- Move the image container inside the button -->
    <div id="imageContainer" class="imageContainer"></div> 
    <?php
        // Check if first name and last name are set
        if(isset($first_name) && isset($last_name)) {
            // Output the button text with the user's name
            echo $first_name . " " . $last_name;
        } else {
            // If first name or last name is not set, display a default text
            echo 'User';
        }
    ?>
</button>

<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                    <li>
                        <a href="#" class="dropdown-item profile-link" onclick="document.getElementById('fileToUpload').click();">
                            Change Picture
                        </a>
                        <input type="file" name="fileToUpload" id="fileToUpload" style="display: none;">
                    </li>
        <li> <a class="dropdown-item profile-link" href="profile.php" style="color: black;">Edit Profile</a></li>
        <li><a id="viewAvitiesBtn" class="dropdown-item" href="#" style="color: black;">View Activity</a></li>
        <li role="separator" class="dropdown-divider"></li>
        <a class="dropdown-item" href="help.php" style="color: black;">Help</a>
        <li style="width: 100%;">
            <form action="" method="post" style="width: 100%;">
                <button type="submit" class="dropdown-item" name="logout" style="width: 100%;">Logout</button>
            </form>
        </li>
    </ul>
</div>

            </div>
        </div>
    </nav>

     


<div class="sidebar">
    <ul>
        <li>
            <a href="javascript:void(0)" onclick="loadPage('/abyssseek/user_dashboard.php')" class="button-with-icon">
                Dashboard
                <img src="images/dashboard.png" alt="Dashboard" width="24" height="24">
            </a>
        </li>
        <li>
            <a href="javascript:void(0)" onclick="loadPage('/abyssseek/1temporaryimage/temporary.php')" class="button-with-icon">
                Look Up
                <img src="images/lookup.png" alt="Dashboard" width="24" height="24">
            </a>
        </li>
        <li>
            <a href="javascript:void(0)" onclick="loadPage('/abyssseek/social_media_finder/html.php')" class="button-with-icon">
                Social Media
                <img src="images/socialmedia.png" alt="Social Media" width="24" height="24">
            </a>
        </li>
        <li>
            <a href="javascript:void(0)" onclick="loadPage('/abyssseek/index-webcrawler.php')" class="button-with-icon">
                Web Crawler
                <img src="images/crawler.png" alt="Web Crawler" width="24" height="24">
            </a>
        </li>
        <li>
            <a href="javascript:void(0)" onclick="loadPage('/abyssseek/webscraper/popup.html')" class="button-with-icon">
                Web Scraper
                <img src="images/scraper.png" alt="Web Scraper" width="24" height="24">
            </a>
        </li>
        <li class="dropdown"> 
            <a href="#" class="dropdown-toggle button-with-icon">
                History
                <img src="images/history.png" alt="History" width="24" height="24">
            </a> <!-- Added onclick event -->
            <ul class="dropdown-menu"> <!-- Added a class for dropdown-menu -->

                <li><a href="javascript:void(0)" onclick="loadPage('history_look_up.php')">Look Up</a></li>
                <li><a href="javascript:void(0)" onclick="loadPage('history_social_media.php')">Social Media</a></li>
                <li><a href="javascript:void(0)" onclick="loadPage('history_webcrawler.php')">Website Crawler</a></li>
                <li><a href="javascript:void(0)" onclick="loadPage('history_web_scraper.php')">Website Scraper</a></li>
                <!-- Add more links as needed -->
            </ul>
        </li>
    </ul>

    <ul class="bottom-links">
         <li>
            <a href="javascript:void(0)" onclick="loadPage('guide.php')" class="button-with-icon">
                Guide
                <img src="images/Guide.png" alt="Guide" width="24" height="24">
            </a>
        </li>
        <li>
            <a href="javascript:void(0)" onclick="loadPage('about.php')" class="button-with-icon">
                About Us
                <img src="images/About.png" alt="About Us" width="24" height="24">
            </a>
        </li>
        <li>
            <a href="javascript:void(0)" onclick="loadPage('contact.php')" class="button-with-icon">
                Contact Us
                <img src="images/Contact.png" alt="Contact Us" width="24" height="24">
            </a>
        </li>
        
        <li>
            <form action="" method="post">
                <input type="submit" name="logout" value="Logout" class="button">
            </form>
        </li>
    </ul>
</div>




<div class="iframe-container" id="iframeContainer">
    <!-- Initially, the iframe is empty -->
    <iframe src=""></iframe>
</div>





<?php include_once "footer.php"; ?>

</body>
</html>