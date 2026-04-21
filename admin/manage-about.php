<?php
// admin/manage-about.php - COMPLETELY FIXED CRUD
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
    }
    
    // Update Core Values
    if(isset($_POST['update_values'])) {
        $title = trim($_POST['values_title']);
        $content = trim($_POST['values_content']);
        $stmt = $pdo->prepare("UPDATE about_content SET title = ?, content = ? WHERE section = 'core_values'");
        if($stmt->execute([$title, $content])) {
            $message = "✅ Core Values updated successfully!";
        } else {
            $error = "❌ Failed to update core values.";
        }
    }
    
    // Add Statistic
    if(isset($_POST['add_stat'])) {
        $stat_number = trim($_POST['stat_number']);
        $stat_label = trim($_POST['stat_label']);
        $display_order = intval($_POST['display_order']);
        
        if(empty($stat_number) || empty($stat_label)) {
            $error = "❌ Please enter both a number and a label.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO about_stats (stat_number, stat_label, display_order) VALUES (?, ?, ?)");
            if($stmt->execute([$stat_number, $stat_label, $display_order])) {
                $message = "✅ Statistic added successfully!";
            } else {
                $error = "❌ Failed to add statistic.";
            }
        }
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
    }
}

// ============================================
// FETCH ALL DATA
// ============================================
$mission = $pdo->query("SELECT * FROM about_content WHERE section = 'mission'")->fetch();
$vision = $pdo->query("SELECT * FROM about_content WHERE section = 'vision'")->fetch();
$history = $pdo->query("SELECT * FROM about_content WHERE section = 'history'")->fetch();
$core_values = $pdo->query("SELECT * FROM about_content WHERE section = 'core_values'")->fetch();
$statistics = $pdo->query("SELECT * FROM about_stats ORDER BY display_order ASC")->fetchAll();

