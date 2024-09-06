<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "abyssseek";

$connection = new mysqli($servername, $username, $password, $database);

$Email = "";
$Password = "";
$First_Name = "";
$Last_Name = "";
$Position = "";
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
    
    $sql = "SELECT * FROM created_account WHERE ID = $ID";
    $result = $connection->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $Email = $row["Email"];
        $Password = $row["Password"];
        $First_Name = $row["First_Name"];
        $Last_Name = $row["Last_Name"];
        $Position = $row["Position"];
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
        echo "User ID is missing";
        exit;
    }
    $Email = $_POST["Email"];
    $Password = $_POST["Password"];
    $First_Name = $_POST["First_Name"];
    $Last_Name = $_POST["Last_Name"];
    $Position = $_POST["Position"];
    $Phone_Number = $_POST["Phone_Number"];

    $sql = "UPDATE created_account SET Email='$Email', Password='$Password', First_Name='$First_Name', Last_Name='$Last_Name', Position='$Position', Phone_Number='$Phone_Number' WHERE ID = $ID";

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
                        window.location.href = '/abyssseek/admin/create_user/index.php'; // Redirect after 2 seconds
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
                <label for="Email" class="form-label">Email</label>
                <input type="email" class="form-control" id="Email" name="Email" value="<?php echo $Email; ?>">
            </div>
            <div class="mb-3">
                <label for="Password" class="form-label">Password</label>
                <input type="text" class="form-control" id="Password" name="Password" value="<?php echo $Password; ?>">
            </div>
            <div class="mb-3">
                <label for="First_Name" class="form-label">First Name</label>
                <input type="text" class="form-control" id="First_Name" name="First_Name" value="<?php echo $First_Name; ?>">
            </div>
            <div class="mb-3">
                <label for="Last_Name" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="Last_Name" name="Last_Name" value="<?php echo $Last_Name; ?>">
            </div>
            <div class="mb-3">
                <label for="Position" class="form-label">Position</label>
                <input type="text" class="form-control" id="Position" name="Position" value="<?php echo $Position; ?>">
            </div>
            <div class="mb-3">
                <label for="Phone_Number" class="form-label">Phone Number</label>
                <input type="text" class="form-control" id="Phone_Number" name="Phone_Number" value="<?php echo $Phone_Number; ?>">
            </div>
           
