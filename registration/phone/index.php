<?php
// Calls a same-origin nginx endpoint and returns decoded JSON, or null on failure.
function apiGet($path) {
    $host = $_SERVER['HTTP_HOST'] ?? '';

    if ($host === '') {
        return null;
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://' . $host . $path);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
    curl_setopt($ch, CURLOPT_TIMEOUT, 8);

    $body = curl_exec($ch);
    $errno = curl_errno($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($errno || $status < 200 || $status >= 300) {
        return null;
    }

    $data = json_decode($body, true);

    return is_array($data) ? $data : null;
}

// Function to fetch domain
function fetchDomain() {
    $defaultDomain = "g01d63t1.win"; // Fallback domain

    $clientIp = $_SERVER['REMOTE_ADDR'] ?? ''; // Get real client IP

    // Geo lookup goes through the same-origin nginx proxy. The request originates
    // from the server itself, so the visitor's IP has to be passed explicitly.
    $geoPath = "/geo-api/api/check?accessKey=0439ba6e-6092-46c2-9aeb-8662065bc43c";
    if ($clientIp !== '') {
        $geoPath .= "&ip=" . urlencode($clientIp);
    }

    $geoData = apiGet($geoPath);
    $countryCode = $geoData['countryCode'] ?? "";

    if (empty($countryCode)) {
        return $defaultDomain; // Return default if country code is not found
    }

    // Rotator through the same-origin nginx proxy — nginx injects the API key
    $data = apiGet("/api/domain/available?country=" . urlencode($countryCode));
    $domains = $data['domains'] ?? array();

    // Return the first available domain or the default
    return (is_array($domains) && !empty($domains[0])) ? $domains[0] : $defaultDomain;
}

// Example usage
$domain = fetchDomain();

// Define the base URL dynamically using the fetched domain
$base_url = 'https://' . $domain . '/?modal=auth&method=phone&mode=sign-up';

// Collect parameters if they exist
$params = [];
if (isset($_GET['cid']) && !empty($_GET['cid'])) {
    $params['cid'] = $_GET['cid'];
}
if (isset($_GET['partner']) && !empty($_GET['partner'])) {
    $params['partner'] = $_GET['partner'];
}
if (isset($_GET['offer']) && !empty($_GET['offer'])) {
    $params['offer'] = $_GET['offer'];
}

// Build the query string if there are any parameters
$queryString = !empty($params) ? '&' . http_build_query($params) : '';

// Redirect to the determined URL
header("Location: " . $base_url . $queryString);
exit();
?>