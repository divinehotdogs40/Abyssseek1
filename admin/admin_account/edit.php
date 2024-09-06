<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "abyssseek";

$connection = new mysqli($servername, $username, $password, $database);

$Admin_Name = "";
$Email = "";
$Password = "";
$Phone_Number = "";
$ID = ""; // Initialize $ID variable

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Retrieve user details based on ID
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['ID'])) {
    $ID = $_GET['ID'];
    // Sanitize the ID to prevent SQL injection
    $ID = $connection->real_escape_string($ID);
    
    $sql = "SELECT * FROM admin_account WHERE ID = $ID";
    $result = $connection->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $Admin_Name = $row["Admin_Name"];
        $Email = $row["Email"];
        $Password = $row["Password"];
        $Phone_Number = $row["Phone_Number"];
    } else {
        echo "User not found";
        exit;
    }
}

// Update user details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ensure $ID is set and not empty before using it in the query
    if(isset($_POST["ID"]) && !empty($_POST["ID"])) {
        $ID = $_POST["ID"];
    } else {
        echo "Admin ID is missing";
        exit;
    }
    $Admin_Name = $_POST["Admin_Name"];
    $Email = $_POST["Email"];
    $Phone_Number = $_POST["Phone_Number"];
    
    // Hash the password
    $hashed_password = password_hash($_POST["Password"], PASSWORD_DEFAULT);

    $sql = "UPDATE admin_account SET  Admin_Name='$Admin_Name', Email='$Email', Password='$hashed_password', Phone_Number='$Phone_Number' WHERE ID = $ID";

    if ($connection->query($sql) === TRUE) {
        // JavaScript code to show temporary popup and redirect after 2 seconds
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    var popup = document.createElement('div');
                    popup.className = 'popup';
                    popup.innerHTML = 'Account Updated Successfully';
                    document.body.appendChild(popup);
                    popup.style.display = 'block'; // Show the popup
                    setTimeout(function() {
                        popup.style.display = 'none';
                        window.location.href = '/abyssseek/admin/admin_account/index.php'; // Redirect after 2 seconds
                    }, 1000); // 1000 milliseconds = 1 seconds
                });
              </script>";
    } else {
        echo "Error updating record: " . $connection->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <style>
        body {
            background: radial-gradient(circle, #3b8898 5%, #284a6f 25%, #151b3d 50%, #0a0a24 90%, #000000 100%);
            color: white;
        }
        .popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 20px;
            border-radius: 10px;
            z-index: 9999;
            display: none; /* Initially hide the popup */
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <h2>Edit User</h2>

        <form method="post">
            <input type="hidden" name="ID" value="<?php echo $ID; ?>">
            <div class="mb-3">
                <label for="Admin_Name" class="form-label">Admin Name</label>
                <input type="text" class="form-control" id="Admin_Name" name="Admin_Name" value="<?php echo $Admin_Name; ?>">
            </div>
            <div class="mb-3">
                <label for="Email" class="form-label">Email</label>
                <input type="email" class="form-control" id="Email" name="Email" value="<?php echo $Email; ?>">
            </div>
            <div class="mb-3">
                <label for="Password" class="form-label">Enter New Password</label>
                <input type="password" class="form-control" id="Password" name="Password" value="">
            </div>
            <div class="mb-3">
                <label for="Phone_Number" class="form-label">Phone Number</label>
                <input type="text" class="form-control" id="Phone_Number" name="Phone_Number" value="<?php echo $Phone_Number; ?>">
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="/abyssseek/admin/admin_account/index.php" class="btn btn-secondary">Back</a>
        </form>
    </div>
</body>
</html>
