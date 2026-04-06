<?php
// admin/manage-academics.php - UPDATED with Special Programs management
require_once '../includes/auth.php';
requireAdmin();
require_once '../includes/db.php';

$message = '';
$active_tab = $_GET['tab'] ?? 'programs'; // Track which tab is active

// ============================================
// ACADEMIC PROGRAMS CRUD
// ============================================

// Add/Edit Academic Program
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_program'])) {
    $id = $_POST['id'] ?? 0;
    $level_id = $_POST['level_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $features = json_encode(array_filter(explode("\n", $_POST['features'])));
    $display_order = $_POST['display_order'];
    
    if($id > 0) {
        $stmt = $pdo->prepare("UPDATE academic_programs SET level_id=?, title=?, description=?, features=?, display_order=? WHERE id=?");
        $stmt->execute([$level_id, $title, $description, $features, $display_order, $id]);
        $message = "Program updated!";
    } else {
        $stmt = $pdo->prepare("INSERT INTO academic_programs (level_id, title, description, features, display_order) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$level_id, $title, $description, $features, $display_order]);
        $message = "Program added!";
    }
}

// Delete Academic Program
if(isset($_GET['delete_program'])) {
    $stmt = $pdo->prepare("DELETE FROM academic_programs WHERE id = ?");
    $stmt->execute([$_GET['delete_program']]);
    $message = "Program deleted!";
}

// Toggle Program Status
if(isset($_GET['toggle_program'])) {
    $stmt = $pdo->prepare("UPDATE academic_programs SET status = IF(status='active', 'inactive', 'active') WHERE id = ?");
    $stmt->execute([$_GET['toggle_program']]);
    $message = "Program status updated!";
}

// Get program for editing
$edit_program = null;
if(isset($_GET['edit_program'])) {
    $stmt = $pdo->prepare("SELECT * FROM academic_programs WHERE id = ?");
    $stmt->execute([$_GET['edit_program']]);
    $edit_program = $stmt->fetch();
}

// ============================================
// SPECIAL PROGRAMS CRUD
// ============================================

// Add/Edit Special Program
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_special'])) {
    $id = $_POST['special_id'] ?? 0;
    $title = $_POST['special_title'];
    $description = $_POST['special_description'];
    $icon_class = $_POST['icon_class'];
    $display_order = $_POST['special_display_order'];
    
    if($id > 0) {
        $stmt = $pdo->prepare("UPDATE special_programs SET title=?, description=?, icon_class=?, display_order=? WHERE id=?");
        $stmt->execute([$title, $description, $icon_class, $display_order, $id]);
        $message = "Special program updated!";
    } else {
        $stmt = $pdo->prepare("INSERT INTO special_programs (title, description, icon_class, display_order) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $description, $icon_class, $display_order]);
        $message = "Special program added!";
    }
}

// Delete Special Program
if(isset($_GET['delete_special'])) {
    $stmt = $pdo->prepare("DELETE FROM special_programs WHERE id = ?");
    $stmt->execute([$_GET['delete_special']]);
    $message = "Special program deleted!";
}

// Toggle Special Program Status
if(isset($_GET['toggle_special'])) {
    $stmt = $pdo->prepare("UPDATE special_programs SET status = IF(status='active', 'inactive', 'active') WHERE id = ?");
    $stmt->execute([$_GET['toggle_special']]);
    $message = "Special program status updated!";
}

// Get special program for editing
$edit_special = null;
if(isset($_GET['edit_special'])) {
    $stmt = $pdo->prepare("SELECT * FROM special_programs WHERE id = ?");
    $stmt->execute([$_GET['edit_special']]);
    $edit_special = $stmt->fetch();
}

// ============================================
// FETCH ALL DATA
// ============================================

$levels = $pdo->query("SELECT * FROM academic_levels ORDER BY display_order")->fetchAll();
$programs = $pdo->query("SELECT p.*, l.level_name FROM academic_programs p LEFT JOIN academic_levels l ON p.level_id = l.id ORDER BY l.display_order, p.display_order")->fetchAll();
$special_programs = $pdo->query("SELECT * FROM special_programs ORDER BY display_order ASC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Academics - Bethel School</title>
    <link rel="stylesheet" href="../css/admin-style.css">
    <style>
        .tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
        }
        .tab {
            padding: 10px 20px;
            background: #f0f0f0;
            text-decoration: none;
            color: #333;
            border-radius: 5px 5px 0 0;
            transition: background 0.3s;
        }
        .tab.active {
            background: var(--primary-color);
            color: white;
        }
        .tab:hover:not(.active) {
            background: #e0e0e0;
        }
        .special-card {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
        }
        .icon-preview {
            font-size: 24px;
            margin-right: 10px;
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
                <h1>Manage Academics</h1>
            </div>
            
            <?php if($message): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <!-- Tabs -->
            <div class="tabs">
                <a href="?tab=programs" class="tab <?php echo $active_tab == 'programs' ? 'active' : ''; ?>">📚 Academic Programs</a>
                <a href="?tab=special" class="tab <?php echo $active_tab == 'special' ? 'active' : ''; ?>">⭐ Special Programs</a>
            </div>
            
            <!-- ============================================ -->
            <!-- ACADEMIC PROGRAMS TAB -->
            <!-- ============================================ -->
            <?php if($active_tab == 'programs'): ?>
            
            <div style="margin-bottom: 20px;">
                <a href="#add-program-form" class="btn-primary" onclick="document.getElementById('add-program-form').scrollIntoView();">+ Add New Program</a>
            </div>
            
            <!-- Programs List -->
            <div class="data-table-container">
                <h2>Academic Programs</h2>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Level</th>
                            <th>Program Title</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Order</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($programs as $program): ?>
                        <tr>
                            <td><?php echo $program['id']; ?>;</td>
                            <td><?php echo htmlspecialchars($program['level_name']); ?>;</td>
                            <td><?php echo htmlspecialchars($program['title']); ?>;</td>
                            <td><?php echo htmlspecialchars(substr($program['description'], 0, 50)); ?>...</td>
                            <td><span class="status-badge status-<?php echo $program['status']; ?>"><?php echo $program['status']; ?></span></td>
                            <td><?php echo $program['display_order']; ?>;</td>
                            <td class="actions">
                                <a href="?edit_program=<?php echo $program['id']; ?>&tab=programs" class="btn-edit">Edit</a>
                                <a href="?toggle_program=<?php echo $program['id']; ?>&tab=programs" class="btn-toggle">Toggle</a>
                                <a href="?delete_program=<?php echo $program['id']; ?>&tab=programs" class="btn-delete" onclick="return confirm('Delete this program?')">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Add/Edit Program Form -->
            <div id="add-program-form" class="form-container" style="margin-top: 40px;">
                <h2><?php echo $edit_program ? 'Edit Academic Program' : 'Add New Academic Program'; ?></h2>
                <form method="POST">
                    <input type="hidden" name="id" value="<?php echo $edit_program['id'] ?? ''; ?>">
                    
                    <div class="form-group">
                        <label>Education Level *</label>
                        <select name="level_id" required>
                            <option value="">Select Level</option>
                            <?php foreach($levels as $level): ?>
                                <option value="<?php echo $level['id']; ?>" <?php echo ($edit_program['level_id'] ?? '') == $level['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($level['level_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Program Title *</label>
                        <input type="text" name="title" required value="<?php echo htmlspecialchars($edit_program['title'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Description *</label>
                        <textarea name="description" rows="4" required><?php echo htmlspecialchars($edit_program['description'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Features (one per line)</label>
                        <textarea name="features" rows="5" placeholder="Feature 1&#10;Feature 2&#10;Feature 3"><?php 
                            if(isset($edit_program['features'])) {
                                $features = json_decode($edit_program['features'], true);
                                if(is_array($features)) {
                                    echo implode("\n", $features);
                                }
                            }
                        ?></textarea>
                        <small>Enter each feature on a new line</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Display Order</label>
                        <input type="number" name="display_order" value="<?php echo $edit_program['display_order'] ?? 0; ?>">
                    </div>
                    
                    <button type="submit" name="save_program" class="btn-primary">Save Program</button>
                    <?php if($edit_program): ?>
                        <a href="manage-academics.php?tab=programs" class="btn-secondary">Cancel Edit</a>
                    <?php endif; ?>
                </form>
            </div>
            
            <?php endif; ?>
            
            <!-- ============================================ -->
            <!-- SPECIAL PROGRAMS TAB -->
            <!-- ============================================ -->
            <?php if($active_tab == 'special'): ?>
            
            <div style="margin-bottom: 20px;">
                <a href="#add-special-form" class="btn-primary" onclick="document.getElementById('add-special-form').scrollIntoView();">+ Add New Special Program</a>
            </div>
            
            <!-- Special Programs List -->
            <div class="data-table-container">
                <h2>Special Programs</h2>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Icon</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Order</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($special_programs as $special): ?>
                        <tr>
                            <td><?php echo $special['id']; ?>;</td>
                            <td style="font-size: 24px;"><?php echo htmlspecialchars($special['icon_class'] ?: '⭐'); ?>;</td>
                            <td><?php echo htmlspecialchars($special['title']); ?>;</td>
                            <td><?php echo htmlspecialchars(substr($special['description'], 0, 60)); ?>...</td>
                            <td><span class="status-badge status-<?php echo $special['status']; ?>"><?php echo $special['status']; ?></span></td>
                            <td><?php echo $special['display_order']; ?>;</td>
                            <td class="actions">
                                <a href="?edit_special=<?php echo $special['id']; ?>&tab=special" class="btn-edit">Edit</a>
                                <a href="?toggle_special=<?php echo $special['id']; ?>&tab=special" class="btn-toggle">Toggle</a>
                                <a href="?delete_special=<?php echo $special['id']; ?>&tab=special" class="btn-delete" onclick="return confirm('Delete this special program?')">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Add/Edit Special Program Form -->
            <div id="add-special-form" class="form-container" style="margin-top: 40px;">
                <h2><?php echo $edit_special ? 'Edit Special Program' : 'Add New Special Program'; ?></h2>
                <form method="POST">
                    <input type="hidden" name="special_id" value="<?php echo $edit_special['id'] ?? ''; ?>">
                    
                    <div class="form-group">
                        <label>Icon (Font Awesome class or emoji)</label>
                        <input type="text" name="icon_class" placeholder="fas fa-globe or 🌍" value="<?php echo htmlspecialchars($edit_special['icon_class'] ?? ''); ?>">
                        <small>Examples: fas fa-globe, fas fa-robot, fas fa-music, fas fa-hand-holding-heart</small>
                        <div style="margin-top: 5px;">
                            <strong>Preview:</strong> 
                            <span style="font-size: 24px;" id="icon-preview"><?php echo htmlspecialchars($edit_special['icon_class'] ?? '⭐'); ?></span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Title *</label>
                        <input type="text" name="special_title" required value="<?php echo htmlspecialchars($edit_special['title'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Description *</label>
                        <textarea name="special_description" rows="3" required><?php echo htmlspecialchars($edit_special['description'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Display Order</label>
                        <input type="number" name="special_display_order" value="<?php echo $edit_special['display_order'] ?? 0; ?>">
                    </div>
                    
                    <button type="submit" name="save_special" class="btn-primary">Save Special Program</button>
                    <?php if($edit_special): ?>
                        <a href="manage-academics.php?tab=special" class="btn-secondary">Cancel Edit</a>
                    <?php endif; ?>
                </form>
            </div>
            
            <script>
                // Live icon preview
                const iconInput = document.querySelector('input[name="icon_class"]');
                const iconPreview = document.getElementById('icon-preview');
                if(iconInput && iconPreview) {
                    iconInput.addEventListener('input', function() {
                        iconPreview.innerHTML = this.value || '⭐';
                    });
                }
            </script>
            
            <?php endif; ?>
            
        </div>
    </div>
</body>
</html>