<?php
include 'config.php';
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$sql = "
    SELECT br.*, u.full_name AS agent_name, bc.name AS category_name
    FROM business_resources br
    JOIN users u ON u.id = br.agent_id
    JOIN business_categories bc ON bc.id = br.category_id
    WHERE br.resource_id = $id
";
$result = mysqli_query($conn, $sql);
$res = mysqli_fetch_assoc($result);

if(!$res) die("Resource not found!");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Resource Details</title>
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
        <h3><?= htmlspecialchars($res['title']) ?></h3>
        <p><strong>Type:</strong> <?= ucfirst(str_replace('_',' ',$res['type'])) ?></p>
        <p><strong>Category:</strong> <?= htmlspecialchars($res['category_name']) ?></p>
        <p><strong>Mentor:</strong> <?= htmlspecialchars($res['agent_name']) ?></p>
        <p><strong>Description:</strong></p>
        <p><?= nl2br(htmlspecialchars($res['description'])) ?></p>
        <p><strong>Content:</strong></p>
        <p><?= nl2br(htmlspecialchars($res['content'])) ?></p>
        <p class="text-muted"><small>Created at: <?= $res['created_at'] ?></small></p>
        <a href="mentor_profile.php?id=<?= $res['agent_id'] ?>" class="btn btn-warning btn-action mt-3">
            <i class="fas fa-user"></i> Mentor Profile
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>