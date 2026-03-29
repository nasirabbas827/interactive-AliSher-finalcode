<?php
include 'config.php';

// Only agents can access
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'agent') {
    header("Location: login.php");
    exit();
}

$agent_id = $_SESSION['user_id'];

// Fetch all feedbacks for this agent
$feedback_sql = "SELECT f.*, u.full_name AS user_name 
                 FROM feedback f 
                 JOIN users u ON u.id = f.user_id
                 WHERE f.agent_id = $agent_id
                 ORDER BY f.created_at DESC";
$feedback_result = mysqli_query($conn, $feedback_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Feedbacks</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
body { 
    background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); 
    min-height: 100vh; 
    margin-left: 260px; 
    color: #fff; 
}
.card-hover { 
    transition: transform 0.3s, box-shadow 0.3s; 
    color:#000; 
}
.card-hover:hover { 
    transform: translateY(-8px); 
    box-shadow: 0 15px 30px rgba(0,0,0,0.2); 
}
.main-content { padding: 30px; }
</style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">
    <h3 class="mb-4">My Feedbacks</h3>

    <div class="row">
        <?php if(mysqli_num_rows($feedback_result) > 0): ?>
            <?php while($fb = mysqli_fetch_assoc($feedback_result)): ?>
                <div class="col-md-6 mb-3">
                    <div class="card card-hover p-3">
                        <p><strong><?= htmlspecialchars($fb['user_name']) ?></strong> rated: <?= $fb['rating'] ?? 'N/A' ?>/5</p>
                        <p><?= nl2br(htmlspecialchars($fb['message'])) ?></p>
                        <small class="text-muted">Submitted at: <?= $fb['created_at'] ?></small>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-light text-dark">You have not received any feedback yet.</div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>