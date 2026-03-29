<?php
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'agent') {
    header("Location: login.php");
    exit();
}

$agent_id = $_SESSION['user_id'];

// Get selected user (if any)
$selected_user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;

// Fetch all users the agent has interacted with + all users for "New Message"
$contacts_sql = "SELECT DISTINCT u.id, u.full_name, u.role,
                        MAX(pm.created_at) as last_message_time
                 FROM users u
                 LEFT JOIN private_messages pm 
                    ON (pm.sender_id = u.id AND pm.receiver_id = $agent_id) 
                    OR (pm.sender_id = $agent_id AND pm.receiver_id = u.id)
                 WHERE u.id != $agent_id
                 GROUP BY u.id, u.full_name, u.role
                 ORDER BY last_message_time DESC, u.full_name ASC";

$contacts_result = mysqli_query($conn, $contacts_sql);

// Fetch conversation if a user is selected
$messages = [];
$selected_user = null;

if ($selected_user_id > 0) {
    // Get selected user info
    $user_sql = "SELECT id, full_name, role FROM users WHERE id = $selected_user_id";
    $user_result = mysqli_query($conn, $user_sql);
    $selected_user = mysqli_fetch_assoc($user_result);

    // Fetch messages between agent and selected user
    $msg_sql = "SELECT pm.*, u.full_name AS sender_name 
                FROM private_messages pm
                JOIN users u ON u.id = pm.sender_id
                WHERE (pm.sender_id = $agent_id AND pm.receiver_id = $selected_user_id)
                   OR (pm.sender_id = $selected_user_id AND pm.receiver_id = $agent_id)
                ORDER BY pm.created_at ASC";

    $msg_result = mysqli_query($conn, $msg_sql);
    while ($row = mysqli_fetch_assoc($msg_result)) {
        $messages[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - Agent Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e0e7ff 100%);
            min-height: 100vh;
            margin-left: 260px;
        }
        .main-content { padding: 30px; }
        .chat-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            height: 75vh;
            display: flex;
            flex-direction: column;
        }
        .contacts-list {
            max-height: 75vh;
            overflow-y: auto;
        }
        .contact-item {
            transition: all 0.2s;
        }
        .contact-item:hover, .contact-item.active {
            background: #6366f1;
            color: white;
        }
        .message-bubble {
            max-width: 75%;
            padding: 12px 18px;
            border-radius: 18px;
            margin-bottom: 10px;
        }
        .message-sent {
            background: #6366f1;
            color: white;
            align-self: flex-end;
            border-bottom-right-radius: 4px;
        }
        .message-received {
            background: #e2e8f0;
            color: #1e2937;
            align-self: flex-start;
            border-bottom-left-radius: 4px;
        }
        .chat-header {
            border-bottom: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-comments me-2"></i> Messages</h2>
        <button class="btn btn-primary btn-action" data-bs-toggle="modal" data-bs-target="#newMessageModal">
            <i class="fas fa-plus"></i> New Message
        </button>
    </div>

    <div class="row">
        <!-- Contacts Sidebar -->
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <strong>Your Contacts</strong>
                </div>
                <div class="contacts-list list-group list-group-flush">
                    <?php if (mysqli_num_rows($contacts_result) > 0): ?>
                        <?php while ($contact = mysqli_fetch_assoc($contacts_result)): ?>
                            <a href="agent_messages.php?user_id=<?= $contact['id'] ?>" 
                               class="list-group-item list-group-item-action contact-item <?= ($selected_user_id == $contact['id']) ? 'active' : '' ?>">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong><?= htmlspecialchars($contact['full_name']) ?></strong>
                                        <small class="d-block text-muted"><?= ucfirst($contact['role']) ?></small>
                                    </div>
                                    <?php if ($contact['last_message_time']): ?>
                                        <small class="text-muted"><?= date('M d', strtotime($contact['last_message_time'])) ?></small>
                                    <?php endif; ?>
                                </div>
                            </a>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="p-4 text-center text-muted">No conversations yet</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Chat Area -->
        <div class="col-md-8">
            <div class="chat-container">
                <?php if ($selected_user): ?>
                    <!-- Chat Header -->
                    <div class="chat-header p-4">
                        <h5 class="mb-0">
                            <?= htmlspecialchars($selected_user['full_name']) ?>
                            <small class="text-muted">(<?= ucfirst($selected_user['role']) ?>)</small>
                        </h5>
                    </div>

                    <!-- Messages -->
                    <div class="flex-grow-1 p-4 overflow-auto" style="background: #f8fafc;">
                        <?php if (!empty($messages)): ?>
                            <?php foreach ($messages as $msg): ?>
                                <div class="d-flex <?= ($msg['sender_id'] == $agent_id) ? 'justify-content-end' : 'justify-content-start' ?>">
                                    <div class="message-bubble <?= ($msg['sender_id'] == $agent_id) ? 'message-sent' : 'message-received' ?>">
                                        <small class="opacity-75">
                                            <?= ($msg['sender_id'] == $agent_id) ? 'You' : htmlspecialchars($msg['sender_name']) ?>
                                        </small>
                                        <?php if (!empty($msg['subject'])): ?>
                                            <p class="mb-1 fw-bold"><?= htmlspecialchars($msg['subject']) ?></p>
                                        <?php endif; ?>
                                        <p class="mb-0"><?= nl2br(htmlspecialchars($msg['body'])) ?></p>
                                        <small class="opacity-50 d-block text-end mt-1">
                                            <?= date('h:i A', strtotime($msg['created_at'])) ?>
                                        </small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center text-muted mt-5">
                                <i class="fas fa-comment-dots fa-3x mb-3"></i>
                                <p>No messages yet. Start the conversation!</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Send Message Form -->
                    <div class="p-4 border-top bg-white">
                        <form method="POST" action="send_message.php">
                            <input type="hidden" name="receiver_id" value="<?= $selected_user_id ?>">
                            <input type="hidden" name="sender_id" value="<?= $agent_id ?>">
                            <div class="input-group">
                                <input type="text" name="subject" class="form-control" placeholder="Subject (optional)">
                                <textarea name="body" class="form-control" rows="2" placeholder="Type your message..." required></textarea>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                <?php else: ?>
                    <div class="h-100 d-flex align-items-center justify-content-center text-center text-muted">
                        <div>
                            <i class="fas fa-inbox fa-4x mb-4"></i>
                            <h5>Select a contact to start chatting</h5>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- New Message Modal -->
<div class="modal fade" id="newMessageModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="send_message.php">
                <div class="modal-body">
                    <label class="form-label">To:</label>
                    <select name="receiver_id" class="form-select" required>
                        <option value="">Select User...</option>
                        <?php
                        mysqli_data_seek($contacts_result, 0); // Reset pointer
                        while ($user = mysqli_fetch_assoc($contacts_result)):
                        ?>
                            <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['full_name']) ?> (<?= ucfirst($user['role']) ?>)</option>
                        <?php endwhile; ?>
                    </select>

                    <label class="form-label mt-3">Subject (Optional)</label>
                    <input type="text" name="subject" class="form-control">

                    <label class="form-label mt-3">Message</label>
                    <textarea name="body" class="form-control" rows="5" required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>