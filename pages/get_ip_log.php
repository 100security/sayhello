<?php
// Project: SayHello
// GitHub: https://github.com/100security/sayhello
// Based on Saycheese
// https://github.com/hangetzzu/saycheese

// Set the path to the log file
$logFile = '../logs/captured_ips.log';

// Check if the file exists
if (!file_exists($logFile)) {
    echo json_encode([
        'success' => false,
        'message' => 'Log file not found'
    ]);
    exit;
}

// Read the log file
try {
    $logContent = file_get_contents($logFile);
    
    // Split the content into lines
    $logLines = explode("\n", $logContent);
    
    // Remove empty lines
    $logLines = array_filter($logLines, function($line) {
        return !empty(trim($line));
    });
    
    // Return the log entries
    echo json_encode([
        'success' => true,
        'entries' => array_values($logLines) // Reset array keys
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error reading log file: ' . $e->getMessage()
    ]);
}
?>
