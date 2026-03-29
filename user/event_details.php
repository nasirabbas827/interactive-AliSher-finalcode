<?php
include 'config.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

// Get event ID
$event_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch event
$event_query = mysqli_query($conn, "SELECT * FROM events WHERE id = $event_id");
$event = mysqli_fetch_assoc($event_query);

if(!$event){
    echo "<h3>Event not found!</h3>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Event Details</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
body { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); min-height: 100vh; margin-left: 260px; color: #fff; }
.main-content { padding: 30px; }
.event-card { max-width: 900px; margin: auto; background: #fff; border-radius: 20px; padding: 30px; color: #000; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); }
.event-img { width: 100%; height: 300px; object-fit: cover; border-radius: 15px; }
.btn-action { border-radius: 50px; font-weight: bold; }
.text-dark { color: #000 !important; }
.badge-custom { font-size: 0.85rem; padding: 6px 12px; border-radius: 20px; }
</style>
</head>

<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">

<div class="event-card">

    <!-- Image -->
    <?php if(!empty($event['thumbnail'])): ?>
        <img src="../admin/uploads/<?= $event['thumbnail'] ?>" class="event-img mb-4">
    <?php endif; ?>

    <!-- Title -->
    <h2 class="mb-3"><?= htmlspecialchars($event['title']) ?></h2>

    <!-- Badges -->
    <div class="mb-3">
        <span class="badge bg-primary badge-custom"><?= ucfirst($event['mode']) ?></span>
        <span class="badge bg-success badge-custom"><?= ucfirst($event['status']) ?></span>
    </div>

    <!-- Dates -->
    <p><strong><i class="fas fa-calendar"></i> Start:</strong>
        <?= date('d M Y, h:i A', strtotime($event['event_date'])) ?>
    </p>

    <?php if($event['end_date']): ?>
    <p><strong><i class="fas fa-calendar-check"></i> End:</strong>
        <?= date('d M Y, h:i A', strtotime($event['end_date'])) ?>
    </p>
    <?php endif; ?>

    <!-- Location -->
    <p><strong><i class="fas fa-map-marker-alt"></i> Location:</strong>
        <?= htmlspecialchars($event['location'] ?: 'N/A') ?>
    </p>

    <!-- Description -->
    <hr>
    <h5>Description</h5>
    <p><?= nl2br(htmlspecialchars($event['description'])) ?></p>

    <!-- Actions -->
    <div class="mt-4 d-flex gap-2">

        <a href="user_events.php" class="btn btn-secondary btn-action">
            <i class="fas fa-arrow-left"></i> Back
        </a>

        <?php if($event['event_link']): ?>
        <a href="<?= $event['event_link'] ?>" target="_blank" class="btn btn-success btn-action">
            <i class="fas fa-external-link-alt"></i> Join Event
        </a>
        <?php endif; ?>

    </div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>