<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
}

$email = $_SESSION['EmailEntry'];

$db_host = 'localhost';
$db_user = 'root';
$db_password = '';
$db_name = 'abyssseek';


$conn1 = new mysqli($db_host, $db_user, $db_password, $db_name);

if ($conn1->connect_error) {
    die("Connection failed: " . $conn1->connect_error);
}


$sql1 = "SELECT * FROM history_lookup_linkedin ORDER BY ID DESC";
$result1 = $conn1->query($sql1);

$sql2 = "SELECT * FROM history_webscraper ORDER BY ID DESC";
$result2 = $conn1->query($sql2);

$sql3 = "SELECT * FROM history_webcrawler ORDER BY ID DESC";
$result3 = $conn1->query($sql3);




// FOR USERS-----------------------------------------

$sqlu4 = "SELECT * FROM history_lookup_linkedin WHERE Email = '$email' ORDER BY ID DESC";
$resultu4 = $conn1->query($sqlu4);

$sqlu5 = "SELECT * FROM history_webscraper WHERE Email = '$email' ORDER BY ID DESC";
$resultu5 = $conn1->query($sqlu5);

$sqlu6 = "SELECT * FROM history_webcrawler WHERE Email = '$email' ORDER BY ID DESC";
$resultu6 = $conn1->query($sqlu6);


$NumberOfUserSearchesValue = 0;
while ($row4 = $resultu4->fetch_assoc()) {
    $NumberOfUserSearchesValue += 1;
}

$NumberOfUserCrawlsValue = 0;
while ($row6 = $resultu6->fetch_assoc()) {
    $NumberOfUserCrawlsValue += 1;
}

$NumberOfUserScrapesValue = 0;
while ($row5 = $resultu5->fetch_assoc()) {
    $NumberOfUserScrapesValue += 1;
}


// END OF FOR USERS-----------------------------------------

if ($result1->num_rows > 0) {
    $row1 = $result1->fetch_assoc(); {
        $NumberOfSearches = $row1['ID'];

    }
} else {
    echo "No results found in Database";
}

if ($result2->num_rows > 0) {
    $row2 = $result2->fetch_assoc(); {
        $NumberOfScrapes = $row2['ID'];
    }
} else {
    echo "No results found in Database";
}

if ($result3->num_rows > 0) {
    $row3 = $result3->fetch_assoc(); {
        $NumberOfCrawls = $row3['ID'];
    }
} else {
    echo "No results found in Database";
}

$conn1->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>DashBoard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @font-face {
          font-family: 'main';
          src: url('files/assets/fonts/JetBrainsMonoNL-ExtraLight.ttf') format('truetype');
        }
        @font-face {
          font-family: 'main1';
          src: url('files/assets/fonts/Rubik-Light.ttf') format('truetype');
        }
        @font-face {
          font-family: 'main2';
          src: url('files/assets/fonts/Rubik-Bold.ttf') format('truetype');
        }
        @font-face {
          font-family: 'main3';
          src: url('files/assets/fonts/RussoOne-Regular.ttf') format('truetype');
        }
        @font-face {
          font-family: 'buttons';
          src: url('files/assets/fonts/JetBrainsMonoNL-Bold.ttf') format('truetype');
        }
        @font-face {
          font-family: 'counts';
          src: url('files/assets/fonts/JetBrainsMonoNL-ExtraBold.ttf') format('truetype');
        }
       body {
            background-color: #151b3d;
            height: 100vh;
            width: 90%;
            margin: 0;
            padding: 0;
            display: flex;
            color: white;
            font-family: main1, sans-serif;
        }

        .container {
            background-color: #f5f5ff;
            border-radius: 10px;
            padding: 20px;
            width: 60%; 
            height: 55%;
            max-width: 46%; 
            margin-left: 10px;
            position: absolute; 
            top: 95%; 
            left: 24.5%; 
            transform: translate(-49%, -50%); 
        }

        .containerGraph {
            background-color: #f5f5ff;
            border-radius: 10px;
            padding: 20px;
            width: 60%; 
            height: 55%;
            max-width: 46%; 
            margin: auto;
            position: absolute;
            top: 95%;
            left: 74%;
            transform: translate(-50%, -50%)
        }

        .useractivitycontainer{
            background-color: transparent;
            border-radius: 10px;
            width: 30%; 
            max-width: 40%; 
            position: absolute; 
            display: flex; 
            align-items: center; 
            top: 135%;
            left: 49.5%;
            height: 1px;
        }

        .containeruser, .containeradmin, .containernumreq, .containerlookup {
            background-color: #f5f5ff;
            border-radius: 10px;
            margin-left: 100px;
            padding: 20px;
            width: 90%; 
            max-width: 35%; 
            margin: auto;
            position: absolute; 
            transform: translate(-40%, -50%); 
            display: flex; 
            align-items: left; 
        }

        .containeruser img,
        .containeradmin img,
        .containernumreq img,
        .containerlookup img {
            margin-left: 50%; 
        }

        .containeruser p,
        .containeradmin p,
        .containernumreq p,
        .containerlookup p {
            margin-left: 5px;
            color: white; 
        }



        .containeruser {
            top: 25%; 
            left: 27%; 
        }

        .containeradmin {
            top: 25%; 
            left: 65%; 
        }

        .containernumreq{
            top: 45%;
            left: 27%;
        }

        .containerlookup{
            top: 45%;
            left: 65%;
        }

        h2 {
            color: black;
            text-align: center;
            display: flex;
        }

        canvas {
            margin-top: 20px;
        }

        .data-activity {
            position: absolute;
            top: 58%;
            left: 50%;
            transform: translateX(-50%);
            color: white;
            font-size: 30px;
            font-weight: bold;
            width: 100%;
            text-align: center;
            font-family: main2, sans-serif;
        }

        .data-history {
            position: absolute;
            top: 130%;
            left: 50%;
            transform: translateX(-50%);
            color: white;
            font-size: 30px;
            font-weight: bold;
            width: 100%;
            text-align: center;
            font-family: main2;
        }

        .dashboard-text {
            position: absolute;
            top: 80px;
            left: 20px;
            font-size: 50px;
            color: orange;
            font-weight: bold;
            font-family: main2, sans-serif;
        }

        .NumberValues {
            font-weight: bold;
            font-family: main2, sans-serif;
            font-size: 30px;
            color: #146C94;
        }

    </style>
