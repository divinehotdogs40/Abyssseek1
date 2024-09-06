<?php
// Start the session
session_start();

// Check if user is authenticated
if (!isset($_SESSION['user_id'])) {
    // Return error response if user is not authenticated
    echo json_encode(array("success" => false, "message" => "User not authenticated."));
    exit;
}

// Retrieve user ID from session
$userId = $_SESSION['user_id'];

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "abyssseek";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if a file was uploaded
if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
    // Prepare and execute SQL statement to update the profile picture in the created_account table
    $stmtUpdate = $conn->prepare("UPDATE created_account SET pp = ? WHERE ID = ?");
    $stmtUpdate->bind_param("si", $imageData, $userId);
    $imageData = file_get_contents($_FILES['file']['tmp_name']);
    $stmtUpdate->send_long_data(0, $imageData); // For large data, use send_long_data
    $stmtUpdate->execute();
    $stmtUpdate->close();

    // Insert the uploaded image data into the profile_pictures table
    $stmtInsert = $conn->prepare("INSERT INTO profile_pictures (user_id, image_data) VALUES (?, ?)");
    $stmtInsert->bind_param("is", $userId, $imageData);
    $stmtInsert->execute();
    $stmtInsert->close();

    // Return success response
    echo json_encode(array("success" => true));
} else {
    // Return error response
    echo json_encode(array("success" => false, "message" => "Error uploading file."));
}

// Close database connection
$conn->close();

?>
