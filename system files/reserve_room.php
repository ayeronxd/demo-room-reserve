<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Debug: Save POST data to a file
    file_put_contents('debug_post.txt', print_r($_POST, true));

    // ✅ Updated: Removed 'date' from required field check
    if (
        !isset($_SESSION['user_id'], $_POST['section'], $_POST['professor'], $_POST['subject'],
        $_POST['start_time'], $_POST['end_time'], $_POST['room_id'])
    ) {
        echo "Missing required fields.";
        exit();
    }

    // Assign variables from POST and session
    $user_id    = $_SESSION['user_id'];
    $section    = $_POST['section'];
    $professor  = $_POST['professor'];
    $subject    = $_POST['subject'];
    $start_time = $_POST['start_time'];
    $end_time   = $_POST['end_time'];
    $room_id    = $_POST['room_id'];

    // Extract date part from start_time for comparison
    $date = date('Y-m-d', strtotime($start_time));

    // Conflict check: same room, same date, overlapping time
    $stmt = $conn->prepare("
        SELECT * FROM reservations 
        WHERE room_id = ? 
        AND DATE(start_time) = ?
        AND status != 'declined'
        AND (
            (start_time < ? AND end_time > ?) OR 
            (start_time >= ? AND start_time < ?)
        )
    ");
    $stmt->bind_param("isssss", $room_id, $date, $end_time, $start_time, $start_time, $end_time);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Room already reserved for that time and date.";
        exit();
    }

    // Insert reservation
    $stmt = $conn->prepare("
        INSERT INTO reservations (user_id, section, professor, subject, start_time, end_time, room_id, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')
    ");
    $stmt->bind_param("isssssi", $user_id, $section, $professor, $subject, $start_time, $end_time, $room_id);

    if ($stmt->execute()) {
        echo "Reservation submitted successfully.";
    } else {
        echo "Failed to reserve room.";
    }
}
?>
