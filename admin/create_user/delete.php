<?php
if (isset($_GET["ID"])) {
    $ID = $_GET["ID"];

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "abyssseek";

    $connection = new mysqli($servername, $username, $password, $database);

    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    // Fetch email from created_account table using ID
    $sql_email = "SELECT email FROM created_account WHERE ID=$ID";
    $result = $connection->query($sql_email);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $email = $row["email"];

        // Delete from created_account table
        $sql1 = "DELETE FROM created_account WHERE ID=$ID";

        // Delete from user_status table based on email
        $sql2 = "DELETE FROM user_status WHERE email='$email'";

        if ($connection->query($sql1) === TRUE && $connection->query($sql2) === TRUE) {
            echo "Records deleted successfully";
        } else {
            echo "Error deleting records: " . $connection->error;
        }
    } else {
        echo "No email found for the given ID";
    }

    $connection->close();
}

header("location: /abyssseek/admin/create_user/index.php");
exit;
?>
