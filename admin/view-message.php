<?php
// admin/view-message.php
require_once '../includes/auth.php';
requireAdmin();
require_once '../includes/db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$stmt = $pdo->prepare("SELECT * FROM contact_messages WHERE id = ?");
$stmt->execute([$id]);
$msg = $stmt->fetch();
if(!$msg) { header("Location: manage-messages.php"); exit(); }

if($msg['status'] == 'unread') {
    $stmt = $pdo->prepare("UPDATE contact_messages SET status = 'read' WHERE id = ?");
    $stmt->execute([$id]);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Message - Bethel School</title>
    <link rel="stylesheet" href="../css/admin-style.css">
    <style>
        .message-detail { background: white; border-radius: 16px; padding: 25px; margin-top: 20px; }
        .message-field { margin-bottom: 20px; }
        .message-field label { font-weight: bold; display: block; margin-bottom: 5px; color: var(--primary-color); }
        .message-field .content { background: #f8f9fa; padding: 12px; border-radius: 8px; }
    </style>
</head>
<body>
<div class="admin-wrapper">
    <nav class="admin-nav"><div class="admin-nav-container"><div class="admin-logo">Bethel CMS</div><div class="admin-user"><a href="dashboard.php">Dashboard</a> | <a href="logout.php">Logout</a></div></div></nav>
    <div class="admin-container">
        <div class="page-header"><h1>View Message</h1><a href="manage-messages.php" class="btn-secondary">← Back to Messages</a></div>
        <div class="message-detail">
            <div class="message-field"><label>From</label><div class="content"><?php echo htmlspecialchars($msg['name']); ?> (<?php echo htmlspecialchars($msg['email']); ?>)</div></div>
            <div class="message-field"><label>Subject</label><div class="content"><?php echo htmlspecialchars($msg['subject']); ?></div></div>
            <div class="message-field"><label>Date</label><div class="content"><?php echo date('F d, Y H:i:s', strtotime($msg['created_at'])); ?></div></div>
            <div class="message-field"><label>Message</label><div class="content" style="white-space: pre-wrap;"><?php echo nl2br(htmlspecialchars($msg['message'])); ?></div></div>
            <div class="message-field"><label>IP Address</label><div class="content"><?php echo htmlspecialchars($msg['ip_address']); ?></div></div>
            <a href="manage-messages.php" class="btn-primary">Back to List</a>
        </div>
    </div>
</div>
</body>
</html>