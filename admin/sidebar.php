<!-- sidebar.php -->
<style>
  :root {
    --primary: #6366f1;
    --dark: #1e2937;
  }
  .sidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    width: 260px;
    background: var(--dark);
    color: white;
    padding-top: 80px;
    transition: all 0.3s;
    z-index: 1000;
    overflow-y: auto;
  }
  .sidebar a {
    color: #cbd5e1;
    padding: 15px 25px;
    display: block;
    text-decoration: none;
    transition: all 0.3s;
  }
  .sidebar a:hover,
  .sidebar a.active {
    background: var(--primary);
    color: white;
    padding-left: 35px;
  }
  .sidebar .brand {
    position: fixed;
    top: 0;
    left: 0;
    width: 260px;
    background: var(--dark);
    padding: 20px 25px;
    border-bottom: 1px solid #334155;
    z-index: 1100;
  }
</style>

<div class="sidebar">
  <div class="brand">
    <h4 class="text-white mb-0">
      <i class="fas fa-rocket me-2"></i>Business Portal
    </h4>
  </div>

  <?php if(isset($_SESSION['user_id'])): ?>
    <hr class="border-secondary mx-3 my-2">

    <?php if($_SESSION['role'] == 'admin'): ?>
      <a href="admin_dashboard.php" <?php echo basename($_SERVER['PHP_SELF']) == 'admin_dashboard.php' ? 'class="active"' : ''; ?>>
        <i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard
      </a>
      <a href="admin_users.php" <?php echo basename($_SERVER['PHP_SELF']) == 'admin_users.php' ? 'class="active"' : ''; ?>>
        <i class="fas fa-users me-2"></i>Manage Users
      </a>
      <a href="admin_manage_events.php" <?php echo basename($_SERVER['PHP_SELF']) == 'admin_manage_events.php' ? 'class="active"' : ''; ?>>
        <i class="fas fa-calendar-alt me-2"></i>Manage Events
      </a>
      <a href="admin_manage_tutorials.php" <?php echo basename($_SERVER['PHP_SELF']) == 'admin_manage_tutorials.php' ? 'class="active"' : ''; ?>>
        <i class="fas fa-book me-2"></i>Manage Tutorials
      </a>
    <?php elseif($_SESSION['role'] == 'agent'): ?>
      <a href="agent_dashboard.php" <?php echo basename($_SERVER['PHP_SELF']) == 'agent_dashboard.php' ? 'class="active"' : ''; ?>>
        <i class="fas fa-user-tie me-2"></i>Agent Dashboard
      </a>
    <?php else: ?>
      <a href="user_dashboard.php" <?php echo basename($_SERVER['PHP_SELF']) == 'user_dashboard.php' ? 'class="active"' : ''; ?>>
        <i class="fas fa-user me-2"></i>My Dashboard
      </a>
    <?php endif; ?>

    <a href="logout.php">
      <i class="fas fa-sign-out-alt me-2"></i>Logout
    </a>
  <?php endif; ?>
</div>