<?php
include 'config.php';
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$sql = "
    SELECT o.*, u.full_name AS agent_name, bc.name AS category_name
    FROM opportunities o
    JOIN users u ON u.id = o.agent_id
    LEFT JOIN business_categories bc ON bc.id = o.category_id
    WHERE o.id = $id
";
$result = mysqli_query($conn, $sql);
$opp = mysqli_fetch_assoc($result);

if(!$opp) die("Opportunity not found!");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Opportunity Details</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
body { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); min-height: 100vh; margin-left: 260px; color: #fff; }
.main-content { padding: 30px; }
.card-hover { transition: transform 0.3s, box-shadow 0.3s; color: #000; }
.card-hover:hover { transform: translateY(-8px); box-shadow: 0 15px 30px rgba(0,0,0,0.2); }
.btn-action { border-radius: 50px; font-weight: bold; }
.text-dark { color: #000 !important; }
</style>
</head>
<body>
<?php include 'sidebar.php'; ?>
<div class="main-content">

    <div class="card card-hover p-4">
        <h3><?= htmlspecialchars($opp['title']) ?></h3>
        <p><strong>Type:</strong> <?= ucfirst($opp['type']) ?></p>
        <p><strong>Category:</strong> <?= htmlspecialchars($opp['category_name'] ?: 'N/A') ?></p>
        <p><strong>Mentor:</strong> <?= htmlspecialchars($opp['agent_name']) ?></p>
        <p><strong>Location:</strong> <?= htmlspecialchars($opp['location'] ?: 'N/A') ?></p>
        <p><strong>Deadline:</strong> <?= $opp['deadline'] ?: 'N/A' ?></p>
        <p><strong>Status:</strong> <?= ucfirst($opp['status']) ?></p>
        <p><strong>Description:</strong></p>
        <p><?= nl2br(htmlspecialchars($opp['description'])) ?></p>
        <p class="text-muted"><small>Created at: <?= $opp['created_at'] ?> | Updated at: <?= $opp['updated_at'] ?></small></p>
        <a href="mentor_profile.php?id=<?= $opp['agent_id'] ?>" class="btn btn-warning btn-action mt-3">
            <i class="fas fa-user"></i> Mentor Profile
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>