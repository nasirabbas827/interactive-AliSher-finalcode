<?php
include 'config.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

// Fetch categories
$cat_query = mysqli_query($conn, "SELECT * FROM business_categories ORDER BY name ASC");

// Filters
$search_title = isset($_GET['title']) ? mysqli_real_escape_string($conn, $_GET['title']) : '';
$search_cat   = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
$search_level = isset($_GET['level']) ? $_GET['level'] : '';

// Fetch tutorials
$sql = "
    SELECT t.*, bc.name AS category_name
    FROM tutorials t
    LEFT JOIN business_categories bc ON bc.id = t.category_id
    WHERE 1
";

if($search_title) $sql .= " AND t.title LIKE '%$search_title%'";
if($search_cat) $sql .= " AND t.category_id = $search_cat";
if($search_level) $sql .= " AND t.level = '$search_level'";

$sql .= " ORDER BY t.created_at DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Tutorials</title>

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

<h2 class="mb-4 text-white">Tutorials</h2>

<!-- Search -->
<form method="GET" class="row mb-4">

    <div class="col-md-4">
        <input type="text" name="title" class="form-control"
        placeholder="Search by Title" value="<?= htmlspecialchars($search_title) ?>">
    </div>

    <div class="col-md-3">
        <select name="category_id" class="form-select">
            <option value="0">All Categories</option>
            <?php while($cat = mysqli_fetch_assoc($cat_query)): ?>
                <option value="<?= $cat['id'] ?>" <?= $search_cat==$cat['id']?'selected':'' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="col-md-3">
        <select name="level" class="form-select">
            <option value="">All Levels</option>
            <option value="beginner" <?= $search_level=='beginner'?'selected':'' ?>>Beginner</option>
            <option value="intermediate" <?= $search_level=='intermediate'?'selected':'' ?>>Intermediate</option>
            <option value="advanced" <?= $search_level=='advanced'?'selected':'' ?>>Advanced</option>
        </select>
    </div>

    <div class="col-md-2">
        <button class="btn btn-success btn-action w-100">
            <i class="fas fa-search"></i> Search
        </button>
    </div>

</form>

<!-- List -->
<div class="row">

<?php if(mysqli_num_rows($result) > 0): ?>
    <?php while($t = mysqli_fetch_assoc($result)): ?>

    <div class="col-md-4 mb-4">
        <div class="card card-hover p-3">

            <h5><?= htmlspecialchars($t['title']) ?></h5>

            <p><strong>Category:</strong> <?= htmlspecialchars($t['category_name']) ?></p>
            <p><strong>Level:</strong> <?= ucfirst($t['level']) ?></p>
            <p><strong>Duration:</strong> <?= $t['duration_min'] ?> min</p>

            <div class="d-flex justify-content-between mt-3">
                <a href="tutorial_details.php?id=<?= $t['id'] ?>" class="btn btn-primary btn-sm btn-action">
                    View Details
                </a>

                <?php if($t['video_url']): ?>
                <a href="<?= $t['video_url'] ?>" target="_blank" class="btn btn-warning btn-sm btn-action">
                    Watch
                </a>
                <?php endif; ?>
            </div>

        </div>
    </div>

    <?php endwhile; ?>
<?php else: ?>

<div class="col-12">
    <div class="alert alert-light text-dark">No tutorials found.</div>
</div>

<?php endif; ?>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>