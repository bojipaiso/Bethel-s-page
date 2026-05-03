<?php
// admin/manage-messages.php
require_once '../includes/auth.php';
requireAdmin();
require_once '../includes/db.php';

$message = '';

if(isset($_GET['mark_read'])) {
    $stmt = $pdo->prepare("UPDATE contact_messages SET status = 'read' WHERE id = ?");
    $stmt->execute([intval($_GET['mark_read'])]);
    $message = "Message marked as read!";
    header("Location: manage-messages.php");
    exit();
}
if(isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM contact_messages WHERE id = ?");
    $stmt->execute([intval($_GET['delete'])]);
    $message = "Message deleted!";
    header("Location: manage-messages.php");
    exit();
}

$messages = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC")->fetchAll();
$unread_count = $pdo->query("SELECT COUNT(*) FROM contact_messages WHERE status='unread'")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Messages - Bethel School</title>
    <link rel="stylesheet" href="../css/admin-style.css">
</head>
<body>
<div class="admin-wrapper">
    <nav class="admin-nav"><div class="admin-nav-container"><div class="admin-logo">Bethel CMS</div><div class="admin-user"><a href="dashboard.php">Dashboard</a> | <a href="logout.php">Logout</a></div></div></nav>
    <div class="admin-container">
        <div class="page-header"><h1>Contact Messages <?php if($unread_count > 0): ?><span style="background: red; color: white; padding: 2px 8px; border-radius: 20px; font-size: 14px;"><?php echo $unread_count; ?> new</span><?php endif; ?></h1></div>
        <?php if($message): ?><div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>
        <div class="data-table-container">
            <table class="data-table">
                <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Subject</th><th>Message</th><th>Date</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    <?php foreach($messages as $m): ?>
                    <tr>
                        <td><?php echo $m['id']; ?></td>
                        <td><?php echo htmlspecialchars($m['name']); ?></td>
                        <td><?php echo htmlspecialchars($m['email']); ?></td>
                        <td><?php echo htmlspecialchars($m['subject']); ?></td>
                        <td><?php echo htmlspecialchars(substr($m['message'], 0, 50)); ?>...</td>
                        <td><?php echo date('M d, Y H:i', strtotime($m['created_at'])); ?></td>
                        <td><span class="status-badge status-<?php echo $m['status']; ?>"><?php echo $m['status']; ?></span></td>
                        <td class="actions"><a href="view-message.php?id=<?php echo $m['id']; ?>" class="btn-edit">View</a> <a href="?mark_read=<?php echo $m['id']; ?>" class="btn-toggle">Mark Read</a> <a href="?delete=<?php echo $m['id']; ?>" class="btn-delete" onclick="return confirm('Delete?')">Delete</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>