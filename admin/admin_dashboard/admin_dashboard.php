<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User and Admin Count Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-3d.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    
    <style>
        body {
            background: white;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .dashboard {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: flex-start;
            padding: 20px;
            box-sizing: border-box;
        }

        .widget1 {
            background-color: rgba(255, 153, 153, 0.6); /* Light red background */
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin: 20px;
            padding: 20px;
            width: 300px;
            margin-right: 125px; /* Add this line to push it to the left */
            margin-left: 0; /* Add this line to reset any left margin */
        }

        .image-container {
            width: 50px; /* Adjust as needed */
            margin-left: -160px; /* Positioned to the left of Users widget */
            margin-right: 0px;
        }

        .image1 {
            width: 170%; /* Adjusted size */
            height: 100px;
            margin-top: 37px; /* Move down the picture */
            margin-left: -90px;
        }

        .widget2 {
            background-color: #ADD8E6; /* Light blue background */
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin: 20px;
            padding: 20px;
            width: 300px;
            margin-right: 125px; /* Add this line to push it to the left */
            margin-left: 0; /* Add this line to reset any left margin */
        }

        .image2-container {
            width: 50px; /* Adjust as needed */
            margin-left: -160px; /* Positioned to the left of Users widget */
            margin-right: 0px;
        }

        .image2 {
            width: 170%; /* Adjusted size */
            height: 100px;
            margin-top: 37px; /* Move down the picture */
            margin-left: -90px;
        }

        .widget3 {
            background-color: #92a8d1; /* Light purple background */
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin: 20px;
            padding: 20px;
            width: 300px;
            margin-right: 125px; /* Add this line to push it to the left */
            margin-left: 0; /* Add this line to reset any left margin */
        }

        .image3-container {
            width: 50px; /* Adjust as needed */
            margin-left: -160px; /* Positioned to the left of Users widget */
            margin-right: 0px;
        }

        .image3 {
            width: 170%; /* Adjusted size */
            height: 100px;
            margin-top: 37px; /* Move down the picture */
            margin-left: -90px;
        }

        .widget h2 {
            color: #333;
            margin-bottom: 10px;
        }

        .widget p {
            color: #666;
        }

        .highcharts-figure1,
        .highcharts-data-table table {
            min-width: 250px;
            max-width: 850px;
        }

        .highcharts-data-table table {
            font-family: Verdana, sans-serif;
            border-collapse: collapse;
            border: 1px solid #ebebeb;
            margin: 10px auto;
            text-align: center;
            width: 100%;
            max-width: 500px;
        }

        .highcharts-data-table caption {
            padding: 1em 0;
            font-size: 1.2em;
            color: #555;
        }

        .highcharts-data-table th {
            font-weight: 600;
            padding: 0.5em;
        }

        .highcharts-data-table td,
        .highcharts-data-table th,
        .highcharts-data-table caption {
            padding: 0.5em;
        }

        .highcharts-data-table thead tr,
        .highcharts-data-table tr:nth-child(even) {
            background: #f8f8f8;
        }

        .highcharts-data-table tr:hover {
            background: #f1f7ff;
        }

        #container {
            height: 400px;
        }

        .highcharts-figure1 {
            min-width: 250px;
            max-width: 750px;
        }

        .highcharts-data-table table {
            font-family: Verdana, sans-serif;
            border-collapse: collapse;
            border: 1px solid #ebebeb;
            margin: 10px auto;
            text-align: center;
            width: 100%;
            max-width: 500px;
        }

        .highcharts-data-table caption {
            padding: 1em 0;
            font-size: 1.2em;
            color: #555;
        }

        .highcharts-data-table th {
            font-weight: 600;
            padding: 0.5em;
        }

        .highcharts-data-table td,
        .highcharts-data-table th,
        .highcharts-data-table caption {
            padding: 0.5em;
        }

        .highcharts-data-table thead tr,
        .highcharts-data-table tr:nth-child(even) {
            background: #f8f8f8;
        }

        .highcharts-data-table tr:hover {
            background: #f1f7ff;
        }

        #container {
            height: 400px;

        }
    </style>

<style>
    #container2 { /* Updated ID */
    height: 400px;
}

.highcharts-figure2,
.highcharts-data-table table {
    min-width: 700px;
    max-width: 2900px;
    margin-top: -422px;
    margin-left: 780px;
  
}

.highcharts-data-table table {
    font-family: Verdana, sans-serif;
    border-collapse: collapse;
    border: 1px solid #ebebeb;
    margin: 10px auto;
    text-align: center;
    width: 100%;
    max-width: 500px;
}

