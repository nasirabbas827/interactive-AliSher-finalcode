<?php
include 'config.php';
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sender_id = (int)$_POST['sender_id'];
    $receiver_id = (int)$_POST['receiver_id'];
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $body = mysqli_real_escape_string($conn, $_POST['body']);

    mysqli_query($conn, "INSERT INTO private_messages (sender_id, receiver_id, subject, body) VALUES ($sender_id, $receiver_id, '$subject', '$body')");

    header("Location: mentor_profile.php?id=$receiver_id");
    exit();
}
?>