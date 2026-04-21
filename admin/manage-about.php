<?php
// admin/manage-about.php - REMOVED TOGGLE FEATURES FROM CORE VALUES
require_once '../includes/auth.php';
requireAdmin();
require_once '../includes/db.php';

$message = '';
$error = '';

// ============================================
// UPDATE ABOUT CONTENT
// ============================================
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Update Mission
    if(isset($_POST['update_mission'])) {
        $title = trim($_POST['mission_title']);
        $content = trim($_POST['mission_content']);
        $stmt = $pdo->prepare("UPDATE about_content SET title = ?, content = ? WHERE section = 'mission'");
        if($stmt->execute([$title, $content])) {
            $message = "✅ Mission statement updated successfully!";
        } else {
            $error = "❌ Failed to update mission statement.";
        }
        header("Location: manage-about.php");
        exit();
    }
    
    // Update Vision
    if(isset($_POST['update_vision'])) {
        $title = trim($_POST['vision_title']);
        $content = trim($_POST['vision_content']);
        $stmt = $pdo->prepare("UPDATE about_content SET title = ?, content = ? WHERE section = 'vision'");
        if($stmt->execute([$title, $content])) {
            $message = "✅ Vision statement updated successfully!";
        } else {
            $error = "❌ Failed to update vision statement.";
        }
        header("Location: manage-about.php");
        exit();
    }
    
    // Update History
    if(isset($_POST['update_history'])) {
        $title = trim($_POST['history_title']);
        $content = trim($_POST['history_content']);
        $stmt = $pdo->prepare("UPDATE about_content SET title = ?, content = ? WHERE section = 'history'");
        if($stmt->execute([$title, $content])) {
            $message = "✅ Our Story updated successfully!";
        } else {
            $error = "❌ Failed to update our story.";
        }
        header("Location: manage-about.php");
        exit();
    }
    
    // Add Core Value
    if(isset($_POST['add_core_value'])) {
        $title = trim($_POST['core_title']);
        $description = trim($_POST['core_description']);
        $icon_class = trim($_POST['core_icon']);
        $display_order = intval($_POST['core_display_order']);
        
        $stmt = $pdo->prepare("INSERT INTO core_values (title, description, icon_class, display_order) VALUES (?, ?, ?, ?)");
        if($stmt->execute([$title, $description, $icon_class, $display_order])) {
            $message = "✅ Core value added successfully!";
        } else {
            $error = "❌ Failed to add core value.";
        }
        header("Location: manage-about.php");
        exit();
    }
    
    // Edit Core Value
    if(isset($_POST['edit_core_value'])) {
        $id = intval($_POST['core_id']);
        $title = trim($_POST['core_title']);
        $description = trim($_POST['core_description']);
        $icon_class = trim($_POST['core_icon']);
        $display_order = intval($_POST['core_display_order']);
        
        $stmt = $pdo->prepare("UPDATE core_values SET title = ?, description = ?, icon_class = ?, display_order = ? WHERE id = ?");
        if($stmt->execute([$title, $description, $icon_class, $display_order, $id])) {
            $message = "✅ Core value updated successfully!";
        } else {
            $error = "❌ Failed to update core value.";
        }
        header("Location: manage-about.php");
        exit();
    }
    
    // Delete Core Value
    if(isset($_POST['delete_core_value'])) {
        $id = intval($_POST['delete_core_value']);
        $stmt = $pdo->prepare("DELETE FROM core_values WHERE id = ?");
        if($stmt->execute([$id])) {
            $message = "✅ Core value deleted successfully!";
        } else {
            $error = "❌ Failed to delete core value.";
        }
        header("Location: manage-about.php");
        exit();
    }
    
    // Add Statistic
    if(isset($_POST['add_stat'])) {
        $stat_number = trim($_POST['stat_number']);
        $stat_label = trim($_POST['stat_label']);
        $display_order = intval($_POST['display_order']);
        
        $stmt = $pdo->prepare("INSERT INTO about_stats (stat_number, stat_label, display_order) VALUES (?, ?, ?)");
        if($stmt->execute([$stat_number, $stat_label, $display_order])) {
            $message = "✅ Statistic added successfully!";
        } else {
            $error = "❌ Failed to add statistic.";
        }
        header("Location: manage-about.php");
        exit();
    }
    
    // Edit Statistic
    if(isset($_POST['edit_stat'])) {
        $stat_id = intval($_POST['stat_id']);
        $stat_number = trim($_POST['stat_number']);
        $stat_label = trim($_POST['stat_label']);
        $display_order = intval($_POST['display_order']);
        
        $stmt = $pdo->prepare("UPDATE about_stats SET stat_number = ?, stat_label = ?, display_order = ? WHERE id = ?");
        if($stmt->execute([$stat_number, $stat_label, $display_order, $stat_id])) {
            $message = "✅ Statistic updated successfully!";
        } else {
            $error = "❌ Failed to update statistic.";
        }
        header("Location: manage-about.php");
        exit();
    }
    
    // Delete Statistic
    if(isset($_POST['delete_stat'])) {
        $stat_id = intval($_POST['delete_stat']);
        $stmt = $pdo->prepare("DELETE FROM about_stats WHERE id = ?");
        if($stmt->execute([$stat_id])) {
            $message = "✅ Statistic deleted successfully!";
        } else {
            $error = "❌ Failed to delete statistic.";
        }
        header("Location: manage-about.php");
        exit();
    }
}

