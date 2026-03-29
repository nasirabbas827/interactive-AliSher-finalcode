<?php
include 'config.php';

if (isset($_POST['sender_id']) && isset($_POST['receiver_id']) && isset($_POST['body'])) {
    $sender_id   = (int)$_POST['sender_id'];
    $receiver_id = (int)$_POST['receiver_id'];
    $subject     = mysqli_real_escape_string($conn, $_POST['subject'] ?? '');
    $body        = mysqli_real_escape_string($conn, $_POST['body']);

    $sql = "INSERT INTO private_messages (sender_id, receiver_id, subject, body) 
            VALUES ($sender_id, $receiver_id, '$subject', '$body')";

    if (mysqli_query($conn, $sql)) {
        // Redirect back to messages with selected user
        header("Location: agent_messages.php?user_id=" . $receiver_id);
        exit();
    } else {
        echo "Error sending message.";
    }
} else {
    header("Location: agent_messages.php");
}
?>