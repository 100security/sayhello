<?php
// Directory for logs
$logDir = '../logs';

// Create logs directory if it doesn't exist
if (!file_exists($logDir)) {
    mkdir($logDir, 0755, true);
}

// Log file path
$logFile = $logDir . '/geolocation.txt';

// Function to get real IP address
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

// Get data from POST request
$latitude = isset($_POST['latitude']) ? $_POST['latitude'] : 'Unknown';
$longitude = isset($_POST['longitude']) ? $_POST['longitude'] : 'Unknown';
$accuracy = isset($_POST['accuracy']) ? $_POST['accuracy'] : 'Unknown';
$timestamp = isset($_POST['timestamp']) ? $_POST['timestamp'] : date('Y-m-d H:i:s');
$userAgent = isset($_POST['userAgent']) ? $_POST['userAgent'] : $_SERVER['HTTP_USER_AGENT'];
$platform = isset($_POST['platform']) ? $_POST['platform'] : 'Unknown';
$language = isset($_POST['language']) ? $_POST['language'] : 'Unknown';
$screenResolution = isset($_POST['screenResolution']) ? $_POST['screenResolution'] : 'Unknown';
$deviceType = isset($_POST['deviceType']) ? $_POST['deviceType'] : 'Unknown';
$ip = getRealIpAddr();

// Format the log entry
$logEntry = "=== Geolocation Data ===\n";
$logEntry .= "Timestamp: $timestamp\n";
$logEntry .= "IP Address: $ip\n";
$logEntry .= "Latitude: $latitude\n";
$logEntry .= "Longitude: $longitude\n";
$logEntry .= "Accuracy: $accuracy meters\n";
$logEntry .= "Device Type: $deviceType\n";
$logEntry .= "Platform: $platform\n";
$logEntry .= "Browser: $userAgent\n";
$logEntry .= "Language: $language\n";
$logEntry .= "Screen Resolution: $screenResolution\n";
$logEntry .= "====================\n\n";

// Write to log file
if (file_put_contents($logFile, $logEntry, FILE_APPEND)) {
    echo json_encode(['success' => true, 'message' => 'Geolocation data saved successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save geolocation data']);
}
?>