.highcharts-data-table caption {
    padding: 1em 0;
    font-size: 1.2em;
    color: #555;
}

.highcharts-data-table th {
    font-weight: 600;
    padding: 0.5em;
}

.highcharts-data-table td,
.highcharts-data-table th,
.highcharts-data-table caption {
    padding: 0.5em;
}

.highcharts-data-table thead tr,
.highcharts-data-table tr:nth-child(even) {
    background: #f8f8f8;
}


.highcharts-data-table tr:hover {
    background: #f1f7ff;
}

</style>
</head>
<body>

<?php
// Database credentials
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

// Query to count total requests
$sql_total_requests = "SELECT COUNT(*) AS total_requests FROM requests"; // Replace 'your_table_name' with your actual table name
$result_total_requests = $conn->query($sql_total_requests);
$total_requests = 0; // Initialize total requests count
if ($result_total_requests && $result_total_requests->num_rows > 0) {
    $row_total_requests = $result_total_requests->fetch_assoc();
    $total_requests = $row_total_requests["total_requests"]; // Store total requests count
}

// Query to count user accounts by week
$sql_user = "SELECT YEAR(Date_Time) AS year, WEEK(Date_Time) AS week, COUNT(*) AS user_count FROM created_account GROUP BY YEAR(Date_Time), WEEK(Date_Time)";
$result_user = $conn->query($sql_user);
$user_data = array_fill(1, 31, 0); // Initialize array with zeros for each week of the year
if ($result_user && $result_user->num_rows > 0) {
    while ($row_user = $result_user->fetch_assoc()) {
        $week = $row_user["week"];
        $user_data[$week] = $row_user["user_count"]; // Store count for each week
    }
}

// Query to count admin accounts
$sql_admin = "SELECT COUNT(*) AS total_admin_count FROM admin_account"; // Modified query
$result_admin = $conn->query($sql_admin);
$total_admin_count = 0; // Initialize total admin count
if ($result_admin && $result_admin->num_rows > 0) {
    $row_admin = $result_admin->fetch_assoc();
    $total_admin_count = $row_admin["total_admin_count"]; // Store total admin count
}

// Query to count users
$sql_user_count = "SELECT COUNT(*) AS user_count FROM created_account";
$result_user_count = $conn->query($sql_user_count);
$user_count = 0; // Initialize user count
if ($result_user_count && $result_user_count->num_rows > 0) {
    $row_user_count = $result_user_count->fetch_assoc();
    $user_count = $row_user_count["user_count"]; // Store user count
}

// Query to count admins
$sql_admin_count = "SELECT COUNT(*) AS admin_count FROM admin_account";
$result_admin_count = $conn->query($sql_admin_count);
$admin_count = 0; // Initialize admin count
if ($result_admin_count && $result_admin_count->num_rows > 0) {
    $row_admin_count = $result_admin_count->fetch_assoc();
    $admin_count = $row_admin_count["admin_count"]; // Store admin count
}

// Query to count requests per month
$sql = "SELECT MONTH(Date_Time) as month, COUNT(*) as count 
        FROM requests 
        WHERE YEAR(Date_Time) = YEAR(CURDATE())
        GROUP BY MONTH(Date_Time)";
$result = $conn->query($sql);

$data = array_fill(0, 12, ['name' => '', 'y' => 0]); // Initialize an array for 12 months with empty values

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[$row['month'] - 1] = ['name' => date('M', mktime(0, 0, 0, $row['month'], 1)), 'y' => (int)$row['count']];
    }
}

// Query to count total requests
$sql_total_requests = "SELECT COUNT(*) AS total_requests FROM requests";
$result_total_requests = $conn->query($sql_total_requests);
$total_requests = 0; // Initialize total requests count
if ($result_total_requests && $result_total_requests->num_rows > 0) {
    $row_total_requests = $result_total_requests->fetch_assoc();
    $total_requests = (int)$row_total_requests["total_requests"]; // Store total requests count
}


// Close connection
$conn->close();
?>

<div class="dashboard">
    <div class="widget1">
        <h2>Users</h2>
        <p>Total Users: <?php echo $user_count; ?></p>
    </div>

    <div class="image-container">
        <img src="1.png" alt="User Icon" class="image1">
    </div>

    <div class="widget2">
        <h2>Admins</h2>
        <p>Total Admins: <?php echo $admin_count; ?></p>
    </div>

    <div class="image2-container">
        <img src="22.png" alt="User Icon" class="image2">
    </div>

    <div class="widget3">
        <h2>Requests</h2>
        <p>Total Requests: <?php echo $total_requests; ?></p>
    </div>

    <div class="image3-container">
        <img src="3.png" alt="User Icon" class="image3">
    </div>

