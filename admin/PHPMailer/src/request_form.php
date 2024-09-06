<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abyssseek Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        body {
            background: radial-gradient(circle, #3b8898 5%, #284a6f 25%, #151b3d 50%, #0a0a24 90%, #000000 100%);
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            overflow-y: auto;
            min-height: 100vh;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
            color: black;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 50px;
            width: 90%;
        }

        .list-group-wrapper {
            width: 100%;
        }

        .list-group {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .list-group-item {
            margin: 10px;
            width: 100%;
            height: auto;
            background-color: #3A3985;
            border: none;
            color: white;
            font-size: 14px;
            overflow: hidden;
            position: relative;
            padding-bottom: 60px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .list-group-item img {
            max-width: 80px;
            height: auto;
        }

        .button-container {
            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            display: flex;
            justify-content: center;
        }

        .additional-info {
            display: none;
        }

        .toggle-button {
            cursor: pointer;
            color: #45acab;
            font-weight: bold;
            text-align: center;
        }

        .list-group-item .d-flex {
            flex-direction: column;
            align-items: center;
        }

        .list-group-item .d-flex h5,
        .list-group-item .d-flex p {
            text-align: center;
        }

        .personnel-type-heading {
            width: 100%;
            text-align: center;
            margin-top: 20px;
            font-weight: bold;
            border-bottom: 2px solid white;
            padding-bottom: 5px;
        }

        .civilian-heading {
            color: #000000;
        }

        .military-heading {
            color: #000000;
        }
    </style>
</head>
<body>
    <div class="container mt-5 text-center">
        <div class="mb-4">
            <h2 style="background: black; -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Abyssseek Requests</h2>
        </div>
        <?php
        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\Exception;

        require '../../phpmailer/src/Exception.php';
        require '../../phpmailer/src/PHPMailer.php';
        require '../../phpmailer/src/SMTP.php';

        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "abyssseek";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['approve'])) {
                $email = $_POST['email'];
                $subject = "Your request has been approved";
                $message = "Your request has been approved. Thank you.";
                echo sendEmailNotification($email, $subject, $message);
            } elseif (isset($_POST['reject'])) {
                $email = $_POST['email'];
                $subject = "Your request has been rejected222";
                $message = "Your request has been rejected222. Thank you.";
                echo sendEmailNotification($email, $subject, $message);
            }
        }

        function sendEmailNotification($to, $subject, $message) {
            $mail = new PHPMailer(true);
            try {
                // SMTP server configuration
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'divinehotdogs40@gmail.com'; // Your Gmail address
                $mail->Password = 'mriv dane mzgi pzhx';  // Your Gmail password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port = 465;

                // Email content
                $mail->setFrom('divinehotdogs40@gmail.com', 'Abyssseek Admin');
                $mail->addAddress($to);
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $message;

                // Send email
                $mail->send();
                return "Email sent successfully.";
            } catch (Exception $e) {
                return "Failed to send email. Error: {$mail->ErrorInfo}";
            }
        }

        $sql = "SELECT * FROM requests ORDER BY PersonnelType";
        $result = $conn->query($sql);

        $currentPersonnelType = "";
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if ($currentPersonnelType != $row["PersonnelType"]) {
                    if ($currentPersonnelType != "") {
                        echo "</div>"; // Close previous list-group
                        echo "</div>"; // Close previous list-group-wrapper
                    }
                    $currentPersonnelType = $row["PersonnelType"];
                    $headingClass = $currentPersonnelType === 'Civilian' ? 'civilian-heading' : 'military-heading';
                    echo "<div class='personnel-type-heading $headingClass'>{$currentPersonnelType}</div>";
                    echo "<div class='list-group-wrapper'>";
                    echo "<div class='list-group'>";
                }
                echo "<div class='list-group-item list-group-item-action flex-column align-items-start' onclick='toggleInfo(event, this)'>";
                echo "<div class='d-flex w-100 justify-content-between'>";
                echo "<h5 class='mb-1'>{$row["name"]}</h5>";
                echo "<p class='mb-1'>Personnel Type: {$row["PersonnelType"]}</p>";
                echo "</div>";
                echo "<div class='additional-info'>";
                echo "<h5>{$row["email"]}</h5>";
                echo "<p class='mb-1'>Address: {$row["Address"]}</p>";
                echo "<p class='mb-1'>Age: {$row["Age"]}, Sex: {$row["Sex"]}, Civil Status: {$row["CivilStatus"]}</p>";
                echo "<p class='mb-1'>Date of Birth: {$row["DateOfBirth"]}</p>";
                echo "<p class='mb-1'>Address: {$row["Address"]}</p>";
                echo "<p class='mb-1'>Mobile Number: {$row["MobileNum"]}, Telephone Number: {$row["TelephoneNum"]}</p>";
                echo "<p class='mb-1'>Personnel Type: {$row["PersonnelType"]}, Department: {$row["Department"]}, Department ID: {$row["Departmentid"]}</p>";
                echo "<p class='mb-1'>Branch of Service: {$row["BranchofService"]}, Rank: {$row["Rank"]}, Position: {$row["Position"]}</p>";

                // Check image paths
                $frontPicPath = "uploads/{$row["FrontPic"]}";
                $backPicPath = "uploads/{$row["BackPic"]}";
                echo "<p class='mb-1'>Front Picture Path: $frontPicPath</p>";
                echo "<p class='mb-1'>Back Picture Path: $backPicPath</p>";

                // Check if images exist
                if (file_exists($frontPicPath) && file_exists($backPicPath)) {
                    // Output images
                    echo "<img src='$frontPicPath' alt='Front Picture'>";
                    echo "<img src='$backPicPath' alt='Back Picture'>";
                } else {
                    // Output error message if images not found
                    echo "<p class='text-danger'>Image not found.</p>";
                }

                echo "</div>"; // Close additional-info div
                echo "<div class='button-container mt-3'>";
                echo "<form id='approveForm_{$row["email"]}' method='post' action='' style='display: inline;'>";
                echo "<input type='hidden' name='email' value='{$row["email"]}'>";
                echo "<button type='submit' name='approve' class='btn btn-success' onclick='confirmApprove(event, \"{$row["email"]}\")'>Approve</button>";
                echo "</form>";
                echo "<form id='rejectForm_{$row["email"]}' method='post' action='' style='display: inline;'>";
                echo "<input type='hidden' name='email' value='{$row["email"]}'>";
                echo "<button type='submit' name='reject' class='btn btn-danger' onclick='confirmReject(event, \"{$row["email"]}\")' >Reject</button>";
                echo "</form>";
                echo "</div>";
                echo "</div>";
            }
            echo "</div>"; // Close the last list-group
            echo "</div>"; // Close the last list-group-wrapper
        } else {
            echo "0 results";
        }

        $conn->close();
        ?>
    </div>
    <script>
        function toggleInfo(event, element) {
            event.stopPropagation();
            const info = element.querySelector('.additional-info');
            if (info.style.display === 'block') {
                info.style.display = 'none';
            } else {
                info.style.display = 'block';
            }
        }

        function confirmApprove(event, email) {
            event.preventDefault();
            if (confirm('Are you sure you want to approve this request?')) {
                document.getElementById('approveForm_' + email).submit();
            }
        }

        function confirmReject(event, email) {
            event.preventDefault();
            if (confirm('Are you sure you want to reject this request?')) {
                document.getElementById('rejectForm_' + email).submit();
            }
        }
    </script>
</body>
</html>
