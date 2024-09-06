<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "abyssseek";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Something went wrong;");
}

?>