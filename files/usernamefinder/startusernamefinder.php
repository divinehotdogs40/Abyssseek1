<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usernamefinderFieldValue = $_POST["usernamefinderField"];
    
    // Replace 'python3' with the correct command for running Python on your system
    $command = "python3 usernamefinder.py $usernamefinderFieldValue";
    
    // Execute the Python file
    exec($command, $output, $return_value);
    
    // Check if the Python file executed successfully
    if ($return_value === 0) {
        echo "Python file executed successfully!";
    } else {
        echo "Failed to execute Python file.";
    }
}
?>
