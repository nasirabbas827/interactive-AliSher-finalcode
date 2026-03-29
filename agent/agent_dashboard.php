<?php 
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'agent') {
    header("Location: login.php");
    exit();
}

$agent_id = $_SESSION['user_id'];

// Fetch Agent's Own Statistics
$my_ideas = $conn->query("SELECT COUNT(*) as total FROM business_ideas WHERE agent_id = $agent_id")->fetch_assoc()['total'];
$my_templates = $conn->query("SELECT COUNT(*) as total FROM business_templates WHERE agent_id = $agent_id")->fetch_assoc()['total'];
$my_opportunities = $conn->query("SELECT COUNT(*) as total FROM opportunities WHERE agent_id = $agent_id")->fetch_assoc()['total'];
$my_resources = $conn->query("SELECT COUNT(*) as total FROM business_resources WHERE agent_id = $agent_id")->fetch_assoc()['total'];

// Feedback Received
$feedback = $conn->query("SELECT COUNT(*) as total, ROUND(AVG(rating), 1) as avg_rating 
    FROM feedback WHERE agent_id = $agent_id")->fetch_assoc();

// Recent Feedback
$recent_feedback = $conn->query("SELECT f.rating, f.message, u.full_name as user_name, f.created_at 
    FROM feedback f 
    JOIN users u ON f.user_id = u.id 
    WHERE f.agent_id = $agent_id 
    ORDER BY f.created_at DESC LIMIT 5")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Dashboard - Business Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            margin-left: 260px;
            min-height: 100vh;
            color: #333;
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
        }
        .welcome-text {
            color: rgba(255,255,255,0.9);
            font-size: 1.1rem;
        }
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="text-white mb-1">Welcome back, <?= htmlspecialchars($_SESSION['full_name']) ?>!</h1>
            <p class="welcome-text">Manage your content, track performance, and grow your impact as a mentor.</p>
        </div>
        <div>
            <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                <i class="fas fa-user-tie me-1"></i> Agent / Mentor
            </span>
        </div>
    </div>

    <!-- Quick Stats Row -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card card-hover h-100 bg-white border-0 shadow-sm text-center">
                <div class="card-body">
                    <i class="fas fa-lightbulb fa-3x text-warning mb-3"></i>
                    <h5 class="text-muted">My Business Ideas</h5>
                    <h3 class="stat-number text-warning"><?= number_format($my_ideas) ?></h3>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="agent_ideas.php" class="btn btn-outline-warning btn-sm w-100">Manage Ideas</a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-hover h-100 bg-white border-0 shadow-sm text-center">
                <div class="card-body">
                    <i class="fas fa-file-alt fa-3x text-info mb-3"></i>
                    <h5 class="text-muted">My Templates</h5>
                    <h3 class="stat-number text-info"><?= number_format($my_templates) ?></h3>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="agent_templates.php" class="btn btn-outline-info btn-sm w-100">Manage Templates</a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-hover h-100 bg-white border-0 shadow-sm text-center">
                <div class="card-body">
                    <i class="fas fa-handshake fa-3x text-success mb-3"></i>
                    <h5 class="text-muted">My Opportunities</h5>
                    <h3 class="stat-number text-success"><?= number_format($my_opportunities) ?></h3>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="agent_opportunities.php" class="btn btn-outline-success btn-sm w-100">Manage Opportunities</a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-hover h-100 bg-white border-0 shadow-sm text-center">
                <div class="card-body">
                    <i class="fas fa-book fa-3x text-primary mb-3"></i>
                    <h5 class="text-muted">My Resources</h5>
                    <h3 class="stat-number text-primary"><?= number_format($my_resources) ?></h3>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="agent_resources.php" class="btn btn-outline-primary btn-sm w-100">Manage Resources</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Feedback Section -->
    <div class="row">
        <div class="col-md-8">
            <div class="card bg-white border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-star text-warning"></i> Feedback Received</h5>
                </div>
                <div class="card-body">
                    <?php if ($feedback['total'] > 0): ?>
                        <div class="d-flex align-items-center mb-4">
                            <h2 class="mb-0 me-3"><?= $feedback['avg_rating'] ?? '0.0' ?> ★</h2>
                            <div>
                                <h5>Average Rating</h5>
                                <p class="text-muted mb-0"><?= number_format($feedback['total']) ?> reviews from entrepreneurs</p>
                            </div>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">You haven't received any feedback yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-white border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="fas fa-users fa-3x text-success mb-3"></i>
                    <h5 class="text-muted">Total Reach</h5>
                    <h3 class="stat-number text-success"><?= number_format($my_ideas + $my_templates + $my_opportunities + $my_resources) ?></h3>
                    <small class="text-muted">Content pieces published</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Feedback -->
    <?php if (!empty($recent_feedback)): ?>
    <div class="mt-5">
        <h4 class="text-white mb-3">Recent Feedback</h4>
        <div class="row g-3">
            <?php foreach ($recent_feedback as $fb): ?>
                <div class="col-md-6">
                    <div class="card bg-white border-0 shadow-sm card-hover">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <strong><?= htmlspecialchars($fb['user_name']) ?></strong>
                                <span class="text-warning"><?= str_repeat('★', $fb['rating']) ?></span>
                            </div>
                            <p class="mt-2 mb-1 small"><?= htmlspecialchars($fb['message']) ?></p>
                            <small class="text-muted"><?= date('d M, Y', strtotime($fb['created_at'])) ?></small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Quick Actions -->
    <div class="mt-5">
        <h4 class="text-white mb-3">Quick Actions</h4>
        <div class="row g-3">
            <div class="col-md-2">
                <a href="post_idea.php" class="btn btn-warning w-100 py-3">
                    <i class="fas fa-lightbulb"></i><br>
                    <small>New Idea</small>
                </a>
            </div>
            <div class="col-md-2">
                <a href="post_template.php" class="btn btn-info w-100 py-3">
                    <i class="fas fa-file-alt"></i><br>
                    <small>New Template</small>
                </a>
            </div>
            <div class="col-md-2">
                <a href="post_opportunity.php" class="btn btn-success w-100 py-3">
                    <i class="fas fa-handshake"></i><br>
                    <small>New Opportunity</small>
                </a>
            </div>
            <div class="col-md-2">
                <a href="post_resource.php" class="btn btn-primary w-100 py-3">
                    <i class="fas fa-book"></i><br>
                    <small>New Resource</small>
                </a>
            </div>
            <div class="col-md-2">
                <a href="my_profile.php" class="btn btn-secondary w-100 py-3">
                    <i class="fas fa-user-edit"></i><br>
                    <small>Edit Profile</small>
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>