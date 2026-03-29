<?php
include 'config.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch categories
$cat_query = mysqli_query($conn, "SELECT * FROM business_categories ORDER BY name ASC");

// ADD
if(isset($_POST['add_tutorial'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $category_id = (int)$_POST['category_id'];
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $video_url = mysqli_real_escape_string($conn, $_POST['video_url']);
    $duration = (int)$_POST['duration_min'];
    $level = $_POST['level'];

    $sql = "INSERT INTO tutorials (category_id, title, content, video_url, duration_min, level)
            VALUES ($category_id, '$title', '$content', '$video_url', $duration, '$level')";
    mysqli_query($conn, $sql);

    $success = "Tutorial added successfully!";
}

// EDIT
if(isset($_POST['edit_tutorial'])) {
    $id = (int)$_POST['id'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $category_id = (int)$_POST['category_id'];
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $video_url = mysqli_real_escape_string($conn, $_POST['video_url']);
    $duration = (int)$_POST['duration_min'];
    $level = $_POST['level'];

    $sql = "UPDATE tutorials SET 
            title='$title',
            category_id=$category_id,
            content='$content',
            video_url='$video_url',
            duration_min=$duration,
            level='$level'
            WHERE id=$id";

    mysqli_query($conn, $sql);
    $success = "Tutorial updated successfully!";
}

// DELETE
if(isset($_POST['delete_tutorial'])) {
    $id = (int)$_POST['id'];
    mysqli_query($conn, "DELETE FROM tutorials WHERE id=$id");
    $success = "Tutorial deleted successfully!";
}

// FETCH
$tutorial_query = mysqli_query($conn, "
    SELECT t.*, bc.name AS category_name
    FROM tutorials t
    LEFT JOIN business_categories bc ON bc.id = t.category_id
    ORDER BY t.id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Tutorials</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
body { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); min-height: 100vh; margin-left: 260px; color: #fff; }
.opp-card { max-width: 1000px; margin: 50px auto; background: #fff; border-radius: 20px; padding: 30px; color: #000; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); }
.btn-action { border-radius: 50px; font-weight: bold; }
.text-dark { color: #000 !important; }
</style>
</head>

<body>

<?php include 'sidebar.php'; ?>

<div class="container">
<div class="opp-card">

<div class="d-flex justify-content-between mb-4">
    <h2 class="text-dark">Manage Tutorials</h2>
    <button class="btn btn-success btn-action" data-bs-toggle="modal" data-bs-target="#addTutorialModal">
        <i class="fas fa-plus"></i> Add Tutorial
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
<th>Level</th>
<th>Duration</th>
<th>Actions</th>
</tr>
</thead>

<tbody>
<?php while($t = mysqli_fetch_assoc($tutorial_query)): ?>
<tr>
<td><?= $t['id'] ?></td>
<td><?= htmlspecialchars($t['title']) ?></td>
<td><?= htmlspecialchars($t['category_name']) ?></td>
<td><?= ucfirst($t['level']) ?></td>
<td><?= $t['duration_min'] ?> min</td>

<td>

<!-- EDIT -->
<button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editTutorialModal<?= $t['id'] ?>">
<i class="fas fa-edit"></i>
</button>

<!-- DELETE -->
<form method="POST" class="d-inline">
<input type="hidden" name="id" value="<?= $t['id'] ?>">
<button type="submit" name="delete_tutorial" class="btn btn-danger btn-sm">
<i class="fas fa-trash"></i>
</button>
</form>

<!-- EDIT MODAL -->
<div class="modal fade" id="editTutorialModal<?= $t['id'] ?>">
<div class="modal-dialog">
<div class="modal-content">

<div class="modal-header">
<h5 class="modal-title text-dark">Edit Tutorial</h5>
<button class="btn-close" data-bs-dismiss="modal"></button>
</div>

<form method="POST">
<div class="modal-body">

<input type="hidden" name="id" value="<?= $t['id'] ?>">

<div class="mb-3">
<label class="form-label text-dark">Title</label>
<input type="text" name="title" class="form-control" value="<?= htmlspecialchars($t['title']) ?>" required>
</div>

<div class="mb-3">
<label class="form-label text-dark">Category</label>
<select name="category_id" class="form-select">
<?php mysqli_data_seek($cat_query, 0); while($cat = mysqli_fetch_assoc($cat_query)): ?>
<option value="<?= $cat['id'] ?>" <?= $cat['id']==$t['category_id']?'selected':'' ?>>
<?= htmlspecialchars($cat['name']) ?>
</option>
<?php endwhile; ?>
</select>
</div>

<div class="mb-3">
<label class="form-label text-dark">Content</label>
<textarea name="content" class="form-control"><?= htmlspecialchars($t['content']) ?></textarea>
</div>

<div class="mb-3">
<label class="form-label text-dark">Video URL</label>
<input type="text" name="video_url" class="form-control" value="<?= $t['video_url'] ?>">
</div>

<div class="mb-3">
<label class="form-label text-dark">Duration (min)</label>
<input type="number" name="duration_min" class="form-control" value="<?= $t['duration_min'] ?>">
</div>

<div class="mb-3">
<label class="form-label text-dark">Level</label>
<select name="level" class="form-select">
<option value="beginner" <?= $t['level']=='beginner'?'selected':'' ?>>Beginner</option>
<option value="intermediate" <?= $t['level']=='intermediate'?'selected':'' ?>>Intermediate</option>
<option value="advanced" <?= $t['level']=='advanced'?'selected':'' ?>>Advanced</option>
</select>
</div>

</div>

<div class="modal-footer">
<button type="submit" name="edit_tutorial" class="btn btn-primary btn-action w-100">Save Changes</button>
</div>

</form>
</div>
</div>
</div>

</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

</div>
</div>

<!-- ADD MODAL -->
<div class="modal fade" id="addTutorialModal">
<div class="modal-dialog">
<div class="modal-content">

<div class="modal-header">
<h5 class="modal-title text-dark">Add Tutorial</h5>
<button class="btn-close" data-bs-dismiss="modal"></button>
</div>

<form method="POST">
<div class="modal-body">

<div class="mb-3">
<label class="form-label text-dark">Title</label>
<input type="text" name="title" class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label text-dark">Category</label>
<select name="category_id" class="form-select">
<?php mysqli_data_seek($cat_query, 0); while($cat = mysqli_fetch_assoc($cat_query)): ?>
<option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
<?php endwhile; ?>
</select>
</div>

<div class="mb-3">
<label class="form-label text-dark">Content</label>
<textarea name="content" class="form-control"></textarea>
</div>

<div class="mb-3">
<label class="form-label text-dark">Video URL</label>
<input type="text" name="video_url" class="form-control">
</div>

<div class="mb-3">
<label class="form-label text-dark">Duration (min)</label>
<input type="number" name="duration_min" class="form-control">
</div>

<div class="mb-3">
<label class="form-label text-dark">Level</label>
<select name="level" class="form-select">
<option value="beginner">Beginner</option>
<option value="intermediate">Intermediate</option>
<option value="advanced">Advanced</option>
</select>
</div>

</div>

<div class="modal-footer">
<button type="submit" name="add_tutorial" class="btn btn-success btn-action w-100">Add Tutorial</button>
</div>

</form>

</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>