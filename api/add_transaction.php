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

// Check if data is provided
if (!isset($_POST['description'], $_POST['amount'], $_POST['type'])) {
    die(json_encode(['error' => 'Missing transaction data']));
}

// Sanitize input
$description = $conn->real_escape_string($_POST['description']);
$amount = floatval($_POST['amount']);
$type = $conn->real_escape_string($_POST['type']);
$date = date('Y-m-d H:i:s');

// SQL query to add transaction
$sql = "INSERT INTO transactions (description, amount, type, date) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sdss", $description, $amount, $type, $date);

if ($stmt->execute()) {
    $last_id = $stmt->insert_id;
    echo json_encode(['message' => 'Transaction added successfully', 'id' => $last_id]);
} else {
    echo json_encode(['error' => 'Error adding transaction']);
}

$stmt->close();
$conn->close();
?>