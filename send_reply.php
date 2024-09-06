<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Abyssseek";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if a session is already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure email is set
if (!isset($_SESSION['email'])) {
    die("User email is not set in session.");
}

$email = $_SESSION['email'];

// Check if reply data is posted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply'], $_POST['reply_id'])) {
    $reply_text = $_POST['reply'];
    $reply_id = intval($_POST['reply_id']); // Ensure reply_id is an integer

    // Find the help_id for the current user and reply_id
    $sql = "
        SELECT h.id AS help_id
        FROM helps h
        INNER JOIN replies r ON r.help_id = h.id
        WHERE h.email = ? AND r.id = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $email, $reply_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $help_id = $row['help_id'];

        // Insert the reply into user_replies table
        $insert_sql = "
            INSERT INTO user_replies (help_id, reply_id, user_comment, created_at)
            VALUES (?, ?, ?, NOW())
        ";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("iis", $help_id, $reply_id, $reply_text);
        $insert_stmt->execute();

        if ($insert_stmt->affected_rows > 0) {
            echo 'success';
        } else {
            echo 'error';
        }

        $insert_stmt->close();
    } else {
        echo 'invalid_reply_id';
    }

    $stmt->close();
} else {
    echo 'invalid_request';
}

$conn->close();
?>
