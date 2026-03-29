<?php
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

$mentor_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($mentor_id <= 0) {
    die("Invalid mentor ID!");
}

// Fetch mentor basic info + agent profile (extended data)
$mentor_sql = "SELECT u.id, u.full_name, u.email, u.phone, u.created_at,
                      ap.business_domain, ap.bio, ap.experience_yrs, 
                      ap.linkedin_url, ap.website_url
               FROM users u 
               LEFT JOIN agent_profiles ap ON u.id = ap.user_id
               WHERE u.id = $mentor_id AND u.role = 'agent'";

$mentor_result = mysqli_query($conn, $mentor_sql);
$mentor = mysqli_fetch_assoc($mentor_result);

if (!$mentor) {
    die("Mentor not found or is not an Agent!");
}

// Fetch feedbacks
$feedback_sql = "SELECT f.*, u.full_name AS user_name 
                 FROM feedback f 
                 JOIN users u ON u.id = f.user_id
                 WHERE f.agent_id = $mentor_id
                 ORDER BY f.created_at DESC";
$feedback_result = mysqli_query($conn, $feedback_sql);

// Fetch messages
$msg_sql = "SELECT pm.*, u.full_name AS sender_name 
            FROM private_messages pm 
            JOIN users u ON u.id = pm.sender_id
            WHERE (pm.sender_id = {$_SESSION['user_id']} AND pm.receiver_id = $mentor_id)
               OR (pm.sender_id = $mentor_id AND pm.receiver_id = {$_SESSION['user_id']})
            ORDER BY pm.created_at DESC";
