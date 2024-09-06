<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abyssseek Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
    body {
      background: #eee;
      font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
      font-size: 14px;
      color: #000;
      margin: 0;
      padding: 0;
    }

    :root {
    --main-color1: #673de6;
    --main-color2: #fc5185;
    --main-color3: rgba(0, 0, 0, 0.8);
    --text-color: #2f1c6a;
    --gerideant-color: linear-gradient(163deg, 
        rgba(103, 61, 230, 0.9836309523809523) 23%, 
        rgba(252, 81, 133, 1) 100%);
    --gerideant-color2: rgb(103, 61, 230);
}

        .container {
            background-color: #287bff;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
            color: black;
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 90%;
        }

        .swiper-container {
            width: 100%;
        }

        .swiper-slide {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .content-box {
            background: url('backgroun.jpg') center center/cover no-repeat;
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 550px;
            width: 400px;
            position: relative;
            z-index: 1;
        }

        .image-container {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .image-box {
            width: 180px;
            height: 250px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #ccc;
            background: #fff;
            margin-top: 20px;
            padding: 10px;
            box-sizing: border-box;
        }

        .image-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px;
        }

        .name-position {
            color: white;
            margin-top: 80px;
            text-align: center;
            margin-bottom: 20px;
        }

        .name-position h5 {
            margin: 0;
            font-size: 25px;
            font-weight: bold;
        }

        .name-position p {
            margin: 5px 0;
            font-size: 16px;
            color: white;
        }

        .button-container {
            display: flex;
            gap: 10px;
        }

        .swiper-button-next,
        .swiper-button-prev {
            color: #000;
        }

        .red-box {
            width: 800px;
            height: 800px;
            background-color: white;
            display: none;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 2;
            overflow-y: auto;
            padding: 20px;
            border-radius: 10px;
            color: black;
        }

        #closeIcon {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            font-size: 20px;
            color: black;
        }

        .table {
            width: 100%;
            margin: 40px auto 0;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="container mt-5 text-center">
        <div class="mb-4">
            <h2 style="background: white; -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Abyssseek Account Requests</h2>
        </div>
        <div id="redBox" class="red-box">
            <!-- Close icon -->
            <div id="closeIcon" onclick="closeRedBox()">
                <i class="fas fa-times fa-lg"></i>
            </div>
            <?php
            // Include necessary PHPMailer files
            use PHPMailer\PHPMailer\PHPMailer;
            use PHPMailer\PHPMailer\Exception;

            require 'Exception.php';
            require 'PHPMailer.php';
            require 'SMTP.php';

            // Database connection parameters
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "abyssseek";

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Handle form submission
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Check if approve or reject button is clicked
                if (isset($_POST['approve'])) {
                    // Handle approve action
                    $email = $_POST['email'];
                    $id = $_POST['id']; // Added line to get the ID
                    // Perform approval action
                    // You can perform database updates or any other necessary actions here
                    // Then send email notification
                    echo sendEmailNotification($email, 'approved', $id);
                } elseif (isset($_POST['reject'])) {
                    // Handle reject action
                    $id = $_POST['id']; // Get the ID of the form to be deleted

                    // Perform deletion from database
                    $sql_delete = "DELETE FROM requests WHERE id = $id";
                    if ($conn->query($sql_delete) === TRUE) {
                        // Echo JavaScript function to close red box
                        echo "<script>closeRedBox();</script>";
                    } else {
                        echo "Error: " . $conn->error;
                    }
                }
            }

            // Displaying form information
            $sql = "SELECT * FROM requests";
            $result = $conn->query($sql);

            if ($result !== false && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Each form info is wrapped in a div with a unique ID and a class for easier selection
                    echo "<div id='formInfo_{$row["email"]}' class='form-info' style='display: none;'>";
                    echo "<table class='table'>";
                    echo "<tr><th>Full Name</th><td>{$row["first_name"]} {$row["middle_name"]} {$row["last_name"]}</td></tr>";
                    echo "<tr><th>Email</th><td>{$row["email"]}</td></tr>";
                    echo "<tr><th>Address</th><td>{$row["Address"]}</td></tr>";
                    echo "<tr><th>Age</th><td>{$row["Age"]}</td></tr>";
                    echo "<tr><th>Sex</th><td>{$row["Sex"]}</td></tr>";
                    echo "<tr><th>Civil Status</th><td>{$row["CivilStatus"]}</td></tr>";
                    echo "<tr><th>Date of Birth</th><td>{$row["DateOfBirth"]}</td></tr>";
                    echo "<tr><th>Mobile Number</th><td>{$row["MobileNum"]}</td></tr>";
                    echo "<tr><th>Telephone Number</th><td>{$row["TelephoneNum"]}</td></tr>";
                    echo "<tr><th>Personnel Type</th><td>{$row["PersonnelType"]}</td></tr>";
                    echo "<tr><th>Department</th><td>{$row["Department"]}</td></tr>";
                    echo "<tr><th>Department ID</th><td>{$row["Departmentid"]}</td></tr>";
                    echo "<tr><th>Branch of Service</th><td>{$row["BranchofService"]}</td></tr>";
                    echo "<tr><th>Rank</th><td>{$row["Rank"]}</td></tr>";
                    echo "<tr><th>Position</th><td>{$row["Position"]}</td></tr>";
                    echo "</table>";
                    echo "</div>";
                }
            } else {
                echo "0 results";
            }
            ?>
        </div>

        <div class="swiper-container">
            <div class="swiper-wrapper">
                <?php
                // Create connection
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // SQL query to retrieve data from the table
                $sql = "SELECT * FROM requests";

                // Execute the query
                $result = $conn->query($sql);

                // Displaying swiper slides and buttons
                if ($result !== false && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
        // Each form info is wrapped in a div with a unique ID and a class for easier selection
        echo "<div id='formInfo_{$row["email"]}' class='form-info' style='display: none;'>";
        echo "<table class='table'>";
        echo "<tr><th>Full Name</th><td>{$row["first_name"]} {$row["middle_name"]} {$row["last_name"]}</td></tr>";
        echo "<tr><th>Email</th><td>{$row["email"]}</td></tr>";
        echo "<tr><th>Address</th><td>{$row["Address"]}</td></tr>";
        echo "<tr><th>Age</th><td>{$row["Age"]}</td></tr>";
        echo "<tr><th>Sex</th><td>{$row["Sex"]}</td></tr>";
        echo "<tr><th>Civil Status</th><td>{$row["CivilStatus"]}</td></tr>";
        echo "<tr><th>Date of Birth</th><td>{$row["DateOfBirth"]}</td></tr>";
        echo "<tr><th>Mobile Number</th><td>{$row["MobileNum"]}</td></tr>";
        echo "<tr><th>Telephone Number</th><td>{$row["TelephoneNum"]}</td></tr>";
        echo "<tr><th>Personnel Type</th><td>{$row["PersonnelType"]}</td></tr>";
        echo "<tr><th>Department</th><td>{$row["Department"]}</td></tr>";
        echo "<tr><th>Department ID</th><td>{$row["Departmentid"]}</td></tr>";
        echo "<tr><th>Branch of Service</th><td>{$row["BranchofService"]}</td></tr>";
        echo "<tr><th>Rank</th><td>{$row["Rank"]}</td></tr>";
        echo "<tr><th>Position</th><td>{$row["Position"]}</td></tr>";
        echo "</table>";
        echo "</div>";
    }
} else {
    echo "0 results";
}
            ?>
        </div>

        <div class="swiper-container">
            <div class="swiper-wrapper">
                <?php
                // Create connection
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // SQL query to retrieve data from the table
                $sql = "SELECT * FROM requests";

                // Execute the query
                $result = $conn->query($sql);

                // Displaying swiper slides and buttons
                if ($result !== false && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='swiper-slide'>";
                        echo "<div class='content-box' onclick=\"toggleRedBox('{$row["email"]}');\">";
                        echo "<div class='image-container'>";
                        echo "<div class='image-box' onclick='showFullImage(\"uploads/{$row["FrontPic"]}\")'><img id='frontPic_{$row["email"]}' src='uploads/{$row["FrontPic"]}' alt='Front Image'></div>";
                        echo "<div class='image-box' onclick='showFullImage(\"uploads/{$row["BackPic"]}\")'><img id='backPic_{$row["email"]}' src='uploads/{$row["BackPic"]}' alt='Back Image'></div>";
                                                              
                        echo "</div>";
                        echo "<div class='name-position'>";
                        echo "<h5>{$row["first_name"]} {$row["middle_name"]} {$row["last_name"]}</h5>";
                        echo "<p>{$row["PersonnelType"]}</p>";
                        echo "</div>";

                        echo "<div class='button-container'>";
                        echo "<form id='approveForm_{$row["email"]}' method='post' action='create1.php'>"; // Updated action attribute
                        echo "<input type='hidden' name='email' value='{$row["email"]}'>";
                        echo "<input type='hidden' name='id' value='{$row["id"]}'>";
                        echo "<button type='submit' name='approve' class='btn btn-success'>Approve</button>";
                        echo "</form>";
                        
                        echo "</form>";
                        echo "<form id='rejectForm_{$row["email"]}' method='post' action=''>";
                        echo "<input type='hidden' name='email' value='{$row["email"]}'>";
                        echo "<input type='hidden' name='id' value='{$row["id"]}'>";

                        echo "<button type='submit' name='reject' class='btn btn-danger' onclick='confirmReject(event)'>Reject</button>";
                        echo "</form>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "0 results";
                }
                
                ?>


            </div>
        </div>
        <!-- Add Pagination -->
        <div class="swiper-pagination"></div>

        <!-- Add Navigation -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>

    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        var swiper = new Swiper('.swiper-container', {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });

        function toggleRedBox(email) {
            var redBox = document.getElementById('redBox');
            var formInfo = document.getElementById('formInfo_' + email);

            // Check if the clicked element is FrontPic or BackPic
            var clickedElement = event.target;
            if (clickedElement.tagName === 'IMG' && (clickedElement.id.includes('frontPic') || clickedElement.id.includes('backPic'))) {
                return; // Do nothing if FrontPic or BackPic is clicked
            }

            if (redBox.style.display === 'none' || !formInfo) {
                // Hide all other form infos initially
                var allFormInfos = redBox.querySelectorAll('.form-info');
                allFormInfos.forEach(info => {
                    info.style.display = 'none';
                });

                // Show the clicked form info and red box
                if (formInfo) {
                    formInfo.style.display = 'block';
                    redBox.style.display = 'block';
                } else {
                    // If formInfo not found, hide red box
                    redBox.style.display = 'none';
                }
            } else {
                // Toggle visibility of red box and form info
                if (formInfo.style.display === 'block') {
                    redBox.style.display = 'none';
                } else {
                    // Hide all other form infos and show the clicked form
                    var allFormInfos = redBox.querySelectorAll('.form-info');
                    allFormInfos.forEach(info => {
                        info.style.display = 'none';
                    });
                    formInfo.style.display = 'block';
                    redBox.style.display = 'block';
                }
            }
        }

        function closeRedBox() {
            var redBox = document.getElementById('redBox');
            redBox.style.display = 'none';
        }

        function confirmReject(event) {
            if (!confirm("Are you sure you want to reject this form?")) {
                event.preventDefault();
            }
        }

        function showFullImage(imageUrl) {
            var modal = document.createElement('div');
            modal.style.position = 'fixed';
            modal.style.top = '0';
            modal.style.left = '0';
            modal.style.width = '100%';
            modal.style.height = '100%';
            modal.style.backgroundColor = 'rgba(0, 0, 0, 0.8)';
            modal.style.zIndex = '1000';
            modal.style.display = 'flex';
            modal.style.justifyContent = 'center';
            modal.style.alignItems = 'center';
            modal.style.overflow = 'auto';

            var image = document.createElement('img');
            image.src = imageUrl;
            image.style.maxWidth = '90%';
            image.style.maxHeight = '90%';
            image.style.objectFit = 'contain';
            image.style.borderRadius = '10px';

            modal.appendChild(image);
            document.body.appendChild(modal);

            // Close modal when clicking outside the image
            modal.addEventListener('click', function(event) {
                if (event.target === modal) {
                    modal.remove();
                }
            });

            // Close modal on escape key press
            document.addEventListener('keydown', function(event) {
                if (event.key === "Escape") {
                    modal.remove();
                }
            });
        }
    </script>

</body>
</html>
