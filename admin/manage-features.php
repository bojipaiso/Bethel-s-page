<?php
// admin/manage-features.php - FIXED (preserves images)
require_once '../includes/auth.php';
requireAdmin();
require_once '../includes/db.php';

$message = '';

// Handle add/edit
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_feature'])) {
    $id = $_POST['id'] ?? 0;
    $title = $_POST['title'];
    $description = $_POST['description'];
    $display_order = $_POST['display_order'];
    
    // Handle image upload
    $image_url = '';
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $filename = $_FILES['image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if(in_array($ext, $allowed)) {
            if(!is_dir('../uploads')) {
                mkdir('../uploads', 0777, true);
            }
            
            $new_filename = time() . '_' . preg_replace('/[^a-zA-Z0-9]/', '_', $filename);
            $upload_path = '../uploads/' . $new_filename;
            
            if(move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                $image_url = 'uploads/' . $new_filename;
                
                // Delete old image if exists
                if(!empty($_POST['old_image']) && file_exists('../' . $_POST['old_image'])) {
                    unlink('../' . $_POST['old_image']);
                }
            }
        }
    }
    
    if($id > 0) {
        // UPDATE EXISTING FEATURE
        if(!empty($image_url)) {
            // New image uploaded - update everything
            $stmt = $pdo->prepare("UPDATE features SET title=?, description=?, image_url=?, display_order=? WHERE id=?");
            $stmt->execute([$title, $description, $image_url, $display_order, $id]);
            $message = "Feature updated with new image!";
        } else {
            // No new image - keep existing image
            $stmt = $pdo->prepare("UPDATE features SET title=?, description=?, display_order=? WHERE id=?");
            $stmt->execute([$title, $description, $display_order, $id]);
            $message = "Feature updated! (image unchanged)";
        }
    } else {
        // ADD NEW FEATURE
        $stmt = $pdo->prepare("INSERT INTO features (title, description, image_url, display_order) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $description, $image_url, $display_order]);
        $message = "Feature added!";
    }
}

// Handle delete
if(isset($_GET['delete'])) {
    // Get image to delete
    $stmt = $pdo->prepare("SELECT image_url FROM features WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    $feature = $stmt->fetch();
    if($feature && $feature['image_url'] && file_exists('../' . $feature['image_url'])) {
        unlink('../' . $feature['image_url']);
    }
    
    $stmt = $pdo->prepare("DELETE FROM features WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    $message = "Feature deleted!";
}

// Handle status toggle
if(isset($_GET['toggle_status'])) {
    $stmt = $pdo->prepare("UPDATE features SET status = IF(status='active', 'inactive', 'active') WHERE id = ?");
    $stmt->execute([$_GET['toggle_status']]);
    $message = "Status updated!";
}

// Get all features
$features = $pdo->query("SELECT * FROM features ORDER BY display_order ASC, created_at DESC")->fetchAll();

// Get single feature for editing
$editFeature = null;
if(isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM features WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editFeature = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Features - Bethel School</title>
    <link rel="stylesheet" href="../css/admin-style.css">
</head>
<body>
    <div class="admin-wrapper">
        <nav class="admin-nav">
            <div class="admin-nav-container">
                <div class="admin-logo">Bethel CMS</div>
                <div class="admin-user">
                    <a href="dashboard.php">Dashboard</a> |
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        </nav>
        
        <div class="admin-container">
            <div class="page-header">
                <h1>Manage Features</h1>
                <a href="manage-features.php" class="btn-secondary">← Back to List</a>
            </div>
            
            <?php if(isset($message)): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <!-- Add/Edit Form -->
            <div class="form-container">
                <h2><?php echo $editFeature ? 'Edit Feature' : 'Add New Feature'; ?></h2>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $editFeature['id'] ?? ''; ?>">
                    <input type="hidden" name="old_image" value="<?php echo $editFeature['image_url'] ?? ''; ?>">
                    
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" required value="<?php echo htmlspecialchars($editFeature['title'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" rows="5" required><?php echo htmlspecialchars($editFeature['description'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Feature Image</label>
                        <?php if(isset($editFeature['image_url']) && $editFeature['image_url']): ?>
                            <div class="current-image">
                                <img src="../<?php echo $editFeature['image_url']; ?>" alt="Current image" style="max-width: 200px; margin-bottom: 10px;">
                                <p><strong>Current image:</strong> <?php echo basename($editFeature['image_url']); ?></p>
                                <p><small>Leave file upload empty to keep this image</small></p>
                            </div>
                        <?php else: ?>
                            <p><small>No image currently set. Upload one below.</small></p>
                        <?php endif; ?>
                        <input type="file" name="image" accept="image/*">
                        <small>Leave empty to keep current image. Recommended size: 800x600px</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Display Order (lower numbers appear first)</label>
                        <input type="number" name="display_order" value="<?php echo $editFeature['display_order'] ?? 0; ?>">
                    </div>
                    
                    <button type="submit" name="save_feature" class="btn-primary">Save Feature</button>
                </form>
            </div>
            
            <!-- Features List -->
            <div class="data-table-container">
                <h2>Current Features</h2>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($features as $feature): ?>
                        <tr>
                            <td><?php echo $feature['display_order']; ?></td>
                            <td>
                                <?php if($feature['image_url']): ?>
                                    <img src="../<?php echo $feature['image_url']; ?>" alt="<?php echo htmlspecialchars($feature['title']); ?>" style="width: 50px; height: 50px; object-fit: cover;">
                                <?php else: ?>
                                    <span style="color: #999;">No image</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($feature['title']); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo $feature['status']; ?>">
                                    <?php echo $feature['status']; ?>
                                </span>
                            </td>
                            <td class="actions">
                                <a href="?edit=<?php echo $feature['id']; ?>" class="btn-edit">Edit</a>
                                <a href="?toggle_status=<?php echo $feature['id']; ?>" class="btn-toggle">Toggle</a>
                                <a href="?delete=<?php echo $feature['id']; ?>" class="btn-delete" onclick="return confirm('Delete this feature?')">Delete</a>
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