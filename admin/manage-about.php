<?php
// admin/manage-about.php
require_once '../includes/auth.php';
requireAdmin();
require_once '../includes/db.php';

$message = '';
$error = '';

// Update text content
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_mission'])) {
        $stmt = $pdo->prepare("UPDATE about_content SET title = ?, content = ? WHERE section = 'mission'");
        $stmt->execute([$_POST['mission_title'], $_POST['mission_content']]);
        $message = "Mission updated!";
        header("Location: manage-about.php");
        exit();
    }
    if (isset($_POST['update_vision'])) {
        $stmt = $pdo->prepare("UPDATE about_content SET title = ?, content = ? WHERE section = 'vision'");
        $stmt->execute([$_POST['vision_title'], $_POST['vision_content']]);
        $message = "Vision updated!";
        header("Location: manage-about.php");
        exit();
    }
    if (isset($_POST['update_history'])) {
        $stmt = $pdo->prepare("UPDATE about_content SET title = ?, content = ? WHERE section = 'history'");
        $stmt->execute([$_POST['history_title'], $_POST['history_content']]);
        $message = "History updated!";
        header("Location: manage-about.php");
        exit();
    }
    
    // Core values - now using separate `core_values` table
    if (isset($_POST['update_core_values'])) {
        $titles = isset($_POST['core_title']) ? $_POST['core_title'] : [];
        $descs  = isset($_POST['core_description']) ? $_POST['core_description'] : [];
        
        // Start transaction
        $pdo->beginTransaction();
        try {
            // Delete all existing core values
            $pdo->exec("DELETE FROM core_values");
            
            // Insert new ones in order
            $stmt = $pdo->prepare("INSERT INTO core_values (title, description, display_order) VALUES (?, ?, ?)");
            $order = 0;
            for ($i = 0; $i < count($titles); $i++) {
                $title = trim($titles[$i]);
                $desc  = trim($descs[$i]);
                if ($title !== '') {
                    $stmt->execute([$title, $desc, $order]);
                    $order++;
                }
            }
            $pdo->commit();
            $message = "Core values updated!";
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = "Failed to save core values: " . $e->getMessage();
        }
        header("Location: manage-about.php");
        exit();
    }
    
    // Statistics
    if (isset($_POST['add_stat'])) {
        $stmt = $pdo->prepare("INSERT INTO about_stats (stat_number, stat_label, display_order) VALUES (?, ?, ?)");
        $stmt->execute([$_POST['stat_number'], $_POST['stat_label'], $_POST['display_order']]);
        $message = "Statistic added!";
        header("Location: manage-about.php");
        exit();
    }
    if (isset($_POST['edit_stat'])) {
        $stmt = $pdo->prepare("UPDATE about_stats SET stat_number = ?, stat_label = ?, display_order = ? WHERE id = ?");
        $stmt->execute([$_POST['stat_number'], $_POST['stat_label'], $_POST['display_order'], $_POST['stat_id']]);
        $message = "Statistic updated!";
        header("Location: manage-about.php");
        exit();
    }
    if (isset($_POST['delete_stat'])) {
        $stmt = $pdo->prepare("DELETE FROM about_stats WHERE id = ?");
        $stmt->execute([$_POST['delete_stat']]);
        $message = "Statistic deleted!";
        header("Location: manage-about.php");
        exit();
    }
}

// Fetch data
$mission = $pdo->query("SELECT * FROM about_content WHERE section = 'mission'")->fetch();
$vision = $pdo->query("SELECT * FROM about_content WHERE section = 'vision'")->fetch();
$history = $pdo->query("SELECT * FROM about_content WHERE section = 'history'")->fetch();

// Core values from dedicated table (ordered by display_order)
$core_values = $pdo->query("SELECT title, description FROM core_values ORDER BY display_order")->fetchAll();

