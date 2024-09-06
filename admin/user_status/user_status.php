<?php
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

// Fetch data from user_status and created_account tables
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
    while($row = $result->fetch_assoc()) {
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

        // Store the row data in the array
        $rows[] = [
            'email' => $row['email'], 
            'status' => $status, 
            'lastOnline' => $lastOnline,
            'profilePicture' => $row['pp']
        ];
    }
} else {
    echo "0 results";
}

// Close connection
$connection->close();

// Function to format time difference into human-readable format
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Status</title>
    <style>
        body {
            margin: 0;
            padding: 0;
        }
        .upper-container {
            background-color: #ffffff;
            color: #287bff;
            padding: 20px;
            text-align: center;
        }
        .container {
            background-color: white ;
            display: flex;
        }
        .white-div {
            background-color: #ffffff; /* White background color for the white div */
            flex: 1; /* Take up remaining width */
            display: flex;
            flex-direction: column;
        }
        .glass-container {
            background: rgba(255, 255, 255, 0.47); /* Semi-transparent white */
            border-radius: 16px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1); /* Shadow effect */
            backdrop-filter: blur(30px); /* Apply blur effect */
            -webkit-backdrop-filter: blur(30px); /* For Safari */
            border: 1px solid rgba(255, 255, 255, 0.19); /* Border */
            padding: 20px; /* Padding */
            width: 100%; /* Full width */
            margin-bottom: 20px; /* Add margin at the bottom */
            overflow: auto; /* Enable overflow scrolling */
        }
        .table-container {
            flex: 1; /* Take up remaining height */
            overflow: auto; /* Enable overflow scrolling */
        }
        table {
            width: 100%; /* Adjusted width to fill the glass container */
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .status-circle {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }
        .status-online {
            background-color: #4CAF50; /* Green */
        }
        .status-offline {
            background-color: #f44336; /* Red */
        }
        /* Hover effect for table rows */
        tr:hover {
            background-color: #f5f5f5; /* Light gray background color */
        }
        .edit-button {
            background-color: blue; /* Updated button color to blue */
            color: #fff; /* Text color for better visibility */
            border: none; /* Remove border */
            padding: 4px 19px; /* Adjust padding */
            border-radius: 4px; /* Rounded corners */
            cursor: pointer; /* Add cursor pointer */
        }
        /* Style hover effect for button */
        .edit-button:hover {
            background-color: darkblue; /* Darker blue on hover */
        }
        
        /* Style for circle container */
        .circle-container {
            display: inline-block;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #e0e0e0; /* Circle background color */
            text-align: center;
            margin-right: 8px; /* Space between the circle and the email column */
            overflow: hidden; /* Hide overflow */
        }
        .circle-container img {
            width: 100%;
            height: 100%;
            object-fit: cover; /* Ensure image covers the circle */
        }
    </style>
</head>
<body>
    <div class="upper-container">
        <!-- Content for the upper container -->
        <h1>Abyssseek Users</h1>
    </div>
    <div class="container">
        <div class="white-div">
            <!-- White div for the glass container -->
            <div class="glass-container">
                <!-- Your glass container content goes here -->
                <table>
                    <thead>
                        <tr>
                            <th>Users</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Last Online</th>
                            <th>Edit User</th> <!-- New column for edit button -->
                        </tr>
                    </thead>
                    <tbody id="statusTable">
                        <?php 
                        $userNumber = 1;
                        foreach ($rows as $row): ?>
                            <tr onclick="handleRowClick('<?php echo $row['email']; ?>', '<?php echo $row['status']; ?>', '<?php echo $row['lastOnline']; ?>')">
                                <td>
                                    <div class="circle-container">
                                        <img src="data:image/jpeg;base64,<?php echo base64_encode($row['profilePicture']); ?>" alt="Profile Picture">
                                    </div>
                                </td>
                                <td><?php echo $row['email']; ?></td>
                                <td>
                                    <span class="status-circle <?php echo $row['status'] === 'Online' ? 'status-online' : 'status-offline'; ?>"></span>
                                    <?php echo $row['status']; ?>
                                </td>
                                <td><?php echo $row['lastOnline']; ?></td>
                                <td><a href="/abyssseek/admin/create_user/index.php" class="edit-button">Edit</a></td>
                            </tr>
                        <?php 
                        $userNumber++;
                        endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Font Awesome CSS (assuming you have it included in your project) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script>
        function updateStatus() {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'fetch_data.php', true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        document.getElementById('statusTable').innerHTML = xhr.responseText;
                    }
                }
            };
            xhr.send();
        }

        setInterval(updateStatus, 1000);

        function handleRowClick(email, status, lastOnline) {
            // Example of handling row click, you can modify this as needed
            console.log('Email: ' + email);
            console.log('Status: ' + status);
            console.log('Last Online: ' + lastOnline);
            // Add your logic here, such as showing a popup or navigating to a new page
        }
    </script>
</body>
</html>

