<?php
// Start the session
session_start();


// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "abyssseek";


// Check if the session variable is set
if (!isset($_SESSION['Email'])) {
    die("User ID is not set in session.");
}

$id = $_SESSION['ID'];

try {
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check if a file was uploaded
        if(isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0) {
            // Generate a unique filename for the uploaded image
            $imageFilename = time() . '_' . basename($_FILES["photo"]["name"]);

            // Move the uploaded file to the specified directory
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], "uploads/" . $imageFilename)) {
                // Prepare the SQL statement to update the image filename in the database
                $update_sql = "UPDATE created_account SET ImageFilename = ? WHERE ID = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("si", $imageFilename, $id);

                // Execute the SQL statement
                if ($update_stmt->execute()) {
                    echo "Image uploaded successfully.";
                    // Refresh the page to display the updated image
                    header("Location: {$_SERVER['PHP_SELF']}");
                    exit();
                } else {
                    echo "Error updating image filename in the database: " . $conn->error;
                }
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            echo "No file uploaded.";
        }
    }

    // Fetch user data including the image filename from the database
    $sql = "SELECT * FROM created_account WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result === false) {
        throw new Exception("Error fetching user data: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id = $row["ID"];
        $email = $row["Email"];
        $imageFilename = $row["ImageFilename"];
        $position = $row["Position"];
        $First_Name = $row["First_Name"];
        $Last_Name = $row["Last_Name"];
    } else {
        echo "0 results";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

// Check if the logout form is submitted
if (isset($_POST['logout'])) {
  // Set the timezone to Philippine time
  date_default_timezone_set('Asia/Manila');

  // Get current date and time
  $logout_time = date('Y-m-d H:i:s');

  // Get the username from the session
  $email = $_SESSION['email'];

  // Update the logout time in the database
  $sql = "UPDATE login SET logout_time = '$logout_time' WHERE email = '$email' AND logout_time IS NULL";
  if ($conn->query($sql) === TRUE) {
      echo "User time-out recorded to database successfully.";
  } else {
      echo "Error updating record: " . $conn->error;
  }

  // Destroy the session
  session_destroy();

  // Redirect to the login page
  header("Location: login.php");
  exit;
}

// Check if the logout form is submitted
if (isset($_POST['logout'])) {
    // Get the username from the session
    $email = $_SESSION['email'];

    // Update user status to offline
    $sql_update_status = "UPDATE user_status SET status = 'Offline' WHERE email = ?";
    $stmt_update_status = $conn->prepare($sql_update_status);
    $stmt_update_status->bind_param("s", $email);
    $stmt_update_status->execute();
    $stmt_update_status->close();

    // Destroy the session
    session_destroy();

    // Redirect to the login page
    header("Location: login.php");
    exit;
}

    // Fetch user data including the image filename from the database
    $sql = "SELECT * FROM created_account WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result === false) {
        throw new Exception("Error fetching user data: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id = $row["ID"];
        $email = $row["Email"];
        $imageFilename = $row["ImageFilename"];
        $position = $row["Position"];
        $First_Name = $row["First_Name"];
        $Last_Name = $row["Last_Name"];
    } else {
        echo "0 results";
    }

$conn->close();
?>




<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Abyssseek</title>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/style.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.0/font/bootstrap-icons.css" rel="stylesheet">
        <script src="assets/js/jquery-1.10.2.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="assets/js/custom.js"></script>
<style>

body {
                color: #ffff; 
                font-family: 'Courier New', Courier, monospace; 
                margin-bottom: 200px;   
                background: radial-gradient(circle, #3b8898 5%, #284a6f 25%, #151b3d 50%, #0a0a24 90%, #000000 100%);
                background-size: contain;
                font-size: 18px;
                height: 100%;   
    
            }   

            .navbar {
              display:flex;
              justify-content: flex-end;
              background-color: rgba(0, 0, 0, 1);
              color: #45acab; /* Green text color */
            }
            .navbar-nav li a, .navbar-brand {
                color: #45acab !important; /* Green text color */
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

            .logo-container {
                display: flex;
                justify-content: center;
                align-items: center;
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

.in{
  font-size: 23px;
}

.display-4{
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
.user-email-label {
    margin-left: 5px; /* Adjust spacing between image container and label */
    font-size: 14px; /* Adjust font size */
    color: #333; /* Adjust color */
}
.imageContainer {
    position: absolute;
    bottom: 50%; /* Adjust vertically */
    right: 200px; /* Adjust horizontally */
    transform: translateY(50%); /* Align vertically */
    width: 40px; /* Adjust width */
    height: 40px; /* Adjust height */
    border: 1px solid #ccc;
    overflow: hidden;
    border-radius: 30%;
    background-image: url('your-image-url.jpg'); /* Replace 'your-image-url.jpg' with the URL of your image */
    background-size: cover;


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
}

.dropdown-menu {
    min-width: 90%;
    background-color: rgba(255, 255, 255, 0.9); /* Transparent white background */
    border: 1px solid #ddd;
    border-radius: 4px;
    box-shadow: 0 3px 5px rgba(0, 0, 0, 0.125);
    margin-right: 0; /* Remove the right margin */
    right: 5%; /* Align with the right edge of the dropdown button */
    top: 70%; /* Align below the dropdown button */
    text-align: center;
}

.dropdown-item {
    color: #45acab;
    display: block;
    width: auto;
    clear: both;
    text-align: inherit;
    white-space: nowrap;
    background-color: transparent;
    border: 0;
    padding: 10px 10px;
    font-size: 14px;
    line-height: 1.42857143;
}

.dropdown-item:hover {
    background-color: #808080;
    box-shadow: 0px 15px 20px rgba(46, 229, 157, 0.4);
    color: #fff;
}


.btn.btn-default.btn-sm.dropdown-toggle::after {
    content: none !important; /* Remove the arrow down icon */
}
/* Modal styles */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 9999; /* Sit on top */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content/Box */
.modal-content {
  background-color: #fefefe;
  margin: 15% auto; /* 15% from the top and centered */
  padding: 20px;
  border: 1px solid #888;
  width: 80%; /* Could be more or less, depending on screen size */
}

/* Close Button */
.close {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}
#userActivitiesContainer.activity-feed {
    background-color: #fff; /* Background color */
    border: 2px solid #45acab; /* Border color */
    border-radius: 8px; /* Border radius */
    padding: 20px; /* Padding inside the container */
    margin-top: 20px; /* Margin from the top */
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); /* Box shadow */
}

#userActivitiesContainer.activity-feed h2 {
    margin-top: 0; /* Remove default margin */
    font-size: 24px; /* Heading font size */
    color: #333; /* Heading color */
}

#userActivitiesContainer.activity-feed p {
    margin-bottom: 10px; /* Bottom margin for paragraphs */
    font-size: 16px; /* Paragraph font size */
    color: #666; /* Paragraph color */
}

</style>
</head>
<body>
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">ABYSSSEEK</a>
        </div>
        
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li><a href="index.php">Home</a></li>
                <li><a href="guide.php">Guide</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="contact.php">Contact Us</a></li>

                

                <div class="dropdown" style="position: absolute; bottom: -25px; right: 15px;">
                <button class="btn btn-default btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <div id="imageContainer" class="imageContainer"></div> 
    <p style="text-align: center; margin: 0;"><strong></strong> <?php echo $First_Name," ",$Last_Name; ?></p>

</button>
    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
<li style="width: 100%;">
<button class="dropdown-item" onclick="document.getElementById('fileToUpload').click();" style="width: 100%;">Upload!</button>
<input type="file" name="fileToUpload" id="fileToUpload" style="display: none;">
</li>
        <li><a class="dropdown-item profile-link" href="profile.php">Profile</a></li>
        <li><a id="userActivitiesContainer"class= "dropdown-item" href="#">View Activities</a></li>
        <li role="separator" class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="help.php">Help</a></li>
        
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

<div class="container">
        <div class="user-info-container">
            <!-- Your existing user information -->
            <button id="viewActivitiesBtn">View Activities</button>
        </div>

        <!-- Container for user activities (initially hidden) -->
        <div id="userActivitiesContainer" class="activity-feed" style="display:none;">
            <h2>User Activity Feed</h2>
            <!-- Add your user activities here -->
            <p>User activity 1</p>
            <p>User activity 2</p>
            <!-- Adjust with your actual user activities -->
        </div>
    </div>
<div class="container-fluid" style="margin-top:50px">
    <div class="row">
        <div class="col-sm-3 text-justify text-center">
            <h1 class="display-4">About Abyssseek</h1> <br>
            <p class="in"><strong>Abyssseek.com</strong> serves as a platform where developers build, launch, and share tools for web scraping, data extraction, and web automation in user-friendly formats for Armed Forces of the Philippines (AFP) personnel.</p>
            <div class="text-center" style="padding-top:115px">
                <button class="button" role="button" ><a href="about.php" style="text-decoration:none; color: #ffffff">Read More.... <span></span><span></span><span></span><span></span></button></a>
            </div>
        </div>
        <div class="col-sm-6 text-center">
        <div class="logo-container">
            <img src="images/logo2.png" class="img-fluid" alt="Logo">
</div>
    </div>
        <div class="col-sm-3 text-justify text-center">
            <h1 class="display-4">Welcome seekers!</h1> <br>
            <p class="in">Are you new here? Here's how to get started<br> 
              <br><strong>*Signing up for account.</strong>
              <br>Access the form to request an account from the admin. <br> <br> 
              <br><strong>*Account Approval and Access</strong> Receive an email from the admin containing instructions on how to activate your account.</p>
            <div class="text-center">
                <button class="button" role="button"><a href="guide.php" style="text-decoration:none; color: #ffffff">Read More.... <span></span><span></span><span></span><span></span></button></a>
            </div>
        </div>
    </div>
</div>

<script>
   
   window.onload = function() {
            // Add event listener to the button
            document.getElementById('viewActivitiesBtn').addEventListener('click', function() {
                // Toggle the display property of the user activities container
                var userActivitiesContainer = document.getElementById('userActivitiesContainer');
                if (userActivitiesContainer.style.display === 'none') {
                    userActivitiesContainer.style.display = 'block';
                } else {
                    userActivitiesContainer.style.display = 'none';
                }
            });
        };
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<?php include_once "footer.php"; ?>
