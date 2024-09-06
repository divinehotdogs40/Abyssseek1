<?php
header('Content-Type: application/json'); // Set response content type as JSON

$data = json_decode(file_get_contents('php://input'), true); // Decode JSON data from POST request body

if (isset($data['content']) && isset($data['web_address'])) {
    $content = $data['content']; // Extract iframe content from POST data
    $web_address = extractExactUrl($data['web_address']); // Extract exact URL from the provided web_address

    // Database connection parameters
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "abyssseek";

    // Create a new MySQL connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check if connection was successful
    if ($conn->connect_error) {
        die(json_encode(['success' => false, 'error' => 'Database connection failed: ' . $conn->connect_error]));
    }

    // Prepare SQL statement to insert data into iframe_history table
    $stmt = $conn->prepare("INSERT INTO iframe_history (web_address, content) VALUES (?, ?)");
    $stmt->bind_param("ss", $web_address, $content); // Bind parameters

    // Execute SQL statement
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Iframe content saved successfully.']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to save iframe content to database: ' . $conn->error]);
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Missing content or web_address parameter.']);
}

// Function to extract exact URL from a provided URL
function extractExactUrl($url) {
    $parsed_url = parse_url($url); // Parse the URL into its components
    return $parsed_url['scheme'] . '://' . $parsed_url['host'] . $parsed_url['path']; // Return scheme, host, and path
}
?>
