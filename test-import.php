<?php

require_once 'vendor/autoload.php';

use Illuminate\Http\UploadedFile;

// Create a simple test to check if the import route is working
echo "Testing Ranking Code Import Route\n";

// Check if the route exists
echo "Route: http://127.0.0.1:8002/ranking-codes/import\n";

// Try to simulate a POST request to the import endpoint
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:8002/ranking-codes/import");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Set headers
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: multipart/form-data',
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: " . $httpCode . "\n";
if ($httpCode == 404) {
    echo "ERROR: Route not found (404)\n";
    echo "This indicates the route is not properly configured or the server is not running correctly.\n";
} else {
    echo "Response received\n";
}

echo "Response: " . substr($response, 0, 200) . "...\n";