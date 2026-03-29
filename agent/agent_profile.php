<?php
include 'config.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'agent') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch existing profile if available
$profile_query = mysqli_query($conn, "SELECT * FROM agent_profiles WHERE user_id = $user_id");
$profile = mysqli_fetch_assoc($profile_query);

if(isset($_POST['save_profile'])) {
    $business_domain = mysqli_real_escape_string($conn, $_POST['business_domain']);
    $bio = mysqli_real_escape_string($conn, $_POST['bio']);
    $experience_yrs = (int)$_POST['experience_yrs'];
    $linkedin_url = mysqli_real_escape_string($conn, $_POST['linkedin_url']);
    $website_url = mysqli_real_escape_string($conn, $_POST['website_url']);

    if($profile) {
        // Update existing profile
        $sql = "UPDATE agent_profiles SET 
                    business_domain='$business_domain',
                    bio='$bio',
                    experience_yrs='$experience_yrs',
                    linkedin_url='$linkedin_url',
                    website_url='$website_url'
                WHERE user_id=$user_id";
        $message = "Profile updated successfully!";
    } else {
        // Insert new profile
        $sql = "INSERT INTO agent_profiles 
                    (user_id, business_domain, bio, experience_yrs, linkedin_url, website_url)
                VALUES 
                    ($user_id, '$business_domain', '$bio', '$experience_yrs', '$linkedin_url', '$website_url')";
        $message = "Profile created successfully!";
    }

    if(mysqli_query($conn, $sql)) {
        $profile_query = mysqli_query($conn, "SELECT * FROM agent_profiles WHERE user_id = $user_id");
        $profile = mysqli_fetch_assoc($profile_query);
        $success = $message;
    } else {
        $error = "Something went wrong! Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Agent Profile - Entrepreneur Portal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
      min-height: 100vh;
      margin-left: 260px;
    }
    .profile-card {
      max-width: 600px;
      margin: 80px auto;
      border: none;
      border-radius: 20px;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
      background: #fff;
      padding: 40px;
    }
    .profile-card h2 {
      color: #4f46e5;
    }
    .form-control, .form-select {
      border-radius: 12px;
      height: 50px;
      font-size: 1rem;
    }
    textarea.form-control {
      height: auto;
      min-height: 100px;
    }
    .btn-save {
      border-radius: 50px;
      font-weight: bold;
      background: #4f46e5;
      color: #fff;
      height: 55px;
      font-size: 1.1rem;
    }
  </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="container">
  <div class="profile-card">
    <div class="text-center mb-4">
      <i class="fas fa-user-tie fa-3x text-primary mb-3"></i>
      <h2 class="fw-bold"><?= $profile ? 'Update Your Profile' : 'Create Your Profile' ?></h2>
      <p class="text-muted">Manage your agent profile details</p>
    </div>

    <?php if(isset($success)): ?>
      <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    <?php if(isset($error)): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Business Domain</label>
        <input type="text" name="business_domain" class="form-control" required value="<?= htmlspecialchars($profile['business_domain'] ?? '') ?>">
      </div>

      <div class="mb-3">
        <label class="form-label">Bio</label>
        <textarea name="bio" class="form-control"><?= htmlspecialchars($profile['bio'] ?? '') ?></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Years of Experience</label>
        <input type="number" name="experience_yrs" class="form-control" min="0" max="50" value="<?= htmlspecialchars($profile['experience_yrs'] ?? 0) ?>">
      </div>

      <div class="mb-3">
        <label class="form-label">LinkedIn URL</label>
        <input type="url" name="linkedin_url" class="form-control" value="<?= htmlspecialchars($profile['linkedin_url'] ?? '') ?>">
      </div>

      <div class="mb-3">
        <label class="form-label">Website URL</label>
        <input type="url" name="website_url" class="form-control" value="<?= htmlspecialchars($profile['website_url'] ?? '') ?>">
      </div>

      <button type="submit" name="save_profile" class="btn btn-save w-100">
        <?= $profile ? 'Update Profile' : 'Create Profile' ?>
      </button>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>