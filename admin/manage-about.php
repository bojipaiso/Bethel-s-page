<?php
// admin/manage-about.php - Simple editor format (like Hero section)
require_once '../includes/auth.php';
requireAdmin();
require_once '../includes/db.php';

$message = '';

// ============================================
// UPDATE ABOUT CONTENT
// ============================================
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Update Mission
    if(isset($_POST['update_mission'])) {
        $stmt = $pdo->prepare("UPDATE about_content SET title = ?, content = ? WHERE section = 'mission'");
        $stmt->execute([$_POST['mission_title'], $_POST['mission_content']]);
        $message = "Mission updated successfully!";
    }
    
    // Update Vision
    if(isset($_POST['update_vision'])) {
        $stmt = $pdo->prepare("UPDATE about_content SET title = ?, content = ? WHERE section = 'vision'");
        $stmt->execute([$_POST['vision_title'], $_POST['vision_content']]);
        $message = "Vision updated successfully!";
    }
    
    // Update History
    if(isset($_POST['update_history'])) {
        $stmt = $pdo->prepare("UPDATE about_content SET title = ?, content = ? WHERE section = 'history'");
        $stmt->execute([$_POST['history_title'], $_POST['history_content']]);
        $message = "Our Story updated successfully!";
    }
    
    // Update Core Values
    if(isset($_POST['update_values'])) {
        $stmt = $pdo->prepare("UPDATE about_content SET title = ?, content = ? WHERE section = 'core_values'");
        $stmt->execute([$_POST['values_title'], $_POST['values_content']]);
        $message = "Core Values updated successfully!";
    }
    
    // Add/Edit Statistic
    if(isset($_POST['save_stat'])) {
        $id = $_POST['stat_id'] ?? 0;
        $stat_number = $_POST['stat_number'];
        $stat_label = $_POST['stat_label'];
        $display_order = $_POST['display_order'];
        
        if($id > 0) {
            $stmt = $pdo->prepare("UPDATE about_stats SET stat_number=?, stat_label=?, display_order=? WHERE id=?");
            $stmt->execute([$stat_number, $stat_label, $display_order, $id]);
            $message = "Statistic updated!";
        } else {
            $stmt = $pdo->prepare("INSERT INTO about_stats (stat_number, stat_label, display_order) VALUES (?, ?, ?)");
            $stmt->execute([$stat_number, $stat_label, $display_order]);
            $message = "Statistic added!";
        }
    }
    
    // Delete Statistic
    if(isset($_POST['delete_stat'])) {
        $stmt = $pdo->prepare("DELETE FROM about_stats WHERE id = ?");
        $stmt->execute([$_POST['delete_stat']]);
        $message = "Statistic deleted!";
    }
}

// ============================================
// FETCH ALL DATA
// ============================================

$mission = $pdo->query("SELECT * FROM about_content WHERE section = 'mission'")->fetch();
$vision = $pdo->query("SELECT * FROM about_content WHERE section = 'vision'")->fetch();
$history = $pdo->query("SELECT * FROM about_content WHERE section = 'history'")->fetch();
$core_values = $pdo->query("SELECT * FROM about_content WHERE section = 'core_values'")->fetch();
$statistics = $pdo->query("SELECT * FROM about_stats ORDER BY display_order")->fetchAll();

