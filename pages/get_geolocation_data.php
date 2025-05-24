<?php
// Path to the geolocation log file
$logFile = '../logs/geolocation.txt';

// Check if the file exists
if (!file_exists($logFile)) {
    echo json_encode([
        'success' => false,
        'message' => 'No geolocation data available'
    ]);
    exit;
}

// Read the log file
$logContent = file_get_contents($logFile);

// Parse the log entries
$locations = [];
$entries = explode("=== Geolocation Data ===", $logContent);

// Skip the first empty entry if it exists
if (empty(trim($entries[0]))) {
    array_shift($entries);
}

foreach ($entries as $entry) {
    if (empty(trim($entry))) continue;
    
    // Extract data using regex
    $location = [];
    
    // Timestamp
    if (preg_match('/Timestamp: (.+)/', $entry, $matches)) {
        $location['timestamp'] = trim($matches[1]);
    }
    
    // IP Address
    if (preg_match('/IP Address: (.+)/', $entry, $matches)) {
        $location['ip'] = trim($matches[1]);
    }
    
    // Latitude
    if (preg_match('/Latitude: (.+)/', $entry, $matches)) {
        $location['latitude'] = trim($matches[1]);
    }
    
    // Longitude
    if (preg_match('/Longitude: (.+)/', $entry, $matches)) {
        $location['longitude'] = trim($matches[1]);
    }
    
    // Accuracy
    if (preg_match('/Accuracy: (.+) meters/', $entry, $matches)) {
        $location['accuracy'] = trim($matches[1]);
    }
    
    // Device Type
    if (preg_match('/Device Type: (.+)/', $entry, $matches)) {
        $location['deviceType'] = trim($matches[1]);
    }
    
    // Platform
    if (preg_match('/Platform: (.+)/', $entry, $matches)) {
        $location['platform'] = trim($matches[1]);
    }
    
    // Browser
    if (preg_match('/Browser: (.+)/', $entry, $matches)) {
        $location['browser'] = trim($matches[1]);
    }
    
    // Language
    if (preg_match('/Language: (.+)/', $entry, $matches)) {
        $location['language'] = trim($matches[1]);
    }
    
    // Screen Resolution
    if (preg_match('/Screen Resolution: (.+)/', $entry, $matches)) {
        $location['screenResolution'] = trim($matches[1]);
    }
    
    // Only add entries that have latitude and longitude
    if (isset($location['latitude']) && isset($location['longitude']) && 
        $location['latitude'] !== 'Unknown' && $location['longitude'] !== 'Unknown') {
        $locations[] = $location;
    }
}

// Sort locations by timestamp (newest first)
usort($locations, function($a, $b) {
    return strtotime($b['timestamp']) - strtotime($a['timestamp']);
});

// Return the data as JSON
echo json_encode([
    'success' => true,
    'count' => count($locations),
    'locations' => $locations
]);
?>
