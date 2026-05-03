<?php
// admin/manage-announcements.php
require_once '../includes/auth.php';
requireAdmin();
require_once '../includes/db.php';

$message = '';

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_announcement'])) {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $day = intval($_POST['day']);
    $month = trim($_POST['month']);
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $display_order = intval($_POST['display_order']);
    
    if($id > 0) {
        $stmt = $pdo->prepare("UPDATE announcements SET day=?, month=?, title=?, description=?, display_order=? WHERE id=?");
        $stmt->execute([$day, $month, $title, $description, $display_order, $id]);
        $message = "Announcement updated!";
    } else {
        $stmt = $pdo->prepare("INSERT INTO announcements (day, month, title, description, display_order) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$day, $month, $title, $description, $display_order]);
        $message = "Announcement added!";
    }
    header("Location: manage-announcements.php");
    exit();
}

if(isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM announcements WHERE id = ?");
    $stmt->execute([intval($_GET['delete'])]);
    $message = "Announcement deleted!";
    header("Location: manage-announcements.php");
    exit();
}

if(isset($_GET['toggle_status'])) {
    $stmt = $pdo->prepare("UPDATE announcements SET status = IF(status='active', 'inactive', 'active') WHERE id = ?");
    $stmt->execute([intval($_GET['toggle_status'])]);
    $message = "Status updated!";
    header("Location: manage-announcements.php");
    exit();
}

$announcements = $pdo->query("SELECT * FROM announcements ORDER BY display_order ASC, created_at DESC")->fetchAll();
$edit = null;
if(isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM announcements WHERE id = ?");
    $stmt->execute([intval($_GET['edit'])]);
    $edit = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Announcements - Bethel School</title>
    <link rel="stylesheet" href="../css/admin-style.css">
    <style>
        .form-container { max-width: 800px; margin: 0 auto; }
    </style>
</head>
<body>
<div class="admin-wrapper">
    <nav class="admin-nav"><div class="admin-nav-container"><div class="admin-logo">Bethel CMS</div><div class="admin-user"><a href="dashboard.php">Dashboard</a> | <a href="logout.php">Logout</a></div></div></nav>
    <div class="admin-container">
        <div class="page-header"><h1>Manage Announcements</h1><a href="manage-announcements.php" class="btn-secondary">← Back to List</a></div>
        <?php if($message): ?><div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>
        <div class="form-container">
            <h2><?php echo $edit ? 'Edit Announcement' : 'Add New Announcement'; ?></h2>
            <form method="POST">
                <input type="hidden" name="id" value="<?php echo isset($edit['id']) ? $edit['id'] : ''; ?>">
                <div class="form-row">
                    <div class="form-group"><label>Day (1-31)</label><input type="number" name="day" required value="<?php echo isset($edit['day']) ? $edit['day'] : ''; ?>"></div>
                    <div class="form-group"><label>Month</label><input type="text" name="month" required value="<?php echo isset($edit['month']) ? htmlspecialchars($edit['month']) : ''; ?>" placeholder="e.g., June"></div>
                </div>
                <div class="form-group"><label>Title</label><input type="text" name="title" required value="<?php echo isset($edit['title']) ? htmlspecialchars($edit['title']) : ''; ?>"></div>
                <div class="form-group"><label>Description</label><textarea name="description" rows="4" required><?php echo isset($edit['description']) ? htmlspecialchars($edit['description']) : ''; ?></textarea></div>
                <div class="form-group"><label>Display Order (lower first)</label><input type="number" name="display_order" value="<?php echo isset($edit['display_order']) ? $edit['display_order'] : 0; ?>"></div>
                <button type="submit" name="save_announcement" class="btn-primary">Save Announcement</button>
            </form>
        </div>
        <div class="data-table-container">
            <h2>Current Announcements</h2>
            <table class="data-table">
                <thead><tr><th>Order</th><th>Date</th><th>Title</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    <?php foreach($announcements as $a): ?>
                    <tr>
                        <td><?php echo $a['display_order']; ?></td>
                        <td><?php echo $a['month'] . ' ' . $a['day']; ?></td>
                        <td><?php echo htmlspecialchars($a['title']); ?></td>
                        <td><span class="status-badge status-<?php echo $a['status']; ?>"><?php echo $a['status']; ?></span></td>
                        <td class="actions">
                            <a href="?edit=<?php echo $a['id']; ?>" class="btn-edit">Edit</a>
                            <a href="?toggle_status=<?php echo $a['id']; ?>" class="btn-toggle">Toggle Status</a>
                            <a href="?delete=<?php echo $a['id']; ?>" class="btn-delete" onclick="return confirm('Delete?')">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>