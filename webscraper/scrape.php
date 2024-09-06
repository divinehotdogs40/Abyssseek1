<?php
header('Content-Type: application/json'); // Set response content type as JSON

if (isset($_GET['url'])) {
    $url = $_GET['url']; // Get the URL parameter from GET request

    // Command to run Node.js script with Puppeteer and capture output
    $output = shell_exec("node scraper.js \"$url\" 2>&1");

    // Parse output from Node.js script as JSON
    $data = json_decode($output, true);

    // Check if valid JSON data received from Node.js script
    if ($data && isset($data['success'])) {
        echo json_encode($data); // Output the scraped data as JSON
    } else {
        echo json_encode(['success' => false, 'error' => 'Scraping failed.']); // Output error message if scraping failed
    }
} else {
    echo json_encode(['success' => false, 'error' => 'URL parameter is missing.']); // Output error if URL parameter is not provided
}
?>
