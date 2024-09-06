<?php
if ( isset($_GET["ID"])) {
    $ID = $_GET["ID"];

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "abyssseek";

$connection = new mysqli($servername, $username, $password, $database);
  
    $sql = "DELETE FROM admin_account WHERE ID=$ID";
    $connection->query($sql);

}

header("location: /abyssseek/admin/admin_account/index.php");
exit;
?>
