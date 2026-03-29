<?php
include 'config.php';
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $agent_id = (int)$_POST['agent_id'];
    $user_id  = (int)$_POST['user_id'];
    $message  = mysqli_real_escape_string($conn, $_POST['message']);
    $rating   = (int)$_POST['rating'];

    mysqli_query($conn, "INSERT INTO feedback (agent_id, user_id, message, rating) VALUES ($agent_id, $user_id, '$message', $rating)");

    header("Location: mentor_profile.php?id=$agent_id");
    exit();
}
?>