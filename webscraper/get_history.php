<?php
header('Content-Type: application/json'); // Set response content type as JSON

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "abyssseek";

// Create a new MySQL connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if connection was successful
if ($conn->connect_error) {
    // If connection fails, return an error message as JSON and stop script execution
    die(json_encode(['success' => false, 'error' => 'Database connection failed: ' . $conn->connect_error]));
}

// SQL query to select historical data from iframe_history table, ordered by created_at in descending order
$sql = "SELECT id, web_address, content, created_at FROM iframe_history ORDER BY created_at DESC";

// Execute SQL query
$result = $conn->query($sql);

// Initialize an empty array to store history data
$history = [];

// Check if there are rows returned from the query
if ($result->num_rows > 0) {
    // Iterate through each row in the result set
    while ($row = $result->fetch_assoc()) {
        // Add each row (history entry) to the $history array
        $history[] = $row;
    }
}

// Output the $history array as JSON response
echo json_encode(['success' => true, 'history' => $history]);

// Close the database connection
$conn->close();
?>
