<?php
// Define the formatTime function here
function formatTime($timeDiff) {
    if ($timeDiff < 60) {
        return $timeDiff . ' seconds ago';
    } elseif ($timeDiff < 3600) {
        return floor($timeDiff / 60) . ' minutes ago';
    } elseif ($timeDiff < 86400) {
        return floor($timeDiff / 3600) . ' hours ago';
    } elseif ($timeDiff < 2592000) {
        return floor($timeDiff / 86400) . ' days ago';
    } elseif ($timeDiff < 31536000) {
        return floor($timeDiff / 2592000) . ' months ago';
    } else {
        return floor($timeDiff / 31536000) . ' years ago';
    }
}

$servername = "localhost";
$username = "root";
$password = "";
$database = "abyssseek";

// Create connection
$connection = new mysqli($servername, $username, $password, $database);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Fetch data from user_status table and join with created_account to get profile pictures
$sql = "SELECT us.email, us.status, us.last_login, ca.pp
        FROM user_status us
        LEFT JOIN created_account ca ON us.email = ca.Email";
$result = $connection->query($sql);

// Initialize an empty array to store the rows
$rows = array();

if ($result->num_rows > 0) {
    // Fetch current timestamp and subtract 360 minutes (6 hours) to start from 0
    $currentTime = time() + (360 * 60);

    // Fetch each row and store it in the $rows array
    while ($row = $result->fetch_assoc()) {
        // Check if the user is offline and set status to "Offline"
        if ($row['status'] === 'Online') {
            $status = 'Online';
            $lastOnline = 'Active'; // User is currently online, so no last online time
        } else {
            // Calculate the time elapsed since the user went offline
            $last_login = strtotime($row['last_login']);
            $timeDiff = $currentTime - $last_login;

            // Construct the last online time string
            $lastOnline = formatTime($timeDiff);

            $status = 'Offline';
        }

        // Get the base64 encoded image for the profile picture
        $profilePic = $row['pp'];
        $profilePicBase64 = base64_encode($profilePic);
        $profilePicSrc = 'data:image/jpeg;base64,' . $profilePicBase64;

        // Store the row data in the array
        $rows[] = [
            'email' => $row['email'],
            'status' => $status,
            'lastOnline' => $lastOnline,
            'profilePicSrc' => $profilePicSrc
        ];
    }
} else {
    echo "0 results";
}

// Close connection
$connection->close();

// Output the HTML code for the table rows
$userNumber = 1; // Initialize user number
foreach ($rows as $row) {
    echo "<tr>";
    // Use profile picture instead of circle container
    echo "<td><div class='circle-container'><img src='" . $row['profilePicSrc'] . "' alt='Profile Picture' /></div></td>";
    echo "<td>" . $row['email'] . "</td>";
    echo "<td><span class='status-circle " . ($row['status'] === 'Online' ? 'status-online' : 'status-offline') . "'></span>" . $row['status'] . "</td>";
    echo "<td>" . $row['lastOnline'] . "</td>";
    echo "<td><a href='/abyssseek/admin/create_user/index.php' class='edit-button'>Edit</a></td>";
    echo "</tr>";
    $userNumber++; // Increment user number
}
?>
