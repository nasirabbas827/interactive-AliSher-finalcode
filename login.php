<?php 
include 'config.php';

if(isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $role = mysqli_real_escape_string($conn, $_POST['role'] ?? 'user');

    $query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email' AND role = '$role'");

    if(mysqli_num_rows($query) > 0) {
        $user = mysqli_fetch_assoc($query);
        if(password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['full_name'] = $user['full_name'];

            if($user['role'] == 'admin') {
                header("Location: admin/admin_dashboard.php");
            } elseif($user['role'] == 'agent') {
                header("Location: agent/agent_dashboard.php");
            } else {
                header("Location: user/user_dashboard.php");
            }
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "No user found with this email and role!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Entrepreneur Portal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
      min-height: 100vh;
    }
    .login-card {
      max-width: 480px;
      margin: 80px auto;
      border: none;
      border-radius: 20px;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
      padding: 40px;
    }
    .login-card h2 {
      color: #4f46e5;
    }
    .form-control, .form-select {
      border-radius: 12px;
      height: 50px;
      font-size: 1rem;
    }
    .btn-login {
      border-radius: 50px;
      font-weight: bold;
      background: #4f46e5;
      color: #fff;
      height: 55px;
      font-size: 1.1rem;
    }
    .btn-login:hover{
      background-color: #18108bff;
      color:white;
    }
  </style>
</head>
<body>

<?php include 'top_nav.php'; ?>


<div class="container">
  <div class="login-card bg-white">
    <div class="text-center mb-4">
      <i class="fas fa-rocket fa-3x text-primary mb-3"></i>
      <h2 class="fw-bold">Welcome Back</h2>
      <p class="text-muted">Sign in to access your dashboard</p>
    </div>

    <?php if(isset($error)): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Email Address</label>
        <input type="email" name="email" class="form-control form-control-lg" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control form-control-lg" required>
      </div>

      <div class="mb-4">
        <label class="form-label">Login As</label>
        <select name="role" class="form-select form-select-lg" required>
          <option value="user">User</option>
          <option value="agent">Agent</option>
          <option value="admin">Administrator</option>
        </select>
      </div>

      <button type="submit" name="login" class="btn btn-login w-100">
        Sign In
      </button>
    </form>

    <p class="text-center mt-4 mb-0">
      Don't have an account? <a href="register.php" class="text-primary fw-bold">Register Now</a>
    </p>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>