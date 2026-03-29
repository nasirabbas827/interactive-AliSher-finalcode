<?php
include 'config.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch categories
$cat_query = mysqli_query($conn, "SELECT * FROM business_categories ORDER BY name ASC");

// Add Thread
if(isset($_POST['add_thread'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $body = mysqli_real_escape_string($conn, $_POST['body']);
    $category_id = (int)$_POST['category_id'];
    $user_id = $_SESSION['user_id'];

    mysqli_query($conn, "INSERT INTO forum_threads (user_id, category_id, title, body) VALUES ($user_id, $category_id, '$title', '$body')");
    $success = "Thread created successfully!";
}

// Fetch threads
$threads_query = mysqli_query($conn, "
    SELECT ft.*, u.full_name, bc.name AS category_name
    FROM forum_threads ft
    JOIN users u ON u.id = ft.user_id
    JOIN business_categories bc ON bc.id = ft.category_id
    ORDER BY ft.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Forum</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
body { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); margin-left: 260px; min-height: 100vh; color: #fff; }
.forum-card { max-width: 1000px; margin: 50px auto; background: #fff; border-radius: 20px; padding: 30px; color: #000; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); }
.btn-action { border-radius: 50px; font-weight: bold; }
.text-dark { color: #000 !important; }
</style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="container">
    <div class="forum-card">
        <div class="d-flex justify-content-between mb-4">
            <h2 class="text-dark">Forum Threads</h2>
            <button class="btn btn-success btn-action" data-bs-toggle="modal" data-bs-target="#addThreadModal">
                <i class="fas fa-plus"></i> New Thread
            </button>
        </div>

        <?php if(isset($success)): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>

        <table class="table table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Creator</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php while($thread = mysqli_fetch_assoc($threads_query)): ?>
                <tr>
                    <td><?= $thread['id'] ?></td>
                    <td><a href="forum_discussion.php?id=<?= $thread['id'] ?>"><?= htmlspecialchars($thread['title']) ?></a></td>
                    <td><?= htmlspecialchars($thread['category_name']) ?></td>
                    <td><?= htmlspecialchars($thread['full_name']) ?></td>
                    <td><?= $thread['created_at'] ?></td>
                    <td>
                        <!-- Only creator can delete -->
                        <?php if($thread['user_id'] == $_SESSION['user_id']): ?>
                        <form method="POST" action="forum_delete.php" class="d-inline">
                            <input type="hidden" name="id" value="<?= $thread['id'] ?>">
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Thread Modal -->
<div class="modal fade" id="addThreadModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-dark">New Thread</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST">
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label text-dark">Title</label>
            <input type="text" name="title" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label text-dark">Category</label>
            <select name="category_id" class="form-select" required>
              <?php mysqli_data_seek($cat_query, 0); while($cat = mysqli_fetch_assoc($cat_query)): ?>
                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label text-dark">Body</label>
            <textarea name="body" class="form-control" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="add_thread" class="btn btn-success btn-action w-100">Create Thread</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>