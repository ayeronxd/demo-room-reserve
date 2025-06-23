<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $floor = $_POST['floor'];
    $stmt = $conn->prepare("INSERT INTO rooms (name, floor) VALUES (?, ?)");
    $stmt->bind_param("si", $name, $floor);
    $stmt->execute();
    echo "Room added.";
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"), $data);
    $id = $data['id'];
    $stmt = $conn->prepare("DELETE FROM rooms WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo "Room deleted.";
}