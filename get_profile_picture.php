<?php
session_start(); // Start the session

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

// Retrieve the profile picture for the current user from the database
$stmt = $conn->prepare("SELECT image_data FROM profile_pictures WHERE user_id = ? ORDER BY id DESC LIMIT 1");
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Output the image data
    $stmt->bind_result($imageData);
    $stmt->fetch();
    // Return success response with image data
    echo json_encode(array("success" => true, "imageUrl" => "data:image/jpeg;base64," . base64_encode($imageData)));
} else {
    // Return error response if no profile picture found for the current user
    echo json_encode(array("success" => false, "message" => "No profile picture found."));
}

// Close statement and database connection
$stmt->close();
$conn->close();
?>