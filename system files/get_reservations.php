<?php
session_start();
include 'config.php';

$floor = $_GET['floor'] ?? 1;
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT r.*, rooms.name AS room_name 
    FROM reservations r 
    JOIN rooms ON r.room_id = rooms.id 
    WHERE rooms.floor = ? AND r.user_id = ?
    ORDER BY start_time DESC
");
$stmt->bind_param("ii", $floor, $user_id);
$stmt->execute();
$result = $stmt->get_result();

$reservations = [];
while ($row = $result->fetch_assoc()) {
    $reservations[] = $row;
}
echo json_encode($reservations);