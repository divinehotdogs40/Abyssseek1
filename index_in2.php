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

    $action = isset($_GET['action']) ? $_GET['action'] : ''; // start or stop
    $openvpnConnectExecutable = "C:\\Program Files\\OpenVPN Connect\\OpenVPNConnect.exe"; // Full path to OpenVPN Connect executable
    $openvpnProfile = "C:\\abyssseekvpn\\vpnserver\\jp-free-101007.protonvpn.udp.ovpn"; // Full path to the OpenVPN profile configuration file

    if ($action == "start") {
        // Start OpenVPN Connect with the specified profile
        $command = "start \"\" \"$openvpnConnectExecutable\" --config \"$openvpnProfile\"";
        pclose(popen($command, "r"));
    } elseif ($action == "stop") {
        // Stop OpenVPN Connect
        $command = "taskkill /IM OpenVPNConnect.exe /F";
        exec($command);
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
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
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
            background-color: rgba(54, 121, 250, 1);
            color: #fff; /* Green text color */
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .navbar-nav li a  {
            color: white !important; /* Green text color */
        }

        .navbar-brand {
            position: absolute;
            left: 95px; /* Adjust this value to move the text more to the left */
            top: 3px; /* Adjust this value to move the text upwards */
            color: #ffffff !important;
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
            color: #000000;
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
    margin-right: -0px; /* Remove the right margin */
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
            top: 5px;
            left: 56px; /* Adjust the left value to position the iframe beside the sidebar */
            width: 101%; /* Set the width to fill the remaining space */
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
        }

        .button-with-icon img {
            margin-left: 5px; /* Adjust the margin as needed */
        }

        .sidebar.sidebar-closed {
            transition: transform 0.3s ease;
            transform: translateX(-250px); /* Move the sidebar out of the viewport to the left */
        }

        .sidebar.sidebar-closed:hover {
            transform: translateX(0); /* Move the sidebar into view on hover */
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

        
        .additional-button:not(:checked) + .sidebar.sidebar-closed:hover {
            transform: translateX(-250px); /* Move the sidebar out of the viewport to the left */
        }

        /* Hide the sidebar hover transition when the additional button is toggled */
        .additional-button:checked + .sidebar.sidebar-closed:hover {
            transform: translateX(-250px); /* Move the sidebar out of the viewport to the left */
        }

        /* Disable transition for sidebar when it's being shown */
        .additional-button:checked + .sidebar.sidebar-closed {
            transform: translateX(0); /* Keep the sidebar in place when shown */
            transition: none; /* Disable transition effect */
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
            background-color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px;
            border-radius: 5px;
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
            background-color: #287bff; /* Adjust background color as needed */
            padding-top: 50px; /* Add padding to the top of the sidebar */
            transition: transform 0.3s ease; /* Add transition effect */
        }

        .sidebar.sidebar-closed {
            transform: translateX(-82%); /* Move the sidebar out of the viewport to the left */
            transition: transform 0.3s ease;
        }
        
        .sidebar:not(.sidebar-closed):hover {
            transform: translateX(0); /* Keep the sidebar in place when open */
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
  display: flex;
  border-radius: 50px;
  position: fixed; /* Fixed positioning */
  top: 15px; /* Adjust this value to suit your design */
  right: 280px; /* Fixed position from the right */
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

/* Media queries for different screen sizes */
@media (max-width: 768px) {
  label {
    top: 20px; /* Adjust these values as needed */
    right: 10px; /* Adjust these values as needed */
  }
}

@media (max-width: 480px) {
  label {
    top: 30px; /* Adjust these values as needed */
    right: 10px; /* Adjust these values as needed */
  }
}





#vpn-text {
    position: absolute;
    top: 9px; /* Adjust top position as needed */
    right: 340px; /* Adjust right position as needed */
    color: #ffffff; /* Text color */
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
    console.log("Sidebar toggled"); // Add this line for debugging
}
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


      
  <div class="switch">
        <input type="checkbox" id="switch" <?php echo ($action == "start") ? 'checked' : ''; ?> onchange="toggleVPN()">
        <label for="switch" class="slider"></label>
    </div>

    <script>
        function toggleVPN() {
            var toggle = document.getElementById('switch');
            if (toggle.checked) {
                location.href = '?action=start';
            } else {
                location.href = '?action=stop';
            }
        }
    </script>

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
        <li> <a class="dropdown-item profile-link" href="javascript:void(0)" onclick="loadPage('/abyssseek/profile.php')" style="color: black;" style="color: black;">Edit Profile</a></li>
        <li><a id="viewAvitiesBtn" class="dropdown-item" href="javascript:void(0)" onclick="loadPage('/abyssseek/user_dashboard.php')" style="color: black;">View Activity</a></li>
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



















<!-- Notification Function -->

<div class="notif_pos">

<?php
// Database connection
$servername = "localhost";
$username = "root";  // Typically, 'root' is the default username for XAMPP
$password = "";      // By default, XAMPP's MySQL root user has no password
$dbname = "Abyssseek";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the number of notifications
$sql = "SELECT COUNT(*) as count FROM notifications";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$notification_count = $row['count'];

// Get the notifications
$sql = "SELECT id, message, date_time FROM notifications ORDER BY date_time DESC";
$messages_result = $conn->query($sql);
$messages = [];
while ($message = $messages_result->fetch_assoc()) {
    $messages[] = $message;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification Bell</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/5.5.2/collection/components/icon/icon.min.css" rel="stylesheet">
    <style>

        .notification {
            position: relative;
            display: inline-block;
            cursor: pointer;
        }
        .notification .badge {
            position: absolute;
            top: -10px;
            right: -10px;
            padding: 5px 10px;
            border-radius: 50%;
            background: red;
            color: white;
        }
        .notification:hover {
            background-color: #f0f0f0;
        }
        .notification-dropdown {
            display: none;
            position: absolute;
            top: 40px;
            right: -50px; /* Adjust position to align with bell */
            background-color: white;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
            max-height: 600px; /* Increase height for longer display */
            overflow-y: auto;
            width: 650px; /* Set a fixed width */
            padding: 10px; /* Add padding for better look */
        }
        .notification-dropdown .message-container {
            display: flex;
            align-items: flex-start; /* Align items at the top */
            padding: 10px;
            border-bottom: 1px solid #ddd;
            word-wrap: break-word; /* Ensure long words wrap */
            transition: background-color 0.3s; /* Smooth background color transition */
        }
        .notification-dropdown .message-container:hover {
            background-color: #f0f0f0;
        }
        .notification-dropdown .message-container:last-child {
            border-bottom: none;
        }
        .notification-buttons {
            display: flex;
            flex-direction: column;
        }
        .notification-buttons button {
            margin-bottom: 5px;
            padding: 8px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .notification-buttons button.delete {
            margin-top: 40px;
            background-color: red;
            color: white;
        }
        .notification-buttons button.reply {
            background-color: #287bff;
            color: white;
        }
        .admin-profile {
            width: 40px; /* Adjust size as needed */
            height: 40px; /* Adjust size as needed */
            border-radius: 50%;
            overflow: hidden;
            margin-right: 10px;
        }
        .admin-profile img {
            width: 100%;
            height: auto;
        }
        .message-content {
            flex: 1; /* Make message content take remaining space */
        }
        .message-content small {
            color: #999;
        }
        .message-header {
            margin-bottom: 5px; /* Space between admin info and message */
        }
        .popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            width: 400px;
            max-width: 90%;
            border-radius: 8px;
            display: none;
        }
        .popup.active {
            display: block;
        }
        .popup-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .popup-content {
            margin-bottom: 10px;
        }
        .popup textarea {
            width: 100%;
            min-height: 100px;
            resize: vertical;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .popup-buttons {
            text-align: right;
        }
        .popup-buttons button {
            margin-left: 10px;
            padding: 8px 20px;
            border: none;
            background-color: #287bff;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }
        .popup-buttons button.cancel {
            background-color: #ccc;
        }
    </style>
</head>
<body>
    <div class="notification">
        <ion-icon name="notifications-outline" size="large"></ion-icon>
        <span class="badge"><?php echo $notification_count; ?></span>
        <div class="notification-dropdown">
            <?php foreach ($messages as $message): ?>
                <div class="message-container" id="message-<?php echo $message['id']; ?>" onclick="replyMessage(<?php echo $message['id']; ?>)">
                    <div class="admin-profile">
                        <img src="admin.png" alt="Admin Profile">
                    </div>
                    <div class="message-content">
                        <div class="message-header">
                            <strong>From: Admin</strong><br>
                        </div>
                        <?php echo $message['message']; ?><br>
                        <small><?php echo $message['date_time']; ?></small>
                    </div>
                    <div class="notification-buttons">
                        <button class="delete" onclick="deleteMessage(<?php echo $message['id']; ?>)">Delete</button>
                        <button class="reply" onclick="replyMessage(<?php echo $message['id']; ?>)">Reply</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="popup" id="replyPopup">
        <div class="popup-header">
            <div><strong>From: Admin</strong></div>
            <div><small id="popupDate"></small></div>
        </div>
        <div class="popup-content">
            <p id="popupMessage"></p>
            <textarea id="replyTextarea" placeholder="Enter your reply"></textarea>
        </div>
        <div class="popup-buttons">
            <button onclick="sendReply()">Send Reply</button>
            <button class="cancel" onclick="closePopup()">Cancel</button>
        </div>
    </div>

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script>
        document.querySelector('.notification').addEventListener('click', function() {
            const dropdown = document.querySelector('.notification-dropdown');
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        });

        function deleteMessage(id) {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "delete_notification.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    if (xhr.responseText === 'success') {
                        const messageElement = document.getElementById('message-' + id);
                        messageElement.parentNode.removeChild(messageElement);
                        const badge = document.querySelector('.badge');
                        badge.textContent = parseInt(badge.textContent) - 1;
                    } else {
                        alert('Failed to delete the message.');
                    }
                }
            };
            xhr.send("id=" + id);
        }

        function replyMessage(id) {
            const messageElement = document.getElementById('message-' + id);
            const messageContent = messageElement.querySelector('.message-content').innerText;
            const messageDate = messageElement.querySelector('small').innerText;

            document.getElementById('popupMessage').innerText = messageContent.replace(/From: Admin/, '').replace(new RegExp(messageDate, 'g'), '').trim();
            document.getElementById('popupDate').innerText = messageDate;

            const popup = document.getElementById('replyPopup');
            popup.classList.add('active');
        }

        function sendReply() {
            const replyText = document.getElementById('replyTextarea').value;

            if(replyText.trim() === "") {
                alert('Reply cannot be empty.');
                return;
            }

            // Add your reply functionality here (AJAX request or form submission)
            alert('Reply sent: ' + replyText);

            closePopup();
        }

        function closePopup() {
            const popup = document.getElementById('replyPopup');
            popup.classList.remove('active');
            document.getElementById('replyTextarea').value = "";
        }
    </script>
</body>
</html>
</div>









































    

<div class="sidebar">
    <ul>
        <li>
            <a href="javascript:void(0)" onclick="loadPage('/abyssseek/user_dashboard.php')" class="button-with-icon">
                Dashboard
                <ion-icon name="home-outline"></ion-icon>
            </a> 
        </li>
        <li>
            <a href="javascript:void(0)" onclick="loadPage('/abyssseek/index-lookup.php')" class="button-with-icon">
                Look Up
                <ion-icon name="eye-outline"></ion-icon>
            </a>
        </li>
        <li>
            <a href="javascript:void(0)" onclick="loadPage('/abyssseek/social_media_finder/html.php')" class="button-with-icon">
                Social Media
                <ion-icon name="logo-instagram"></ion-icon>
            </a>
        </li>
        <li>
            <a href="javascript:void(0)" onclick="loadPage('/abyssseek/index-webcrawler.php')" class="button-with-icon">
                Web Crawler
                <ion-icon name="bug-outline"></ion-icon>
            </a>
        </li>
        <li>
            <a href="javascript:void(0)" onclick="loadPage('/abyssseek/webscraper/popup.html')" class="button-with-icon">
                Web Scraper
                <ion-icon name="documents-outline"></ion-icon>
            </a>
        </li>
        <li>
            <a href="javascript:void(0)" onclick="loadPage('/abyssseek/geolocate/index.php')" class="button-with-icon">
                IP Geolocate
                <ion-icon name="earth-outline"></ion-icon>
            </a>
        </li>
        <li class="dropdown"> 
            <a href="#" class="dropdown-toggle button-with-icon">
                History 
                <ion-icon name="book-outline"></ion-icon>
            </a> <!-- Added onclick event -->
            <ul class="dropdown-menu"> 

                <li><a href="javascript:void(0)" onclick="loadPage('history_lookup.php')">Look Up</a></li>
                <li><a href="javascript:void(0)" onclick="loadPage('history_social_media.php')">Social Media</a></li>
                <li><a href="javascript:void(0)" onclick="loadPage('history_webcrawler.php')">Website Crawler</a></li>
                <li><a href="javascript:void(0)" onclick="loadPage('history_webscraper.php')">Website Scraper</a></li>
                
            </ul>
        </li>
    </ul>

    <ul class="bottom-links">
         <li>
            <a href="javascript:void(0)" onclick="loadPage('guide.php')" class="button-with-icon">
                Guide
                <ion-icon name="library-outline"></ion-icon>
            </a>
        </li>
        <li>
            <a href="javascript:void(0)" onclick="loadPage('about.php')" class="button-with-icon">
                About Us
                <ion-icon name="help-circle-outline"></ion-icon>
            </a>
        </li>
        <li>
            <a href="javascript:void(0)" onclick="loadPage('contact.php')" class="button-with-icon">
                Contact Us
                <ion-icon name="call-outline"></ion-icon>
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