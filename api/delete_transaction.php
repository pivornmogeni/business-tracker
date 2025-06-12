<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Database configuration
$host = "localhost";
$user = "root"; // Default XAMPP MySQL user
$pass = ""; // Default XAMPP MySQL password
$db = "business_tracker";

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]));
}

// Check if ID is provided
if (!isset($_GET['id'])) {
    die(json_encode(['error' => 'Transaction ID not provided']));
}

// SQL query to delete transaction
$id = intval($_GET['id']);
$sql = "DELETE FROM transactions WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(['message' => 'Transaction deleted successfully']);
} else {
    echo json_encode(['error' => 'Error deleting transaction']);
}

$stmt->close();
$conn->close();
?>