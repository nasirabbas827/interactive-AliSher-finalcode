<?php
include 'config.php';
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

// Fetch categories for search
$cat_query = mysqli_query($conn, "SELECT * FROM business_categories ORDER BY name ASC");

// Handle search
$search_title = isset($_GET['title']) ? mysqli_real_escape_string($conn, $_GET['title']) : '';
$search_cat   = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
$search_type  = isset($_GET['type']) ? $_GET['type'] : '';

// Fetch opportunities
$sql = "
    SELECT o.*, u.full_name AS agent_name, bc.name AS category_name
    FROM opportunities o
    JOIN users u ON u.id = o.agent_id
    LEFT JOIN business_categories bc ON bc.id = o.category_id
    WHERE 1
";

if($search_title) $sql .= " AND o.title LIKE '%$search_title%'";
if($search_cat) $sql .= " AND o.category_id = $search_cat";
if($search_type) $sql .= " AND o.type = '$search_type'";

$sql .= " ORDER BY o.created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Business Opportunities</title>
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
    <h2 class="mb-4 text-white">Business Opportunities</h2>

    <!-- Search Form -->
    <form method="GET" class="row mb-4">
        <div class="col-md-3">
            <input type="text" name="title" class="form-control" placeholder="Search by Title" value="<?= htmlspecialchars($search_title) ?>">
        </div>
        <div class="col-md-3">
            <select name="category_id" class="form-select">
                <option value="0">All Categories</option>
                <?php while($cat = mysqli_fetch_assoc($cat_query)): ?>
                    <option value="<?= $cat['id'] ?>" <?= $search_cat==$cat['id']?'selected':'' ?>><?= htmlspecialchars($cat['name']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-3">
            <select name="type" class="form-select">
                <option value="">All Types</option>
                <?php
                $types = ['investment','partnership','franchise','co-founder','other'];
                foreach($types as $t): ?>
                    <option value="<?= $t ?>" <?= $search_type==$t?'selected':'' ?>><?= ucfirst($t) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-success btn-action w-100"><i class="fas fa-search"></i> Search</button>
        </div>
    </form>

    <!-- Opportunities List -->
    <div class="row">
        <?php if(mysqli_num_rows($result) > 0): ?>
            <?php while($opp = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-4 mb-4">
                    <div class="card card-hover p-3">
                        <h5><?= htmlspecialchars($opp['title']) ?></h5>
                        <p><strong>Type:</strong> <?= ucfirst($opp['type']) ?></p>
                        <p><strong>Category:</strong> <?= htmlspecialchars($opp['category_name'] ?: 'N/A') ?></p>
                        <p><strong>Mentor:</strong> <?= htmlspecialchars($opp['agent_name']) ?></p>
                        <div class="d-flex justify-content-between mt-3">
                            <a href="opportunity_details.php?id=<?= $opp['id'] ?>" class="btn btn-primary btn-sm btn-action">View Details</a>
                            <a href="mentor_profile.php?id=<?= $opp['agent_id'] ?>" class="btn btn-warning btn-sm btn-action">Mentor Profile</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-light text-dark">No opportunities found.</div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>