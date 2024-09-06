<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "abyssseek";

$connection = new mysqli($servername, $username, $password, $database);

$Email = "";
$Password = "";
$First_Name = "";
$Last_Name = "";
$Position = "";
$Phone_Number = "";

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Email = $_POST["Email"];
    $Password = $_POST["Password"];
    $First_Name = $_POST["First_Name"];
    $Last_Name = $_POST["Last_Name"];
    $Position = $_POST["Position"];
    $Phone_Number = $_POST["Phone_Number"];

    // Check if any field is empty
    if (empty($Email) || empty($Password) || empty($First_Name) || empty($Last_Name) || empty($Position) || empty($Phone_Number)) {
        $errorMessage = "All fields are required";
    } else {
        // Check if email is valid
        if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
            $errorMessage = "Invalid email format";
        } else {
            // Check if email already exists in the database
            $stmt_check_email = $connection->prepare("SELECT Email FROM created_account WHERE Email = ?");
            $stmt_check_email->bind_param("s", $Email);
            $stmt_check_email->execute();
            $result_check_email = $stmt_check_email->get_result();

            if ($result_check_email->num_rows > 0) {
                $errorMessage = "Email already exists";
            } else {
                // Hash the password
                $hashed_password = password_hash($Password, PASSWORD_DEFAULT);

                // Prepare and bind the SQL statement for created_account table
                $sql_created_account = "INSERT INTO created_account (Email, Password, First_Name, Last_Name, Position, Date_Time, Phone_Number) VALUES (?, ?, ?, ?, ?, NOW(), ?)";
                $stmt_created_account = $connection->prepare($sql_created_account);
                $stmt_created_account->bind_param("ssssss", $Email, $hashed_password, $First_Name, $Last_Name, $Position, $Phone_Number);

                // Execute the statement for created_account table
                if ($stmt_created_account->execute()) {
                    $successMessage = "New user added successfully";

                    // Prepare and bind the SQL statement for user_status table
                    $sql_user_status = "INSERT INTO user_status (email, password, last_login) VALUES (?, ?, UNIX_TIMESTAMP())";
                    $stmt_user_status = $connection->prepare($sql_user_status);
                    $stmt_user_status->bind_param("ss", $Email, $hashed_password);

                    // Execute the statement for user_status table
                    $stmt_user_status->execute();

                    // JavaScript code to redirect to /abyssseek/admin/create_user/index.php after 1 second
                    echo "<script>
                            setTimeout(function() {
                                window.location.href = '/abyssseek/admin/create_user/index.php';
                            }, 1000); // 1000 milliseconds = 1 second
                          </script>";
                } else {
                    $errorMessage = "Error: " . $stmt_created_account->error;
                }

                $stmt_created_account->close();
                $stmt_user_status->close();
            }

            $stmt_check_email->close();
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
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
        <h2 class="text-center">New User</h2>

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
                <label for="Email" class="form-label">Email</label>
                <input type="email" class="form-control" id="Email" name="Email">
            </div>
            <div class="mb-3">
                <label for="Password" class="form-label">Password</label>
                <input type="password" class="form-control" id="Password" name="Password">
            </div>
            <div class="mb-3">
                <label for="First_Name" class="form-label">First Name</label>
                <input type="text" class="form-control" id="First_Name" name="First_Name">
            </div>
            <div class="mb-3">
                <label for="Last_Name" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="Last_Name" name="Last_Name">
            </div>
            <div class="mb-3">
                <label for="Position" class="form-label">Position</label>
                <input type="text" class="form-control" id="Position" name="Position">
            </div>
            <div class="mb-3">
                <label for="Phone_Number" class="form-label">Phone Number</label>
                <input type="text" class="form-control" id="Phone_Number" name="Phone_Number">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="/abyssseek/admin/create_user/index.php" class="btn btn-secondary">Back</a>
        </form>
    </div>
</body>
</html>
