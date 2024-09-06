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

$sql = "SELECT * FROM login WHERE DATE(login_time) = '$searchDate' ORDER BY login_time DESC";

$result = $conn->query($sql);

if ($result === false) {
    // Output the error message if the query fails
    die("Error executing query: " . $conn->error);
}

// Start building the HTML content for the table
$table_content = '';
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $table_content .= "<tr>";
        $table_content .= "<td>" . $row["email"] . "</td>";
        $table_content .= "<td>" . $row["login_time"] . "</td>";
        $table_content .= "<td>" . $row["logout_time"] . "</td>";
        // Add more columns as needed
        $table_content .= "</tr>";
    }
} else {
    // If no data found
    $table_content .= "<tr><td colspan='3'>No data found</td></tr>"; // Adjust colspan according to your number of columns
}

// Close the connection
$conn->close();

// Return the table content
echo $table_content;
?>
