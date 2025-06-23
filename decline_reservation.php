<?php
include 'config.php';

$id = $_POST['id'];
$stmt = $conn->prepare("UPDATE reservations SET status='declined' WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
echo "Declined.";