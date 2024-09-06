<?php
session_start(); // Start the session

if (!isset($_SESSION['EmailEntry'])) {
    die("Email entry is not set in the session.");
}

$emailEntry = $_SESSION['EmailEntry'];
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "abyssseek";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$found_links = "STOPPED";
// Using prepared statements to avoid SQL injection
$stmt = $conn->prepare("UPDATE webcrawler_status SET crawlerstatus=? WHERE Email=?");
$stmt->bind_param("ss", $found_links, $emailEntry);
if ($stmt->execute()) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $stmt->error;
}
$stmt->close();
$conn->close();

?>
