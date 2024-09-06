<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "abyssseek";

$connection = new mysqli($servername, $username, $password, $database);

$Admin_Name = "";
$Email = "";
$Password = "";
$Phone_Number = "";

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Admin_Name = $_POST["Admin_Name"];
    $Email = $_POST["Email"];
    $Password = $_POST["Password"];
    $Phone_Number = $_POST["Phone_Number"];

    // Check if any field is empty
    if (empty($Admin_Name) || empty($Email) || empty($Password) || empty($Phone_Number)) {
        $errorMessage = "All fields are required";
    } else {
        // Hash the password
        $hashed_password = password_hash($Password, PASSWORD_DEFAULT);
        
        // Check if email already exists in the database
        $stmt_check_email = $connection->prepare("SELECT Email FROM admin_account WHERE Email = ?");
        $stmt_check_email->bind_param("s", $Email);
        $stmt_check_email->execute();
        $result_check_email = $stmt_check_email->get_result();

        if ($result_check_email->num_rows > 0) {
            $errorMessage = "Email already exists";
        } else {
            // Insert into admin_account table
            $sql1 = "INSERT INTO admin_account (Admin_Name, Email, Password, Phone_Number) VALUES (?, ?, ?, ?)";
            
            // Prepare the statement
            $stmt = $connection->prepare($sql1);
            
            if ($stmt) {
                // Bind parameters
                $stmt->bind_param("ssss", $Admin_Name, $Email, $hashed_password, $Phone_Number);
                
                // Execute the statement
                if ($stmt->execute()) {
                    $successMessage = "New admin added successfully";
                    // JavaScript code to redirect to /abyssseek/admin/admin_account/index.php after 1 second
                    echo "<script>
                            setTimeout(function() {
                                window.location.href = '/abyssseek/admin/admin_account/index.php';
                            }, 1000); // 1000 milliseconds = 1 second
                          </script>";
                } else {
                    $errorMessage = "Error: " . $stmt->error;
                }
                
                // Close the statement
                $stmt->close();
            } else {
                $errorMessage = "Error preparing statement: " . $connection->error;
            }
        }
        
        // Close the check email statement
        $stmt_check_email->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        body {
            background: radial-gradient(circle, #3b8898 5%, #284a6f 25%, #151b3d 50%, #0a0a24 90%, #000000 100%);
            height: 100vh;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #000;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center">New Admin</h2>

        <?php if (!empty($errorMessage)) : ?>
            <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                <strong><?php echo $errorMessage; ?></strong>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($successMessage)) : ?>
            <div class='alert alert-success alert-dismissible fade show' role='alert'>
                <strong><?php echo $successMessage; ?></strong>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label for="Admin_Name" class="form-label">Admin Name</label>
                <input type="text" class="form-control" id="Admin_Name" name="Admin_Name">
            </div>
            <div class="mb-3">
                <label for="Email" class="form-label">Email</label>
                <input type="email" class="form-control" id="Email" name="Email">
            </div>
            <div class="mb-3">
                <label for="Password" class="form-label">Password</label>
                <input type="password" class="form-control" id="Password" name="Password">
            </div>
            <div class="mb-3">
                <label for="Phone_Number" class="form-label">Phone Number</label>
                <input type="text" class="form-control" id="Phone_Number" name="Phone_Number">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="/abyssseek/admin/admin_account/index.php" class="btn btn-secondary">Back</a>
        </form>
    </div>
</body>
</html>
