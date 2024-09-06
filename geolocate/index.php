<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Geolocate IP Address</title>
    <!-- Include Leaflet CSS and JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <link href='https://fonts.googleapis.com/css?family=Angkor' rel='stylesheet'>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Include jQuery -->
    <!-- Include spline-viewer script -->
    <script type="module" src="https://unpkg.com/@splinetool/viewer@1.5.0/build/spline-viewer.js"></script>
    <style>
        body {
            display: flex;
            flex-direction: column;
            margin: 0;
            padding: 0;
            /* overflow: hidden; */
            position: relative;
            color: white; /* Set font color to white */
        }
        spline-viewer {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1; /* Ensure the spline is behind other elements */
        }
        #map {
    height: calc(100vh - 100px); /* Adjust height to be responsive */
    width: 100%;
    margin-top: 20px;
}

        #search-box {
            display: flex;
            align-items: center;
            margin-top: -4%;
            margin-left: 42%;
        }
        #ipInput {
            width: 360px; /* Adjust the width as needed */
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px; 
            margin-left: 0px;
        }
        #result {
            margin-bottom: 20px;
            color: white; /* Set font color to white */
            text-align: center;
            width: 40%; /* Set width to control size */
            margin: 0 auto; /* Center horizontally */
            padding: 20px; /* Add padding */
            box-sizing: border-box; /* Include padding and border in width */
        }
        table {
            width: 100%; /* Make table fill the container */
        }
        th, td {
            padding: 8px 12px;
            border: 1px solid #ccc; /* Outline for cells */
            text-align: left;
            background-color: transparent; /* Transparent background */
            color: white; /* White text color */
        }
        th {
            background-color: transparent; /* Transparent background */
            color: white;
        }
        td {
            background-color: transparent; /* Transparent background */
            color: white;
        }
        .header-container {
            display: flex;
            align-items: center;
            margin-left: 25%;
            margin-bottom: 10px;
        }
        .logo {
            margin-top: 40px; /* Adjust the top margin as needed */
        }
        .logo img {
            height: 250px; /* Adjust the height as needed */
        }
        .header-text {
            margin-left: 10px; /* Adjust the spacing as needed */
        }
        h1 {
            margin: 0; /* Remove default margins */
            font-size: 35px; /* Adjust the font size as needed */
        }
        h4 {
            margin: 20px 0 0 0; /* Adjust the margins to reduce space */
            font-family: 'Blaka Ink', cursive; /* Set the font to a cursive style */
            font-size: 15px; /* Adjust the font size as needed */
        }
        #search-box img {
            height: 95px;
            width: auto;
        }
    </style>
</head>
<body>
    <!-- Include the spline-viewer component for the background -->
    <script type="module" src="https://unpkg.com/@splinetool/viewer@1.5.0/build/spline-viewer.js"></script>
    <spline-viewer url="https://prod.spline.design/SeHqqOAOrkjUE5Ki/scene.splinecode"></spline-viewer>

    <div class="header-container">
        <div class="logo">
            <img src="images/logo11.png" alt="Abyssseek logo">
        </div>
        <div class="header-text">
            <br><br><h1>Welcome to Abyssseek Geolocate IP Address</h1>
            <h4>&nbsp;&nbsp;"Discover the power of location with IP Geolocate: Precision, Insight, and Control".</h4>
        </div>
    </div>
    <div id="search-box">
        <input type="text" id="ipInput" placeholder="IP(Ex. 123.123.123.123)" />
        <img src="images/ab22.png" alt="Abyssseek logo">
    </div>
    <div id="result"></div>
    
    <div id="map"></div>

    <script>
        // Your existing JavaScript code remains the same
        var map;
        var markers = L.layerGroup();

        
        function geolocate(ip) {
            $.ajax({
                url: 'geolocate.php?ip=' + ip,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.status === 'success') {
                        document.getElementById('result').innerHTML = `
                            <table>
                                <tr><th>IP</th><td>${data.query}</td></tr>
                                <tr><th>Country</th><td>${data.country}</td></tr>
                                <tr><th>Region</th><td>${data.regionName}</td></tr>
                                <tr><th>City</th><td>${data.city}</td></tr>
                                <tr><th>ZIP</th><td>${data.zip}</td></tr>
                                <tr><th>Latitude</th><td>${data.lat}</td></tr>
                                <tr><th>Longitude</th><td>${data.lon}</td></tr>
                                <tr><th>ISP</th><td>${data.isp}</td></tr>
                            </table>
                        `;


                        if (!map) { // Create map if it doesn't exist
                            map = L.map('map').setView([data.lat, data.lon], 12);

                            // Add OpenStreetMap tile layer
                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                            }).addTo(map);

                            markers.addTo(map); // Add marker layer to the map
                        }

                        // Clear existing markers
                        markers.clearLayers();


                        // Add new marker to the map
                        var marker = L.marker([data.lat, data.lon]).addTo(markers);
                        marker.bindPopup(data.city);


                        // Update map view
                        map.setView([data.lat, data.lon], 12);
                    } else {
                        document.getElementById('result').innerHTML = '<p>Unable to geolocate the IP address.</p>';
                    }
                },
                error: function() {
                    document.getElementById('result').innerHTML = '<p>Error fetching data.</p>';
                }
            });
        }

        $(document).ready(function() {
            $('#ipInput').on('input', function() {
                var ip = $(this).val();
                if (ip !== '') {
                    geolocate(ip);
                } else {
                    $('#result').html('');
                    if (map) { // Clear map if no IP is entered
                        markers.clearLayers();
                    }
                }
            });
        });
    </script>

</body>
</html>
