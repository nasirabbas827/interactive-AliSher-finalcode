<?php
include 'config.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'agent') {
    header("Location: login.php");
    exit();
}

// Fetch categories for dropdown
$cat_query = mysqli_query($conn, "SELECT * FROM business_categories ORDER BY name ASC");

// Add idea
if(isset($_POST['add_idea'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $category_id = (int)$_POST['category_id'];
    $agent_id = $_SESSION['user_id'];

    $sql = "INSERT INTO business_ideas (agent_id, category_id, title, description) VALUES ($agent_id, $category_id, '$title', '$description')";
    mysqli_query($conn, $sql);
    $success = "Business idea added successfully!";
}

// Edit idea
if(isset($_POST['edit_idea'])) {
    $id = (int)$_POST['id'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $category_id = (int)$_POST['category_id'];

    $sql = "UPDATE business_ideas SET title='$title', description='$description', category_id=$category_id WHERE id=$id AND agent_id=".$_SESSION['user_id'];
    mysqli_query($conn, $sql);
    $success = "Business idea updated successfully!";
}

// Delete idea
if(isset($_POST['delete_idea'])) {
    $id = (int)$_POST['id'];
    mysqli_query($conn, "DELETE FROM business_ideas WHERE id=$id AND agent_id=".$_SESSION['user_id']);
    $success = "Business idea deleted successfully!";
}

// Fetch all ideas of this agent
$ideas_query = mysqli_query($conn, "
    SELECT bi.*, bc.name AS category_name 
    FROM business_ideas bi
    JOIN business_categories bc ON bc.id = bi.category_id
    WHERE bi.agent_id=".$_SESSION['user_id']."
    ORDER BY bi.id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Business Ideas</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
    body { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); min-height: 100vh; margin-left: 260px; color: #fff; }
    .ideas-card { max-width: 1000px; margin: 50px auto; background: #fff; border-radius: 20px; padding: 30px; color: #000; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); }
    .btn-action { border-radius: 50px; font-weight: bold; }
    .text-dark { color: #000 !important; }
</style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="container">
    <div class="ideas-card">
        <div class="d-flex justify-content-between mb-4">
            <h2 class="text-dark">Business Ideas</h2>
            <button class="btn btn-success btn-action" data-bs-toggle="modal" data-bs-target="#addIdeaModal">
                <i class="fas fa-plus"></i> Add Idea
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
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while($idea = mysqli_fetch_assoc($ideas_query)): ?>
                <tr>
                    <td><?= $idea['id'] ?></td>
                    <td><?= htmlspecialchars($idea['title']) ?></td>
                    <td><?= htmlspecialchars($idea['category_name']) ?></td>
                    <td><?= htmlspecialchars($idea['description']) ?></td>
                    <td>
                        <!-- Edit Button -->
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editIdeaModal<?= $idea['id'] ?>">
                            <i class="fas fa-edit"></i>
                        </button>

                        <!-- Delete Form -->
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="id" value="<?= $idea['id'] ?>">
                            <button type="submit" name="delete_idea" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editIdeaModal<?= $idea['id'] ?>" tabindex="-1" aria-labelledby="editIdeaLabel<?= $idea['id'] ?>" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title text-dark" id="editIdeaLabel<?= $idea['id'] ?>">Edit Business Idea</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                              </div>
                              <form method="POST">
                                <div class="modal-body">
                                  <input type="hidden" name="id" value="<?= $idea['id'] ?>">
                                  <div class="mb-3">
                                    <label class="form-label text-dark">Title</label>
                                    <input type="text" name="title" class="form-control" required value="<?= htmlspecialchars($idea['title']) ?>">
                                  </div>
                                  <div class="mb-3">
                                    <label class="form-label text-dark">Category</label>
                                    <select name="category_id" class="form-select" required>
                                      <?php
                                      mysqli_data_seek($cat_query, 0);
                                      while($cat = mysqli_fetch_assoc($cat_query)): ?>
                                        <option value="<?= $cat['id'] ?>" <?= $cat['id']==$idea['category_id']?'selected':'' ?>>
                                          <?= htmlspecialchars($cat['name']) ?>
                                        </option>
                                      <?php endwhile; ?>
                                    </select>
                                  </div>
                                  <div class="mb-3">
                                    <label class="form-label text-dark">Description</label>
                                    <textarea name="description" class="form-control" required><?= htmlspecialchars($idea['description']) ?></textarea>
                                  </div>
                                </div>
                                <div class="modal-footer">
                                  <button type="submit" name="edit_idea" class="btn btn-primary btn-action w-100">Save Changes</button>
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

<!-- Add Idea Modal -->
<div class="modal fade" id="addIdeaModal" tabindex="-1" aria-labelledby="addIdeaLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-dark" id="addIdeaLabel">Add New Business Idea</h5>
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
              <?php
              mysqli_data_seek($cat_query, 0);
              while($cat = mysqli_fetch_assoc($cat_query)): ?>
                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label text-dark">Description</label>
            <textarea name="description" class="form-control" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="add_idea" class="btn btn-success btn-action w-100">Add Idea</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>