$msg_result = mysqli_query($conn, $msg_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($mentor['full_name']) ?> - Mentor Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            min-height: 100vh;
            margin-left: 260px;
            color: #fff;
        }
        .main-content { padding: 30px; }
        .profile-card {
            background: rgba(255,255,255,0.95);
            color: #1e2937;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }
        .card-hover {
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        .btn-action {
            border-radius: 50px;
            font-weight: 600;
            padding: 10px 25px;
        }
        .info-label { font-weight: 600; color: #6366f1; }
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">
    <div class="profile-card p-5 mb-4">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h2 class="mb-1"><?= htmlspecialchars($mentor['full_name']) ?></h2>
                <p class="text-muted mb-3">
                    <i class="fas fa-user-tie"></i> Professional Agent / Mentor
                </p>
            </div>
            <div class="text-end">
                <span class="badge bg-success fs-6">Verified Agent</span>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <p><span class="info-label">Email:</span> <?= htmlspecialchars($mentor['email']) ?></p>
                <?php if(!empty($mentor['phone'])): ?>
                    <p><span class="info-label">Phone:</span> <?= htmlspecialchars($mentor['phone']) ?></p>
                <?php endif; ?>
                <p><span class="info-label">Joined:</span> <?= date('F Y', strtotime($mentor['created_at'])) ?></p>
            </div>
            <div class="col-md-6">
                <?php if(!empty($mentor['business_domain'])): ?>
                    <p><span class="info-label">Domain:</span> <?= htmlspecialchars($mentor['business_domain']) ?></p>
                <?php endif; ?>
                <?php if(!empty($mentor['experience_yrs'])): ?>
                    <p><span class="info-label">Experience:</span> <?= $mentor['experience_yrs'] ?> years</p>
                <?php endif; ?>
            </div>
        </div>

        <?php if(!empty($mentor['bio'])): ?>
            <hr>
            <h5 class="info-label">About Me</h5>
            <p class="lead"><?= nl2br(htmlspecialchars($mentor['bio'])) ?></p>
        <?php endif; ?>

        <div class="d-flex gap-3 mt-4">
            <a href="#" class="btn btn-success btn-action" data-bs-toggle="modal" data-bs-target="#feedbackModal">
                <i class="fas fa-star"></i> Give Feedback
            </a>
            <a href="#" class="btn btn-primary btn-action" data-bs-toggle="modal" data-bs-target="#messageModal">
                <i class="fas fa-envelope"></i> Send Message
            </a>
            <?php if(!empty($mentor['linkedin_url'])): ?>
                <a href="<?= htmlspecialchars($mentor['linkedin_url']) ?>" target="_blank" class="btn btn-dark btn-action">
                    <i class="fab fa-linkedin"></i> LinkedIn
                </a>
            <?php endif; ?>
            <?php if(!empty($mentor['website_url'])): ?>
                <a href="<?= htmlspecialchars($mentor['website_url']) ?>" target="_blank" class="btn btn-outline-primary btn-action">
                    <i class="fas fa-globe"></i> Website
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Feedback Section -->
    <h4 class="mb-3 text-white">Feedbacks from Entrepreneurs</h4>
    <div class="row">
        <?php if(mysqli_num_rows($feedback_result) > 0): ?>
            <?php while($fb = mysqli_fetch_assoc($feedback_result)): ?>
                <div class="col-md-6 mb-3">
                    <div class="card card-hover p-4 bg-white text-dark">
                        <div class="d-flex justify-content-between">
                            <strong><?= htmlspecialchars($fb['user_name']) ?></strong>
                            <span class="badge bg-warning text-dark"><?= $fb['rating'] ?? 'N/A' ?>/5 ★</span>
                        </div>
                        <p class="mt-3"><?= nl2br(htmlspecialchars($fb['message'])) ?></p>
                        <small class="text-muted">Posted on <?= date('M d, Y', strtotime($fb['created_at'])) ?></small>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-light text-dark">No feedbacks received yet.</div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Messages Section -->
    <h4 class="mt-5 mb-3 text-white">Conversation History</h4>
    <div class="card bg-white text-dark p-4 card-hover">
        <?php if(mysqli_num_rows($msg_result) > 0): ?>
            <div class="list-group">
                <?php while($msg = mysqli_fetch_assoc($msg_result)): ?>
                    <div class="list-group-item list-group-item-action p-3">
                        <div class="d-flex justify-content-between">
                            <strong><?= htmlspecialchars($msg['sender_name']) ?></strong>
                            <small class="text-muted"><?= date('M d, h:i A', strtotime($msg['created_at'])) ?></small>
                        </div>
                        <?php if(!empty($msg['subject'])): ?>
                            <p class="mb-1 fw-bold"><?= htmlspecialchars($msg['subject']) ?></p>
                        <?php endif; ?>
                        <p class="mb-0"><?= nl2br(htmlspecialchars($msg['body'])) ?></p>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-light">No messages exchanged yet. Send the first message!</div>
        <?php endif; ?>
    </div>
</div>

<!-- Feedback Modal -->
<div class="modal fade" id="feedbackModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" action="submit_feedback.php">
        <input type="hidden" name="agent_id" value="<?= $mentor_id ?>">
        <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark">Give Feedback to <?= htmlspecialchars($mentor['full_name']) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label class="form-label text-dark">Rating (1-5 stars)</label>
                <input type="number" name="rating" class="form-control mb-3" min="1" max="5" required>
                <label class="form-label text-dark">Your Feedback</label>
                <textarea name="message" class="form-control" rows="5" placeholder="Share your experience..." required></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success">Submit Feedback</button>
            </div>
        </div>
    </form>
  </div>
</div>

<!-- Message Modal -->
<div class="modal fade" id="messageModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" action="send_message.php">
        <input type="hidden" name="receiver_id" value="<?= $mentor_id ?>">
        <input type="hidden" name="sender_id" value="<?= $_SESSION['user_id'] ?>">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark">Send Message to <?= htmlspecialchars($mentor['full_name']) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label class="form-label text-dark">Subject (Optional)</label>
                <input type="text" name="subject" class="form-control mb-3">
                <label class="form-label text-dark">Message</label>
                <textarea name="body" class="form-control" rows="5" required></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Send Message</button>
            </div>
        </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>