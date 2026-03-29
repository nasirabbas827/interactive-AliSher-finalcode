<?php
include 'config.php';
if(!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$thread_id = (int)$_GET['id'];

// Add reply
if(isset($_POST['add_reply'])) {
    $reply = mysqli_real_escape_string($conn, $_POST['reply']);
    $user_id = $_SESSION['user_id'];
    mysqli_query($conn, "INSERT INTO forum_replies (thread_id, user_id, reply) VALUES ($thread_id, $user_id, '$reply')");
    $success = "Reply added successfully!";
}

// Fetch thread
$thread_query = mysqli_query($conn, "
    SELECT ft.*, u.full_name, bc.name AS category_name
    FROM forum_threads ft
    JOIN users u ON u.id=ft.user_id
    JOIN business_categories bc ON bc.id=ft.category_id
    WHERE ft.id=$thread_id
");
$thread = mysqli_fetch_assoc($thread_query);

// Fetch replies
$replies_query = mysqli_query($conn, "
    SELECT fr.*, u.full_name 
    FROM forum_replies fr
    JOIN users u ON u.id = fr.user_id
    WHERE fr.thread_id=$thread_id
    ORDER BY fr.created_at ASC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Forum Discussion</title>
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
        <h2 class="text-dark"><?= htmlspecialchars($thread['title']) ?></h2>
        <p class="text-muted">Category: <?= htmlspecialchars($thread['category_name']) ?> | By: <?= htmlspecialchars($thread['full_name']) ?> | <?= $thread['created_at'] ?></p>
        <div class="mb-4"><?= nl2br(htmlspecialchars($thread['body'])) ?></div>

        <?php if(isset($success)): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>

        <h4 class="text-dark">Replies</h4>
        <?php while($reply = mysqli_fetch_assoc($replies_query)): ?>
        <div class="border rounded p-3 mb-2">
            <strong><?= htmlspecialchars($reply['full_name']) ?>:</strong>
            <p><?= nl2br(htmlspecialchars($reply['reply'])) ?></p>
        </div>
        <?php endwhile; ?>

        <!-- Add Reply -->
        <form method="POST">
            <div class="mb-3">
                <textarea name="reply" class="form-control" placeholder="Write your reply..." required></textarea>
            </div>
            <button type="submit" name="add_reply" class="btn btn-success btn-action">Reply</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>