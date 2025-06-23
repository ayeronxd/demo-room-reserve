<?php
session_start();
include 'config.php';

$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$role = $_POST['role'];

if ($role === 'admin') {
  $admin_key = $_POST['admin_key'] ?? '';
  $valid_key = 'cite2025'; // You can change this key
  if ($admin_key !== $valid_key) {
    echo "<script>alert('Invalid admin key.'); window.location.href='register_admin.html';</script>";
    exit();
  }
}

$stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $password, $role);
if ($stmt->execute()) {
  echo "<script>alert('Registered successfully. Please login.'); window.location.href='login_" . $role . ".html';</script>";
} else {
  echo "<script>alert('Username already taken.'); window.location.href='register_" . $role . ".html';</script>";
}
?>