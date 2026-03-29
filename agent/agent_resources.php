<?php
include 'config.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'agent') {
    header("Location: login.php");
    exit();
}

// Fetch categories for dropdown
$cat_query = mysqli_query($conn, "SELECT * FROM business_categories ORDER BY name ASC");

// Add resource
if(isset($_POST['add_resource'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $category_id = (int)$_POST['category_id'];
    $type = $_POST['type'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $agent_id = $_SESSION['user_id'];

    $sql = "INSERT INTO business_resources (agent_id, category_id, title, content, type, description)
            VALUES ($agent_id, $category_id, '$title', '$content', '$type', '$description')";
    mysqli_query($conn, $sql);
    $success = "Resource added successfully!";
}

// Edit resource
if(isset($_POST['edit_resource'])) {
    $id = (int)$_POST['resource_id'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $category_id = (int)$_POST['category_id'];
    $type = $_POST['type'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $sql = "UPDATE business_resources 
            SET title='$title', content='$content', category_id=$category_id, type='$type', description='$description'
            WHERE resource_id=$id AND agent_id=".$_SESSION['user_id'];
    mysqli_query($conn, $sql);
    $success = "Resource updated successfully!";
}

// Delete resource
if(isset($_POST['delete_resource'])) {
    $id = (int)$_POST['resource_id'];
    mysqli_query($conn, "DELETE FROM business_resources WHERE resource_id=$id AND agent_id=".$_SESSION['user_id']);
    $success = "Resource deleted successfully!";
}

// Fetch resources
$res_query = mysqli_query($conn, "
    SELECT br.*, bc.name AS category_name 
    FROM business_resources br
    JOIN business_categories bc ON bc.id = br.category_id
    WHERE br.agent_id=".$_SESSION['user_id']."
    ORDER BY br.resource_id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Business Resources</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
    body { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); min-height: 100vh; margin-left: 260px; color: #fff; }
    .res-card { max-width: 1000px; margin: 50px auto; background: #fff; border-radius: 20px; padding: 30px; color: #000; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); }
    .btn-action { border-radius: 50px; font-weight: bold; }
    .text-dark { color: #000 !important; }
</style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="container">
    <div class="res-card">
        <div class="d-flex justify-content-between mb-4">
            <h2 class="text-dark">Business Resources</h2>
            <button class="btn btn-success btn-action" data-bs-toggle="modal" data-bs-target="#addResourceModal">
                <i class="fas fa-plus"></i> Add Resource
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
                    <th>Type</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while($res = mysqli_fetch_assoc($res_query)): ?>
                <tr>
                    <td><?= $res['resource_id'] ?></td>
                    <td><?= htmlspecialchars($res['title']) ?></td>
                    <td><?= htmlspecialchars($res['category_name']) ?></td>
                    <td><?= $res['type'] ?></td>
                    <td><?= htmlspecialchars($res['description']) ?></td>
                    <td>
                        <!-- Edit Button -->
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editResourceModal<?= $res['resource_id'] ?>">
                            <i class="fas fa-edit"></i>
                        </button>

                        <!-- Delete Form -->
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="resource_id" value="<?= $res['resource_id'] ?>">
                            <button type="submit" name="delete_resource" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editResourceModal<?= $res['resource_id'] ?>" tabindex="-1" aria-labelledby="editResourceLabel<?= $res['resource_id'] ?>" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title text-dark" id="editResourceLabel<?= $res['resource_id'] ?>">Edit Resource</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                              </div>
                              <form method="POST">
                                <div class="modal-body">
                                  <input type="hidden" name="resource_id" value="<?= $res['resource_id'] ?>">
                                  <div class="mb-3">
                                    <label class="form-label text-dark">Title</label>
                                    <input type="text" name="title" class="form-control" required value="<?= htmlspecialchars($res['title']) ?>">
                                  </div>
                                  <div class="mb-3">
                                    <label class="form-label text-dark">Category</label>
                                    <select name="category_id" class="form-select" required>
                                      <?php
                                      mysqli_data_seek($cat_query, 0);
                                      while($cat = mysqli_fetch_assoc($cat_query)): ?>
                                        <option value="<?= $cat['id'] ?>" <?= $cat['id']==$res['category_id']?'selected':'' ?>>
                                          <?= htmlspecialchars($cat['name']) ?>
                                        </option>
                                      <?php endwhile; ?>
                                    </select>
                                  </div>
                                  <div class="mb-3">
                                    <label class="form-label text-dark">Type</label>
                                    <select name="type" class="form-select" required>
                                        <?php
                                        $types = ['framework','market_research','startup_plan','funding_guide','legal_document','case_study'];
                                        foreach($types as $t): ?>
                                            <option value="<?= $t ?>" <?= $res['type']==$t?'selected':'' ?>><?= ucfirst(str_replace('_',' ',$t)) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                  </div>
                                  <div class="mb-3">
                                    <label class="form-label text-dark">Content</label>
                                    <textarea name="content" class="form-control"><?= htmlspecialchars($res['content']) ?></textarea>
                                  </div>
                                  <div class="mb-3">
                                    <label class="form-label text-dark">Description</label>
                                    <textarea name="description" class="form-control"><?= htmlspecialchars($res['description']) ?></textarea>
                                  </div>
                                </div>
                                <div class="modal-footer">
                                  <button type="submit" name="edit_resource" class="btn btn-primary btn-action w-100">Save Changes</button>
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

<!-- Add Resource Modal -->
<div class="modal fade" id="addResourceModal" tabindex="-1" aria-labelledby="addResourceLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-dark" id="addResourceLabel">Add New Resource</h5>
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
            <label class="form-label text-dark">Type</label>
            <select name="type" class="form-select" required>
                <option value="framework">Framework</option>
                <option value="market_research">Market Research</option>
                <option value="startup_plan">Startup Plan</option>
                <option value="funding_guide">Funding Guide</option>
                <option value="legal_document">Legal Document</option>
                <option value="case_study">Case Study</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label text-dark">Content</label>
            <textarea name="content" class="form-control"></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label text-dark">Description</label>
            <textarea name="description" class="form-control"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="add_resource" class="btn btn-success btn-action w-100">Add Resource</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>