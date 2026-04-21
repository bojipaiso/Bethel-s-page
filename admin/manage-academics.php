<?php
// admin/manage-academics.php - REDESIGNED with modal editing and icon picker
require_once '../includes/auth.php';
requireAdmin();
require_once '../includes/db.php';

$message = '';
$error = '';

// ============================================
// HANDLE FORM SUBMISSIONS
// ============================================
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Save Academic Program
    if (isset($_POST['save_program'])) {
        $id = isset($_POST['program_id']) ? intval($_POST['program_id']) : 0;
        $level_id = intval($_POST['level_id']);
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        $features = trim($_POST['features']);
        $display_order = intval($_POST['display_order']);
        $icon_class = trim($_POST['icon_class']);
        
        // Convert features to JSON array
        $features_array = array_filter(array_map('trim', explode("\n", $features)));
        $features_json = json_encode(array_values($features_array));
        
        if ($id > 0) {
            $stmt = $pdo->prepare("UPDATE academic_programs SET level_id = ?, title = ?, description = ?, features = ?, display_order = ?, icon_class = ? WHERE id = ?");
            if ($stmt->execute([$level_id, $title, $description, $features_json, $display_order, $icon_class, $id])) {
                $message = "✅ Program updated successfully!";
            } else {
                $error = "❌ Failed to update program.";
            }
        } else {
            $stmt = $pdo->prepare("INSERT INTO academic_programs (level_id, title, description, features, display_order, icon_class) VALUES (?, ?, ?, ?, ?, ?)");
            if ($stmt->execute([$level_id, $title, $description, $features_json, $display_order, $icon_class])) {
                $message = "✅ Program added successfully!";
            } else {
                $error = "❌ Failed to add program.";
            }
        }
        header("Location: manage-academics.php?tab=" . ($_GET['tab'] ?? 'programs'));
        exit();
    }
    
    // Save Special Program
    if (isset($_POST['save_special'])) {
        $id = isset($_POST['special_id']) ? intval($_POST['special_id']) : 0;
        $title = trim($_POST['special_title']);
        $description = trim($_POST['special_description']);
        $icon_class = trim($_POST['icon_class']);
        $display_order = intval($_POST['special_display_order']);
        
        if ($id > 0) {
            $stmt = $pdo->prepare("UPDATE special_programs SET title = ?, description = ?, icon_class = ?, display_order = ? WHERE id = ?");
            if ($stmt->execute([$title, $description, $icon_class, $display_order, $id])) {
                $message = "✅ Special program updated successfully!";
            } else {
                $error = "❌ Failed to update special program.";
            }
        } else {
            $stmt = $pdo->prepare("INSERT INTO special_programs (title, description, icon_class, display_order) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$title, $description, $icon_class, $display_order])) {
                $message = "✅ Special program added successfully!";
            } else {
                $error = "❌ Failed to add special program.";
            }
        }
        header("Location: manage-academics.php?tab=special");
        exit();
    }
}

// ============================================
// HANDLE DELETE/TOGGLE ACTIONS
// ============================================
if (isset($_GET['delete_program'])) {
    $id = intval($_GET['delete_program']);
    $stmt = $pdo->prepare("DELETE FROM academic_programs WHERE id = ?");
    if ($stmt->execute([$id])) {
        $message = "✅ Program deleted successfully!";
    }
    header("Location: manage-academics.php?tab=programs");
    exit();
}

if (isset($_GET['delete_special'])) {
    $id = intval($_GET['delete_special']);
    $stmt = $pdo->prepare("DELETE FROM special_programs WHERE id = ?");
    if ($stmt->execute([$id])) {
        $message = "✅ Special program deleted successfully!";
    }
    header("Location: manage-academics.php?tab=special");
    exit();
}

if (isset($_GET['toggle_program'])) {
    $id = intval($_GET['toggle_program']);
    $stmt = $pdo->prepare("UPDATE academic_programs SET status = IF(status='active', 'inactive', 'active') WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: manage-academics.php?tab=programs");
    exit();
}

if (isset($_GET['toggle_special'])) {
    $id = intval($_GET['toggle_special']);
    $stmt = $pdo->prepare("UPDATE special_programs SET status = IF(status='active', 'inactive', 'active') WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: manage-academics.php?tab=special");
    exit();
}