$statistics = $pdo->query("SELECT * FROM about_stats ORDER BY display_order")->fetchAll();
$next_order = $pdo->query("SELECT COALESCE(MAX(display_order),0)+1 FROM about_stats")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage About Us - Bethel School</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin-style.css">
    <style>
        .content-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(350px,1fr)); gap: 25px; margin-bottom: 30px; }
        .simple-section { background: white; border-radius: 12px; border: 1px solid var(--gray-border); padding: 20px; }
        .simple-section h3 { margin-bottom: 15px; padding-bottom: 8px; border-bottom: 2px solid var(--accent-color); display: inline-block; }
        .core-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .core-table th, .core-table td { padding: 10px; border-bottom: 1px solid var(--gray-border); text-align: left; }
        .stats-table { width: 100%; border-collapse: collapse; }
        .stats-table th, .stats-table td { padding: 10px; border-bottom: 1px solid var(--gray-border); }
        .stat-number-badge { background: var(--primary-color); color: var(--accent-color); padding: 2px 8px; border-radius: 20px; display: inline-block; }
        .add-stat-form { display: flex; gap: 10px; flex-wrap: wrap; align-items: flex-end; margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--gray-border); }
        .add-stat-form .form-group { margin-bottom: 0; flex: 1; }
    </style>
</head>
<body>
<div class="admin-wrapper">
    <nav class="admin-nav"><div class="admin-nav-container"><div class="admin-logo">Bethel <span>CMS</span></div><div class="admin-user"><a href="dashboard.php">Dashboard</a> | <a href="logout.php">Logout</a></div></div></nav>
    <div class="admin-container">
        <div class="page-header"><h1><i class="fas fa-info-circle"></i> Manage About Us</h1></div>
        <?php if($message): ?><div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>
        <?php if($error): ?><div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

        <!-- Mission, Vision, Story -->
        <div class="content-grid">
            <div class="simple-section"><h3><i class="fas fa-bullseye"></i> Mission</h3><form method="POST"><div class="form-group"><label>Title</label><input type="text" name="mission_title" value="<?php echo htmlspecialchars($mission['title']); ?>"></div><div class="form-group"><label>Content</label><textarea name="mission_content" rows="4"><?php echo htmlspecialchars($mission['content']); ?></textarea></div><button type="submit" name="update_mission" class="btn-primary">Save Mission</button></form></div>
            <div class="simple-section"><h3><i class="fas fa-eye"></i> Vision</h3><form method="POST"><div class="form-group"><label>Title</label><input type="text" name="vision_title" value="<?php echo htmlspecialchars($vision['title']); ?>"></div><div class="form-group"><label>Content</label><textarea name="vision_content" rows="4"><?php echo htmlspecialchars($vision['content']); ?></textarea></div><button type="submit" name="update_vision" class="btn-primary">Save Vision</button></form></div>
            <div class="simple-section"><h3><i class="fas fa-history"></i> Our Story</h3><form method="POST"><div class="form-group"><label>Title</label><input type="text" name="history_title" value="<?php echo htmlspecialchars($history['title']); ?>"></div><div class="form-group"><label>Content</label><textarea name="history_content" rows="6"><?php echo htmlspecialchars($history['content']); ?></textarea></div><button type="submit" name="update_history" class="btn-primary">Save Story</button></form></div>
        </div>

        <!-- Core Values (now stored in separate `core_values` table) -->
        <div class="simple-section">
            <h3><i class="fas fa-gem"></i> Core Values</h3>
            <form method="POST" id="coreValuesForm">
                <table class="core-table">
                    <thead><tr><th>Value</th><th>Description</th><th></th></tr></thead>
                    <tbody id="coreValuesTbody">
                        <?php foreach($core_values as $cv): ?>
                        <tr>
                            <td><input type="text" name="core_title[]" value="<?php echo htmlspecialchars($cv['title']); ?>" style="width:100%"></td>
                            <td><input type="text" name="core_description[]" value="<?php echo htmlspecialchars($cv['description']); ?>" style="width:100%"></td>
                            <td><button type="button" class="btn-delete" onclick="removeCoreValueRow(this)">Remove</button></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <button type="button" class="btn-secondary" onclick="addCoreValueRow()">+ Add Value</button>
                <button type="submit" name="update_core_values" class="btn-primary" style="margin-left:10px;">Save Core Values</button>
            </form>
        </div>

        <!-- Statistics -->
        <div class="simple-section">
            <h3><i class="fas fa-chart-bar"></i> School Statistics</h3>
            <table class="stats-table">
                <thead><tr><th>Number</th><th>Label</th><th>Order</th><th>Actions</th></tr></thead>
                <tbody>
                    <?php foreach($statistics as $stat): ?>
                    <tr>
                        <td><span class="stat-number-badge"><?php echo htmlspecialchars($stat['stat_number']); ?></span></td>
                        <td><?php echo htmlspecialchars($stat['stat_label']); ?></td>
                        <td><?php echo $stat['display_order']; ?></td>
                        <td><button class="btn-edit" onclick="editStat(<?php echo $stat['id']; ?>, '<?php echo htmlspecialchars($stat['stat_number']); ?>', '<?php echo htmlspecialchars($stat['stat_label']); ?>', <?php echo $stat['display_order']; ?>)">Edit</button>
                        <form method="POST" style="display:inline-block;" onsubmit="return confirm('Delete?')"><input type="hidden" name="delete_stat" value="<?php echo $stat['id']; ?>"><button type="submit" class="btn-delete">Delete</button></form></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="add-stat-form">
                <div class="form-group"><label>Number</label><input type="text" id="new_stat_number" placeholder="e.g., 20+"></div>
                <div class="form-group"><label>Label</label><input type="text" id="new_stat_label" placeholder="e.g., Years"></div>
                <div class="form-group"><label>Order</label><input type="number" id="new_stat_order" value="<?php echo $next_order; ?>"></div>
                <button class="btn-primary" onclick="addStat()">Add Statistic</button>
            </div>
        </div>
    </div>
