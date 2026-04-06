<?php
// admin/manage-admissions.php
require_once '../includes/auth.php';
requireAdmin();
require_once '../includes/db.php';

$message = '';

// Update admissions content
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_content'])) {
    $stmt = $pdo->prepare("UPDATE admissions_content SET content = ? WHERE section = ?");
    $stmt->execute([$_POST['content'], $_POST['section']]);
    $message = "Content updated successfully!";
}

// Add/Edit admission step
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_step'])) {
    $id = $_POST['id'] ?? 0;
    if($id > 0) {
        $stmt = $pdo->prepare("UPDATE admission_steps SET step_number=?, title=?, description=?, display_order=? WHERE id=?");
        $stmt->execute([$_POST['step_number'], $_POST['title'], $_POST['description'], $_POST['display_order'], $id]);
        $message = "Step updated!";
    } else {
        $stmt = $pdo->prepare("INSERT INTO admission_steps (step_number, title, description, display_order) VALUES (?, ?, ?, ?)");
        $stmt->execute([$_POST['step_number'], $_POST['title'], $_POST['description'], $_POST['display_order']]);
        $message = "Step added!";
    }
}

// Delete step
if(isset($_GET['delete_step'])) {
    $stmt = $pdo->prepare("DELETE FROM admission_steps WHERE id = ?");
    $stmt->execute([$_GET['delete_step']]);
    $message = "Step deleted!";
}

$admission_content = $pdo->query("SELECT * FROM admissions_content ORDER BY display_order")->fetchAll();
$admission_steps = $pdo->query("SELECT * FROM admission_steps ORDER BY display_order")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Admissions - Bethel School</title>
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
                <h1>Manage Admissions</h1>
            </div>
            
            <?php if($message): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <!-- Admission Content -->
            <div class="form-container">
                <h2>Admission Page Content</h2>
                <?php foreach($admission_content as $content): ?>
                <form method="POST" style="margin-bottom: 20px; padding: 20px; background: #f8f9fa; border-radius: 10px;">
                    <input type="hidden" name="section" value="<?php echo $content['section']; ?>">
                    <h3><?php echo ucfirst(str_replace('_', ' ', $content['section'])); ?></h3>
                    <div class="form-group">
                        <textarea name="content" rows="4" style="width: 100%;"><?php echo htmlspecialchars($content['content']); ?></textarea>
                    </div>
                    <button type="submit" name="update_content" class="btn-primary">Update</button>
                </form>
                <?php endforeach; ?>
            </div>
            
            <!-- Admission Steps -->
            <div class="data-table-container">
                <h2>Admission Steps</h2>
                <a href="edit-admission-step.php" class="btn-primary" style="margin-bottom: 20px;">+ Add New Step</a>
                
                <table class="data-table">
                    <thead>
                        <tr><th>Order</th><th>Step #</th><th>Title</th><th>Description</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach($admission_steps as $step): ?>
                        <tr>
                            <td><?php echo $step['display_order']; ?>;</td>
                            <td><?php echo $step['step_number']; ?>;</td>
                            <td><?php echo htmlspecialchars($step['title']); ?>;</td>
                            <td><?php echo htmlspecialchars(substr($step['description'], 0, 50)); ?>...</td>
                            <td class="actions">
                                <a href="edit-admission-step.php?id=<?php echo $step['id']; ?>" class="btn-edit">Edit</a>
                                <a href="?delete_step=<?php echo $step['id']; ?>" class="btn-delete" onclick="return confirm('Delete this step?')">Delete</a>
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