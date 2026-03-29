<?php 
include 'config.php';

if(isset($_POST['register'])) {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email     = mysqli_real_escape_string($conn, $_POST['email']);
    $phone     = mysqli_real_escape_string($conn, $_POST['phone'] ?? '');
    $password  = $_POST['password'];
    $role      = mysqli_real_escape_string($conn, $_POST['role']); // Get role from dropdown

    // Validate role
    $allowed_roles = ['user', 'agent', 'admin'];
    if (!in_array($role, $allowed_roles)) {
        $role = 'user'; // fallback
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $check = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");
    
    if(mysqli_num_rows($check) > 0) {
        $error = "Email already registered!";
    } else {
        $sql = "INSERT INTO users (full_name, email, password_hash, role, phone) 
                VALUES ('$full_name', '$email', '$password_hash', '$role', '$phone')";
       
        if(mysqli_query($conn, $sql)) {
            header("Location: login.php?success=registered");
            exit();
        } else {
            $error = "Registration failed! Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - Entrepreneur Portal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
      min-height: 100vh;
    }
    .register-card {
      max-width: 480px;
      margin: 80px auto;
      border: none;
      border-radius: 20px;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    .btn-lg:hover{
      background-color: #18108bff;
      color:white;
    }
  </style>
</head>
<body>

<?php include 'top_nav.php'; ?>

<div class="container">
  <div class="register-card bg-white p-5">
    <div class="text-center mb-4">
      <i class="fas fa-rocket fa-3x text-primary mb-3"></i>
      <h2 class="fw-bold">Create Account</h2>
      <p class="text-muted">Join the Entrepreneur Portal today</p>
    </div>

    <?php if(isset($error)): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Full Name</label>
        <input type="text" name="full_name" class="form-control form-control-lg" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Email Address</label>
        <input type="email" name="email" class="form-control form-control-lg" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Phone Number (Optional)</label>
        <input type="tel" name="phone" class="form-control form-control-lg">
      </div>

      <div class="mb-3">
        <label class="form-label">Register As</label>
        <select name="role" class="form-select form-select-lg" required>
          <option value="user">User</option>
          <option value="agent">Agent</option>
        </select>
      </div>

      <div class="mb-4">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control form-control-lg" required>
      </div>

      <button type="submit" name="register" class="btn btn-primary btn-lg w-100 py-3 rounded-pill">
        Create My Account
      </button>
    </form>

    <p class="text-center mt-4 mb-0">
      Already have an account? <a href="login.php" class="text-primary fw-bold">Login here</a>
    </p>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>