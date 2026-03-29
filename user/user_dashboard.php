<?php
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

// Fetch statistics for the logged-in user
$user_id = $_SESSION['user_id'];

// Total counts (visible to users)
$ideas_count = $conn->query("SELECT COUNT(*) as total FROM business_ideas")->fetch_assoc()['total'];
$opp_count = $conn->query("SELECT COUNT(*) as total FROM opportunities WHERE status = 'open'")->fetch_assoc()['total'];
$template_count = $conn->query("SELECT COUNT(*) as total FROM business_templates")->fetch_assoc()['total'];
$resource_count = $conn->query("SELECT COUNT(*) as total FROM business_resources")->fetch_assoc()['total'];

// Recent Business Ideas
$recent_ideas = $conn->query("SELECT bi.id, bi.title, bi.description, u.full_name as agent_name, c.name as category 
    FROM business_ideas bi 
    JOIN users u ON bi.agent_id = u.id 
    JOIN business_categories c ON bi.category_id = c.id 
    ORDER BY bi.created_at DESC LIMIT 6")->fetch_all(MYSQLI_ASSOC);

// Recent Open Opportunities
$recent_opp = $conn->query("SELECT id, title, type, location, deadline 
    FROM opportunities 
    WHERE status = 'open' 
    ORDER BY created_at DESC LIMIT 5")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard - Business Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            margin-left: 260px;
            color: #333;
            min-height: 100vh;
        }
        .main-content {
            padding: 30px;
        }
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        .stat-number {
            font-size: 2.8rem;
            font-weight: 700;
            line-height: 1;
        }
        .welcome-text {
            font-size: 1.1rem;
            color: rgba(255,255,255,0.9);
        }
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="text-white mb-1">Welcome back, <?= htmlspecialchars($_SESSION['full_name']) ?>!</h1>
            <p class="welcome-text">Discover new business ideas, connect with mentors, and grow your venture.</p>
        </div>
        <div>
            <span class="badge bg-light text-dark fs-6 px-3 py-2">
                <i class="fas fa-user me-1"></i> End User Dashboard
            </span>
        </div>
    </div>



    <!-- Statistics Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card card-hover h-100 bg-white border-0 shadow-sm text-center">
                <div class="card-body">
                    <i class="fas fa-lightbulb fa-3x text-warning mb-3"></i>
                    <h5 class="text-muted">Business Ideas</h5>
                    <h3 class="stat-number text-warning"><?= number_format($ideas_count) ?></h3>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="ideas.php" class="btn btn-outline-warning btn-sm w-100">Browse Ideas</a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-hover h-100 bg-white border-0 shadow-sm text-center">
                <div class="card-body">
                    <i class="fas fa-handshake fa-3x text-success mb-3"></i>
                    <h5 class="text-muted">Open Opportunities</h5>
                    <h3 class="stat-number text-success"><?= number_format($opp_count) ?></h3>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="user_opportunities.php" class="btn btn-outline-success btn-sm w-100">View Opportunities</a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-hover h-100 bg-white border-0 shadow-sm text-center">
                <div class="card-body">
                    <i class="fas fa-file-alt fa-3x text-info mb-3"></i>
                    <h5 class="text-muted">Templates & Resources</h5>
                    <h3 class="stat-number text-info"><?= number_format($template_count + $resource_count) ?></h3>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="user_resources.php" class="btn btn-outline-info btn-sm w-100">Explore Resources</a>
                </div>
            </div>
        </div>

        
    </div>

    <!-- Recent Business Ideas -->
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="text-white">Featured Business Ideas</h4>
            <a href="ideas.php" class="text-light text-decoration-none">View All <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="row g-4">
            <?php if (empty($recent_ideas)): ?>
                <div class="col-12">
                    <div class="alert alert-info">No business ideas available yet.</div>
                </div>
            <?php else: ?>
                <?php foreach ($recent_ideas as $idea): ?>
                    <div class="col-md-4">
                        <div class="card card-hover h-100 bg-white border-0 shadow-sm">
                            <div class="card-body">
                                <span class="badge bg-secondary mb-2"><?= htmlspecialchars($idea['category']) ?></span>
                                <h5><?= htmlspecialchars($idea['title']) ?></h5>
                                <p class="small text-muted">
                                    <?= substr(strip_tags($idea['description']), 0, 110) ?>...
                                </p>
                                <small class="text-muted d-block">by <?= htmlspecialchars($idea['agent_name']) ?></small>
                            </div>
                            
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Recent Opportunities -->
    <div>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="text-white">Latest Opportunities</h4>
            <a href="user_opportunities.php" class="text-light text-decoration-none">View All <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="row g-3">
            <?php if (empty($recent_opp)): ?>
                <div class="col-12">
                    <div class="alert alert-info">No open opportunities at the moment.</div>
                </div>
            <?php else: ?>
                <?php foreach ($recent_opp as $opp): ?>
                    <div class="col-md-6">
                        <div class="card bg-white border-0 shadow-sm card-hover">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <h5 class="mb-1"><?= htmlspecialchars($opp['title']) ?></h5>
                                    <span class="badge bg-success"><?= ucfirst($opp['type']) ?></span>
                                </div>
                                <?php if (!empty($opp['location'])): ?>
                                    <small class="text-muted"><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($opp['location']) ?></small>
                                <?php endif; ?>
                                <?php if (!empty($opp['deadline'])): ?>
                                    <small class="text-danger d-block mt-1">
                                        Deadline: <?= date('d M, Y', strtotime($opp['deadline'])) ?>
                                    </small>
                                <?php endif; ?>
                            </div>
                            
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>