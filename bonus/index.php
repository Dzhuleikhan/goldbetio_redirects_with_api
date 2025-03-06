<?php
// Function to fetch domain
// function fetchDomain() {
//     $defaultDomain = "goldbet3.com"; // Fallback domain
    
//     // Get user's country code using API
//     $geoApiUrl = "https://apiip.net/api/check?accessKey=0439ba6e-6092-46c2-9aeb-8662065bc43c";
    
//     $ch = curl_init();
//     curl_setopt($ch, CURLOPT_URL, $geoApiUrl);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
//     $geoResponse = curl_exec($ch);
    
//     if (curl_errno($ch)) {
//         curl_close($ch);
//         return $defaultDomain; // Return default on error
//     }
    
//     $geoData = json_decode($geoResponse, true);
//     curl_close($ch);
    
//     $countryCode = $geoData['countryCode'] ?? "";
    
//     if (empty($countryCode)) {
//         return $defaultDomain; // Return default if country code is not found
//     }
    
//     // API endpoint with countryCode parameter
//     $apiUrl = "https://gbetauth.com/api/v2/rotator/available-domain?country=" . urlencode($countryCode);
    
//     $ch = curl_init();
//     curl_setopt($ch, CURLOPT_URL, $apiUrl);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
//     $response = curl_exec($ch);
    
//     if (curl_errno($ch)) {
//         curl_close($ch);
//         return $defaultDomain; // Return default on error
//     }
    
//     curl_close($ch);
    
//     // Decode JSON response
//     $data = json_decode($response, true);
    
//     // Return the domain or the default
//     return $data['domain'] ?? $defaultDomain;
// }

// // Example usage
// $domain = fetchDomain();

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
        error_log("Geo API Request Error: " . curl_error($ch));
        curl_close($ch);
        return $defaultDomain; // Return default on error
    }

    curl_close($ch);

    // Decode JSON response
    $geoData = json_decode($geoResponse, true);

    if ($geoData === null) {
        error_log("Geo API JSON Decode Error: " . json_last_error_msg());
        return $defaultDomain; // Return default on JSON error
    }

    error_log("Geo API Response: " . print_r($geoData, true));

    // Extract country code
    $countryCode = $geoData['countryCode'] ?? "";

    if (empty($countryCode)) {
        error_log("Country Code Not Found, Using Default Domain");
        return $defaultDomain; // Return default if country code is not found
    }

    error_log("Extracted Country Code: " . $countryCode);

    // API endpoint with countryCode parameter
    $apiUrl = "https://gbetauth.com/api/v2/rotator/available-domain?country=" . urlencode($countryCode);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        error_log("Domain API Request Error: " . curl_error($ch));
        curl_close($ch);
        return $defaultDomain; // Return default on error
    }

    curl_close($ch);

    // Decode JSON response
    $data = json_decode($response, true);

    if ($data === null) {
        error_log("Domain API JSON Decode Error: " . json_last_error_msg());
        return $defaultDomain; // Return default on JSON error
    }

    error_log("Domain API Response: " . print_r($data, true));

    // Return the domain or the default
    return $data['domain'] ?? $defaultDomain;
}

// Example usage
$domain = fetchDomain();
echo "Selected Domain: " . $domain;


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