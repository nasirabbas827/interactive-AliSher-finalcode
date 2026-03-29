<?php
include 'config.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'agent') {
    header("Location: login.php");
    exit();
}

// Fetch categories for dropdown
$cat_query = mysqli_query($conn, "SELECT * FROM business_categories ORDER BY name ASC");

// Handle Add Template
if(isset($_POST['add_template'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $category_id = (int)$_POST['category_id'];
    $agent_id = $_SESSION['user_id'];

    // Handle file upload
    $file_url = '';
    if(isset($_FILES['file']) && $_FILES['file']['error'] == 0){
        $target_dir = "templates/";
        if(!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $filename = time().'_'.basename($_FILES['file']['name']);
        $target_file = $target_dir.$filename;
        if(move_uploaded_file($_FILES['file']['tmp_name'], $target_file)){
            $file_url = $target_file;
        }
    }

    $sql = "INSERT INTO business_templates (agent_id, category_id, title, description, file_url) 
            VALUES ($agent_id, $category_id, '$title', '$description', '$file_url')";
    mysqli_query($conn, $sql);
    $success = "Template added successfully!";
}

// Handle Edit Template
if(isset($_POST['edit_template'])){
    $id = (int)$_POST['id'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $category_id = (int)$_POST['category_id'];

    $sql = "UPDATE business_templates SET title='$title', description='$description', category_id=$category_id 
            WHERE id=$id AND agent_id=".$_SESSION['user_id'];

    // Check file update
    if(isset($_FILES['file']) && $_FILES['file']['error'] == 0){
        $target_dir = "templates/";
        if(!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $filename = time().'_'.basename($_FILES['file']['name']);
        $target_file = $target_dir.$filename;
        if(move_uploaded_file($_FILES['file']['tmp_name'], $target_file)){
            $sql = "UPDATE business_templates SET title='$title', description='$description', category_id=$category_id, file_url='$target_file' 
                    WHERE id=$id AND agent_id=".$_SESSION['user_id'];
        }
    }

    mysqli_query($conn, $sql);
    $success = "Template updated successfully!";
}

// Handle Delete Template
if(isset($_POST['delete_template'])){
    $id = (int)$_POST['id'];
    mysqli_query($conn, "DELETE FROM business_templates WHERE id=$id AND agent_id=".$_SESSION['user_id']);
    $success = "Template deleted successfully!";
}

// Fetch all templates of this agent
$templates_query = mysqli_query($conn, "
    SELECT bt.*, bc.name AS category_name 
    FROM business_templates bt
    LEFT JOIN business_categories bc ON bc.id = bt.category_id
    WHERE bt.agent_id=".$_SESSION['user_id']."
    ORDER BY bt.id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Business Templates</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
    body { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); min-height: 100vh; margin-left: 260px; color: #fff; }
    .card-container { max-width: 1000px; margin: 50px auto; background: #fff; border-radius: 20px; padding: 30px; color: #000; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); }
    .btn-action { border-radius: 50px; font-weight: bold; }
    .text-dark { color: #000 !important; }
</style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="container">
    <div class="card-container">
        <div class="d-flex justify-content-between mb-4">
            <h2 class="text-dark">Business Templates</h2>
            <button class="btn btn-success btn-action" data-bs-toggle="modal" data-bs-target="#addTemplateModal">
                <i class="fas fa-plus"></i> Add Template
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
                    <th>File</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while($tpl = mysqli_fetch_assoc($templates_query)): ?>
                <tr>
                    <td><?= $tpl['id'] ?></td>
                    <td><?= htmlspecialchars($tpl['title']) ?></td>
                    <td><?= htmlspecialchars($tpl['category_name']) ?></td>
                    <td><a href="<?= htmlspecialchars($tpl['file_url']) ?>" target="_blank">Download</a></td>
                    <td><?= htmlspecialchars($tpl['description']) ?></td>
                    <td>
                        <!-- Edit Button -->
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editTemplateModal<?= $tpl['id'] ?>">
                            <i class="fas fa-edit"></i>
                        </button>

                        <!-- Delete Form -->
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="id" value="<?= $tpl['id'] ?>">
                            <button type="submit" name="delete_template" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editTemplateModal<?= $tpl['id'] ?>" tabindex="-1" aria-labelledby="editTemplateLabel<?= $tpl['id'] ?>" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title text-dark" id="editTemplateLabel<?= $tpl['id'] ?>">Edit Template</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                              </div>
                              <form method="POST" enctype="multipart/form-data">
                                <div class="modal-body">
                                  <input type="hidden" name="id" value="<?= $tpl['id'] ?>">
                                  <div class="mb-3">
                                    <label class="form-label text-dark">Title</label>
                                    <input type="text" name="title" class="form-control" required value="<?= htmlspecialchars($tpl['title']) ?>">
                                  </div>
                                  <div class="mb-3">
                                    <label class="form-label text-dark">Category</label>
                                    <select name="category_id" class="form-select">
                                      <?php
                                      mysqli_data_seek($cat_query, 0);
                                      while($cat = mysqli_fetch_assoc($cat_query)): ?>
                                        <option value="<?= $cat['id'] ?>" <?= $cat['id']==$tpl['category_id']?'selected':'' ?>>
                                          <?= htmlspecialchars($cat['name']) ?>
                                        </option>
                                      <?php endwhile; ?>
                                    </select>
                                  </div>
                                  <div class="mb-3">
                                    <label class="form-label text-dark">Description</label>
                                    <textarea name="description" class="form-control"><?= htmlspecialchars($tpl['description']) ?></textarea>
                                  </div>
                                  <div class="mb-3">
                                    <label class="form-label text-dark">File</label>
                                    <input type="file" name="file" class="form-control">
                                  </div>
                                </div>
                                <div class="modal-footer">
                                  <button type="submit" name="edit_template" class="btn btn-primary btn-action w-100">Save Changes</button>
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

<!-- Add Template Modal -->
<div class="modal fade" id="addTemplateModal" tabindex="-1" aria-labelledby="addTemplateLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-dark" id="addTemplateLabel">Add New Template</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" enctype="multipart/form-data">
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label text-dark">Title</label>
            <input type="text" name="title" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label text-dark">Category</label>
            <select name="category_id" class="form-select">
              <?php
              mysqli_data_seek($cat_query, 0);
              while($cat = mysqli_fetch_assoc($cat_query)): ?>
                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label text-dark">Description</label>
            <textarea name="description" class="form-control"></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label text-dark">File</label>
            <input type="file" name="file" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="add_template" class="btn btn-success btn-action w-100">Add Template</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>