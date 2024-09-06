<?php
// Database connection parameters
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['reply'])) {
    $id = intval($_POST['id']);
    $reply = $_POST['reply'];

    // Insert reply into the replies table
    $reply_sql = "INSERT INTO replies (help_id, reply) VALUES (?, ?)";
    $stmt = $conn->prepare($reply_sql);
    $stmt->bind_param("is", $id, $reply);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>