</head>
<body>

<div style="margin-left: 100%;">
<div class="dashboard-text">Dashboard</div>

<div class="containeruser">
    <p><h2>Number of Searches:&nbsp;</h2></p> <span class="NumberValues" style="position: fixed; margin-top: 20px; margin-left: 200px; font-size: 50px;"><?php echo $NumberOfSearches?></span>
    <img src="images/searchimg.png" alt="LookUp" width="120" height="120">
</div>

<div class="containeradmin">
    <p><h2>Number of Crawls:&nbsp;</h2></p> <span class="NumberValues" style="position: fixed; margin-top: 20px; margin-left: 200px; font-size: 50px;"><?php echo $NumberOfCrawls?></span>
    <img src="images/crawler128.png" alt="Crawler" width="120" height="120">
</div>

<div class="containernumreq">
    <p><h2>Number of Scrapes:&nbsp;</h2></p> <span class="NumberValues" style="position: fixed; margin-top: 20px; margin-left: 200px; font-size: 50px;"><?php echo $NumberOfScrapes?></span>
    <img src="images/scrapeimg.png" alt="Scrape" width="120" height="120">
</div>

<div class="containerlookup">
    <p><h2>Number of Social Medias:&nbsp;</h2></p>
    <img src="images/socialimg.png" alt="Social Media" width="120" height="120">
</div>
    </div>

<p class="data-activity">
    <span style="display: inline-block; width: 100%; text-align: center; color: orange;">
         User Data Activity
    </span>
</p>


<div class="container">
    <h2>Abyssseek tool you mostly use:<span style="font-size: 16px;"> </span></h2>
    
    <canvas id="registrationChart" width="800" height="400"></canvas>
</div>

<div class="containerGraph">
    <h2>Active<span style="font-size: 16px;"> </span></h2>
    
    <canvas id="socialMediaChart" width="800" height="400"></canvas>
</div>

<div class="useractivitycontainer"> 
</div>

<script>
    var userSearches = <?php echo isset($NumberOfUserSearchesValue) ? $NumberOfUserSearchesValue : 0; ?>;
        var userCrawls = <?php echo isset($NumberOfUserCrawlsValue) ? $NumberOfUserCrawlsValue : 0; ?>;
        var userScrapes = <?php echo isset($NumberOfUserScrapesValue) ? $NumberOfUserScrapesValue : 0; ?>;

        
        console.log("User Searches:", userSearches);
        console.log("User Crawls:", userCrawls);
        console.log("User Scrapes:", userScrapes);

        var ctx = document.getElementById('registrationChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Look Up', 'Social Media', 'Web Crawler', 'Web Scraper'],
                datasets: [{
                    label: 'Uses',
                    data: [userSearches, 0, userCrawls, userScrapes],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    x: {
                        ticks: {
                            font: {
                                family: 'Arial',
                                size: 14, 
                                weight: 'bold', 
                                style: 'italic', 
                            },
                            color: '#138A59'
                        }
                    },
                    y: {
                        ticks: {
                            font: {
                                family: 'Arial', 
                                size: 14, 
                                weight: 'bold', 
                                style: 'italic', 
                            },
                            color: '#138A59' 
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: false
                    }
                }
            }
        });


    var ctx2 = document.getElementById('socialMediaChart').getContext('2d');
    var myChart2 = new Chart(ctx2, {
        type: 'line',
        data: {
            labels: ['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5', 'Day 6', 'Day 7'],
            datasets: [{
                label: 'Activity',
                data: [12, 19, 3, 5, 2, 3, 10],
                fill: false,
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>


</body>
</html>