// ============================================
// FETCH ALL DATA
// ============================================
$levels = $pdo->query("SELECT * FROM academic_levels ORDER BY display_order")->fetchAll();
$programs = $pdo->query("SELECT p.*, l.level_name FROM academic_programs p LEFT JOIN academic_levels l ON p.level_id = l.id ORDER BY l.display_order, p.display_order")->fetchAll();
$special_programs = $pdo->query("SELECT * FROM special_programs ORDER BY display_order")->fetchAll();

// Get single program for editing (via AJAX or modal)
$edit_program = null;
if (isset($_GET['edit_program'])) {
    $id = intval($_GET['edit_program']);
    $stmt = $pdo->prepare("SELECT * FROM academic_programs WHERE id = ?");
    $stmt->execute([$id]);
    $edit_program = $stmt->fetch();
}

$edit_special = null;
if (isset($_GET['edit_special'])) {
    $id = intval($_GET['edit_special']);
    $stmt = $pdo->prepare("SELECT * FROM special_programs WHERE id = ?");
    $stmt->execute([$id]);
    $edit_special = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Academics - Bethel School</title>
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
            max-width: 1400px;
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

        /* Tabs */
        .tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            border-bottom: 2px solid var(--gray-border);
            padding-bottom: 10px;
        }

        .tab {
            padding: 10px 25px;
            background: white;
            text-decoration: none;
            color: #333;
            border-radius: 8px 8px 0 0;
            transition: all 0.3s;
            font-weight: 500;
        }

        .tab.active {
            background: var(--primary-color);
            color: white;
        }

        .tab:hover:not(.active) {
            background: #e0e0e0;
        }

        /* Add Button */
        .add-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 20px;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }

        .add-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 35, 102, 0.3);
        }

        /* Data Table */
        .data-table-container {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--gray-border);
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th {
            background: var(--gray-light);
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: var(--primary-color);
            border-bottom: 1px solid var(--gray-border);
        }

        .data-table td {
            padding: 15px;
            border-bottom: 1px solid var(--gray-border);
            vertical-align: middle;
        }

        .data-table tr:hover {
            background: rgba(0, 35, 102, 0.02);
        }

        /* Badges */
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .status-active {
            background: #d4edda;
            color: #155724;
        }

        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }

        /* Icon Preview */
        .icon-preview {
            font-size: 1.5rem;
            width: 40px;
            text-align: center;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn-edit {
            background: #28a745;
            color: white;
            padding: 5px 12px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.75rem;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.2s;
        }

        .btn-edit:hover {
            background: #218838;
            transform: translateY(-1px);
        }

        .btn-toggle {
            background: #ffc107;
            color: #000;
            padding: 5px 12px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.75rem;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-toggle:hover {
            background: #e0a800;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
            padding: 5px 12px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.75rem;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-delete:hover {
            background: #c82333;
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
            max-width: 700px;
            max-height: 90vh;
            overflow-y: auto;
            animation: modalFadeIn 0.3s ease;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            padding: 20px 25px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h2 {
            font-size: 1.3rem;
        }

        .modal-close {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
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

        /* Form Elements */
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
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--gray-border);
            border-radius: 8px;
            font-size: 0.9rem;
            transition: all 0.3s;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(0, 35, 102, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .icon-picker {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .icon-preview-lg {
            width: 50px;
            height: 50px;
            background: var(--gray-light);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            border: 1px solid var(--gray-border);
        }

        .icon-suggestions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
        }

        .icon-suggestion {
            width: 40px;
            height: 40px;
            background: var(--gray-light);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid var(--gray-border);
        }

        .icon-suggestion:hover {
            background: var(--primary-color);
            color: white;
            transform: scale(1.05);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
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
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
            .data-table-container {
                overflow-x: auto;
            }
            .data-table {
                min-width: 600px;
            }
            .action-buttons {
                flex-direction: column;
                gap: 5px;
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
            <h1><i class="fas fa-graduation-cap"></i> Manage Academics</h1>
        </div>

        <?php if($message): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php if($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="tabs">
            <a href="manage-academics.php?tab=programs" class="tab <?php echo (!isset($_GET['tab']) || $_GET['tab'] == 'programs') ? 'active' : ''; ?>">
                <i class="fas fa-book"></i> Academic Programs
            </a>
            <a href="manage-academics.php?tab=special" class="tab <?php echo isset($_GET['tab']) && $_GET['tab'] == 'special' ? 'active' : ''; ?>">
                <i class="fas fa-star"></i> Special Programs
            </a>
        </div>

        <!-- ACADEMIC PROGRAMS TAB -->
        <?php if (!isset($_GET['tab']) || $_GET['tab'] == 'programs'): ?>
            <button class="add-button" onclick="openProgramModal()">
                <i class="fas fa-plus"></i> Add New Program
            </button>

            <div class="data-table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Icon</th>
                            <th>Level</th>
                            <th>Program</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Order</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($programs as $program): ?>
                        <tr>
                            <td class="icon-preview"><i class="<?php echo htmlspecialchars($program['icon_class'] ?: 'fas fa-graduation-cap'); ?>"></i>;</td>
                            <td><?php echo htmlspecialchars($program['level_name']); ?>;</td>
                            <td><strong><?php echo htmlspecialchars($program['title']); ?></strong>;</td>
                            <td><?php echo htmlspecialchars(substr($program['description'], 0, 60)); ?>...;</td>
                            <td><span class="status-badge status-<?php echo $program['status']; ?>"><?php echo $program['status']; ?></span>;</td>
                            <td><?php echo $program['display_order']; ?>;</td>
                            <td class="action-buttons">
                                <button onclick="editProgram(<?php echo htmlspecialchars(json_encode($program)); ?>)" class="btn-edit">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <a href="?toggle_program=<?php echo $program['id']; ?>&tab=programs" class="btn-toggle">
                                    <i class="fas fa-sync-alt"></i> Toggle
                                </a>
                                <a href="?delete_program=<?php echo $program['id']; ?>&tab=programs" class="btn-delete" onclick="return confirm('Delete this program?')">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Program Modal -->
            <div id="programModal" class="modal">
                <div class="modal-content">
                    <form method="POST" action="manage-academics.php?tab=programs">
                        <div class="modal-header">
                            <h2 id="programModalTitle">Add New Program</h2>
                            <button type="button" class="modal-close" onclick="closeProgramModal()">&times;</button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="program_id" id="program_id" value="">
                            
                            <div class="form-group">
                                <label><i class="fas fa-layer-group"></i> Education Level</label>
                                <select name="level_id" id="level_id" required>
                                    <option value="">Select Level</option>
                                    <?php foreach($levels as $level): ?>
                                        <option value="<?php echo $level['id']; ?>"><?php echo htmlspecialchars($level['level_name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label><i class="fas fa-heading"></i> Program Title</label>
                                <input type="text" name="title" id="title" required>
                            </div>
                            
                            <div class="form-group">
                                <label><i class="fas fa-align-left"></i> Description</label>
                                <textarea name="description" id="description" rows="4" required></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label><i class="fas fa-list"></i> Features (one per line)</label>
                                <textarea name="features" id="features" rows="5" placeholder="Small class sizes&#10;Experienced teachers&#10;Modern facilities"></textarea>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label><i class="fas fa-sort-numeric-down"></i> Display Order</label>
                                    <input type="number" name="display_order" id="display_order" value="0">
                                </div>
                                <div class="form-group">
                                    <label><i class="fas fa-icons"></i> Icon</label>
                                    <div class="icon-picker">
                                        <div class="icon-preview-lg" id="iconPreview">
                                            <i class="fas fa-graduation-cap"></i>
                                        </div>
                                        <input type="text" name="icon_class" id="icon_class" placeholder="fas fa-graduation-cap" style="flex: 1;">
                                    </div>
                                    <div class="icon-suggestions">
                                        <div class="icon-suggestion" onclick="setIcon('fas fa-graduation-cap')"><i class="fas fa-graduation-cap"></i></div>
                                        <div class="icon-suggestion" onclick="setIcon('fas fa-flask')"><i class="fas fa-flask"></i></div>
                                        <div class="icon-suggestion" onclick="setIcon('fas fa-chart-line')"><i class="fas fa-chart-line"></i></div>
                                        <div class="icon-suggestion" onclick="setIcon('fas fa-laptop-code')"><i class="fas fa-laptop-code"></i></div>
                                        <div class="icon-suggestion" onclick="setIcon('fas fa-palette')"><i class="fas fa-palette"></i></div>
                                        <div class="icon-suggestion" onclick="setIcon('fas fa-microscope')"><i class="fas fa-microscope"></i></div>
                                        <div class="icon-suggestion" onclick="setIcon('fas fa-calculator')"><i class="fas fa-calculator"></i></div>
                                        <div class="icon-suggestion" onclick="setIcon('fas fa-draw-polygon')"><i class="fas fa-draw-polygon"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn-secondary" onclick="closeProgramModal()">Cancel</button>
                            <button type="submit" name="save_program" class="btn-primary">Save Program</button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <!-- SPECIAL PROGRAMS TAB -->
        <?php if (isset($_GET['tab']) && $_GET['tab'] == 'special'): ?>
            <button class="add-button" onclick="openSpecialModal()">
                <i class="fas fa-plus"></i> Add New Special Program
            </button>

            <div class="data-table-container">
                <table class="data-table">
                    <thead>
                        <tr>
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
                            <td class="icon-preview"><i class="<?php echo htmlspecialchars($special['icon_class'] ?: 'fas fa-star'); ?>"></i>;</td>
                            <td><strong><?php echo htmlspecialchars($special['title']); ?></strong>;</td>
                            <td><?php echo htmlspecialchars(substr($special['description'], 0, 80)); ?>...;</td>
                            <td><span class="status-badge status-<?php echo $special['status']; ?>"><?php echo $special['status']; ?></span>;</td>
                            <td><?php echo $special['display_order']; ?>;</td>
                            <td class="action-buttons">
                                <button onclick="editSpecial(<?php echo htmlspecialchars(json_encode($special)); ?>)" class="btn-edit">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <a href="?toggle_special=<?php echo $special['id']; ?>&tab=special" class="btn-toggle">
                                    <i class="fas fa-sync-alt"></i> Toggle
                                </a>
                                <a href="?delete_special=<?php echo $special['id']; ?>&tab=special" class="btn-delete" onclick="return confirm('Delete this program?')">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Special Program Modal -->
            <div id="specialModal" class="modal">
                <div class="modal-content">
                    <form method="POST" action="manage-academics.php?tab=special">
                        <div class="modal-header">
                            <h2 id="specialModalTitle">Add New Special Program</h2>
                            <button type="button" class="modal-close" onclick="closeSpecialModal()">&times;</button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="special_id" id="special_id" value="">
                            
                            <div class="form-group">
                                <label><i class="fas fa-heading"></i> Program Title</label>
                                <input type="text" name="special_title" id="special_title" required>
                            </div>
                            
                            <div class="form-group">
                                <label><i class="fas fa-align-left"></i> Description</label>
                                <textarea name="special_description" id="special_description" rows="4" required></textarea>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label><i class="fas fa-sort-numeric-down"></i> Display Order</label>
                                    <input type="number" name="special_display_order" id="special_display_order" value="0">
                                </div>
                                <div class="form-group">
                                    <label><i class="fas fa-icons"></i> Icon</label>
                                    <div class="icon-picker">
                                        <div class="icon-preview-lg" id="specialIconPreview">
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <input type="text" name="icon_class" id="special_icon_class" placeholder="fas fa-star" style="flex: 1;">
                                    </div>
                                    <div class="icon-suggestions">
                                        <div class="icon-suggestion" onclick="setSpecialIcon('fas fa-globe')"><i class="fas fa-globe"></i></div>
                                        <div class="icon-suggestion" onclick="setSpecialIcon('fas fa-robot')"><i class="fas fa-robot"></i></div>
                                        <div class="icon-suggestion" onclick="setSpecialIcon('fas fa-music')"><i class="fas fa-music"></i></div>
                                        <div class="icon-suggestion" onclick="setSpecialIcon('fas fa-heart')"><i class="fas fa-heart"></i></div>
                                        <div class="icon-suggestion" onclick="setSpecialIcon('fas fa-palette')"><i class="fas fa-palette"></i></div>
                                        <div class="icon-suggestion" onclick="setSpecialIcon('fas fa-dove')"><i class="fas fa-dove"></i></div>
                                        <div class="icon-suggestion" onclick="setSpecialIcon('fas fa-hand-holding-heart')"><i class="fas fa-hand-holding-heart"></i></div>
                                        <div class="icon-suggestion" onclick="setSpecialIcon('fas fa-seedling')"><i class="fas fa-seedling"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn-secondary" onclick="closeSpecialModal()">Cancel</button>
                            <button type="submit" name="save_special" class="btn-primary">Save Program</button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // ============================================
        // ACADEMIC PROGRAM MODAL
        // ============================================
        function openProgramModal() {
            document.getElementById('programModal').classList.add('active');
            document.getElementById('programModalTitle').innerText = 'Add New Program';
            document.getElementById('program_id').value = '';
            document.getElementById('level_id').value = '';
            document.getElementById('title').value = '';
            document.getElementById('description').value = '';
            document.getElementById('features').value = '';
            document.getElementById('display_order').value = '0';
            document.getElementById('icon_class').value = 'fas fa-graduation-cap';
            document.getElementById('iconPreview').innerHTML = '<i class="fas fa-graduation-cap"></i>';
        }

        function closeProgramModal() {
            document.getElementById('programModal').classList.remove('active');
        }

        function editProgram(program) {
            document.getElementById('programModal').classList.add('active');
            document.getElementById('programModalTitle').innerText = 'Edit Program';
            document.getElementById('program_id').value = program.id;
            document.getElementById('level_id').value = program.level_id;
            document.getElementById('title').value = program.title;
            document.getElementById('description').value = program.description;
            
            // Decode features JSON
            if (program.features) {
                let features = JSON.parse(program.features);
                document.getElementById('features').value = features.join('\n');
            }
            
            document.getElementById('display_order').value = program.display_order;
            document.getElementById('icon_class').value = program.icon_class || 'fas fa-graduation-cap';
            document.getElementById('iconPreview').innerHTML = '<i class="' + (program.icon_class || 'fas fa-graduation-cap') + '"></i>';
        }

        function setIcon(iconClass) {
            document.getElementById('icon_class').value = iconClass;
            document.getElementById('iconPreview').innerHTML = '<i class="' + iconClass + '"></i>';
        }

        // ============================================
        // SPECIAL PROGRAM MODAL
        // ============================================
        function openSpecialModal() {
            document.getElementById('specialModal').classList.add('active');
            document.getElementById('specialModalTitle').innerText = 'Add New Special Program';
            document.getElementById('special_id').value = '';
            document.getElementById('special_title').value = '';
            document.getElementById('special_description').value = '';
            document.getElementById('special_display_order').value = '0';
            document.getElementById('special_icon_class').value = 'fas fa-star';
            document.getElementById('specialIconPreview').innerHTML = '<i class="fas fa-star"></i>';
        }

        function closeSpecialModal() {
            document.getElementById('specialModal').classList.remove('active');
        }

        function editSpecial(program) {
            document.getElementById('specialModal').classList.add('active');
            document.getElementById('specialModalTitle').innerText = 'Edit Special Program';
            document.getElementById('special_id').value = program.id;
            document.getElementById('special_title').value = program.title;
            document.getElementById('special_description').value = program.description;
            document.getElementById('special_display_order').value = program.display_order;
            document.getElementById('special_icon_class').value = program.icon_class || 'fas fa-star';
            document.getElementById('specialIconPreview').innerHTML = '<i class="' + (program.icon_class || 'fas fa-star') + '"></i>';
        }

        function setSpecialIcon(iconClass) {
            document.getElementById('special_icon_class').value = iconClass;
            document.getElementById('specialIconPreview').innerHTML = '<i class="' + iconClass + '"></i>';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const programModal = document.getElementById('programModal');
            const specialModal = document.getElementById('specialModal');
            if (event.target === programModal) closeProgramModal();
            if (event.target === specialModal) closeSpecialModal();
        }
    </script>
</body>
</html>