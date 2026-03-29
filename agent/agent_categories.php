<?php
include 'config.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'agent') {
    header("Location: login.php");
    exit();
}

// Add category
if(isset($_POST['add_category'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $sql = "INSERT INTO business_categories (name, description) VALUES ('$name', '$description')";
    if(mysqli_query($conn, $sql)) {
        $success = "Category added successfully!";
    } else {
        $error = "Failed to add category!";
    }
}

// Edit category
if(isset($_POST['edit_category'])) {
    $id = (int)$_POST['id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $sql = "UPDATE business_categories SET name='$name', description='$description' WHERE id=$id";
    if(mysqli_query($conn, $sql)) {
        $success = "Category updated successfully!";
    } else {
        $error = "Failed to update category!";
    }
}

// Delete category
if(isset($_POST['delete_category'])) {
    $id = (int)$_POST['id'];
    mysqli_query($conn, "DELETE FROM business_categories WHERE id=$id");
    $success = "Category deleted successfully!";
}

// Fetch all categories
$categories_query = mysqli_query($conn, "SELECT * FROM business_categories ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Business Categories</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
    body { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); min-height: 100vh; margin-left: 260px; color: #fff; }
    .categories-card { max-width: 1000px; margin: 50px auto; background: #fff; border-radius: 20px; padding: 30px; color: #000; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); }
    .btn-action { border-radius: 50px; font-weight: bold; }
</style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="container">
    <div class="categories-card">
        <div class="d-flex justify-content-between mb-4">
            <h2 class="text-primary">Business Categories</h2>
            <button class="btn btn-success btn-action" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                <i class="fas fa-plus"></i> Add Category
            </button>
        </div>

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
                    <th>Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while($cat = mysqli_fetch_assoc($categories_query)): ?>
                <tr>
                    <td><?= $cat['id'] ?></td>
                    <td><?= htmlspecialchars($cat['name']) ?></td>
                    <td><?= htmlspecialchars($cat['description']) ?></td>
                    <td>
                        <!-- Edit Button -->
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editCategoryModal<?= $cat['id'] ?>">
                            <i class="fas fa-edit"></i>
                        </button>

                        <!-- Delete Form -->
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                            <button type="submit" name="delete_category" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editCategoryModal<?= $cat['id'] ?>" tabindex="-1" aria-labelledby="editCategoryLabel<?= $cat['id'] ?>" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title text-dark" id="editCategoryLabel<?= $cat['id'] ?>">Edit Category</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                              </div>
                              <form method="POST">
                                <div class="modal-body">
                                  <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                                  <div class="mb-3">
                                    <label class="form-label text-dark">Name</label>
                                    <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($cat['name']) ?>">
                                  </div>
                                  <div class="mb-3">
                                    <label class="form-label text-dark">Description</label>
                                    <textarea name="description" class="form-control"><?= htmlspecialchars($cat['description']) ?></textarea>
                                  </div>
                                </div>
                                <div class="modal-footer">
                                  <button type="submit" name="edit_category" class="btn btn-primary btn-action w-100">Save Changes</button>
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

<!-- Add Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-dark" id="addCategoryLabel">Add New Category</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST">
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label text-dark">Name</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label text-dark">Description</label>
            <textarea name="description" class="form-control"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="add_category" class="btn btn-success btn-action w-100">Add Category</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>