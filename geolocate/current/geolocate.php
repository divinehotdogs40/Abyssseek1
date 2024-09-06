<?php
header('Content-Type: application/json');

// Function to get the real IP address
function getRealIpAddr() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

// Get the user's IP address from query parameter or use the default method
$ip = isset($_GET['ip']) ? $_GET['ip'] : getRealIpAddr();

// Validate the IP address
if (!filter_var($ip, FILTER_VALIDATE_IP)) {
    echo json_encode([
        'status' => 'fail',
        'message' => 'Invalid IP address',
        'ip' => $ip
    ]);
    exit;
}

// For debugging purposes, you can hardcode an IP address
// $ip = '138.199.21.197';

// IP-API URL
$url = "http://ip-api.com/json/$ip";

// Initialize cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// Execute the cURL request
$response = curl_exec($ch);

// Check for cURL errors
if ($response === FALSE) {
    $error_msg = curl_error($ch);
    curl_close($ch);
    echo json_encode([
        'status' => 'fail',
        'message' => 'cURL Error: ' . $error_msg,
        'ip' => $ip
    ]);
    exit;
}

// Close cURL
curl_close($ch);

// Decode the JSON response
$data = json_decode($response, true);

// Check for JSON decoding errors
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode([
        'status' => 'fail',
        'message' => 'JSON Error: ' . json_last_error_msg(),
        'response' => $response,
        'ip' => $ip
    ]);
    exit;
}

// Check API response status
if ($data['status'] !== 'success') {
    echo json_encode([
        'status' => 'fail',
        'message' => 'API Error: ' . $data['message'],
        'ip' => $ip
    ]);
    exit;
}

// Return the geolocation data
echo json_encode($data);
?>
