<?php
include 'config.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Update user status
if(isset($_POST['update_status'])) {
    $user_id = (int)$_POST['user_id'];
    $status = $_POST['status'];
    $allowed_status = ['active','inactive','banned'];

    if(in_array($status, $allowed_status)) {
        mysqli_query($conn, "UPDATE users SET status='$status' WHERE id=$user_id");
        $success = "User status updated successfully!";
    } else {
        $error = "Invalid status selected!";
    }
}

// Fetch all users
$users_query = mysqli_query($conn, "
    SELECT u.*, a.business_domain, a.bio, a.experience_yrs, a.linkedin_url, a.website_url
    FROM users u
    LEFT JOIN agent_profiles a ON u.id = a.user_id
    ORDER BY u.id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin - Manage Users</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
    body { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); min-height: 100vh; margin-left: 260px; color: #fff; }
    .users-card { max-width: 1000px; margin: 50px auto; background: #fff; border-radius: 20px; padding: 30px; color: #000; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); }
    .btn-update { border-radius: 50px; font-weight: bold; background: #4f46e5; color: #fff; }
</style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="container">
    <div class="users-card">
        <h2 class="mb-4 text-center text-primary">Manage Users</h2>

        <?php if(isset($success)): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>
        <?php if(isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <table class="table table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php while($user = mysqli_fetch_assoc($users_query)): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= htmlspecialchars($user['full_name']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= ucfirst($user['role']) ?></td>
                    <td><?= ucfirst($user['status']) ?></td>
                    <td>
                        <!-- Update Status Form -->
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                            <select name="status" class="form-select form-select-sm d-inline w-auto">
                                <option value="active" <?= $user['status']=='active'?'selected':'' ?>>Active</option>
                                <option value="inactive" <?= $user['status']=='inactive'?'selected':'' ?>>Inactive</option>
                                <option value="banned" <?= $user['status']=='banned'?'selected':'' ?>>Banned</option>
                            </select>
                            <button type="submit" name="update_status" class="btn btn-update btn-sm mt-1">Update</button>
                        </form>

                        <?php if($user['role']=='agent'): ?>
                        <a href="mentor_profile.php?id=<?= $user['id'] ?>" class="btn btn-warning btn-sm btn-action">Mentor Profile</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>