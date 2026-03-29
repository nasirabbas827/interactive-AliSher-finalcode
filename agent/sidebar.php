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
    <?php elseif($_SESSION['role'] == 'agent'): ?>
      <a href="agent_dashboard.php" <?php echo basename($_SERVER['PHP_SELF']) == 'agent_dashboard.php' ? 'class="active"' : ''; ?>>
        <i class="fas fa-user-tie me-2"></i>Agent Dashboard
      </a>
      <a href="agent_profile.php" <?php echo basename($_SERVER['PHP_SELF']) == 'agent_profile.php' ? 'class="active"' : ''; ?>>
        <i class="fas fa-id-card me-2"></i>Agent Profile
      </a>
      <a href="agent_categories.php" <?php echo basename($_SERVER['PHP_SELF']) == 'agent_categories.php' ? 'class="active"' : ''; ?>>
        <i class="fas fa-tags me-2"></i>Agent Categories
      </a>
      <a href="agent_ideas.php" <?php echo basename($_SERVER['PHP_SELF']) == 'agent_ideas.php' ? 'class="active"' : ''; ?>>
        <i class="fas fa-lightbulb me-2"></i>Agent Ideas
      </a>
      <a href="agent_templates.php" <?php echo basename($_SERVER['PHP_SELF']) == 'agent_templates.php' ? 'class="active"' : ''; ?>>
        <i class="fas fa-file-alt me-2"></i>Agent Templates
      </a>
      <a href="agent_opportunities.php" <?php echo basename($_SERVER['PHP_SELF']) == 'agent_opportunities.php' ? 'class="active"' : ''; ?>>
        <i class="fas fa-briefcase me-2"></i>Agent Opportunities
      </a>
      <a href="agent_resources.php" <?php echo basename($_SERVER['PHP_SELF']) == 'agent_resources.php' ? 'class="active"' : ''; ?>>
        <i class="fas fa-book-open me-2"></i>Agent Resources
      </a>
      <a href="forum.php" <?php echo basename($_SERVER['PHP_SELF']) == 'forum.php' ? 'class="active"' : ''; ?>>
        <i class="fas fa-comments me-2"></i>Forum
      </a>
      <a href="agent_messages.php" <?php echo basename($_SERVER['PHP_SELF']) == 'agent_messages.php' ? 'class="active"' : ''; ?>>
        <i class="fas fa-envelope me-2"></i>Messages
      </a>
      <a href="agent_feedbacks.php" <?php echo basename($_SERVER['PHP_SELF']) == 'agent_feedbacks.php' ? 'class="active"' : ''; ?>>
        <i class="fas fa-star me-2"></i>Feedbacks
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