<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "abyssseek";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_SESSION['email'];
$sql = "SELECT links_detected FROM webcrawler_status WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="All_links_detected.csv"');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, array('All links detected:'));

    while ($row = $result->fetch_assoc()) {
        $links = explode("\n", $row['links_detected']);
        foreach ($links as $link) {
            $clean_link = str_replace("'", "", $link);
            fputcsv($output, array($clean_link));
        }
    }

    fclose($output);
} else {
    echo "No data found.";
}

$stmt->close();
$conn->close();
?>