// Get next display order for new stats
$next_order = $pdo->query("SELECT MAX(display_order) + 1 as next FROM about_stats")->fetch();
$next_display_order = $next_order['next'] ?? 1;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage About Us - Bethel School</title>
    <link rel="stylesheet" href="../css/admin-style.css">
    <style>
        .form-section {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .form-section h2 {
            color: var(--primary-color);
            font-size: 1.5rem;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--accent-color);
        }
        
        .stats-list {
            margin-bottom: 20px;
        }
        
        .stat-item {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .stat-number {
            font-size: 1.3rem;
            font-weight: bold;
            color: var(--accent-color);
            background: var(--primary-color);
            padding: 5px 12px;
            border-radius: 20px;
            display: inline-block;
        }
        
        .stat-label {
            flex: 1;
            margin-left: 15px;
        }
        
        .stat-actions {
            display: flex;
            gap: 8px;
        }
        
        .add-stat-form {
            background: #f8f9fa;
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
        }
        
        .inline-form {
            display: flex;
            gap: 10px;
            align-items: flex-end;
            flex-wrap: wrap;
        }
        
        .inline-form .form-group {
            margin-bottom: 0;
        }
        
        .preview-box {
            background: #e8f4f8;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
            border-left: 4px solid var(--accent-color);
        }
        
        .preview-box h4 {
            color: var(--primary-color);
            margin-bottom: 8px;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .help-text {
            font-size: 0.8rem;
            color: #666;
            margin-top: 5px;
        }
        
        .stat-preview {
            display: inline-block;
            text-align: center;
            padding: 10px 15px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 10px;
            color: white;
            min-width: 100px;
        }
        
        .stat-preview .num {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--accent-color);
        }
        
        .stat-preview .lbl {
            font-size: 0.7rem;
        }
    </style>
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
                <h1>Manage About Us Page</h1>
                <p>Edit the content that appears on the About Us page</p>
            </div>
            
            <?php if($message): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <!-- ============================================ -->
            <!-- MISSION SECTION -->
            <!-- ============================================ -->
            <div class="form-section">
                <h2>🎯 Mission Statement</h2>
                <form method="POST">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="mission_title" class="form-control" value="<?php echo htmlspecialchars($mission['title'] ?? 'Our Mission'); ?>" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    </div>
                    <div class="form-group">
                        <label>Content</label>
                        <textarea name="mission_content" rows="5" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;"><?php echo htmlspecialchars($mission['content'] ?? ''); ?></textarea>
                    </div>
                    <button type="submit" name="update_mission" class="btn-primary">Save Mission</button>
                </form>
                <div class="preview-box">
                    <h4>📱 Live Preview:</h4>
                    <div style="background: white; padding: 15px; border-radius: 5px;">
                        <strong style="color: var(--primary-color);"><?php echo htmlspecialchars($mission['title'] ?? 'Our Mission'); ?></strong>
                        <p style="margin-top: 8px; color: #555;"><?php echo nl2br(htmlspecialchars(substr($mission['content'] ?? '', 0, 150))); ?>...</p>
                    </div>
                </div>
            </div>
            
            <!-- ============================================ -->
            <!-- VISION SECTION -->
            <!-- ============================================ -->
            <div class="form-section">
                <h2>👁️ Vision Statement</h2>
                <form method="POST">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="vision_title" class="form-control" value="<?php echo htmlspecialchars($vision['title'] ?? 'Our Vision'); ?>" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    </div>
                    <div class="form-group">
                        <label>Content</label>
                        <textarea name="vision_content" rows="5" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;"><?php echo htmlspecialchars($vision['content'] ?? ''); ?></textarea>
                    </div>
                    <button type="submit" name="update_vision" class="btn-primary">Save Vision</button>
                </form>
                <div class="preview-box">
                    <h4>📱 Live Preview:</h4>
                    <div style="background: white; padding: 15px; border-radius: 5px;">
                        <strong style="color: var(--primary-color);"><?php echo htmlspecialchars($vision['title'] ?? 'Our Vision'); ?></strong>
                        <p style="margin-top: 8px; color: #555;"><?php echo nl2br(htmlspecialchars(substr($vision['content'] ?? '', 0, 150))); ?>...</p>
                    </div>
                </div>
            </div>
            
            <!-- ============================================ -->
            <!-- HISTORY SECTION -->
            <!-- ============================================ -->
            <div class="form-section">
                <h2>📖 Our Story</h2>
                <form method="POST">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="history_title" class="form-control" value="<?php echo htmlspecialchars($history['title'] ?? 'Our Story'); ?>" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    </div>
                    <div class="form-group">
                        <label>Content</label>
                        <textarea name="history_content" rows="8" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;"><?php echo htmlspecialchars($history['content'] ?? ''); ?></textarea>
                    </div>
                    <button type="submit" name="update_history" class="btn-primary">Save Story</button>
                </form>
                <div class="preview-box">
                    <h4>📱 Live Preview:</h4>
                    <div style="background: white; padding: 15px; border-radius: 5px; max-height: 150px; overflow-y: auto;">
                        <strong style="color: var(--primary-color);"><?php echo htmlspecialchars($history['title'] ?? 'Our Story'); ?></strong>
                        <p style="margin-top: 8px; color: #555;"><?php echo nl2br(htmlspecialchars(substr($history['content'] ?? '', 0, 200))); ?>...</p>
                    </div>
                </div>
            </div>
            
            <!-- ============================================ -->
            <!-- CORE VALUES SECTION -->
            <!-- ============================================ -->
            <div class="form-section">
                <h2>💎 Core Values</h2>
                <form method="POST">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="values_title" class="form-control" value="<?php echo htmlspecialchars($core_values['title'] ?? 'Our Core Values'); ?>" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    </div>
                    <div class="form-group">
                        <label>Values (comma separated)</label>
                        <textarea name="values_content" rows="3" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" placeholder="Excellence, Faith, Service, Global Citizenship, Innovation"><?php echo htmlspecialchars($core_values['content'] ?? ''); ?></textarea>
                        <div class="help-text">💡 Enter values separated by commas (e.g., Excellence, Faith, Service)</div>
                    </div>
                    <button type="submit" name="update_values" class="btn-primary">Save Core Values</button>
                </form>
                <div class="preview-box">
                    <h4>📱 Live Preview:</h4>
                    <div style="background: white; padding: 15px; border-radius: 5px;">
                        <strong style="color: var(--primary-color);"><?php echo htmlspecialchars($core_values['title'] ?? 'Our Core Values'); ?></strong>
                        <div style="margin-top: 8px;">
                            <?php 
                            $values = explode(',', $core_values['content'] ?? 'Excellence, Faith, Service, Global Citizenship, Innovation');
                            foreach($values as $value):
                            ?>
                            <span style="display: inline-block; background: #f0f0f0; padding: 3px 10px; border-radius: 15px; margin: 3px; font-size: 0.85rem;">🦅 <?php echo trim($value); ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- ============================================ -->
            <!-- STATISTICS SECTION -->
            <!-- ============================================ -->
            <div class="form-section">
                <h2>📊 School Statistics</h2>
                <p>These numbers appear on the About Us page. Add statistics like years of excellence, student count, etc.</p>
                
                <!-- Statistics List -->
                <div class="stats-list">
                    <?php foreach($statistics as $stat): ?>
                    <div class="stat-item">
                        <span class="stat-number"><?php echo htmlspecialchars($stat['stat_number']); ?></span>
                        <span class="stat-label"><?php echo htmlspecialchars($stat['stat_label']); ?></span>
                        <div class="stat-actions">
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="stat_id" value="<?php echo $stat['id']; ?>">
                                <input type="hidden" name="stat_number" value="<?php echo htmlspecialchars($stat['stat_number']); ?>">
                                <input type="hidden" name="stat_label" value="<?php echo htmlspecialchars($stat['stat_label']); ?>">
                                <input type="hidden" name="display_order" value="<?php echo $stat['display_order']; ?>">
                                <button type="submit" name="edit_stat_btn" class="btn-edit" onclick="editStat(<?php echo $stat['id']; ?>, '<?php echo htmlspecialchars($stat['stat_number']); ?>', '<?php echo htmlspecialchars($stat['stat_label']); ?>', <?php echo $stat['display_order']; ?>)">Edit</button>
                            </form>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Delete this statistic?')">
                                <input type="hidden" name="delete_stat" value="<?php echo $stat['id']; ?>">
                                <button type="submit" class="btn-delete">Delete</button>
                            </form>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Add New Statistic Form -->
                <div class="add-stat-form">
                    <h3 style="margin-bottom: 15px; color: var(--primary-color);">➕ Add New Statistic</h3>
                    <form method="POST" class="inline-form">
                        <div class="form-group" style="flex: 1;">
                            <label>Number</label>
                            <input type="text" name="stat_number" required placeholder="e.g., 20+, 1,500+, 98%" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                        </div>
                        <div class="form-group" style="flex: 2;">
                            <label>Label</label>
                            <input type="text" name="stat_label" required placeholder="e.g., Years of Excellence" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                        </div>
                        <div class="form-group">
                            <label>Order</label>
                            <input type="number" name="display_order" value="<?php echo $next_display_order; ?>" style="width: 70px; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                        </div>
                        <div class="form-group">
                            <button type="submit" name="save_stat" class="btn-primary">Add</button>
                        </div>
                    </form>
                </div>
                
                <!-- Statistics Preview -->
                <div class="preview-box" style="margin-top: 20px;">
                    <h4>📊 Statistics Preview (how it looks on website)</h4>
                    <div style="display: flex; gap: 15px; flex-wrap: wrap; margin-top: 15px; justify-content: center;">
                        <?php foreach($statistics as $stat): ?>
                        <div class="stat-preview">
                            <div class="num"><?php echo htmlspecialchars($stat['stat_number']); ?></div>
                            <div class="lbl"><?php echo htmlspecialchars($stat['stat_label']); ?></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    
    <script>
        function editStat(id, number, label, order) {
            // Simple prompt for editing
            var newNumber = prompt("Edit statistic number:", number);
            if(newNumber !== null) {
                var newLabel = prompt("Edit statistic label:", label);
                if(newLabel !== null) {
                    var form = document.createElement('form');
                    form.method = 'POST';
                    form.innerHTML = `
                        <input type="hidden" name="stat_id" value="${id}">
                        <input type="hidden" name="stat_number" value="${newNumber}">
                        <input type="hidden" name="stat_label" value="${newLabel}">
                        <input type="hidden" name="display_order" value="${order}">
                        <input type="hidden" name="save_stat" value="1">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            }
        }
    </script>
</body>
</html>