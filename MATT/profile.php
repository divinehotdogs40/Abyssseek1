<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "abyssseek";

// Check if the session variable is set
if (!isset($_SESSION['ID'])) {
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

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information</title>
    <style>
        body {
            color: #fff;
            font-family: 'Courier New', Courier, monospace;
            background: radial-gradient(circle, #3b8898 5%, #284a6f 25%, #151b3d 50%, #0a0a24 90%, #000000 100%);
            background-size: contain;
            font-size: 18px;
            height: 100%;
        }
        .toggle-password-btn {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 12px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .toggle-password-btn:hover {
            background-color: #338a89;
        }
        .container {
            position: relative;
            max-width: 1700px;
            margin: 40px auto;
            background: radial-gradient(circle, #3b8898 5%, #284a6f 25%, #151b3d 50%, #0a0a24 90%, #000000 100%);;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            color: #000;
            border: 1px solid #ccc; 
            display: flex; 
            justify-content: space-between;
            height: 900px;
        }
        .user-info-container {
            flex: 1; 
            padding-right: 40px;
        }
        .download-history-container {
            flex: 0 0 300px;
            background-color: #f0f0f0; 
            padding: 50px;
            border-radius: 8px;
        }
        .activity-feed {
            color: white;
            flex: 1;
            background-color: #808080;
            padding: 40px 160px; /* Adjust padding top and right */
            border-radius: 8px;
            margin-left: 0px;
            margin-bottom: -5px;
        }
        h1 {
            color: white;
            text-align: center;
            margin-bottom: 20px;
        }
        .user-info {
            color: white;
            margin-top: 20px;
            border-top: 1px solid #ccc;
            padding-top: 20px;
        }
        .user-info p {
            margin-bottom: 10px;
        }
        .user-image-container {
            position: absolute;
            top: 130px;
            right: 1230px; 
            width: 100px;
            height: 100px;
        }

        .user-image {
            width: 90%;
            height: 90%;
            border-radius: 50%;
            object-fit: cover;
        }

        .edit-btn input[type="file"],
        .submit-btn input[type="file"] {
            display: none; 
        }

        .edit-btn button,
        .submit-btn button {
            background-color: white;
            font-size: 12px;
            border: none;
            color: black;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .edit-btn button:hover,
        .submit-btn button:hover {
            background-color: #45a049; 
        }

        .upload-btn {
            position: absolute;
            top: 250px;
            right: 1240px; 
            text-align: center;
        }
        .upload-btn input[type="file"] {
            display: none; 
        }

        .upload-btn label {
            background-color: white;
            color: black;
            padding: 5px 5px;
            text-align: center;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .upload-btn label:hover {
            background-color: #45a049; 
        }

        .activity-feed {
            background: radial-gradient(circle, #3b8898 5%, #284a6f 25%, #151b3d 50%, #0a0a24 90%, #000000 100%);
            background-size:auto;
        }

        .user-info-container {
            margin-right: auto;
        }
        .activity-feed p strong {
            color: white;
            font-size: 16px;
            font-style: italic;
        }
        
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f1f1f1;
            min-width: 160px;
            box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
            z-index: 1;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .dropdown-content li {
            padding: 10px;
            text-align: left;
        }

        .dropdown-toggle {
            cursor: pointer;
}

</style>
</head>
<body>

    <div class="container">
        <div class="user-info-container">   

            <h1>User Information</h1>
           <div class="user-info">
                <p><strong>ID:</strong> <?php echo $id; ?></p>
                <p><strong>Email:</strong> <?php echo $email; ?></p> 

                </p>
                <p><strong>Position:</strong> <?php echo $position; ?></p>
                <p><strong>Age:</strong> <span id="age" contenteditable="true"></span></p>
                <p><strong>Location:</strong> <span id="location" contenteditable="true"></span></p>
                <p><strong>Occupation:</strong> <span id="occupation" contenteditable="true"></span></p>
            </div>

            <form id="user-form" action="#" method="post" enctype="multipart/form-data">
                <div class="upload-btn">
                    <label for="photo">Upload:</label>
                    <input type="file" id="photo" name="photo" onchange="displayImage(this)">
                </div>
                <!-- Add a submit button here if needed -->
            </form>



            <div class="user-image-container">
                <?php if (!empty($imageFilename)): ?>
                    <img id="user-image" class="user-image" src="uploads/<?php echo $imageFilename; ?>" alt="User Image">
                <?php else: ?>
                    <!-- Default image if no image uploaded -->
                    <img id="user-image" class="user-image" src="default-image.jpg" alt="Default User Image">
                <?php endif; ?>
            </div>

                <div class="edit-btn">
                <button type="button" onclick="toggleEdit()">Edit Information</button>
                </div>
                <div class="submit-btn" style="display: none;">
                <input type="button" value="Submit" onclick="submitForm()">
                </div>
            </form>
        </div>

        

    

    <script>
        window.onload = function() {
            loadUserInfo();
            loadUserImage();
        };

        function displayImage(input) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('user-image').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
        function toggleDropdown() {
        var dropdownContent = document.getElementById("download-history-list");
        if (dropdownContent.style.display === "none" || dropdownContent.style.display === "") {
            dropdownContent.style.display = "block";
        } else {
            dropdownContent.style.display = "none";
        }
}

        function toggleEdit() {
            var spans = document.querySelectorAll('.user-info span');
            spans.forEach(function(span) {
                span.contentEditable = true;
                span.style.border = '1px solid #ccc';
            });
            document.querySelector('.edit-btn').style.display = 'none';
            document.querySelector('.submit-btn').style.display = 'block';
        }

        function submitForm() {
            var userInfo = {
                age: document.getElementById('age').innerText,
                location: document.getElementById('location').innerText,
                occupation: document.getElementById('occupation').innerText
            };
            localStorage.setItem('userInfo', JSON.stringify(userInfo));
            var spans = document.querySelectorAll('.user-info span');
            spans.forEach(function(span) {
                span.contentEditable = false;
                span.style.border = 'none';
            });
            document.querySelector('.submit-btn').style.display = 'none';
            document.querySelector('.edit-btn').style.display = 'block';
        }

        function loadUserInfo() {
            var userInfo = localStorage.getItem('userInfo');
            if (userInfo) {
                userInfo = JSON.parse(userInfo);
                document.getElementById('age').innerText = userInfo.age;
                document.getElementById('location').innerText = userInfo.location;
                document.getElementById('occupation').innerText = userInfo.occupation;
            }
        }

        function loadUserImage() {
            var userImage = localStorage.getItem('userImage');
            if (userImage) {
                document.getElementById('user-image').src = userImage;
            }
        }

        function togglePasswordVisibility() {
    var passwordField = document.getElementById('password');
    var button = document.querySelector('.toggle-password-btn');

    if (passwordField.getAttribute('data-visible') === 'false') {
        // Show password
        passwordField.setAttribute('data-visible', 'true');
        passwordField.innerHTML = passwordField.getAttribute('data-password'); // Display actual password
        button.textContent = 'Hide';
    } else {
        // Hide password
        passwordField.setAttribute('data-visible', 'false');
        passwordField.innerHTML = '*'.repeat(passwordField.textContent.length); // Display asterisks
        button.textContent = 'Show';
    }
}


    </script>
</body>
</html>
