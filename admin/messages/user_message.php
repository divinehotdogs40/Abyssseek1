<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abyssseek Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        body {
            background: white;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            color: #000;
            overflow-y: auto;
            min-height: 100vh;
        }
        .container {
    background-color: rgba(255, 255, 255, 0.8);
    border-radius: 20px;
    padding: 20px;
    box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
    color: black;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    margin-top: 20px;
    width: 80%;
}

.list-group {
    display: flex; /* This arranges items horizontally */
    flex-wrap: wrap; /* Allows items to wrap onto the next line if needed */
    gap: 10px; /* Adds space between items */
}

.list-group-item {
    width: 300px;
    background-color: #287bff;
    border: none;
    color: white;
    font-size: 14px;
    overflow: hidden;
    position: relative;
    padding-top: 60px; /* Added padding to make space for the profile picture */
    cursor: pointer; /* Change cursor to indicate it's clickable */
}

        .profile-picture {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            position: absolute;
            top: 5px;
            left: 50%;
            transform: translateX(-50%);
            object-fit: cover;
        }
        .message-content {
            display: none;
        }
        .button-container {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }
        .reply-form {
            display: none;
        }
        .reply {
            background-color: #e0e0e0;
            color: #000;
            border-radius: 10px;
            padding: 10px;
            margin-top: 5px;
            text-align: right;
            max-width: 80%;
            margin-left: auto;
        }
        .user-reply {
            background-color: #d4edda;
            color: #000;
            border-radius: 10px;
            padding: 10px;
            margin-top: 5px;
            max-width: 80%;
            margin-right: auto;
            text-align: left;
        }
        .message-header {
            text-align: center;
        }
        .message-header h5 {
            font-weight: bold;
            color: black;
        }
        .message-header p {
            margin: 0;
        }

        .message-box {
    background-color: #fff3cd;
    border: 1px solid #ffeeba;
    border-radius: 5px;
    padding: 10px;
    margin-bottom: 10px;
    color: #856404;
    font-size: 14px;
}

    </style>
 <script>
    function toggleMessage(id) {
        var content = document.getElementById("message-" + id);
        content.style.display = (content.style.display === "none") ? "block" : "none";
    }

    function toggleReplyForm(id) {
        var form = document.getElementById("reply-form-" + id);
        form.style.display = (form.style.display === "none") ? "block" : "none";
    }

    function deleteMessage(id) {
        if (confirm("Are you sure you want to delete this entire conversation?")) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    alert("Conversation deleted successfully.");
                    location.reload(); // Reload the page to update the message list
                }
            };
            xhr.send("delete_id=" + id);
        }
    }

    function replyToMessage(id) {
        var replyText = document.getElementById("reply-text-" + id).value;
        if (replyText.trim() !== "") {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "reply_message.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    if (xhr.responseText === 'success') {
                        alert("Reply sent successfully.");
                        location.reload(); // Reload the page to update the message list
                    } else {
                        alert("Failed to send reply.");
                    }
                }
            };
            xhr.send("id=" + id + "&reply=" + encodeURIComponent(replyText));
        } else {
            alert("Reply message cannot be empty.");
        }
    }
</script>

</head>
<body>
    <div class="container mt-3">
        <div class="mb-4 w-100 text-center">
            <h2 style="background: black; -webkit-background-clip: text; -webkit-text-fill-color: transparent;">User Concerns</h2>
        </div>
        <?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "abyssseek";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle deletion request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Delete from user_replies
        $delete_user_replies_sql = "DELETE FROM user_replies WHERE help_id = ?";
        $stmt_user_replies = $conn->prepare($delete_user_replies_sql);
        $stmt_user_replies->bind_param("i", $delete_id);
        $stmt_user_replies->execute();
        $stmt_user_replies->close();

        // Delete from admin replies
        $delete_admin_replies_sql = "DELETE FROM replies WHERE help_id = ?";
        $stmt_admin_replies = $conn->prepare($delete_admin_replies_sql);
        $stmt_admin_replies->bind_param("i", $delete_id);
        $stmt_admin_replies->execute();
        $stmt_admin_replies->close();

        // Delete from helps
        $delete_helps_sql = "DELETE FROM helps WHERE id = ?";
        $stmt_helps = $conn->prepare($delete_helps_sql);
        $stmt_helps->bind_param("i", $delete_id);
        $stmt_helps->execute();
        $stmt_helps->close();

        // Commit transaction
        $conn->commit();

        echo "<script>alert('Conversation deleted successfully.'); window.location.href = '';</script>";
    } catch (Exception $e) {
        // Rollback transaction
        $conn->rollback();
        echo "<script>alert('Error deleting conversation: " . $conn->error . "');</script>";
    }
}

