<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
}

?>

<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            color: #ffff;
            font-family: main, sans-serif, monospace;
            margin-bottom: 200px;
            background: radial-gradient(circle, #3b8898 5%, #284a6f 25%, #151b3d 50%, #0a0a24 90%, #000000 100%);
            background-size: contain;
            color: black;
            font-size: 18px;
            height: 100%;
            background-position: center; /* Align the background image to the center */
        }

        .container {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #f0f0f0;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 1500px;
            height: 500px; /* Set a fixed height */
            overflow: auto; /* Enable vertical scrolling */
        }

        /* Style for the table */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            white-space: nowrap; /* Prevent text wrapping */
            overflow: hidden; /* Hide overflowed text */
            text-overflow: ellipsis; /* Display ellipsis for overflowed text */
            max-width: 200px; /* Set a maximum width for the cells */
            top: 500px;
        }

        th {
            background-color: #f2f2f2;
            position: sticky;
            top: 0; /* Stick to the top of the container */
            z-index: 1; /* Ensure it's above the content */
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
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

        $email = $_SESSION['EmailEntry'];
        $sql = "SELECT * FROM history_webcrawler WHERE Email = '$email' ORDER BY ID DESC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table>
            <thead>
            <tr>
            <th style='top: -33px;'>Time</th>
            <th style='top: -33px;'>Link</th>
            <th style='top: -33px;'>Keyword</th>
            <th style='top: -33px;'>Limit</th>
            <th style='top: -33px;'>Search Mode</th>
            </tr>
            </thead>
            <tbody>";
            // output data of each row
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                <td style='width: 150px;'>".$row["Time"]."</td>
                <td style='width: 200px;'>".$row["Link"]."</td>
                <td style='width: 150px;'>".$row["Keyword"]."</td>
                <td style='width: 100px;'>".$row["LimitCrawls"]."</td>
                <td style='width: 100px;'>".$row["SearchMode"]."</td>
                </tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "0 results";
        }
        $conn->close();
        ?>
    </div>
</body>
</html>