<!-- Top Navigation -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand text-white fw-bold fs-3" href="index.php">
            <i class="fas fa-rocket text-primary me-2"></i>Business<span class="text-primary">Portal</span>
        </a>
        
        <div class="ms-auto d-flex align-items-center gap-3">
            <?php if(isset($_SESSION['user_id'])): ?>
                <span class="text-white-50 d-none d-md-inline">
                    Hi, <?= htmlspecialchars($_SESSION['full_name']) ?>
                </span>
                <a href="<?php 
                    echo ($_SESSION['role'] == 'admin') ? 'admin_dashboard.php' : 
                         ($_SESSION['role'] == 'agent' ? 'agent_dashboard.php' : 'user_dashboard.php'); 
                ?>" class="btn btn-primary rounded-pill px-4 py-2">Dashboard</a>
                <a href="logout.php" class="btn btn-outline-light rounded-pill px-4 py-2">Logout</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-outline-light px-4">Login</a>
                <a href="register.php" class="btn btn-primary rounded-pill px-5 py-2 shadow">Join Free</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<style>
            /* Fixed Navigation */
        .navbar {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(255,255,255,0.1);
            padding: 16px 0;
            z-index: 1100;
        }
</style>