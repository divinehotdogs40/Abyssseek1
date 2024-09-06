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

// Pagination
$limit = 20; // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page number
$start = ($page - 1) * $limit; // Offset for SQL query

// Check if a search date is provided
$searchDate = isset($_GET['date']) ? $_GET['date'] : null;

if ($searchDate) {
    $sql = "SELECT * FROM login WHERE DATE(login_time) = '$searchDate' ORDER BY login_time DESC LIMIT $start, $limit";
} else {
    $sql = "SELECT * FROM login ORDER BY login_time DESC LIMIT $start, $limit";
}

$result = $conn->query($sql);

if ($result === false) {
    // Output the error message if the query fails
    die("Error executing query: " . $conn->error);
}

// Start building the HTML content for the table
$table_content = '';
if ($result->num_rows > 0) {
    // Calculate the starting ID for the current page
    $start_id = ($page - 1) * $limit + 1;
    while ($row = $result->fetch_assoc()) {
        $table_content .= "<tr>";
        $table_content .= "<td>" . $start_id++ . "</td>"; // Increment start_id for each row
        $table_content .= "<td>" . $row["email"] . "</td>";
        $table_content .= "<td>" . $row["login_time"] . "</td>";
        $table_content .= "<td>" . $row["logout_time"] . "</td>";
        // Add more columns as needed
        $table_content .= "</tr>";
    }
} else {
    // If no data found
    $table_content .= "<tr><td colspan='4'>No data found</td></tr>"; // Adjust colspan according to your number of columns
}

// Close the connection
$conn->close();

// Return the table content
echo $table_content;
?>
