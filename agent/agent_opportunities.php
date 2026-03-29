<?php
include 'config.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'agent') {
    header("Location: login.php");
    exit();
}

// Fetch categories for dropdown
$cat_query = mysqli_query($conn, "SELECT * FROM business_categories ORDER BY name ASC");

// Add opportunity
if(isset($_POST['add_opportunity'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $category_id = (int)$_POST['category_id'];
    $type = $_POST['type'];
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $deadline = $_POST['deadline'];
    $status = $_POST['status'];
    $agent_id = $_SESSION['user_id'];

    $sql = "INSERT INTO opportunities (agent_id, category_id, title, description, type, location, deadline, status)
            VALUES ($agent_id, $category_id, '$title', '$description', '$type', '$location', '$deadline', '$status')";
    mysqli_query($conn, $sql);
    $success = "Opportunity added successfully!";
}

// Edit opportunity
if(isset($_POST['edit_opportunity'])) {
    $id = (int)$_POST['id'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $category_id = (int)$_POST['category_id'];
    $type = $_POST['type'];
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $deadline = $_POST['deadline'];
    $status = $_POST['status'];

    $sql = "UPDATE opportunities SET title='$title', description='$description', category_id=$category_id, type='$type',
            location='$location', deadline='$deadline', status='$status'
            WHERE id=$id AND agent_id=".$_SESSION['user_id'];
    mysqli_query($conn, $sql);
    $success = "Opportunity updated successfully!";
}

// Delete opportunity
if(isset($_POST['delete_opportunity'])) {
    $id = (int)$_POST['id'];
    mysqli_query($conn, "DELETE FROM opportunities WHERE id=$id AND agent_id=".$_SESSION['user_id']);
    $success = "Opportunity deleted successfully!";
}

// Fetch all opportunities of this agent
$opp_query = mysqli_query($conn, "
    SELECT o.*, bc.name AS category_name
    FROM opportunities o
    LEFT JOIN business_categories bc ON bc.id = o.category_id
    WHERE o.agent_id=".$_SESSION['user_id']."
    ORDER BY o.id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Opportunities</title>
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
            <h2 class="text-dark">My Opportunities</h2>
            <button class="btn btn-success btn-action" data-bs-toggle="modal" data-bs-target="#addOpportunityModal">
                <i class="fas fa-plus"></i> Add Opportunity
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
                    <th>Location</th>
                    <th>Deadline</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while($opp = mysqli_fetch_assoc($opp_query)): ?>
                <tr>
                    <td><?= $opp['id'] ?></td>
                    <td><?= htmlspecialchars($opp['title']) ?></td>
                    <td><?= htmlspecialchars($opp['category_name']) ?></td>
                    <td><?= $opp['type'] ?></td>
                    <td><?= htmlspecialchars($opp['location']) ?></td>
                    <td><?= $opp['deadline'] ?></td>
                    <td><?= $opp['status'] ?></td>
                    <td>
                        <!-- Edit Button -->
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editOpportunityModal<?= $opp['id'] ?>">
                            <i class="fas fa-edit"></i>
                        </button>

                        <!-- Delete Form -->
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="id" value="<?= $opp['id'] ?>">
                            <button type="submit" name="delete_opportunity" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editOpportunityModal<?= $opp['id'] ?>" tabindex="-1" aria-labelledby="editOpportunityLabel<?= $opp['id'] ?>" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title text-dark" id="editOpportunityLabel<?= $opp['id'] ?>">Edit Opportunity</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                              </div>
                              <form method="POST">
                                <div class="modal-body">
                                  <input type="hidden" name="id" value="<?= $opp['id'] ?>">
                                  <div class="mb-3">
                                    <label class="form-label text-dark">Title</label>
                                    <input type="text" name="title" class="form-control" required value="<?= htmlspecialchars($opp['title']) ?>">
                                  </div>
                                  <div class="mb-3">
                                    <label class="form-label text-dark">Category</label>
                                    <select name="category_id" class="form-select" required>
                                      <?php
                                      mysqli_data_seek($cat_query, 0);
                                      while($cat = mysqli_fetch_assoc($cat_query)): ?>
                                        <option value="<?= $cat['id'] ?>" <?= $cat['id']==$opp['category_id']?'selected':'' ?>>
                                          <?= htmlspecialchars($cat['name']) ?>
                                        </option>
                                      <?php endwhile; ?>
                                    </select>
                                  </div>
                                  <div class="mb-3">
                                    <label class="form-label text-dark">Description</label>
                                    <textarea name="description" class="form-control" required><?= htmlspecialchars($opp['description']) ?></textarea>
                                  </div>
                                  <div class="mb-3">
                                    <label class="form-label text-dark">Type</label>
                                    <select name="type" class="form-select">
                                        <option value="investment" <?= $opp['type']=='investment'?'selected':'' ?>>Investment</option>
                                        <option value="partnership" <?= $opp['type']=='partnership'?'selected':'' ?>>Partnership</option>
                                        <option value="franchise" <?= $opp['type']=='franchise'?'selected':'' ?>>Franchise</option>
                                        <option value="co-founder" <?= $opp['type']=='co-founder'?'selected':'' ?>>Co-founder</option>
                                        <option value="other" <?= $opp['type']=='other'?'selected':'' ?>>Other</option>
                                    </select>
                                  </div>
                                  <div class="mb-3">
                                    <label class="form-label text-dark">Location</label>
                                    <input type="text" name="location" class="form-control" value="<?= htmlspecialchars($opp['location']) ?>">
                                  </div>
                                  <div class="mb-3">
                                    <label class="form-label text-dark">Deadline</label>
                                    <input type="date" name="deadline" class="form-control" value="<?= $opp['deadline'] ?>">
                                  </div>
                                  <div class="mb-3">
                                    <label class="form-label text-dark">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="open" <?= $opp['status']=='open'?'selected':'' ?>>Open</option>
                                        <option value="closed" <?= $opp['status']=='closed'?'selected':'' ?>>Closed</option>
                                        <option value="draft" <?= $opp['status']=='draft'?'selected':'' ?>>Draft</option>
                                    </select>
                                  </div>
                                </div>
                                <div class="modal-footer">
                                  <button type="submit" name="edit_opportunity" class="btn btn-primary btn-action w-100">Save Changes</button>
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

<!-- Add Opportunity Modal -->
<div class="modal fade" id="addOpportunityModal" tabindex="-1" aria-labelledby="addOpportunityLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-dark" id="addOpportunityLabel">Add New Opportunity</h5>
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
          <div class="mb-3">
            <label class="form-label text-dark">Type</label>
            <select name="type" class="form-select">
                <option value="investment">Investment</option>
                <option value="partnership">Partnership</option>
                <option value="franchise">Franchise</option>
                <option value="co-founder">Co-founder</option>
                <option value="other">Other</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label text-dark">Location</label>
            <input type="text" name="location" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label text-dark">Deadline</label>
            <input type="date" name="deadline" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label text-dark">Status</label>
            <select name="status" class="form-select">
                <option value="open">Open</option>
                <option value="closed">Closed</option>
                <option value="draft">Draft</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="add_opportunity" class="btn btn-success btn-action w-100">Add Opportunity</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>