<?php
include 'config.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$query = mysqli_query($conn, "
    SELECT t.*, bc.name AS category_name
    FROM tutorials t
    LEFT JOIN business_categories bc ON bc.id = t.category_id
    WHERE t.id = $id
");

$t = mysqli_fetch_assoc($query);

if(!$t){
    echo "<h3>Tutorial not found!</h3>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Tutorial Details</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
body { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); min-height: 100vh; margin-left: 260px; color: #fff; }
.main-content { padding: 30px; }
.card-box { max-width: 900px; margin: auto; background: #fff; border-radius: 20px; padding: 30px; color: #000; }
.btn-action { border-radius: 50px; font-weight: bold; }
</style>
</head>

<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">

<div class="card-box">

<h2><?= htmlspecialchars($t['title']) ?></h2>

<p><strong>Category:</strong> <?= htmlspecialchars($t['category_name']) ?></p>
<p><strong>Level:</strong> <?= ucfirst($t['level']) ?></p>
<p><strong>Duration:</strong> <?= $t['duration_min'] ?> minutes</p>

<hr>

<h5>Content</h5>
<p><?= nl2br(htmlspecialchars($t['content'])) ?></p>

<?php if($t['video_url']): ?>
<hr>

<?php endif; ?>

<div class="mt-4 d-flex gap-2">
    <a href="user_tutorials.php" class="btn btn-secondary btn-action">Back</a>
    <?php if($t['video_url']): ?>
    <a href="<?= $t['video_url'] ?>" target="_blank" class="btn btn-success btn-action">Watch Full Video</a>
    <?php endif; ?>
</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>