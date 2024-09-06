<!DOCTYPE html>
<html>
<head>
    <title>Highcharts with PHP and MySQL</title>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <style>
        #container {
            height: 400px;
        }

        .highcharts-figure,
        .highcharts-data-table table {
            min-width: 320px;
            max-width: 800px;
            margin: 1em auto;
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
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "abyssseek";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
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

$conn->close();
?>

<figure class="highcharts-figure">
    <div id="container"></div>
    <p class="highcharts-description">
        Chart with buttons to modify options, showing how options can be changed
        on the fly. This flexibility allows for more dynamic charts.
    </p>

    <button id="plain">Plain</button>
    <button id="inverted">Inverted</button>
    <button id="polar">Polar</button>
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
                    text: 'Chart option: Plain | Source: ' +
                        '<a href="https://www.nav.no/no/nav-og-samfunn/statistikk/arbeidssokere-og-stillinger-statistikk/helt-ledige"' +
                        'target="_blank">NAV</a>'
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
                    text: 'Chart option: Inverted | Source: ' +
                        '<a href="https://www.nav.no/no/nav-og-samfunn/statistikk/arbeidssokere-og-stillinger-statistikk/helt-ledige"' +
                        'target="_blank">NAV</a>'
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
                    text: 'Chart option: Polar | Source: ' +
                        '<a href="https://www.nav.no/no/nav-og-samfunn/statistikk/arbeidssokere-og-stillinger-statistikk/helt-ledige"' +
                        'target="_blank">NAV</a>'
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
            }
        });
    }

    const chart = Highcharts.chart('container', {
        title: {
            text: 'Monthly Requests in 2021',
            align: 'left'
        },
        subtitle: {
            text: 'Chart option: Plain',
            align: 'left'
        },
        colors: [
            '#4caefe',
            '#3fbdf3',
            '#35c3e8',
            '#2bc9dc',
            '#20cfe1',
            '#16d4e6',
            '#0dd9db',
            '#03dfd0',
            '#00e4c5',
            '#00e9ba',
            '#00eeaf',
            '#23e274'
        ],
        xAxis: {
            categories: [
                'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep',
                'Oct', 'Nov', 'Dec'
            ]
        },
        yAxis: {
            title: {
                text: 'Values'
            },
            min: 0
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

</body>
</html>
