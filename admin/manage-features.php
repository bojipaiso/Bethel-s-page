<?php
// admin/manage-features.php
require_once '../includes/auth.php';
requireAdmin();
require_once '../includes/db.php';

$message = '';

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_feature'])) {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $display_order = intval($_POST['display_order']);
    
    $image_url = isset($_POST['existing_image']) ? $_POST['existing_image'] : '';
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg','jpeg','png','gif','webp'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if(in_array($ext, $allowed)) {
            $upload_dir = '../uploads/features/';
            if(!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9]/', '_', $_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $filename);
            $image_url = 'uploads/features/' . $filename;
            if(isset($_POST['old_image']) && file_exists('../' . $_POST['old_image'])) {
                unlink('../' . $_POST['old_image']);
            }
        }
    }
    
    if($id > 0) {
        if(!empty($image_url)) {
            $stmt = $pdo->prepare("UPDATE features SET title=?, description=?, image_url=?, display_order=? WHERE id=?");
            $stmt->execute([$title, $description, $image_url, $display_order, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE features SET title=?, description=?, display_order=? WHERE id=?");
            $stmt->execute([$title, $description, $display_order, $id]);
        }
        $message = "Feature updated!";
    } else {
        $stmt = $pdo->prepare("INSERT INTO features (title, description, image_url, display_order) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $description, $image_url, $display_order]);
        $message = "Feature added!";
    }
    header("Location: manage-features.php");
    exit();
}

if(isset($_GET['delete'])) {
    $stmt = $pdo->prepare("SELECT image_url FROM features WHERE id = ?");
    $stmt->execute([intval($_GET['delete'])]);
    $feature = $stmt->fetch();
    if(isset($feature['image_url']) && $feature['image_url'] && file_exists('../' . $feature['image_url'])) {
        unlink('../' . $feature['image_url']);
    }
    $stmt = $pdo->prepare("DELETE FROM features WHERE id = ?");
    $stmt->execute([intval($_GET['delete'])]);
    $message = "Feature deleted!";
    header("Location: manage-features.php");
    exit();
}

if(isset($_GET['toggle_status'])) {
    $stmt = $pdo->prepare("UPDATE features SET status = IF(status='active', 'inactive', 'active') WHERE id = ?");
    $stmt->execute([intval($_GET['toggle_status'])]);
    $message = "Status updated!";
    header("Location: manage-features.php");
    exit();
}

$features = $pdo->query("SELECT * FROM features ORDER BY display_order ASC, created_at DESC")->fetchAll();
$edit = null;
if(isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM features WHERE id = ?");
    $stmt->execute([intval($_GET['edit'])]);
    $edit = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Features - Bethel School</title>
    <link rel="stylesheet" href="../css/admin-style.css">
    <style>
        .form-container { max-width: 800px; margin: 0 auto; }
        .current-image { margin-bottom: 10px; }
        .current-image img { max-width: 150px; border-radius: 5px; }
    </style>
</head>
<body>
<div class="admin-wrapper">
    <nav class="admin-nav"><div class="admin-nav-container"><div class="admin-logo">Bethel CMS</div><div class="admin-user"><a href="dashboard.php">Dashboard</a> | <a href="logout.php">Logout</a></div></div></nav>
    <div class="admin-container">
        <div class="page-header"><h1>Manage Features</h1><a href="manage-features.php" class="btn-secondary">← Back to List</a></div>
        <?php if($message): ?><div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>
        <div class="form-container">
            <h2><?php echo $edit ? 'Edit Feature' : 'Add New Feature'; ?></h2>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo isset($edit['id']) ? $edit['id'] : ''; ?>">
                <input type="hidden" name="old_image" value="<?php echo isset($edit['image_url']) ? $edit['image_url'] : ''; ?>">
                <input type="hidden" name="existing_image" value="<?php echo isset($edit['image_url']) ? $edit['image_url'] : ''; ?>">
                <div class="form-group"><label>Title</label><input type="text" name="title" required value="<?php echo isset($edit['title']) ? htmlspecialchars($edit['title']) : ''; ?>"></div>
                <div class="form-group"><label>Description</label><textarea name="description" rows="5" required><?php echo isset($edit['description']) ? htmlspecialchars($edit['description']) : ''; ?></textarea></div>
                <div class="form-group">
                    <label>Feature Image</label>
                    <?php if(isset($edit['image_url']) && $edit['image_url']): ?>
                        <div class="current-image"><img src="../<?php echo $edit['image_url']; ?>" alt="Current"><p><small>Current image. Leave empty to keep.</small></p></div>
                    <?php endif; ?>
                    <input type="file" name="image" accept="image/*"><small>Leave empty to keep current. Recommended size: 800x600px</small>
                </div>
                <div class="form-group"><label>Display Order (lower first)</label><input type="number" name="display_order" value="<?php echo isset($edit['display_order']) ? $edit['display_order'] : 0; ?>"></div>
                <button type="submit" name="save_feature" class="btn-primary">Save Feature</button>
            </form>
        </div>
        <div class="data-table-container">
            <h2>Current Features</h2>
            <table class="data-table">
                <thead><tr><th>Order</th><th>Image</th><th>Title</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    <?php foreach($features as $f): ?>
                    <tr>
                        <td><?php echo $f['display_order']; ?></td>
                        <td><?php if(isset($f['image_url']) && $f['image_url']): ?><img src="../<?php echo $f['image_url']; ?>" style="width:50px; height:50px; object-fit:cover;"><?php else: ?>No image<?php endif; ?></td>
                        <td><?php echo htmlspecialchars($f['title']); ?></td>
                        <td><span class="status-badge status-<?php echo $f['status']; ?>"><?php echo $f['status']; ?></span></td>
                        <td class="actions"><a href="?edit=<?php echo $f['id']; ?>" class="btn-edit">Edit</a> <a href="?toggle_status=<?php echo $f['id']; ?>" class="btn-toggle">Toggle</a> <a href="?delete=<?php echo $f['id']; ?>" class="btn-delete" onclick="return confirm('Delete?')">Delete</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>