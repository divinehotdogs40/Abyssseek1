<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "abyssseek";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

// Validate the session email against the created_account table
$sql_validate_email = "SELECT Email FROM created_account WHERE Email = ?";
$stmt_validate_email = $conn->prepare($sql_validate_email);
if (!$stmt_validate_email) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}
$stmt_validate_email->bind_param("s", $email);
$stmt_validate_email->execute();
$stmt_validate_email->store_result();

if ($stmt_validate_email->num_rows == 0) {
    // Email not found in created_account table, redirect to login
    header("Location: login.php");
    exit();
}
$stmt_validate_email->close();

$imageData = null;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $phone_number = $_POST['phonenumber'];
    $position = $_POST['position']; 

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }

    $sql = "UPDATE created_account SET First_Name=?, Last_Name=?, Phone_Number=?, Position=? WHERE Email=?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    $stmt->bind_param("sssss", $firstname, $lastname, $phone_number, $position, $email);
    if ($stmt->execute()) {
        // Update successful
    }
    $stmt->close();
}

$sql_fetch_user = "SELECT First_Name, Last_Name, Phone_Number, Position FROM created_account WHERE Email = ?";
$stmt_fetch_user = $conn->prepare($sql_fetch_user);
if (!$stmt_fetch_user) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}
$stmt_fetch_user->bind_param("s", $email);
$stmt_fetch_user->execute();
$result = $stmt_fetch_user->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $first_name = $row["First_Name"];
    $last_name = $row["Last_Name"];
    $phone_number = $row["Phone_Number"];
    $position = $row["Position"];
} else {
    echo "0 results";
}

$stmt_fetch_user->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Information</title>
<style>
    body {
        font-family: main, sans-serif, monospace;
        font-style: normal;
        background: radial-gradient(circle, #3b8898 5%, #284a6f 25%, #151b3d 50%, #0a0a24 90%, #000000 100%);
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }
    .container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: calc(100vh - 100px);
        margin-top: -100px;
        width: 100%;
    }
    .user-image {
        size: 30px;
        margin-top: 400px;
        border-radius: 40px;
        margin-left: 30px;
        width: 150px;
    }
    .image-container {
        border: 2px solid black;
        border-radius: 50%;
        overflow: hidden;
        width: 250px;
        height: 250px;
        margin: 0 auto;
        box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.3);
        cursor: pointer;
    }
    .image-container img {
        width: 100%;
        height: auto;
        display: block;
        margin: 0 auto;
    }
    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        display: none;
        justify-content: center;
        align-items: center;
    }
    .overlay img {
        max-width: 90%;
        max-height: 90%;
        object-fit: contain;
    }
    .edit-form {
        margin-top: 0px;
        background-color: #fff;
        padding: 100px;
        border-radius: 8px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        position: relative;
        z-index: 1;
    }
    .user-info {
        margin-right: -2px;
        background-color: #fff;
        border-radius: 30px;
    }
    label {
        display: block;
        padding: 10px;
    }
    input[type="text"],
    input[type="email"],
    input[type="password"] {
        width: 100%;
        padding: 8px;
        margin-bottom: 5px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }
    input[type="submit"] {
        width: 100%;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    input[type="submit"]:hover {
        background-color: #45a049;
    }
    .user-info p {
        padding: 10px;
        font-size: 19px;
        margin-left: 5px;
    }
    .noselect {
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        -webkit-tap-highlight-color: transparent;
    }
    button {
        background-color: #808080;
        color: white;
        padding: 15px 32px;
        text-align: center;
        text-decoration: none;
        font-size: 16px;
        cursor: pointer;
        border: none;
        border-radius: 4px;
        transition: background-color 0.3s ease, border 0.3s ease;
    }
    button:hover {
        background-color: #333;
        border: 2px solid #555555;
        animation: hueRotation 2s linear infinite;
    }
    @keyframes hueRotation {
        to {
            filter: hue-rotate(360deg);
        }
    }
    button:focus {
        outline: none;
    }
    .btnback button:hover {
        background-color: #222222;
    }
    #editFormContainer {
        overflow: hidden;
        opacity: 0;
        transition: height 0.3s ease-in-out, opacity 0.3s ease-in-out;
    }
    #editFormContainer.visible {
        height: auto;
        opacity: 1;
    }
