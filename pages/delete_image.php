<?php
// Project: SayHello
// GitHub: https://github.com/100security/sayhello
// Based on Saycheese
// https://github.com/hangetzzu/saycheese

// Check if the image parameter is provided
if (!isset($_POST['image'])) {
    echo json_encode(['success' => false, 'message' => 'No image specified']);
    exit;
}

// Get the image name
$imageName = $_POST['image'];

// Validate the image name (basic security check)
if (!preg_match('/^[a-zA-Z0-9_\-\.]+\.jpg$/i', $imageName)) {
    echo json_encode(['success' => false, 'message' => 'Invalid image name']);
    exit;
}

// Set the path to the image
$imagePath = '../captures/' . $imageName;

// Check if the file exists
if (!file_exists($imagePath)) {
    echo json_encode(['success' => false, 'message' => 'Image not found']);
    exit;
}

// Try to delete the file
if (unlink($imagePath)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete the image. Check file permissions.']);
}
?>