</div>

<figure class="highcharts-figure1">
    <div id="container"></div>


    <button id="plain">Plain</button>
    <button id="inverted">Inverted</button>
    <button id="polar">Polar</button>


    <figure class="highcharts-figure2">
    <div id="container2"></div> <!-- Updated ID -->
</figure>
</figure>




<script>
    document.addEventListener('DOMContentLoaded', function () {
        const data = <?php echo json_encode($data); ?>;
        const totalRequests = <?php echo $total_requests; ?>;

        updateChart(data, totalRequests);

        document.getElementById('plain').addEventListener('click', () => {
            chart.update({
                chart: {
                    inverted: false,
                    polar: false
                },
                subtitle: {
                    text: 'Abyssseek Chart' +
                        '<a href="https://www.nav.no/no/nav-og-samfunn/statistikk/arbeidssokere-og-stillinger-statistikk/helt-ledige"' +
                        'target="_blank"></a>'
                }
            });
        });

        document.getElementById('inverted').addEventListener('click', () => {
            chart.update({
                chart: {
                    inverted: true,
                    polar: false
                },
                subtitle: {
                    text: 'Abyssseek Chart' +
                        '<a href="https://www.nav.no/no/nav-og-samfunn/statistikk/arbeidssokere-og-stillinger-statistikk/helt-ledige"' +
                        'target="_blank"></a>'
                }
            });
        });

        document.getElementById('polar').addEventListener('click', () => {
            chart.update({
                chart: {
                    inverted: false,
                    polar: true
                },
                subtitle: {
                    text: 'Abyssseek Chart' +
                        '<a href="https://www.nav.no/no/nav-og-samfunn/statistikk/arbeidssokere-og-stillinger-statistikk/helt-ledige"' +
                        'target="_blank"></a>'
                }
            });
        });
    });

    function updateChart(data, totalRequests) {
        chart.update({
            series: [{
                type: 'column',
                name: 'Requests',
                borderRadius: 5,
                colorByPoint: true,
                data: data,
                showInLegend: false
            }],
            title: {
                text: 'Monthly Requests in 2021 (Total: ' + totalRequests + ')',
                align: 'left'
            },
            xAxis: {
                categories: [ // Replace 'Months' with 'categories'
                    'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
                ]
            },
            yAxis: {
                title: {
                    text: 'Values'
                },
                min: 0
            }
        });
    }

    const chart = Highcharts.chart('container', {
        title: {
            text: 'Monthly Requests in 2021',
            align: 'left'
        },
        subtitle: {
            text: 'Abyssseek Chart',
            align: 'left'
        },
        colors: [
            '#4caefe', '#3fbdf3', '#35c3e8', '#2bc9dc',
            '#20cfe1', '#16d4e6', '#0dd9db', '#03dfd0',
            '#00e4c5', '#00e9ba', '#00eeaf', '#23e274'
        ],
        xAxis: {
            categories: [ // Replace 'Months' with 'categories'
                'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
            ]
        },
        yAxis: {
            title: {
                text: 'Values'
            },
            min: 0
        },
        credits: {
                enabled: false  // Disables Highcharts.com attribution
            },
        series: [{
            type: 'column',
            name: 'Requests',
            borderRadius: 5,
            colorByPoint: true,
            data: [], // Initially empty, will be updated with fetched data
            showInLegend: false
        }]
    });
</script>


<script>
    // Data retrieved from https://olympics.com/en/olympic-games/beijing-2022/medals
Highcharts.chart('container2', { // Updated ID here as well
    chart: {
        type: 'pie',
        options3d: {
            enabled: true,
            alpha: 45
        }
    },
    title: {
        text: 'Abyssseek Most Used Tool',
        align: 'left'
    },
    subtitle: {
        text: 'Abyssseek Chart',
        align: 'left'
    },
    plotOptions: {
        pie: {
            innerSize: 100,
            depth: 45
        }
    },
    credits: {
                enabled: false  // Disables Highcharts.com attribution
            },
    series: [{
        name: 'Tools',
        data: [
            ['Look Up', 16],
            ['Social Media', 12],
            ['Web Crawler', 8],
            ['Web Scraper', 8],
            ['IP Geolocate', 6],
        ]
    }]
});

</script>
</body>

</html>