</div>
<script>
    function addCoreValueRow() {
        let tbody = document.getElementById('coreValuesTbody');
        let row = tbody.insertRow();
        row.innerHTML = '<td><input type="text" name="core_title[]" style="width:100%"></td><td><input type="text" name="core_description[]" style="width:100%"></td><td><button type="button" class="btn-delete" onclick="removeCoreValueRow(this)">Remove</button></td>';
    }
    function removeCoreValueRow(btn) { btn.closest('tr').remove(); }

    function addStat() {
        let number = document.getElementById('new_stat_number').value.trim();
        let label = document.getElementById('new_stat_label').value.trim();
        let order = document.getElementById('new_stat_order').value;
        if(!number || !label) { alert('Please fill both fields'); return; }
        let form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = '<input type="hidden" name="add_stat" value="1"><input type="hidden" name="stat_number" value="'+escapeHtml(number)+'"><input type="hidden" name="stat_label" value="'+escapeHtml(label)+'"><input type="hidden" name="display_order" value="'+order+'">';
        document.body.appendChild(form);
        form.submit();
    }

    function editStat(id, number, label, order) {
        let newNumber = prompt('Edit statistic number:', number);
        if(newNumber && newNumber.trim()) {
            let newLabel = prompt('Edit statistic label:', label);
            if(newLabel && newLabel.trim()) {
                let newOrder = prompt('Edit display order:', order);
                let form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = '<input type="hidden" name="edit_stat" value="1"><input type="hidden" name="stat_id" value="'+id+'"><input type="hidden" name="stat_number" value="'+escapeHtml(newNumber.trim())+'"><input type="hidden" name="stat_label" value="'+escapeHtml(newLabel.trim())+'"><input type="hidden" name="display_order" value="'+(newOrder ? newOrder : order)+'">';
                document.body.appendChild(form);
                form.submit();
            }
        }
    }

    function escapeHtml(str) { return str.replace(/[&<>]/g, function(m){ if(m==='&') return '&amp;'; if(m==='<') return '&lt;'; if(m==='>') return '&gt;'; return m;}); }
</script>
</body>
</html>