// SQL query to retrieve data from the Helps table
$sql = "SELECT h.*, c.pp FROM helps h LEFT JOIN created_account c ON h.email = c.Email";

// Execute the query
$result = $conn->query($sql);

// Check if there are any rows returned
if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        echo "<div class='list-group mb-3'>";
        echo "<div class='list-group-item list-group-item-action flex-column align-items-start' onclick='toggleMessage({$row["id"]})'>";
        if ($row["pp"]) {
            $imageData = base64_encode($row["pp"]);
            echo "<img src='data:image/jpeg;base64,{$imageData}' class='profile-picture' alt='Profile Picture'>";
        } else {
            echo "<div class='profile-picture' style='background-color: gray;'></div>";
        }
        echo "<div class='message-header'>";
        echo "<h5 class='mb-1'>{$row["name"]}</h5>";
        echo "<p>ID: {$row["id"]}</p>";
        echo "<p>Organization: {$row["organization"]}</p>";
        echo "<p>Email: {$row["email"]}</p>";
        echo "</div>";
        echo "<div id='message-{$row["id"]}' class='message-content'>";
        echo "<p class='mb-1 message-box'>Message: {$row["message"]}</p>";

        // Fetch user replies and admin replies and merge them into a single array
        $replies = [];

        // Fetch user replies
        $user_replies_sql = "SELECT user_comment AS content, created_at AS date, 'user' AS type FROM user_replies WHERE help_id = ?";
        $user_replies_stmt = $conn->prepare($user_replies_sql);
        $user_replies_stmt->bind_param("i", $row["id"]);
        $user_replies_stmt->execute();
        $user_replies_result = $user_replies_stmt->get_result();

        if ($user_replies_result->num_rows > 0) {
            while ($user_reply = $user_replies_result->fetch_assoc()) {
                $replies[] = $user_reply;
            }
        }
        $user_replies_stmt->close();

        // Fetch admin replies
        $admin_replies_sql = "SELECT reply AS content, replied_at AS date, 'admin' AS type FROM replies WHERE help_id = ?";
        $admin_replies_stmt = $conn->prepare($admin_replies_sql);
        $admin_replies_stmt->bind_param("i", $row["id"]);
        $admin_replies_stmt->execute();
        $admin_replies_result = $admin_replies_stmt->get_result();

        if ($admin_replies_result->num_rows > 0) {
            while ($admin_reply = $admin_replies_result->fetch_assoc()) {
                $replies[] = $admin_reply;
            }
        }
        $admin_replies_stmt->close();

        // Sort replies by date
        usort($replies, function($a, $b) {
            return strtotime($a['date']) - strtotime($b['date']);
        });

        // Display sorted replies
        foreach ($replies as $reply) {
            $replyClass = ($reply['type'] === 'user') ? 'user-reply' : 'reply';
            echo "<div class='{$replyClass}'><strong>" . ucfirst($reply['type']) . ":</strong> {$reply['content']}</div>";
        }

        echo "<div class='button-container'>";
        echo "<button class='btn btn-danger' onclick='event.stopPropagation(); deleteMessage({$row["id"]});'>Delete</button>";
        echo "<button class='btn btn-primary' onclick='event.stopPropagation(); toggleReplyForm({$row["id"]});'>Reply</button>";
        echo "</div>";
        echo "<div id='reply-form-{$row["id"]}' class='reply-form mt-3' onclick='event.stopPropagation();'>";
        echo "<textarea id='reply-text-{$row["id"]}' class='form-control mb-2' placeholder='Enter your reply'></textarea>";
        echo "<button class='btn btn-success' onclick='event.stopPropagation(); replyToMessage({$row["id"]});'>Send Reply</button>";
        echo "<button class='btn btn-secondary' onclick='toggleReplyForm({$row["id"]});'>Cancel</button>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
    }
} else {
    echo "<p class='text-center'>No messages found</p>";
}

// Close connection
$conn->close();
?>

    </div>
</body>
</html>
