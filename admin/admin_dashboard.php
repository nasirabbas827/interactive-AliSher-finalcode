<?php
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch Dashboard Statistics
$stats = [];

// Total Users & Breakdown
$stmt = $conn->query("SELECT 
    COUNT(*) as total_users,
    SUM(role = 'admin') as total_admins,
    SUM(role = 'agent') as total_agents,
    SUM(role = 'user') as total_regular_users,
    SUM(status = 'active') as active_users,
    SUM(status = 'inactive') as inactive_users,
    SUM(status = 'banned') as banned_users
FROM users");
$stats['users'] = $stmt->fetch_assoc();

// Business Ideas
$stmt = $conn->query("SELECT COUNT(*) as total_ideas FROM business_ideas");
$stats['ideas'] = $stmt->fetch_assoc()['total_ideas'];

// Business Templates
$stmt = $conn->query("SELECT COUNT(*) as total_templates FROM business_templates");
$stats['templates'] = $stmt->fetch_assoc()['total_templates'];

// Opportunities
$stmt = $conn->query("SELECT COUNT(*) as total_opp, SUM(status='open') as open_opp FROM opportunities");
$stats['opportunities'] = $stmt->fetch_assoc();

// Resources
$stmt = $conn->query("SELECT COUNT(*) as total_resources FROM business_resources");
$stats['resources'] = $stmt->fetch_assoc()['total_resources'];

// Forum Threads
$stmt = $conn->query("SELECT COUNT(*) as total_threads FROM forum_threads");
$stats['threads'] = $stmt->fetch_assoc()['total_threads'];

// Events
$stmt = $conn->query("SELECT COUNT(*) as total_events FROM events WHERE status IN ('upcoming', 'ongoing')");
$stats['events'] = $stmt->fetch_assoc()['total_events'];

// Feedback
$stmt = $conn->query("SELECT COUNT(*) as total_feedback, AVG(rating) as avg_rating FROM feedback");
$stats['feedback'] = $stmt->fetch_assoc();

// Recent Business Ideas
$recent_ideas = $conn->query("SELECT bi.title, u.full_name as agent_name, bi.created_at 
    FROM business_ideas bi 
    JOIN users u ON bi.agent_id = u.id 
    ORDER BY bi.created_at DESC LIMIT 5")->fetch_all(MYSQLI_ASSOC);

// Recent Opportunities
$recent_opp = $conn->query("SELECT title, type, status, created_at 
    FROM opportunities 
    ORDER BY created_at DESC LIMIT 5")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Entrepreneur Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            margin-left: 260px;
            color: #333;
        }
        .main-content {
            padding: 30px;
            min-height: 100vh;
        }
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
        }
        .sidebar { position: fixed; top: 0; left: 0; height: 100%; width: 260px; z-index: 1000; }
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1 text-white">Welcome back, <?= htmlspecialchars($_SESSION['full_name']) ?>!</h1>
            <p class="text-white">Here's what's happening in your Entrepreneur Portal today.</p>
        </div>
        <div>
            <span class="badge bg-success fs-6">Administrator</span>
        </div>
    </div>

    <!-- Key Metrics Row -->
    <div class="row g-4">
        <div class="col-md-3">
            <div class="card card-hover h-100 bg-white border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-users fa-3x text-primary mb-3"></i>
                    <h5>Total Users</h5>
                    <h3 class="stat-number text-primary"><?= number_format($stats['users']['total_users']) ?></h3>
                    <small class="text-muted">
                        <?= $stats['users']['total_agents'] ?> Agents • 
                        <?= $stats['users']['total_admins'] ?> Admins
                    </small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-hover h-100 bg-white border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-lightbulb fa-3x text-warning mb-3"></i>
                    <h5>Business Ideas</h5>
                    <h3 class="stat-number text-warning"><?= number_format($stats['ideas']) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-hover h-100 bg-white border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-file-alt fa-3x text-success mb-3"></i>
                    <h5>Templates</h5>
                    <h3 class="stat-number text-success"><?= number_format($stats['templates']) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-hover h-100 bg-white border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-handshake fa-3x text-info mb-3"></i>
                    <h5>Open Opportunities</h5>
                    <h3 class="stat-number text-info"><?= number_format($stats['opportunities']['open_opp']) ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Row -->
    <div class="row g-4 mt-2">
        <div class="col-md-4">
            <div class="card card-hover h-100 bg-white border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-book"></i> Resources & Forum</h5>
                    <div class="d-flex justify-content-between mt-3">
                        <div>
                            <strong><?= number_format($stats['resources']) ?></strong><br>
                            <small class="text-muted">Resources</small>
                        </div>
                        <div>
                            <strong><?= number_format($stats['threads']) ?></strong><br>
                            <small class="text-muted">Forum Threads</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-hover h-100 bg-white border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-calendar"></i> Events</h5>
                    <h3 class="stat-number"><?= number_format($stats['events']) ?></h3>
                    <small class="text-success">Upcoming / Ongoing</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-hover h-100 bg-white border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-star"></i> Feedback</h5>
                    <h3 class="stat-number"><?= round($stats['feedback']['avg_rating'] ?? 0, 1) ?> ★</h3>
                    <small class="text-muted"><?= number_format($stats['feedback']['total_feedback']) ?> reviews submitted</small>
                </div>
            </div>
        </div>
    </div>



    <!-- Recent Activity -->
    <div class="row mt-5">
        <div class="col-md-6">
            <div class="card bg-white border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5><i class="fas fa-lightbulb"></i> Recent Business Ideas</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($recent_ideas)): ?>
                        <p class="text-muted">No ideas posted yet.</p>
                    <?php else: ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($recent_ideas as $idea): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><?= htmlspecialchars($idea['title']) ?></strong><br>
                                        <small class="text-muted">by <?= htmlspecialchars($idea['agent_name']) ?></small>
                                    </div>
                                    <small class="text-muted"><?= date('M j', strtotime($idea['created_at'])) ?></small>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card bg-white border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5><i class="fas fa-handshake"></i> Recent Opportunities</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($recent_opp)): ?>
                        <p class="text-muted">No opportunities yet.</p>
                    <?php else: ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($recent_opp as $opp): ?>
                                <li class="list-group-item">
                                    <strong><?= htmlspecialchars($opp['title']) ?></strong> 
                                    <span class="badge bg-<?= $opp['status'] == 'open' ? 'success' : 'secondary' ?>"><?= ucfirst($opp['status']) ?></span>
                                    <br>
                                    <small class="text-muted"><?= ucfirst($opp['type']) ?> • <?= date('M j', strtotime($opp['created_at'])) ?></small>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>