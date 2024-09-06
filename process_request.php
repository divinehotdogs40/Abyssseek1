<?php
// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form submission
    
    // Create directory if it doesn't exist
    $directory = "uploads/";
    if (!is_dir($directory)) {
        mkdir($directory, 0777, true);
    }

    // Move uploaded files to the directory
    $frontIDImagePath = $directory . basename($_FILES["frontIDImage"]["name"]);
    $backIDImagePath = $directory . basename($_FILES["backIDImage"]["name"]);

    if (move_uploaded_file($_FILES["frontIDImage"]["tmp_name"], $frontIDImagePath) &&
        move_uploaded_file($_FILES["backIDImage"]["tmp_name"], $backIDImagePath)) {
        echo "Files uploaded successfully.";
    } else {
        echo "Error uploading files.";
    }

    // Insert form data into the database or perform other necessary actions
}
?>
