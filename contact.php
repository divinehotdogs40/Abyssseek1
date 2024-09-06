<?php include_once "header.php";

$servername = "localhost";
$username = "root";
$password = "";
$database = "abyssseek";

// Create connection
$connection = new mysqli($servername, $username, $password, $database);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $organization = $_POST['organization'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    $sql = "INSERT INTO Helps (name, organization, email, message) VALUES (?, ?, ?, ?)";

    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ssss", $name, $organization, $email, $message);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Message sent successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
}

$connection->close();
?>

<style>
    .custom-box {
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
    }
</style>

<div class="container-fluid" style="margin-top: 50px;">
    <div class="row">
        <div class="col-sm-3"></div>
        <div class="col-sm-6">
            <div class="custom-box">
                <h1 class="display-4 text-center">Contact Us</h1><br>
                <p class="lead" id="typing-text1">How can we help you seekers? Use the form below to contact our team.</p>
                <br>

                <form method="POST" action="">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
                    </div>
                    <div class="form-group">
                        <label for="organization">Organization:</label>
                        <input type="text" class="form-control" id="organization" name="organization" placeholder="Enter your organization" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address:</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Message:</label>
                        <textarea class="form-control" id="message" name="message" rows="3" placeholder="Enter your message" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Form</button>
                </form>

                <br>
                <p class="text-center" style="margin-top: 10px">
                    Contact us through <br>
                    +63 912 345 89<br>
                    abyssseek@gmail.com<br>
                </p>

                <div class="text-center">
                    <a href="#" class="contact-icons"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="contact-icons"><i class="bi bi-twitter"></i></a>
                    <a href="#" class="contact-icons"><i class="bi bi-instagram"></i></a>
                </div>
            </div>
            <div class="col-sm-3"></div>
        </div>
    </div>
</div>

<?php include_once "footer.php"; ?>
