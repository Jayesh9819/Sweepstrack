<?php
include '../db/db_connect.php'; // Ensure you have this file with proper DB connection

header('Content-Type: application/json');

$sql = "SELECT cid, name FROM cashapp"; // Adjust table and column names as necessary
$result = $conn->query($sql);

$cashAppNames = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cashAppNames[] = [
            'id' => $row['cid'],
            'name' => $row['name']
        ];
    }
}

echo json_encode($cashAppNames);
$conn->close();
?>
