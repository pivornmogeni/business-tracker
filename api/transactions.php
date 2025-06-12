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

// Get all transactions
$sql = "SELECT * FROM transactions";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $transactions = [];
    while ($row = $result->fetch_assoc()) {
        $transactions[] = [
            'id' => $row['id'],
            'description' => $row['description'],
            'amount' => $row['amount'],
            'type' => $row['type'],
            'date' => $row['date']
        ];
    }
    echo json_encode($transactions);
} else {
    echo json_encode(['message' => 'No transactions found']);
}

$conn->close();
?>