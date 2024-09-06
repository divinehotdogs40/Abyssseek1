<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "abyssseek";

include_once "header.php";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to check if the username exists in the admin_account table
    $sql_admin = "SELECT * FROM admin_account WHERE Email = ?";
    $stmt_admin = $conn->prepare($sql_admin);
    $stmt_admin->bind_param("s", $email);
    $stmt_admin->execute();
    $result_admin = $stmt_admin->get_result();

    // Query to check if the username exists in the created_account table
    $sql_user = "SELECT * FROM created_account WHERE Email = ?";
    $stmt_user = $conn->prepare($sql_user);
    $stmt_user->bind_param("s", $email);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();

    if ($result_admin->num_rows == 1) {
        $row = $result_admin->fetch_assoc();
        if (password_verify($password, $row['Password'])) {
            // Password is correct for admin, redirect to admin panel
            $_SESSION['email'] = $email;
            $_SESSION['user_id'] = $row['ID'];
            $_SESSION['EmailEntry'] = $row['Email'];
            header("Location: /abyssseek/admin/admin_pannel/admin_pannel.php");
            exit;
        } else {
            echo "Invalid email or password";
        }
    } elseif ($result_user->num_rows == 1) {
        $row = $result_user->fetch_assoc();
        if (password_verify($password, $row['Password'])) {
            // Password is correct for user, update last_login and user status to Online
            $_SESSION['email'] = $email;
            $_SESSION['user_id'] = $row['ID'];
            $_SESSION['EmailEntry'] = $row['Email'];

            // Update last_login in user_status table
            $sql_update_last_login = "UPDATE user_status SET last_login = CURRENT_TIMESTAMP WHERE email = ?";
            $stmt_update_last_login = $conn->prepare($sql_update_last_login);
            $stmt_update_last_login->bind_param("s", $email);
            $stmt_update_last_login->execute();

            // Set user status to Online
            $sql_update_status = "UPDATE user_status SET status = 'Online' WHERE email = ?";
            $stmt_update_status = $conn->prepare($sql_update_status);
            $stmt_update_status->bind_param("s", $email);
            $stmt_update_status->execute();

            // Insert login time into login table
            $sql_insert_login = "INSERT INTO login (email, login_time) VALUES (?, CURRENT_TIMESTAMP)";
            $stmt_insert_login = $conn->prepare($sql_insert_login);
            $stmt_insert_login->bind_param("s", $email);
            $stmt_insert_login->execute();

            // Redirect to user panel or homepage
            header("Location: index_in.php"); // Adjust the redirect URL as needed
            exit;
        } else {
            echo "Invalid email or password";
        }
    } else {
        echo "Invalid email or password";
    }

    $stmt_admin->close();
    $stmt_user->close();
}

$conn->close();
?>


<style>
    .panel-info>.panel-heading {
        color: #31708f;
        background-color: #d9edf7;
    }
</style>

<div class="container" style="padding-bottom:150px">
    <?php display_message(); ?>
    <div id="loginbox" style="margin-top: 230px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">Sign In</div>
            </div>

            <div style="padding-top:30px" class="panel-body">
                <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>

                <form id="loginform" class="form-horizontal" method="post" role="form">
                    <div style="margin-bottom: 25px" class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        <input type="text" class="form-control" name="email" value="" placeholder="email">
                    </div>
                    <div style="margin-bottom: 25px" class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                        <input type="password" class="form-control" name="password" placeholder="password">
                    </div>

                    <!-- Removed the Admin button and adjusted form submission accordingly -->
                    <div style="margin-top:10px" class="form-group">
                        <div class="col-sm-4 controls">
                            <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
                        </div>
                    </div>
                </form>

                <div class="form-group">
                    <div class="col-md-12 control">
                        <div class="text-primary" style="border-top: 1px solid#888; padding-top:15px; font-size:85%">
                            Don't have an account!
                            <a href="requestaccount.php">Request Account Here</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include_once "footer.php";
?>
