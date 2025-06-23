<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
  header("Location: login.html");
  exit();
}
include 'config.php';

$floor = isset($_GET['floor']) ? intval($_GET['floor']) : 1;
$result = $conn->prepare("SELECT * FROM rooms WHERE floor = ?");
$result->bind_param("i", $floor);
$result->execute();
$rooms = $result->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Dashboard</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
  <h2>Room Reservation (Floor <?= $floor ?>)</h2>
  <div class="floor-nav">
    <?php for ($i = 1; $i <= 4; $i++): ?>
      <a href="?floor=<?= $i ?>" class="<?= $i === $floor ? 'active' : '' ?>">Floor <?= $i ?></a>
    <?php endfor; ?>
  </div>

  <form action="reserve_room.php" method="POST">
    <select name="room_id" required>
      <option value="">Select Room</option>
      <?php foreach ($rooms as $room): ?>
        <option value="<?= $room['id'] ?>"><?= $room['name'] ?></option>
      <?php endforeach; ?>
    </select>
    <input type="text" name="section" placeholder="Section" required>
    <input type="text" name="professor" placeholder="Professor" required>
    <input type="text" name="subject" placeholder="Subject" required>
    <label>Start Time:</label>
    <input type="datetime-local" name="start_time" required>
    <label>End Time:</label>
    <input type="datetime-local" name="end_time" required>
    <button type="submit">Reserve</button>
  </form>
  <br>
  <a href="logout.php">Logout</a>
</div>
</body>
</html>