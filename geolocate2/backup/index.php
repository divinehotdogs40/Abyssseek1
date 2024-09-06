<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Geolocate User</title>
    <!-- Include Leaflet CSS and JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Include jQuery -->
    <script>
        var map; // Global variable to store the map reference

        function geolocate(ip) {
            $.ajax({
                url: 'geolocate.php?ip=' + ip,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.status === 'success') {
                        document.getElementById('result').innerHTML = `
                            <p>IP: ${data.query}</p>
                            <p>Country: ${data.country}</p>
                            <p>Region: ${data.regionName}</p>
                            <p>City: ${data.city}</p>
                            <p>ZIP: ${data.zip}</p>
                            <p>Latitude: ${data.lat}</p>
                            <p>Longitude: ${data.lon}</p>
                            <p>ISP: ${data.isp}</p>
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
        #map {
            height: 900px;
            width: 100%;
        }
    </style>
</head>
<body>
    <h1>Geolocate IP Address</h1>
    <input type="text" id="ipInput" placeholder="Enter IP address" />
    <div id="result"></div>
    <div id="map"></div>
</body>
</html>
