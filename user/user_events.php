<?php
include 'config.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

// Handle search filters
$search_title = isset($_GET['title']) ? mysqli_real_escape_string($conn, $_GET['title']) : '';
$search_mode  = isset($_GET['mode']) ? $_GET['mode'] : '';
$search_status = isset($_GET['status']) ? $_GET['status'] : '';

// Fetch events
$sql = "SELECT * FROM events WHERE 1";

if($search_title) $sql .= " AND title LIKE '%$search_title%'";
if($search_mode) $sql .= " AND mode = '$search_mode'";
if($search_status) $sql .= " AND status = '$search_status'";

$sql .= " ORDER BY event_date DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Events</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
body { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); min-height: 100vh; margin-left: 260px; color: #fff; }
.main-content { padding: 30px; }
.card-hover { transition: transform 0.3s, box-shadow 0.3s; color: #000; }
.card-hover:hover { transform: translateY(-8px); box-shadow: 0 15px 30px rgba(0,0,0,0.2); }
.btn-action { border-radius: 50px; font-weight: bold; }
.text-dark { color: #000 !important; }
.event-img { height: 180px; object-fit: cover; border-radius: 10px; }
</style>
</head>

<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">

<h2 class="mb-4 text-white">Events</h2>

<!-- Search Form -->
<form method="GET" class="row mb-4">

    <div class="col-md-4">
        <input type="text" name="title" class="form-control" placeholder="Search by Title"
        value="<?= htmlspecialchars($search_title) ?>">
    </div>

    <div class="col-md-3">
        <select name="mode" class="form-select">
            <option value="">All Modes</option>
            <option value="online" <?= $search_mode=='online'?'selected':'' ?>>Online</option>
            <option value="offline" <?= $search_mode=='offline'?'selected':'' ?>>Offline</option>
            <option value="hybrid" <?= $search_mode=='hybrid'?'selected':'' ?>>Hybrid</option>
        </select>
    </div>

    <div class="col-md-3">
        <select name="status" class="form-select">
            <option value="">All Status</option>
            <option value="upcoming" <?= $search_status=='upcoming'?'selected':'' ?>>Upcoming</option>
            <option value="ongoing" <?= $search_status=='ongoing'?'selected':'' ?>>Ongoing</option>
            <option value="completed" <?= $search_status=='completed'?'selected':'' ?>>Completed</option>
            <option value="cancelled" <?= $search_status=='cancelled'?'selected':'' ?>>Cancelled</option>
        </select>
    </div>

    <div class="col-md-2">
        <button type="submit" class="btn btn-success btn-action w-100">
            <i class="fas fa-search"></i> Search
        </button>
    </div>

</form>

<!-- Events List -->
<div class="row">

<?php if(mysqli_num_rows($result) > 0): ?>
    <?php while($event = mysqli_fetch_assoc($result)): ?>

    <div class="col-md-4 mb-4">
        <div class="card card-hover p-3">

            <!-- Thumbnail -->
            <?php if(!empty($event['thumbnail'])): ?>
                <img src="../admin/uploads/<?= $event['thumbnail'] ?>" class="w-100 mb-3 event-img">
            <?php endif; ?>

            <h5><?= htmlspecialchars($event['title']) ?></h5>

            <p><strong>Date:</strong> <?= date('d M Y, h:i A', strtotime($event['event_date'])) ?></p>
            <p><strong>Mode:</strong> <?= ucfirst($event['mode']) ?></p>
            <p><strong>Status:</strong> <?= ucfirst($event['status']) ?></p>
            <p><strong>Location:</strong> <?= htmlspecialchars($event['location'] ?: 'N/A') ?></p>

            <div class="d-flex justify-content-between mt-3">

                <a href="event_details.php?id=<?= $event['id'] ?>" class="btn btn-primary btn-sm btn-action">
                    View Details
                </a>

                <?php if($event['event_link']): ?>
                <a href="<?= $event['event_link'] ?>" target="_blank" class="btn btn-warning btn-sm btn-action">
                    Join
                </a>
                <?php endif; ?>

            </div>

        </div>
    </div>

    <?php endwhile; ?>

<?php else: ?>

    <div class="col-12">
        <div class="alert alert-light text-dark">No events found.</div>
    </div>

<?php endif; ?>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>