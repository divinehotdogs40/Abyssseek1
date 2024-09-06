<?php 
$servername = "localhost";
$username = "root";
$password = "";
$database = "abyssseek";

// Connect to MySQL database
$mysqli = new mysqli($servername, $username, $password, $database);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Check if session is active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    $userId = $_SESSION['user_id'] ?? null;
}

function get_user_email($conn, $user_id) {
    $user_id = $conn->real_escape_string($user_id);
    $sql = "SELECT email FROM created_account WHERE id = '$user_id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['email'];
    }
    return null;
}

function fetch_search_history($conn, $email, $offset, $limit) {
    $email = $conn->real_escape_string($email);
    $sql = "SELECT id, search_term, platform, search_date FROM social_media_history WHERE email = '$email' ORDER BY search_date DESC LIMIT $offset, $limit";
    $result = $conn->query($sql);
    $search_history = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $search_history[] = $row;
        }
    }
    return $search_history;
}

function get_total_searches($conn, $email) {
    $email = $conn->real_escape_string($email);
    $sql = "SELECT COUNT(*) as total FROM social_media_history WHERE email = '$email'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['total'];
}

function delete_search_history($conn, $delete_ids) {
    $ids = implode(",", array_map('intval', $delete_ids)); // Sanitize input
    $sql = "DELETE FROM social_media_history WHERE id IN ($ids)";
    return $conn->query($sql);
}

$userEmail = get_user_email($mysqli, $userId); // Get user email

// Pagination logic
$limit = 15; // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1); // Ensure that page is at least 1
$offset = ($page - 1) * $limit;
$totalSearches = get_total_searches($mysqli, $userEmail);
$totalPages = ceil($totalSearches / $limit);

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete']) && !empty($_POST['delete_ids'])) {
    delete_search_history($mysqli, $_POST['delete_ids']);
    // Redirect to the same page number after deletion
    header("Location: " . $_SERVER['PHP_SELF'] . "?page=" . $page);
    exit;
}

$searchHistory = fetch_search_history($mysqli, $userEmail, $offset, $limit); // Fetch search history with pagination
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search History</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('bg.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
        }
        .table-container {
            width: 60%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 auto;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .pagination {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }
        .pagination a {
            margin: 0 5px;
            padding: 8px 16px;
            text-decoration: none;
            color: #007bff;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f9f9f9;
        }
        .pagination a:hover {
            background-color: #007bff;
            color: #fff;
        }
        .pagination a.disabled {
            pointer-events: none;
            opacity: 0.5;
        }
        .delete-container {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }
        .delete-button {
            padding: 10px 20px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .delete-button:hover {
            background-color: #c82333;
        }
    </style>
    <script>
        // Function to toggle select all checkboxes
        function toggleSelectAll(source) {
            checkboxes = document.getElementsByName('delete_ids[]');
            for(var i in checkboxes)
                checkboxes[i].checked = source.checked;
        }
    </script>
</head>
<body>

<div class="table-container">
    <h2>Search History</h2>
    <form action="" method="POST" id="deleteForm">
        <?php if (count($searchHistory) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th><input type="checkbox" onclick="toggleSelectAll(this);"></th>
                        <th>Search Term</th>
                        <th>Platform</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($searchHistory as $history): ?>
                        <tr>
                            <td><input type="checkbox" name="delete_ids[]" value="<?php echo htmlspecialchars($history['id']); ?>"></td>
                            <td><?php echo htmlspecialchars($history['search_term']); ?></td>
                            <td><?php echo htmlspecialchars($history['platform']); ?></td>
                            <td><?php echo date("F j, Y, g:i a", strtotime($history['search_date'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No search history found.</p>
        <?php endif; ?>
        
        <div class="delete-container">
            <button type="submit" name="delete" class="delete-button">Delete Selected</button>
        </div>
    </form>
</div>

<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="?page=<?php echo $page - 1; ?>">&laquo; Previous</a>
    <?php else: ?>
        <a class="disabled">&laquo; Previous</a>
    <?php endif; ?>

    <?php if ($page < $totalPages): ?>
        <a href="?page=<?php echo $page + 1; ?>">Next &raquo;</a>
    <?php else: ?>
        <a class="disabled">Next &raquo;</a>
    <?php endif; ?>
</div>

</body>
</html>
