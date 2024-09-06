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
.slider-container {
    margin-top: 880px; /* Adjust the negative margin to remove the space above the slider */
}



.slider {
    width: 100%; /* Make the slider occupy the full width of its container */
    padding-top: 0; /* Remove padding from the top */
    padding-left: 0; /* Remove padding from the left */
    padding-right: 0; /* Remove padding from the right */
}

</style>

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
                        <li><a href="admin.php">Profile</a></li>
                        <form action="" method="post"> <!-- Removed action attribute to submit the form to the same page -->
        <input type="submit" name="logout" value="Logout">
    </form>
</ul>
                </div>
            </div>
     </nav>



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

        <div class="Abyssseek_updates_news">
        <div class="col-sm-12 text-center">
            <h2>ABYSSSEEK SYTEM UPDATES AND NEWS</h2>
        </div>

<div class="slider-container">
    <div class="slider">
        <?php include 'slider.php';?>
    </div>
</div>


</div>

<?php include_once "footer.php"; ?>


