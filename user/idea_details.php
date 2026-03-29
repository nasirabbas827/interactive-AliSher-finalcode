<?php
include 'config.php';
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

// Get idea ID
$idea_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$sql = "
    SELECT bi.*, u.full_name AS agent_name, bc.name AS category_name
    FROM business_ideas bi
    JOIN users u ON u.id = bi.agent_id
    JOIN business_categories bc ON bc.id = bi.category_id
    WHERE bi.id = $idea_id
";
$result = mysqli_query($conn, $sql);
$idea = mysqli_fetch_assoc($result);

if(!$idea) {
    die("Idea not found!");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Business Idea Details</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
    body { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); min-height: 100vh; margin-left: 260px; color: #fff; }
.card-hover { transition: transform 0.3s, box-shadow 0.3s; }
.card-hover:hover { transform: translateY(-8px); box-shadow: 0 15px 30px rgba(0,0,0,0.1); }
.main-content { padding: 30px; }
</style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">
    <div class="card card-hover">
        <div class="card-body">
            <h3 class="card-title"><?= htmlspecialchars($idea['title']) ?></h3>
            <p class="card-text"><strong>Category:</strong> <?= htmlspecialchars($idea['category_name']) ?></p>
            <p class="card-text"><strong>Mentor:</strong> <?= htmlspecialchars($idea['agent_name']) ?></p>
            <p class="card-text"><strong>Description:</strong></p>
            <p><?= nl2br(htmlspecialchars($idea['description'])) ?></p>
            <p class="text-muted"><small>Created at: <?= $idea['created_at'] ?> | Last updated: <?= $idea['updated_at'] ?></small></p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>