</style>
</head>
<body>
    <div class="container">
        <div class="edit-form">
            <div class="user-image" style="margin-left: 20px;" onclick="showFullscreen()">
                <div class="image-container">
                    <?php if($imageData): ?>    
                        <img src="data:image/jpeg;base64,<?= base64_encode($imageData) ?>" alt="Profile Picture">
                    <?php endif; ?>
                    <?php  
                    $userId = $_SESSION['user_id']; 
                    $stmt = $conn->prepare("SELECT pp FROM created_account WHERE ID = ? ORDER BY ID DESC LIMIT 1");
                    if (!$stmt) {
                        die('Prepare failed: ' . htmlspecialchars($conn->error));
                    }
                    $stmt->bind_param("i", $userId);
                    $stmt->execute();
                    $stmt->store_result();
                    if ($stmt->num_rows > 0) {
                        $stmt->bind_result($imageData);
                        $stmt->fetch();
                        echo '<img src="data:image/jpeg;base64,' . base64_encode($imageData) . '" alt="Profile Picture">';
                    } else {
                        echo "No profile picture found";
                    }
                    $stmt->close();
                    $conn->close();
                    ?>
                </div>
            </div>
            <div class="overlay" onclick="hideFullscreen()">
                <img id="fullscreenImage" src="" alt="Profile Picture">
            </div>
            <h2 style="margin-left: 95px; font-size: 15px; font-weight: normal;">Profile picture</h2>
            <div class="user-info">
                <?php
                echo "<p>Name: <strong>" . htmlspecialchars($first_name) . " " . htmlspecialchars($last_name) . "</strong></p>";
                echo "<p>Email: <strong>" . htmlspecialchars($email) . "</strong></p>";
                echo "<p>Position: <strong>" . htmlspecialchars($position) . "</strong></p>";
                echo "<p>Phone Number: <strong>" . htmlspecialchars($phone_number) . "</strong></p>";
                ?>
            </div>
            <button type="button" onclick="toggleForm()" style="background-color: #4CAF50; color: white; padding: 15px 32px; text-align: center; text-decoration: none; font-size: 16px; cursor: pointer; border: none; border-radius: 4px;">Edit Information</button>
            <div id="editFormContainer">
                <h1>Edit Information</h1>
                <form action="" method="POST">
                    <label for="firstname">First Name:</label>
                    <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($first_name); ?>" required><br><br>
                    <label for="lastname">Last Name:</label>
                    <input type="text" id="lastname" name="lastname" value="<?php echo htmlspecialchars($last_name); ?>" required><br><br>
                    <label for="phonenumber">Phone Number:</label>
                    <input type="text" id="phonenumber" name="phonenumber" value="<?php echo htmlspecialchars($phone_number); ?>" required><br><br>
                    <label for="position">Position:</label>
                    <input type="text" id="position" name="position" value="<?php echo htmlspecialchars($position); ?>" required><br><br>
                    <div class="button-container" style="display: flex; gap: 20px; align-items: center;">
                        <button type="submit" name="update" class="noselect" style="background-color: #4CAF50; color: white; padding: 15px 32px; text-align: center; text-decoration: none; font-size: 16px; cursor: pointer; border: none; border-radius: 4px;">Update</button>
                        <button type="button" onclick="closeForm()" style="background-color: #4CAF50; color: white; padding: 15px 32px; text-align: center; text-decoration: none; font-size: 16px; cursor: pointer; border: none; border-radius: 4px;">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function toggleForm() {
            var formContainer = document.getElementById('editFormContainer');
            formContainer.classList.toggle('visible');
        }
        function closeForm() {
            document.getElementById('editFormContainer').classList.remove('visible');
        }
        function showFullscreen() {
            var imageSrc = document.querySelector('.image-container img').src;
            document.getElementById('fullscreenImage').src = imageSrc;
            document.querySelector('.overlay').style.display = 'flex';
        }
        function hideFullscreen() {
            document.querySelector('.overlay').style.display = 'none';
        }
    </script>
</body>
</html>
