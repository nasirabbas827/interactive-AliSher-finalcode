<?php
include 'config.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Add Event
if(isset($_POST['add_event'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $event_date = $_POST['event_date'];
    $end_date = $_POST['end_date'];
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $mode = $_POST['mode'];
    $event_link = mysqli_real_escape_string($conn, $_POST['event_link']);
    $status = $_POST['status'];

    $thumbnail = "";
    if(!empty($_FILES['thumbnail']['name'])) {
        $target = "uploads/" . time() . "_" . $_FILES['thumbnail']['name'];
        move_uploaded_file($_FILES['thumbnail']['tmp_name'], $target);
        $thumbnail = basename($target);
    }

    $sql = "INSERT INTO events (title, description, event_date, end_date, location, mode, event_link, thumbnail, status)
            VALUES ('$title','$description','$event_date','$end_date','$location','$mode','$event_link','$thumbnail','$status')";
    mysqli_query($conn, $sql);

    $success = "Event added successfully!";
}

// Edit Event
if(isset($_POST['edit_event'])) {
    $id = (int)$_POST['id'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $event_date = $_POST['event_date'];
    $end_date = $_POST['end_date'];
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $mode = $_POST['mode'];
    $event_link = mysqli_real_escape_string($conn, $_POST['event_link']);
    $status = $_POST['status'];

    if(!empty($_FILES['thumbnail']['name'])) {
        $target = "uploads/" . time() . "_" . $_FILES['thumbnail']['name'];
        move_uploaded_file($_FILES['thumbnail']['tmp_name'], $target);
        $thumbnail = basename($target);

        $sql = "UPDATE events SET 
                title='$title',
                description='$description',
                event_date='$event_date',
                end_date='$end_date',
                location='$location',
                mode='$mode',
                event_link='$event_link',
                thumbnail='$thumbnail',
                status='$status'
                WHERE id=$id";
    } else {
        $sql = "UPDATE events SET 
                title='$title',
                description='$description',
                event_date='$event_date',
                end_date='$end_date',
                location='$location',
                mode='$mode',
                event_link='$event_link',
                status='$status'
                WHERE id=$id";
    }

    mysqli_query($conn, $sql);
    $success = "Event updated successfully!";
}

// Delete Event
if(isset($_POST['delete_event'])) {
    $id = (int)$_POST['id'];
    mysqli_query($conn, "DELETE FROM events WHERE id=$id");
    $success = "Event deleted successfully!";
}

// Fetch Events
$event_query = mysqli_query($conn, "SELECT * FROM events ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Events</title>

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
    <h2 class="text-dark">Manage Events</h2>
    <button class="btn btn-success btn-action" data-bs-toggle="modal" data-bs-target="#addEventModal">
        <i class="fas fa-plus"></i> Add Event
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
<th>Date</th>
<th>Mode</th>
<th>Status</th>
<th>Actions</th>
</tr>
</thead>

<tbody>
<?php while($event = mysqli_fetch_assoc($event_query)): ?>
<tr>
<td><?= $event['id'] ?></td>
<td><?= htmlspecialchars($event['title']) ?></td>
<td><?= $event['event_date'] ?></td>
<td><?= $event['mode'] ?></td>
<td><?= $event['status'] ?></td>

<td>

<!-- EDIT BUTTON -->
<button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editEventModal<?= $event['id'] ?>">
<i class="fas fa-edit"></i>
</button>

<!-- DELETE -->
<form method="POST" class="d-inline">
<input type="hidden" name="id" value="<?= $event['id'] ?>">
<button type="submit" name="delete_event" class="btn btn-danger btn-sm">
<i class="fas fa-trash"></i>
</button>
</form>

<!-- EDIT MODAL -->
<div class="modal fade" id="editEventModal<?= $event['id'] ?>" tabindex="-1">
<div class="modal-dialog">
<div class="modal-content">

<div class="modal-header">
<h5 class="modal-title text-dark">Edit Event</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<form method="POST" enctype="multipart/form-data">
<div class="modal-body">

<input type="hidden" name="id" value="<?= $event['id'] ?>">

<div class="mb-3">
<label class="form-label text-dark">Title</label>
<input type="text" name="title" class="form-control" required value="<?= htmlspecialchars($event['title']) ?>">
</div>

<div class="mb-3">
<label class="form-label text-dark">Description</label>
<textarea name="description" class="form-control"><?= htmlspecialchars($event['description']) ?></textarea>
</div>

<div class="mb-3">
<label class="form-label text-dark">Event Date</label>
<input type="datetime-local" name="event_date" class="form-control"
value="<?= date('Y-m-d\TH:i', strtotime($event['event_date'])) ?>">
</div>

<div class="mb-3">
<label class="form-label text-dark">End Date</label>
<input type="datetime-local" name="end_date" class="form-control"
value="<?= $event['end_date'] ? date('Y-m-d\TH:i', strtotime($event['end_date'])) : '' ?>">
</div>

<div class="mb-3">
<label class="form-label text-dark">Location</label>
<input type="text" name="location" class="form-control" value="<?= htmlspecialchars($event['location']) ?>">
</div>

<div class="mb-3">
<label class="form-label text-dark">Mode</label>
<select name="mode" class="form-select">
<option value="online" <?= $event['mode']=='online'?'selected':'' ?>>Online</option>
<option value="offline" <?= $event['mode']=='offline'?'selected':'' ?>>Offline</option>
<option value="hybrid" <?= $event['mode']=='hybrid'?'selected':'' ?>>Hybrid</option>
</select>
</div>

<div class="mb-3">
<label class="form-label text-dark">Event Link</label>
<input type="text" name="event_link" class="form-control" value="<?= $event['event_link'] ?>">
</div>

<div class="mb-3">
<label class="form-label text-dark">Status</label>
<select name="status" class="form-select">
<option value="upcoming" <?= $event['status']=='upcoming'?'selected':'' ?>>Upcoming</option>
<option value="ongoing" <?= $event['status']=='ongoing'?'selected':'' ?>>Ongoing</option>
<option value="completed" <?= $event['status']=='completed'?'selected':'' ?>>Completed</option>
<option value="cancelled" <?= $event['status']=='cancelled'?'selected':'' ?>>Cancelled</option>
</select>
</div>

<div class="mb-3">
<label class="form-label text-dark">Thumbnail</label>
<input type="file" name="thumbnail" class="form-control">
</div>

</div>

<div class="modal-footer">
<button type="submit" name="edit_event" class="btn btn-primary btn-action w-100">Save Changes</button>
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
<div class="modal fade" id="addEventModal">
<div class="modal-dialog">
<div class="modal-content">

<div class="modal-header">
<h5 class="modal-title text-dark">Add Event</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<form method="POST" enctype="multipart/form-data">
<div class="modal-body">

<div class="mb-3">
<label class="form-label text-dark">Title</label>
<input type="text" name="title" class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label text-dark">Description</label>
<textarea name="description" class="form-control"></textarea>
</div>

<div class="mb-3">
<label class="form-label text-dark">Event Date</label>
<input type="datetime-local" name="event_date" class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label text-dark">End Date</label>
<input type="datetime-local" name="end_date" class="form-control">
</div>

<div class="mb-3">
<label class="form-label text-dark">Location</label>
<input type="text" name="location" class="form-control">
</div>

<div class="mb-3">
<label class="form-label text-dark">Mode</label>
<select name="mode" class="form-select">
<option value="online">Online</option>
<option value="offline">Offline</option>
<option value="hybrid">Hybrid</option>
</select>
</div>

<div class="mb-3">
<label class="form-label text-dark">Event Link</label>
<input type="text" name="event_link" class="form-control">
</div>

<div class="mb-3">
<label class="form-label text-dark">Status</label>
<select name="status" class="form-select">
<option value="upcoming">Upcoming</option>
<option value="ongoing">Ongoing</option>
<option value="completed">Completed</option>
<option value="cancelled">Cancelled</option>
</select>
</div>

<div class="mb-3">
<label class="form-label text-dark">Thumbnail</label>
<input type="file" name="thumbnail" class="form-control">
</div>

</div>

<div class="modal-footer">
<button type="submit" name="add_event" class="btn btn-success btn-action w-100">Add Event</button>
</div>

</form>

</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>