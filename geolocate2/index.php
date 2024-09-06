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
    <script>
        var map;

        function geolocate(ip) {
            $.ajax({
                url: 'geolocate.php?ip=' + ip,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.status === 'success') {
                        document.getElementById('result').innerHTML = `
                            <p>IP:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;${data.query}</p>
                            <p>Country:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;${data.country}</p>
                            <p>Region:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;${data.regionName}</p>
                            <p>City:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;${data.city}</p>
                            <p>ZIP:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;${data.zip}</p>
                            <p>Latitude:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;${data.lat}</p>
                            <p>Longitude:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;${data.lon}</p>
                            <p>ISP:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;${data.isp}</p>
                        `;

                        if (!map) { // Create map if it doesn't exist
                            map = L.map('map').setView([data.lat, data.lon], 12);

                            // Add OpenStreetMap tile layer
                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                            }).addTo(map);
                        } else { // Update map view and marker position
                            map.setView([data.lat, data.lon], 12);
                            map.eachLayer(function(layer) {
                                if (layer instanceof L.Marker) {
                                    layer.setLatLng([data.lat, data.lon]);
                                    layer.bindPopup(data.city);
                                }
                            });
                        }

                        // Add marker to the map
                        var marker = L.marker([data.lat, data.lon]).addTo(map);
                        marker.bindPopup(data.city);
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
                        map.remove();
                        map = null;
                    }
                }
            });
        });
    </script>
    <style>
        body {
            display: flex;
            background: radial-gradient(circle, #3b8898 5%, #284a6f 25%, #151b3d 50%, #0a0a24 90%, #000000 100%);
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: white; /* Set font color to white */
        }
        #map {
            height: 500px;
            width: 100%;
            margin-top: 20px;
        }
        #search-box {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        #ipInput {
            flex: 1;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
        }

        #result {
            margin-bottom: 20px;
            color: white; /* Set font color to white */
        }
        .header-container {
            display: flex;
            align-items: center;
            margin-bottom: 10px; /* Reduce the bottom margin */
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
        #searchImage {
            width:18%; /* Adjust the width as needed */
            height: 18%; /* Adjust the height as needed */
            padding-right: -20;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div>
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
        <input type="text" id="ipInput" placeholder="IP address (Ex. 123.123.123.123)" /> 
        <img id="searchImage" src="images/ab22.png" alt="Search" /> <!-- Replace with your image -->
    </div>
    
        <div id="result"></div>
        <div id="map"></div>

    </div>
</body>
</html>