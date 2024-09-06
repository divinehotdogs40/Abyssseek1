<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "abyssseek";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Get the email from the session or from a query parameter
$email = isset($_SESSION['EmailEntry']) ? $_SESSION['EmailEntry'] : $_GET['EmailEntry'];

if (empty($email)) {
    die(json_encode(["error" => "Email is required."]));
}

// Prepare and bind
$stmt = $conn->prepare("SELECT crawlerstatus, foundcount, notfoundcount, found_links, search_history, Viewing_URL, Viewing_Response FROM webcrawler_status WHERE Email = ?");
$stmt->bind_param("s", $email);

// Execute the query
$stmt->execute();
$stmt->bind_result($crawlerstatus, $foundcount, $notfoundcount, $found_links, $search_history, $Viewing_URL, $Viewing_Response);

// Fetch the result
if ($stmt->fetch()) {
    // Construct the response array
    $response = [
        "crawlerstatus" => $crawlerstatus,
        "foundcount" => $foundcount,
        "notfoundcount" => $notfoundcount,
        "found_links" => $found_links,
        "search_history" => $search_history,
        "Viewing_URL" => $Viewing_URL,
        "Viewing_Response" => $Viewing_Response
    ];
    echo json_encode($response); // Output JSON response
} else {
    // Handle cases where email doesn't exist or other errors
    echo json_encode(["error" => "No data found for the provided email."]);
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
