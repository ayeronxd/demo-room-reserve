<?php
session_start();
include 'config.php';

$username = $_POST['username'];
$password = $_POST['password'];
$role = $_POST['role'];

$stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND role = ?");
$stmt->bind_param("ss", $username, $role);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
  $user = $result->fetch_assoc();
  if (password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['role'] = $user['role'];
    if ($role === 'admin') {
      header("Location: admin.php");
    } else {
      header("Location: index.php");
    }
    exit();
  }
}
echo "<script>alert('Invalid credentials.'); window.location.href='login_" . $role . ".html';</script>";
?>