<?php
include 'config.php';

$result = $conn->query("SELECT * FROM rooms");
$rooms = [];
while ($row = $result->fetch_assoc()) {
    $rooms[] = $row;
}
echo json_encode($rooms);