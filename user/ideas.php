<?php
include 'config.php';
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

// Fetch categories for filter
$cat_query = mysqli_query($conn, "SELECT * FROM business_categories ORDER BY name ASC");

// Handle search
$search_title = isset($_GET['title']) ? mysqli_real_escape_string($conn, $_GET['title']) : '';
$search_category = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;

// Fetch business ideas
$sql = "
    SELECT bi.*, u.full_name AS agent_name, bc.name AS category_name
    FROM business_ideas bi
    JOIN users u ON u.id = bi.agent_id
    JOIN business_categories bc ON bc.id = bi.category_id
    WHERE 1
";
if($search_title) {
    $sql .= " AND bi.title LIKE '%$search_title%'";
}
if($search_category) {
    $sql .= " AND bi.category_id = $search_category";
}
$sql .= " ORDER BY bi.created_at DESC";

$ideas_query = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Dashboard - Business Ideas</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
    body { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); min-height: 100vh; margin-left: 260px; color: #fff; }
.main-content {
    padding: 30px;
}
.card-hover {
    transition: transform 0.3s, box-shadow 0.3s;
}
.card-hover:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.1);
}
</style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">
    <h1 class="mb-4">Welcome back, <?= htmlspecialchars($_SESSION['full_name']) ?>!</h1>
    
    <div class="alert alert-success">
        You are logged in as <strong>Entrepreneur / User</strong>
    </div>

    <!-- Search Form -->
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-6">
            <input type="text" name="title" class="form-control" placeholder="Search by title..." value="<?= htmlspecialchars($search_title) ?>">
        </div>
        <div class="col-md-4">
            <select name="category_id" class="form-select">
                <option value="0">All Categories</option>
                <?php mysqli_data_seek($cat_query,0); while($cat = mysqli_fetch_assoc($cat_query)): ?>
                    <option value="<?= $cat['id'] ?>" <?= $search_category==$cat['id']?'selected':'' ?>><?= htmlspecialchars($cat['name']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Search</button>
        </div>
    </form>

    <!-- Business Ideas -->
    <div class="row">
        <?php while($idea = mysqli_fetch_assoc($ideas_query)): ?>
        <div class="col-md-4 mb-4">
            <div class="card card-hover h-100">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($idea['title']) ?></h5>
                    <p class="card-text"><strong>Category:</strong> <?= htmlspecialchars($idea['category_name']) ?></p>
                    <p class="card-text"><strong>Mentor:</strong> <?= htmlspecialchars($idea['agent_name']) ?></p>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <a href="idea_details.php?id=<?= $idea['id'] ?>" class="btn btn-primary btn-sm">View Details</a>
                    <a href="mentor_profile.php?id=<?= $idea['agent_id'] ?>" class="btn btn-success btn-sm">Mentor Profile</a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>