// ============================================
// FETCH ALL DATA
// ============================================
$mission = $pdo->query("SELECT * FROM about_content WHERE section = 'mission'")->fetch();
$vision = $pdo->query("SELECT * FROM about_content WHERE section = 'vision'")->fetch();
$history = $pdo->query("SELECT * FROM about_content WHERE section = 'history'")->fetch();
$core_values = $pdo->query("SELECT * FROM core_values ORDER BY display_order ASC")->fetchAll();
$statistics = $pdo->query("SELECT * FROM about_stats ORDER BY display_order ASC")->fetchAll();

// Get next display order
$next_core_order = $pdo->query("SELECT COALESCE(MAX(display_order), 0) + 1 as next FROM core_values")->fetch();
$next_core_display_order = $next_core_order['next'];

$next_stat_order = $pdo->query("SELECT COALESCE(MAX(display_order), 0) + 1 as next FROM about_stats")->fetch();
$next_stat_display_order = $next_stat_order['next'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage About Us - Bethel School</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Roboto, system-ui, sans-serif;
        }

        :root {
            --primary-color: #002366;
            --secondary-color: #0056b3;
            --accent-color: #FFD700;
            --dark-color: #1a1a2e;
            --gray-light: #f8f9fa;
            --gray-border: #dee2e6;
        }

        body {
            background: #f0f2f5;
        }

        .admin-nav {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 15px 0;
            box-shadow: 0 3px 15px rgba(0, 35, 102, 0.3);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .admin-nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .admin-logo {
            font-size: 1.3rem;
            font-weight: bold;
        }

        .admin-logo span {
            color: var(--accent-color);
        }

        .admin-user a {
            color: var(--accent-color);
            text-decoration: none;
            margin-left: 15px;
            transition: color 0.3s;
        }

        .admin-user a:hover {
            color: white;
        }

        .admin-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 30px 20px;
        }

        .page-header {
            margin-bottom: 30px;
        }

        .page-header h1 {
            font-size: 1.8rem;
            color: var(--primary-color);
        }

        .page-header h1 i {
            color: var(--accent-color);
            margin-right: 10px;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        /* Simple Text Box Sections */
        .simple-section {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            border: 1px solid var(--gray-border);
        }

        .simple-section h3 {
            font-size: 1.1rem;
            color: var(--primary-color);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--accent-color);
            display: inline-block;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 0.85rem;
        }

        .form-group label i {
            color: var(--accent-color);
            margin-right: 6px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--gray-border);
            border-radius: 8px;
            font-size: 0.9rem;
            transition: all 0.3s;
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(0, 35, 102, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 35, 102, 0.3);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.8rem;
        }

        /* Core Values Section */
        .core-values-section {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            border: 1px solid var(--gray-border);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--accent-color);
        }

        .section-header h3 {
            font-size: 1.1rem;
            color: var(--primary-color);
        }

        .add-btn {
            background: #28a745;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .values-table {
            width: 100%;
            border-collapse: collapse;
        }

        .values-table th {
            background: var(--gray-light);
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: var(--primary-color);
            border-bottom: 1px solid var(--gray-border);
            font-size: 0.8rem;
        }

        .values-table td {
            padding: 12px;
            border-bottom: 1px solid var(--gray-border);
            vertical-align: middle;
        }

        .values-table tr:hover {
            background: rgba(0, 35, 102, 0.02);
        }

        .value-icon-display {
            font-size: 1.2rem;
            color: var(--primary-color);
        }

        .btn-edit {
            background: #28a745;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.7rem;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.7rem;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        /* Statistics Section */
        .stats-section {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            border: 1px solid var(--gray-border);
        }

        .stats-table {
            width: 100%;
            border-collapse: collapse;
        }

        .stats-table th {
            background: var(--gray-light);
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: var(--primary-color);
            border-bottom: 1px solid var(--gray-border);
            font-size: 0.8rem;
        }

        .stats-table td {
            padding: 12px;
            border-bottom: 1px solid var(--gray-border);
            vertical-align: middle;
        }

        .stat-number-display {
            display: inline-block;
            background: var(--primary-color);
            color: var(--accent-color);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
        }

        .btn-stat-edit {
            background: #28a745;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.7rem;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn-stat-delete {
            background: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.7rem;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .add-stat-form {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid var(--gray-border);
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: flex-end;
        }

        .add-stat-form .form-group {
            flex: 1;
            margin-bottom: 0;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 20px;
            width: 90%;
            max-width: 500px;
            animation: modalFadeIn 0.3s ease;
        }

        @keyframes modalFadeIn {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .modal-header {
            padding: 20px 25px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h2 {
            font-size: 1.1rem;
        }

        .modal-close {
            background: none;
            border: none;
            color: white;
            font-size: 1.3rem;
            cursor: pointer;
        }

        .modal-body {
            padding: 25px;
        }

        .modal-footer {
            padding: 20px 25px;
            background: var(--gray-light);
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
            .add-stat-form {
                flex-direction: column;
            }
            .add-stat-form .form-group {
                width: 100%;
            }
            .values-table, .stats-table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    <nav class="admin-nav">
        <div class="admin-nav-container">
            <div class="admin-logo">Bethel <span>CMS</span></div>
            <div class="admin-user">
                <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </nav>

    <div class="admin-container">
        <div class="page-header">
            <h1><i class="fas fa-info-circle"></i> Manage About Us Page</h1>
        </div>

        <?php if($message): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php if($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- ============================================ -->
        <!-- SIMPLE TEXT BOX SECTIONS -->
        <!-- ============================================ -->

        <!-- Mission Section -->
        <div class="simple-section">
            <h3><i class="fas fa-bullseye"></i> Mission Statement</h3>
            <form method="POST">
                <div class="form-group">
                    <label><i class="fas fa-heading"></i> Title</label>
                    <input type="text" name="mission_title" value="<?php echo htmlspecialchars($mission['title'] ?? 'Our Mission'); ?>">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-align-left"></i> Content</label>
                    <textarea name="mission_content" rows="4"><?php echo htmlspecialchars($mission['content'] ?? ''); ?></textarea>
                </div>
                <button type="submit" name="update_mission" class="btn-primary"><i class="fas fa-save"></i> Save Mission</button>
            </form>
        </div>

        <!-- Vision Section -->
        <div class="simple-section">
            <h3><i class="fas fa-eye"></i> Vision Statement</h3>
            <form method="POST">
                <div class="form-group">
                    <label><i class="fas fa-heading"></i> Title</label>
                    <input type="text" name="vision_title" value="<?php echo htmlspecialchars($vision['title'] ?? 'Our Vision'); ?>">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-align-left"></i> Content</label>
                    <textarea name="vision_content" rows="4"><?php echo htmlspecialchars($vision['content'] ?? ''); ?></textarea>
                </div>
                <button type="submit" name="update_vision" class="btn-primary"><i class="fas fa-save"></i> Save Vision</button>
            </form>
        </div>

        <!-- History Section -->
        <div class="simple-section">
            <h3><i class="fas fa-history"></i> Our Story</h3>
            <form method="POST">
                <div class="form-group">
                    <label><i class="fas fa-heading"></i> Title</label>
                    <input type="text" name="history_title" value="<?php echo htmlspecialchars($history['title'] ?? 'Our Story'); ?>">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-align-left"></i> Content</label>
                    <textarea name="history_content" rows="6"><?php echo htmlspecialchars($history['content'] ?? ''); ?></textarea>
                </div>
                <button type="submit" name="update_history" class="btn-primary"><i class="fas fa-save"></i> Save Story</button>
            </form>
        </div>

        <!-- ============================================ -->
        <!-- CORE VALUES SECTION (NO TOGGLE) -->
        <!-- ============================================ -->
        <div class="core-values-section">
            <div class="section-header">
                <h3><i class="fas fa-gem"></i> Core Values</h3>
                <button class="add-btn" onclick="openCoreValueModal()">
                    <i class="fas fa-plus"></i> Add Core Value
                </button>
            </div>
            <div style="overflow-x: auto;">
                <table class="values-table">
                    <thead>
                        <tr>
                            <th>Icon</th>
                            <th>Value</th>
                            <th>Description</th>
                            <th>Order</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($core_values) > 0): ?>
                            <?php foreach($core_values as $value): ?>
                            <tr>
                                <td class="value-icon-display"><i class="<?php echo htmlspecialchars($value['icon_class'] ?: 'fas fa-star'); ?>"></i>;</td>
                                <td><strong><?php echo htmlspecialchars($value['title']); ?></strong>;</td>
                                <td><?php echo htmlspecialchars(substr($value['description'], 0, 60)); ?>...;</td>
                                <td><?php echo $value['display_order']; ?>;</td>
                                <td>
                                    <button class="btn-edit" onclick="editCoreValue(<?php echo htmlspecialchars(json_encode($value)); ?>)">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <form method="POST" style="display: inline-block;" onsubmit="return confirm('Delete this core value?')">
                                        <input type="hidden" name="delete_core_value" value="<?php echo $value['id']; ?>">
                                        <button type="submit" class="btn-delete">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 40px;">
                                    <i class="fas fa-gem" style="font-size: 2rem; color: #ccc; margin-bottom: 10px; display: block;"></i>
                                    <p>No core values added yet.</p>
                                    <button class="add-btn" onclick="openCoreValueModal()" style="margin-top: 10px;">Add First Core Value</button>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- STATISTICS SECTION -->
        <!-- ============================================ -->
        <div class="stats-section">
            <div class="section-header">
                <h3><i class="fas fa-chart-bar"></i> School Statistics</h3>
            </div>
            <div style="overflow-x: auto;">
                <table class="stats-table">
                    <thead>
                        <tr>
                            <th>Number</th>
                            <th>Label</th>
                            <th>Order</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($statistics) > 0): ?>
                            <?php foreach($statistics as $stat): ?>
                            <tr>
                                <td><span class="stat-number-display"><?php echo htmlspecialchars($stat['stat_number']); ?></span></td>
                                <td><?php echo htmlspecialchars($stat['stat_label']); ?></td>
                                <td><?php echo $stat['display_order']; ?></td>
                                <td>
                                    <button class="btn-stat-edit" onclick="editStat(<?php echo $stat['id']; ?>, '<?php echo htmlspecialchars($stat['stat_number']); ?>', '<?php echo htmlspecialchars($stat['stat_label']); ?>', <?php echo $stat['display_order']; ?>)">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <form method="POST" style="display: inline-block;" onsubmit="return confirm('Delete this statistic?')">
                                        <input type="hidden" name="delete_stat" value="<?php echo $stat['id']; ?>">
                                        <button type="submit" class="btn-stat-delete">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 40px;">
                                    <i class="fas fa-chart-bar" style="font-size: 2rem; color: #ccc; margin-bottom: 10px; display: block;"></i>
                                    <p>No statistics added yet.</p>
                                    <button class="add-btn" onclick="openStatModal()" style="margin-top: 10px;">Add First Statistic</button>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="add-stat-form">
                <div class="form-group">
                    <label>Number</label>
                    <input type="text" id="new_stat_number" placeholder="e.g., 20+">
                </div>
                <div class="form-group">
                    <label>Label</label>
                    <input type="text" id="new_stat_label" placeholder="e.g., Years of Excellence">
                </div>
                <div class="form-group">
                    <label>Order</label>
                    <input type="number" id="new_stat_order" value="<?php echo $next_stat_display_order; ?>">
                </div>
                <button class="btn-primary" onclick="addNewStat()">
                    <i class="fas fa-plus"></i> Add Statistic
                </button>
            </div>
        </div>
    </div>

    <!-- Core Value Modal -->
    <div id="coreValueModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="coreValueModalTitle">Add Core Value</h2>
                <button type="button" class="modal-close" onclick="closeCoreValueModal()">&times;</button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="core_id" value="">
                <div class="form-group">
                    <label>Value Title</label>
                    <input type="text" id="core_title" placeholder="e.g., Excellence, Faith, Service" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea id="core_description" rows="4" placeholder="Describe what this value means to the school..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;"></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Icon (Font Awesome class)</label>
                        <input type="text" id="core_icon" placeholder="fas fa-star" value="fas fa-star" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    </div>
                    <div class="form-group">
                        <label>Display Order</label>
                        <input type="number" id="core_display_order" value="<?php echo $next_core_display_order; ?>" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    </div>
                </div>
                <div id="icon_preview" style="margin-top: 10px; font-size: 1.2rem; color: var(--primary-color);">Preview: <i class="fas fa-star"></i></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeCoreValueModal()">Cancel</button>
                <button type="button" class="btn-primary" onclick="saveCoreValue()">Save Core Value</button>
            </div>
        </div>
    </div>

    <!-- Stat Modal -->
    <div id="statModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="statModalTitle">Add Statistic</h2>
                <button type="button" class="modal-close" onclick="closeStatModal()">&times;</button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="stat_id" value="">
                <div class="form-group">
                    <label>Statistic Number</label>
                    <input type="text" id="stat_number" placeholder="e.g., 20+, 1,500+, 98%">
                </div>
                <div class="form-group">
                    <label>Statistic Label</label>
                    <input type="text" id="stat_label" placeholder="e.g., Years of Excellence">
                </div>
                <div class="form-group">
                    <label>Display Order</label>
                    <input type="number" id="stat_order" value="<?php echo $next_stat_display_order; ?>">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeStatModal()">Cancel</button>
                <button type="button" class="btn-primary" onclick="saveStat()">Save Statistic</button>
            </div>
        </div>
    </div>

    <script>
        // Icon preview
        const iconInput = document.getElementById('core_icon');
        const iconPreview = document.getElementById('icon_preview');
        if (iconInput && iconPreview) {
            iconInput.addEventListener('input', function() {
                iconPreview.innerHTML = 'Preview: <i class="' + this.value + '"></i>';
            });
        }
        
        // ============================================
        // CORE VALUES
        // ============================================
        let editingCoreId = null;
        
        function openCoreValueModal() {
            editingCoreId = null;
            document.getElementById('coreValueModalTitle').innerText = 'Add Core Value';
            document.getElementById('core_id').value = '';
            document.getElementById('core_title').value = '';
            document.getElementById('core_description').value = '';
            document.getElementById('core_icon').value = 'fas fa-star';
            document.getElementById('core_display_order').value = '<?php echo $next_core_display_order; ?>';
            iconPreview.innerHTML = 'Preview: <i class="fas fa-star"></i>';
            document.getElementById('coreValueModal').classList.add('active');
        }
        
        function editCoreValue(value) {
            editingCoreId = value.id;
            document.getElementById('coreValueModalTitle').innerText = 'Edit Core Value';
            document.getElementById('core_id').value = value.id;
            document.getElementById('core_title').value = value.title;
            document.getElementById('core_description').value = value.description;
            document.getElementById('core_icon').value = value.icon_class || 'fas fa-star';
            document.getElementById('core_display_order').value = value.display_order;
            iconPreview.innerHTML = 'Preview: <i class="' + (value.icon_class || 'fas fa-star') + '"></i>';
            document.getElementById('coreValueModal').classList.add('active');
        }
        
        function saveCoreValue() {
            const id = document.getElementById('core_id').value;
            const title = document.getElementById('core_title').value.trim();
            const description = document.getElementById('core_description').value.trim();
            const icon = document.getElementById('core_icon').value.trim();
            const order = document.getElementById('core_display_order').value;
            
            if (!title || !description) {
                alert('Please enter both a title and description.');
                return;
            }
            
            const form = document.createElement('form');
            form.method = 'POST';
            
            if (id) {
                form.innerHTML = `
                    <input type="hidden" name="edit_core_value" value="1">
                    <input type="hidden" name="core_id" value="${id}">
                    <input type="hidden" name="core_title" value="${escapeHtml(title)}">
                    <input type="hidden" name="core_description" value="${escapeHtml(description)}">
                    <input type="hidden" name="core_icon" value="${escapeHtml(icon)}">
                    <input type="hidden" name="core_display_order" value="${order}">
                `;
            } else {
                form.innerHTML = `
                    <input type="hidden" name="add_core_value" value="1">
                    <input type="hidden" name="core_title" value="${escapeHtml(title)}">
                    <input type="hidden" name="core_description" value="${escapeHtml(description)}">
                    <input type="hidden" name="core_icon" value="${escapeHtml(icon)}">
                    <input type="hidden" name="core_display_order" value="${order}">
                `;
            }
            
            document.body.appendChild(form);
            form.submit();
        }
        
        function closeCoreValueModal() {
            document.getElementById('coreValueModal').classList.remove('active');
        }
        
        // ============================================
        // STATISTICS
        // ============================================
        let editingStatId = null;
        
        function addNewStat() {
            const number = document.getElementById('new_stat_number').value.trim();
            const label = document.getElementById('new_stat_label').value.trim();
            const order = document.getElementById('new_stat_order').value;
            
            if (!number || !label) {
                alert('Please enter both a number and a label.');
                return;
            }
            
            const form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `
                <input type="hidden" name="add_stat" value="1">
                <input type="hidden" name="stat_number" value="${escapeHtml(number)}">
                <input type="hidden" name="stat_label" value="${escapeHtml(label)}">
                <input type="hidden" name="display_order" value="${order}">
            `;
            document.body.appendChild(form);
            form.submit();
        }
        
        function editStat(id, number, label, order) {
            editingStatId = id;
            document.getElementById('statModalTitle').innerText = 'Edit Statistic';
            document.getElementById('stat_id').value = id;
            document.getElementById('stat_number').value = number;
            document.getElementById('stat_label').value = label;
            document.getElementById('stat_order').value = order;
            document.getElementById('statModal').classList.add('active');
        }
        
        function saveStat() {
            const id = document.getElementById('stat_id').value;
            const number = document.getElementById('stat_number').value.trim();
            const label = document.getElementById('stat_label').value.trim();
            const order = document.getElementById('stat_order').value;
            
            if (!number || !label) {
                alert('Please enter both a number and a label.');
                return;
            }
            
            const form = document.createElement('form');
            form.method = 'POST';
            
            if (id) {
                form.innerHTML = `
                    <input type="hidden" name="edit_stat" value="1">
                    <input type="hidden" name="stat_id" value="${id}">
                    <input type="hidden" name="stat_number" value="${escapeHtml(number)}">
                    <input type="hidden" name="stat_label" value="${escapeHtml(label)}">
                    <input type="hidden" name="display_order" value="${order}">
                `;
            } else {
                form.innerHTML = `
                    <input type="hidden" name="add_stat" value="1">
                    <input type="hidden" name="stat_number" value="${escapeHtml(number)}">
                    <input type="hidden" name="stat_label" value="${escapeHtml(label)}">
                    <input type="hidden" name="display_order" value="${order}">
                `;
            }
            
            document.body.appendChild(form);
            form.submit();
        }
        
        function closeStatModal() {
            document.getElementById('statModal').classList.remove('active');
        }
        
        function escapeHtml(str) {
            return str.replace(/[&<>]/g, function(m) {
                if (m === '&') return '&amp;';
                if (m === '<') return '&lt;';
                if (m === '>') return '&gt;';
                return m;
            });
        }
        
        // Close modals when clicking outside
        window.onclick = function(event) {
            const coreModal = document.getElementById('coreValueModal');
            const statModal = document.getElementById('statModal');
            if (event.target === coreModal) closeCoreValueModal();
            if (event.target === statModal) closeStatModal();
        }
    </script>
</body>
</html>