<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: auth/login_user.html");
    exit();
}

$floor = isset($_GET['floor']) ? intval($_GET['floor']) : 1;
$result = $conn->query("SELECT * FROM rooms WHERE floor = $floor");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Floor <?php echo $floor; ?> - Rooms</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Floor <?php echo $floor; ?> - Available Rooms</h2>
    <div class="room-list">
        <?php while ($room = $result->fetch_assoc()): ?>
            <div class="room-card">
                <p><strong>Room:</strong> <?php echo htmlspecialchars($room['room_name']); ?></p>
                <button onclick="openForm(<?php echo $room['id']; ?>)">Reserve</button>
            </div>
        <?php endwhile; ?>
    </div>

    <div id="reserveModal" style="display:none;">
        <form id="reserveForm">
            <input type="hidden" name="room_id" id="room_id">

            <label for="section">Section:</label>
            <input type="text" name="section" id="section" required>

            <label for="professor">Professor:</label>
            <input type="text" name="professor" id="professor" required>

            <label for="subject">Subject:</label>
            <input type="text" name="subject" id="subject" required>

            <label for="date">Date:</label>
            <input type="date" name="date" id="date" required>

            <label for="start_time">Start Time:</label>
            <input type="time" name="start_time" id="start_time" required>

            <label for="end_time">End Time:</label>
            <input type="time" name="end_time" id="end_time" required>

            <button type="submit">Reserve</button>
            <button type="button" onclick="closeForm()">Cancel</button>
        </form>
        <p id="reservationResult"></p>
    </div>
     <script src="script.js"></script>
</body>
</html>