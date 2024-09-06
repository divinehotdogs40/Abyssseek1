<?php
// Include necessary PHPMailer files
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'Exception.php';
require 'PHPMailer.php';
require 'SMTP.php';

// Function to send email notification using PHPMailer
function sendEmailNotification($to, $firstName, $lastName, $position, $email, $password, $status, $id = null) {
    $mail = new PHPMailer(true);

    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'AbyssSeek@gmail.com'; // Your Gmail address
        $mail->Password = 'ghzh yqvw mcgx vbvv';  // Your Gmail password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Sender and recipient
        $mail->setFrom('AbyssSeek@gmail.com'); // Set from address
        $mail->addAddress($to); // Add recipient email address

        // Full name
        $fullName = $firstName . ' ' . $lastName;

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Abyssseek Request Status'; // Subject of the email
        $mail->Body = '
       <!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #000000; /* Fallback color */
            background: radial-gradient(circle, #3b8898 5%, #284a6f 25%, #151b3d 50%, #0a0a24 90%, #000000 100%);
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding: 10px 0;
        }
        .header h1 {
            margin: 0;
            color: black;
        }
        .content {
            margin: 20px 0;
        }
        .content p {
            font-size: 16px;
            color: #000000;
            line-height: 1.5;
        }
        .credentials {
            margin: 20px 0;
        }
        .credentials p {
            font-size: 16px;
            color: black;
            line-height: 1.5;
        }
        .footer {
            text-align: center;
            padding: 10px 0;
            font-size: 14px;
            color: #999999;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome Seeker</h1>
        </div>
        <div class="content">
            <p>Dear ' . $fullName . ',</p>
            <p>We are excited to have you on board. Here are your login credentials to access our website:</p>
            <div class="credentials">
              <p><strong>Email: ' . $email . '</p></strong>
          <p><strong>Password: ' . $password . '</p></strong>
            </div>
            <p>Please keep this information secure and do not share it with anyone.</p>
            <p>If you have any questions or need further assistance, feel free to reach out to our support team.</p>
            <p>Best regards,</p>
            <p>Abyssseek Team</p>
        </div>
        <div class="footer">
            <p>&copy; 2024 Abyssseek. All rights reserved.</p>
        </div>
    </div>
</body>
</html>';
        // Send email
        $mail->send();

        // Redirect to create1.php if approval and ID provided
        if ($status === 'approved' && $id !== null) {
            header("Location: create1.php?id=$id");
            exit();
        }

        return "Email sent successfully.";
    } catch (Exception $e) {
        return "Failed to send email. Error: {$mail->ErrorInfo}";
    }
}

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

// Check if request_id is provided in the URL parameters
if (isset($_GET['id'])) {
    $request_id = $_GET['id'];

    // Fetch data from requests table using the request ID
    $stmt_fetch_request = $connection->prepare("SELECT email, first_name, last_name, Position, MobileNum FROM requests WHERE id = ?");
    $stmt_fetch_request->bind_param("i", $request_id);
    $stmt_fetch_request->execute();
    $result_fetch_request = $stmt_fetch_request->get_result();

    if ($result_fetch_request->num_rows > 0) {
        $row = $result_fetch_request->fetch_assoc();
        $Email = $row['email'];
        $First_Name = $row['first_name'];
        $Last_Name = $row['last_name'];
        $Position = $row['Position'];
        $Phone_Number = $row['MobileNum'];
    } else {
        $errorMessage = "Invalid request ID";
    }

    $stmt_fetch_request->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Only execute validation logic if the form is submitted
    if (isset($_POST["Email"])) {
        $Email = $_POST["Email"];
    }
    if (isset($_POST["Password"])) {
        $Password = $_POST["Password"];
    }
    if (isset($_POST["First_Name"])) {
        $First_Name = $_POST["First_Name"];
    }
    if (isset($_POST["Last_Name"])) {
        $Last_Name = $_POST["Last_Name"];
    }
    if (isset($_POST["Position"])) {
        $Position = $_POST["Position"];
    }
    if (isset($_POST["Phone_Number"])) {
        $Phone_Number = $_POST["Phone_Number"];
    }

    // Check if any field is empty
    if (empty($Email) || empty($Password) || empty($First_Name) || empty($Last_Name) || empty($Position) || empty($Phone_Number)) {

    } else {
        // Validation and database operations continue as before
        // ...
    }
}

        // Check if email is valid
        if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
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

                    // Send email notification
                    $emailStatus = sendEmailNotification($Email, $First_Name, $Last_Name, $Position, $Email, $Password, 'approved');

                    // Check email sending status
                    if ($emailStatus !== "Email sent successfully.") {
                        $errorMessage = "Error sending email: " . $emailStatus;
                    }

                    // JavaScript code to redirect to /abyssseek/admin/request_form/request_form.php after 1 second
                    echo "<script>
                            setTimeout(function() {
                                window.location.href = '/abyssseek/admin/request_form/request_form.php';
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
                <input type="email" class="form-control" id="Email" name="Email" value="<?php echo htmlspecialchars($Email); ?>">
            </div>
            <div class="mb-3">
                <label for="Password" class="form-label">Password</label>
                <input type="password" class="form-control" id="Password" name="Password" value="<?php echo htmlspecialchars($Password); ?>">
            </div>
            <div class="mb-3">
                <label for="First_Name" class="form-label">First Name</label>
                <input type="text" class="form-control" id="First_Name" name="First_Name" value="<?php echo htmlspecialchars($First_Name); ?>">
            </div>
            <div class="mb-3">
                <label for="Last_Name" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="Last_Name" name="Last_Name" value="<?php echo htmlspecialchars($Last_Name); ?>">
            </div>
            <div class="mb-3">
                <label for="Position" class="form-label">Position</label>
                <input type="text" class="form-control" id="Position" name="Position" value="<?php echo htmlspecialchars($Position); ?>">
            </div>
            <div class="mb-3">
                <label for="Phone_Number" class="form-label">Phone Number</label>
                <input type="text" class="form-control" id="Phone_Number" name="Phone_Number" value="<?php echo htmlspecialchars($Phone_Number); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="/abyssseek/admin/request_form/request_form.php" class="btn btn-secondary">Back</a>
        </form>
    </div>
</body>
</html>
