<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  header("Location: login.html");
  exit();
}
include 'config.php';

$rooms = $conn->query("SELECT * FROM rooms")->fetch_all(MYSQLI_ASSOC);
$reservations = $conn->query("
  SELECT r.*, u.username, rm.name AS room_name 
  FROM reservations r
  JOIN users u ON r.user_id = u.id
  JOIN rooms rm ON r.room_id = rm.id
  ORDER BY r.start_time DESC
")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="container">
  <h2>Manage Rooms</h2>
  <form action="manage_rooms.php" method="POST">
    <input type="text" name="name" placeholder="Room Name" required>
    <input type="number" name="floor" placeholder="Floor (1-4)" min="1" max="4" required>
    <button type="submit">Add Room</button>
  </form>
  <ul>
    <?php foreach ($rooms as $room): ?>
      <li><?= $room['name'] ?> (Floor <?= $room['floor'] ?>)
        <form action="manage_rooms.php" method="POST" onsubmit="return confirm('Delete this room?');" style="display:inline;">
          <input type="hidden" name="_method" value="DELETE">
          <input type="hidden" name="id" value="<?= $room['id'] ?>">
          <button type="submit">Delete</button>
        </form>
      </li>
    <?php endforeach; ?>
  </ul>

  <h2>Reservations</h2>
  <?php foreach ($reservations as $res): ?>
    <div class="card">
      <p><strong><?= $res['room_name'] ?></strong> — <?= $res['section'] ?> (<?= $res['subject'] ?> by <?= $res['professor'] ?>)</p>
      <p>From: <?= date("M d, Y h:i A", strtotime($res['start_time'])) ?> 
         To: <?= date("M d, Y h:i A", strtotime($res['end_time'])) ?></p>
      <p>User: <?= $res['username'] ?> | Status: <?= ucfirst($res['status']) ?></p>
      <?php if ($res['status'] === 'pending'): ?>
        <form action="approve_reservation.php" method="POST" style="display:inline;">
          <input type="hidden" name="id" value="<?= $res['id'] ?>">
          <button type="submit">Accept</button>
        </form>
        <form action="decline_reservation.php" method="POST" style="display:inline;">
          <input type="hidden" name="id" value="<?= $res['id'] ?>">
          <button type="submit">Decline</button>
        </form>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>

  <br><a href="logout.php">Logout</a>
</div>
</body>
</html>