// Get next display order for new stats
$next_order = $pdo->query("SELECT COALESCE(MAX(display_order), 0) + 1 as next FROM about_stats")->fetch();
$next_display_order = $next_order['next'];
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
            --gray-border: #ddd;
        }

        body {
            background: #f0f2f5;
        }

        /* Admin Navigation */
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

        /* Main Container */
        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 20px;
        }

        /* Page Header */
        .page-header {
            margin-bottom: 30px;
        }

        .page-header h1 {
            font-size: 1.8rem;
            color: var(--primary-color);
            margin-bottom: 5px;
        }

        .page-header h1 i {
            color: var(--accent-color);
            margin-right: 10px;
        }

        .page-header p {
            color: #666;
        }

        /* Alert Messages */
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
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
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

        /* Form Sections */
        .form-section {
            background: white;
            border-radius: 16px;
            margin-bottom: 25px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--gray-border);
        }

        .section-header {
            padding: 18px 25px;
            background: var(--gray-light);
            border-bottom: 1px solid var(--gray-border);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .section-header i {
            font-size: 1.3rem;
            color: var(--accent-color);
        }

        .section-header h2 {
            font-size: 1.1rem;
            color: var(--primary-color);
            font-weight: 600;
        }

        .section-body {
            padding: 25px;
        }

        /* Form Groups */
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

        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--gray-border);
            border-radius: 10px;
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

        /* Statistics Table */
        .stats-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .stats-table th {
            text-align: left;
            padding: 12px;
            background: var(--gray-light);
            font-weight: 600;
            color: var(--primary-color);
            border-bottom: 1px solid var(--gray-border);
        }

        .stats-table td {
            padding: 12px;
            border-bottom: 1px solid var(--gray-border);
            vertical-align: middle;
        }

        .stats-table tr:hover {
            background: rgba(0, 35, 102, 0.02);
        }

        .stat-number-display {
            font-weight: bold;
            color: var(--accent-color);
            background: var(--primary-color);
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 10px 24px;
            border: none;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
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
            font-size: 0.75rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            text-decoration: none;
        }

        .btn-edit {
            background: #28a745;
            color: white;
            padding: 5px 12px;
            border: none;
            border-radius: 5px;
            font-size: 0.7rem;
            cursor: pointer;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
            padding: 5px 12px;
            border: none;
            border-radius: 5px;
            font-size: 0.7rem;
            cursor: pointer;
        }

        .add-stat-form {
            background: var(--gray-light);
            padding: 20px;
            border-radius: 12px;
            margin-top: 20px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            .stats-table {
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
            <p>Edit the content that appears on the About Us page - Mission, Vision, History, Core Values, and Statistics</p>
        </div>

        <?php if($message): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <span><?php echo $message; ?></span>
            </div>
        <?php endif; ?>

        <?php if($error): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-triangle"></i>
                <span><?php echo $error; ?></span>
            </div>
        <?php endif; ?>

        <!-- ============================================ -->
        <!-- MISSION SECTION -->
        <!-- ============================================ -->
        <div class="form-section">
            <div class="section-header">
                <i class="fas fa-bullseye"></i>
                <h2>Mission Statement</h2>
            </div>
            <div class="section-body">
                <form method="POST">
                    <div class="form-group">
                        <label><i class="fas fa-heading"></i> Title</label>
                        <input type="text" name="mission_title" value="<?php echo htmlspecialchars($mission['title'] ?? 'Our Mission'); ?>">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-align-left"></i> Content</label>
                        <textarea name="mission_content" rows="4"><?php echo htmlspecialchars($mission['content'] ?? ''); ?></textarea>
                    </div>
                    <button type="submit" name="update_mission" class="btn-primary">
                        <i class="fas fa-save"></i> Save Mission
                    </button>
                </form>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- VISION SECTION -->
        <!-- ============================================ -->
        <div class="form-section">
            <div class="section-header">
                <i class="fas fa-eye"></i>
                <h2>Vision Statement</h2>
            </div>
            <div class="section-body">
                <form method="POST">
                    <div class="form-group">
                        <label><i class="fas fa-heading"></i> Title</label>
                        <input type="text" name="vision_title" value="<?php echo htmlspecialchars($vision['title'] ?? 'Our Vision'); ?>">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-align-left"></i> Content</label>
                        <textarea name="vision_content" rows="4"><?php echo htmlspecialchars($vision['content'] ?? ''); ?></textarea>
                    </div>
                    <button type="submit" name="update_vision" class="btn-primary">
                        <i class="fas fa-save"></i> Save Vision
                    </button>
                </form>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- HISTORY SECTION -->
        <!-- ============================================ -->
        <div class="form-section">
            <div class="section-header">
                <i class="fas fa-history"></i>
                <h2>Our Story / History</h2>
            </div>
            <div class="section-body">
                <form method="POST">
                    <div class="form-group">
                        <label><i class="fas fa-heading"></i> Title</label>
                        <input type="text" name="history_title" value="<?php echo htmlspecialchars($history['title'] ?? 'Our Story'); ?>">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-align-left"></i> Content</label>
                        <textarea name="history_content" rows="6"><?php echo htmlspecialchars($history['content'] ?? ''); ?></textarea>
                    </div>
                    <button type="submit" name="update_history" class="btn-primary">
                        <i class="fas fa-save"></i> Save Story
                    </button>
                </form>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- CORE VALUES SECTION -->
        <!-- ============================================ -->
        <div class="form-section">
            <div class="section-header">
                <i class="fas fa-gem"></i>
                <h2>Core Values</h2>
            </div>
            <div class="section-body">
                <form method="POST">
                    <div class="form-group">
                        <label><i class="fas fa-heading"></i> Title</label>
                        <input type="text" name="values_title" value="<?php echo htmlspecialchars($core_values['title'] ?? 'Our Core Values'); ?>">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-list"></i> Values (comma separated)</label>
                        <textarea name="values_content" rows="3" placeholder="Excellence, Faith, Service, Global Citizenship, Innovation"><?php echo htmlspecialchars($core_values['content'] ?? ''); ?></textarea>
                        <small style="color: #666;">💡 Separate each value with a comma</small>
                    </div>
                    <button type="submit" name="update_values" class="btn-primary">
                        <i class="fas fa-save"></i> Save Core Values
                    </button>
                </form>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- STATISTICS SECTION -->
        <!-- ============================================ -->
        <div class="form-section">
            <div class="section-header">
                <i class="fas fa-chart-bar"></i>
                <h2>School Statistics</h2>
            </div>
            <div class="section-body">
                <?php if(count($statistics) > 0): ?>
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
                            <?php foreach($statistics as $stat): ?>
                            <tr>
                                <td><span class="stat-number-display"><?php echo htmlspecialchars($stat['stat_number']); ?></span></td>
                                <td><?php echo htmlspecialchars($stat['stat_label']); ?></td>
                                <td><?php echo $stat['display_order']; ?></td>
                                <td>
                                    <form method="POST" style="display: inline-block;">
                                        <input type="hidden" name="stat_id" value="<?php echo $stat['id']; ?>">
                                        <input type="hidden" name="stat_number" value="<?php echo htmlspecialchars($stat['stat_number']); ?>">
                                        <input type="hidden" name="stat_label" value="<?php echo htmlspecialchars($stat['stat_label']); ?>">
                                        <input type="hidden" name="display_order" value="<?php echo $stat['display_order']; ?>">
                                        <button type="button" class="btn-edit" onclick="editStat(<?php echo $stat['id']; ?>, '<?php echo htmlspecialchars($stat['stat_number']); ?>', '<?php echo htmlspecialchars($stat['stat_label']); ?>', <?php echo $stat['display_order']; ?>)">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                    </form>
                                    <form method="POST" style="display: inline-block;" onsubmit="return confirm('Delete this statistic?')">
                                        <input type="hidden" name="delete_stat" value="<?php echo $stat['id']; ?>">
                                        <button type="submit" class="btn-delete">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p style="text-align: center; padding: 20px; color: #666;">No statistics added yet. Add your first statistic below.</p>
                <?php endif; ?>

                <!-- Add New Statistic Form -->
                <div class="add-stat-form">
                    <h3 style="margin-bottom: 15px; color: var(--primary-color);">
                        <i class="fas fa-plus-circle"></i> Add New Statistic
                    </h3>
                    <form method="POST">
                        <div class="form-row">
                            <div class="form-group">
                                <label><i class="fas fa-number"></i> Statistic Number</label>
                                <input type="text" name="stat_number" required placeholder="e.g., 20+, 1,500+, 98%, 100%">
                            </div>
                            <div class="form-group">
                                <label><i class="fas fa-tag"></i> Statistic Label</label>
                                <input type="text" name="stat_label" required placeholder="e.g., Years of Excellence">
                            </div>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-sort-numeric-down"></i> Display Order</label>
                            <input type="number" name="display_order" value="<?php echo $next_display_order; ?>" placeholder="Lower numbers appear first">
                        </div>
                        <button type="submit" name="add_stat" class="btn-primary">
                            <i class="fas fa-plus"></i> Add Statistic
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function editStat(id, number, label, order) {
            var newNumber = prompt("Edit statistic number:", number);
            if(newNumber !== null && newNumber !== "") {
                var newLabel = prompt("Edit statistic label:", label);
                if(newLabel !== null && newLabel !== "") {
                    var newOrder = prompt("Edit display order (lower = higher priority):", order);
                    if(newOrder !== null) {
                        var form = document.createElement('form');
                        form.method = 'POST';
                        form.innerHTML = `
                            <input type="hidden" name="edit_stat" value="1">
                            <input type="hidden" name="stat_id" value="${id}">
                            <input type="hidden" name="stat_number" value="${newNumber}">
                            <input type="hidden" name="stat_label" value="${newLabel}">
                            <input type="hidden" name="display_order" value="${newOrder}">
                        `;
                        document.body.appendChild(form);
                        form.submit();
                    }
                }
            }
        }
    </script>
</body>
</html>