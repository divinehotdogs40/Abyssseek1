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

// Retrieve email from GET request
if (isset($_GET['email'])) {
    $email = $_GET['email'];
} else {
    die("Email parameter is missing");
}

// Prepare SQL query
$sql = "SELECT r.id, r.first_name, r.last_name, r.email, l.login_time, l.logout_time
        FROM requests r
        LEFT JOIN login l ON r.email = l.email
        WHERE r.email LIKE ?";
$searchEmail = "%" . $email . "%";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $searchEmail);

// Execute SQL query
$stmt->execute();
$result = $stmt->get_result();

// Check if there are results
if ($result->num_rows > 0) {
    // Output table headers
    echo "<tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Login Time</th><th>Logout Time</th></tr>";

    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id"]. "</td>";
        echo "<td>" . $row["first_name"]. "</td>";
        echo "<td>" . $row["last_name"]. "</td>";
        echo "<td>" . $row["email"]. "</td>";
        echo "<td>" . $row["login_time"]. "</td>";
        echo "<td>" . $row["logout_time"]. "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6'>No results found</td></tr>";
}

// Close statement and connection
$stmt->close();
$conn->close();
?>
