<?php
// Function to fetch domain
function fetchDomain() {
    $defaultDomain = "goldbet3.com"; // Fallback domain
    
    // Get user's country code using API
    $geoApiUrl = "https://apiip.net/api/check?accessKey=0439ba6e-6092-46c2-9aeb-8662065bc43c";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $geoApiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $geoResponse = curl_exec($ch);
    
    if (curl_errno($ch)) {
        curl_close($ch);
        return $defaultDomain; // Return default on error
    }
    
    $geoData = json_decode($geoResponse, true);
    curl_close($ch);

    echo $geoData['countryCode'];
    
    $countryCode = $geoData['countryCode'] ?? "";
    
    if (empty($countryCode)) {
        return $defaultDomain; // Return default if country code is not found
    }
    
    // API endpoint with countryCode parameter
    $apiUrl = "https://gbetauth.com/api/v2/rotator/available-domain?country=" . urlencode($countryCode);

    echo $countryCode;
    echo $apiUrl;
    
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
$base_url = 'https://' . $domain . '/bonuses/';

// Check if 'cid' parameter is present
if (isset($_GET['cid']) && !empty($_GET['cid'])) {
    // If 'cid' is present, append it to the URL
    $cid = $_GET['cid'];
    $new_url = $base_url . "?cid=" . urlencode($cid); // Note: '?' added instead of '&' for a valid query string
} else {
    // If 'cid' is not present, use the base URL
    $new_url = $base_url;
}

// Redirect to the determined URL
// header("Location: " . $new_url);
exit();
?>