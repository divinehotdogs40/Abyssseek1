<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "abyssseek"; // Change this to your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if a search date is provided
$searchDate = isset($_GET['date']) ? $_GET['date'] : null;

if (!$searchDate) {
    die("No date provided");
}

// Prepare SQL query
$sql = "SELECT * FROM login WHERE DATE(login_time) = '$searchDate' ORDER BY login_time DESC";
$result = $conn->query($sql);

if ($result === false) {
    die("Error executing query: " . $conn->error);
}

// Set headers to download the file
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="records_' . $searchDate . '.csv"');

// Open the output stream
$output = fopen('php://output', 'w');

// Output column headings if there are any results
if ($result->num_rows > 0) {
    // Output column headings
    fputcsv($output, ['Email', 'Login Time', 'Logout Time']);
    
    // Output each row of the data
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [$row['email'], $row['login_time'], $row['logout_time']]);
    }
} else {
    fputcsv($output, ['No data found for the specified date']);
}

// Close the connection
$conn->close();

// Close the output stream
fclose($output);
exit;
?>
