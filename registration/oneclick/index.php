<?php
// Function to fetch domain
function fetchDomain() {
    $defaultDomain = "goldbet3.com"; // Fallback domain
    
    // Get user's country code using API
    $geoApiUrl = "https://apiip.net/api/check?accessKey=0439ba6e-6092-46c2-9aeb-8662065bc43c";
    
    $clientIp = $_SERVER['REMOTE_ADDR'] ?? ''; // Get real client IP

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $geoApiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "X-Forwarded-For: $clientIp"
    ]);
    
    $geoResponse = curl_exec($ch);
    
    if (curl_errno($ch)) {
        curl_close($ch);
        return $defaultDomain; // Return default on error
    }
    
    $geoData = json_decode($geoResponse, true);
    curl_close($ch);
    
    $countryCode = $geoData['countryCode'] ?? "";
    
    if (empty($countryCode)) {
        return $defaultDomain; // Return default if country code is not found
    }
    
    // API endpoint with countryCode parameter
    $apiUrl = "https://gbetauth.com/api/v2/rotator/available-domain?country=" . urlencode($countryCode);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $response = curl_exec($ch);
    
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

// Example usage
$domain = fetchDomain();

// Define the base URL dynamically using the fetched domain
$base_url = 'https://' . $domain . '/?modal=auth&method=oneclick&mode=sign-up';

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
$queryString = !empty($params) ? '?' . http_build_query($params) : '';

// Redirect to the determined URL
header("Location: " . $base_url . $queryString);
exit();
?>