<?php
// admin/manage-admissions.php - FULL CONTENT VISIBLE ON CARDS
require_once '../includes/auth.php';
requireAdmin();
require_once '../includes/db.php';

$message = '';
$error = '';

// ============================================
// HANDLE FORM SUBMISSIONS
// ============================================
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Update Content Sections
    if (isset($_POST['update_welcome'])) {
        $title = trim($_POST['title']);
        $content = trim($_POST['content']);
        $stmt = $pdo->prepare("UPDATE admissions_content SET title = ?, content = ? WHERE section = 'welcome'");
        if ($stmt->execute([$title, $content])) {
            $message = "✅ Welcome section updated successfully!";
        } else {
            $error = "❌ Failed to update welcome section.";
        }
        header("Location: manage-admissions.php");
        exit();
    }
    
    if (isset($_POST['update_requirements'])) {
        $title = trim($_POST['title']);
        $content = trim($_POST['content']);
        $stmt = $pdo->prepare("UPDATE admissions_content SET title = ?, content = ? WHERE section = 'requirements'");
        if ($stmt->execute([$title, $content])) {
            $message = "✅ Requirements updated successfully!";
        } else {
            $error = "❌ Failed to update requirements.";
        }
        header("Location: manage-admissions.php");
        exit();
    }
    
    if (isset($_POST['update_enrollment'])) {
        $title = trim($_POST['title']);
        $content = trim($_POST['content']);
        $stmt = $pdo->prepare("UPDATE admissions_content SET title = ?, content = ? WHERE section = 'enrollment_period'");
        if ($stmt->execute([$title, $content])) {
            $message = "✅ Enrollment period updated successfully!";
        } else {
            $error = "❌ Failed to update enrollment period.";
        }
        header("Location: manage-admissions.php");
        exit();
    }
    
    if (isset($_POST['update_classes'])) {
        $title = trim($_POST['title']);
        $content = trim($_POST['content']);
        $stmt = $pdo->prepare("UPDATE admissions_content SET title = ?, content = ? WHERE section = 'classes_start'");
        if ($stmt->execute([$title, $content])) {
            $message = "✅ Classes start date updated successfully!";
        } else {
            $error = "❌ Failed to update classes start date.";
        }
        header("Location: manage-admissions.php");
        exit();
    }
    
    // Add/Edit Admission Step
    if (isset($_POST['save_step'])) {
        $id = isset($_POST['step_id']) ? intval($_POST['step_id']) : 0;
        $step_number = intval($_POST['step_number']);
        $title = trim($_POST['step_title']);
        $description = trim($_POST['step_description']);
        $display_order = intval($_POST['step_display_order']);
        
        if ($id > 0) {
            $stmt = $pdo->prepare("UPDATE admission_steps SET step_number = ?, title = ?, description = ?, display_order = ? WHERE id = ?");
            if ($stmt->execute([$step_number, $title, $description, $display_order, $id])) {
                $message = "✅ Admission step updated successfully!";
            } else {
                $error = "❌ Failed to update admission step.";
            }
        } else {
            $stmt = $pdo->prepare("INSERT INTO admission_steps (step_number, title, description, display_order) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$step_number, $title, $description, $display_order])) {
                $message = "✅ Admission step added successfully!";
            } else {
                $error = "❌ Failed to add admission step.";
            }
        }
        header("Location: manage-admissions.php");
        exit();
    }
}

// ============================================
// HANDLE DELETE ACTIONS
// ============================================
if (isset($_GET['delete_step'])) {
    $id = intval($_GET['delete_step']);
    $stmt = $pdo->prepare("DELETE FROM admission_steps WHERE id = ?");
    if ($stmt->execute([$id])) {
        $message = "✅ Admission step deleted successfully!";
    } else {
        $error = "❌ Failed to delete admission step.";
    }
    header("Location: manage-admissions.php");
    exit();
}

// ============================================
// FETCH ALL DATA
// ============================================
$welcome = $pdo->query("SELECT * FROM admissions_content WHERE section = 'welcome'")->fetch();
$requirements = $pdo->query("SELECT * FROM admissions_content WHERE section = 'requirements'")->fetch();
$enrollment = $pdo->query("SELECT * FROM admissions_content WHERE section = 'enrollment_period'")->fetch();
$classes = $pdo->query("SELECT * FROM admissions_content WHERE section = 'classes_start'")->fetch();
$steps = $pdo->query("SELECT * FROM admission_steps ORDER BY display_order ASC")->fetchAll();

// Get next display order
$next_order = $pdo->query("SELECT COALESCE(MAX(display_order), 0) + 1 as next FROM admission_steps")->fetch();
$next_display_order = $next_order['next'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Admissions - Bethel School</title>
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
            max-width: 1200px;
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

        /* Main Content Sections - Welcome & Requirements */
        .main-sections {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            margin-bottom: 30px;
        }

        .main-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--gray-border);
            transition: transform 0.3s;
        }

        .main-card:hover {
            transform: translateY(-2px);
        }

        .card-header {
            padding: 18px 20px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-header i {
            font-size: 1.3rem;
            color: var(--accent-color);
        }

        .card-header h3 {
            font-size: 1rem;
            font-weight: 600;
        }

        .card-body {
            padding: 20px;
        }

        .content-preview {
            background: var(--gray-light);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 0.85rem;
            color: #666;
            max-height: 250px;
            overflow-y: auto;
            white-space: pre-wrap;
            line-height: 1.6;
        }

        .content-preview strong {
            color: var(--primary-color);
            display: block;
            margin-bottom: 10px;
            font-size: 1rem;
        }

        .content-preview ul {
            margin-left: 20px;
            margin-top: 8px;
        }

        .content-preview li {
            margin-bottom: 5px;
        }

        .edit-btn {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s;
        }

        .edit-btn:hover {
            background: var(--secondary-color);
            transform: translateY(-1px);
        }

        /* Simple Info List (Enrollment & Classes) */
        .info-list {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--gray-border);
            margin-bottom: 30px;
        }

        .info-list-header {
            padding: 18px 20px;
            background: var(--gray-light);
            border-bottom: 1px solid var(--gray-border);
        }

        .info-list-header h3 {
            font-size: 1rem;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-items {
            display: flex;
            flex-direction: column;
        }

        .info-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 20px;
            border-bottom: 1px solid var(--gray-border);
            transition: background 0.2s;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-item:hover {
            background: var(--gray-light);
        }

        .info-label {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            color: var(--primary-color);
        }

        .info-label i {
            color: var(--accent-color);
            width: 25px;
        }

        .info-value {
            color: #666;
            flex: 1;
            margin-left: 20px;
        }

        .info-value strong {
            color: var(--primary-color);
        }

        .small-edit-btn {
            background: none;
            border: none;
            color: var(--secondary-color);
            cursor: pointer;
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 10px;
            border-radius: 5px;
            transition: all 0.2s;
        }

        .small-edit-btn:hover {
            background: var(--gray-light);
            color: var(--primary-color);
        }

        /* Steps Section */
        .steps-section {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--gray-border);
            margin-top: 20px;
        }

        .steps-header {
            padding: 18px 20px;
            background: var(--gray-light);
            border-bottom: 1px solid var(--gray-border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .steps-header h3 {
            font-size: 1rem;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .add-btn {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
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

        .steps-table {
            width: 100%;
            border-collapse: collapse;
        }

        .steps-table th {
            background: var(--gray-light);
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            color: var(--primary-color);
            border-bottom: 1px solid var(--gray-border);
            font-size: 0.8rem;
        }

        .steps-table td {
            padding: 12px 15px;
            border-bottom: 1px solid var(--gray-border);
            vertical-align: middle;
            font-size: 0.85rem;
        }

        .steps-table tr:hover {
            background: rgba(0, 35, 102, 0.02);
        }

        .step-number-badge {
            display: inline-block;
            width: 28px;
            height: 28px;
            background: var(--primary-color);
            color: var(--accent-color);
            border-radius: 50%;
            text-align: center;
            line-height: 28px;
            font-weight: bold;
            font-size: 0.8rem;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
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
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
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
            position: sticky;
            top: 0;
        }

        .modal-header h2 {
            font-size: 1.2rem;
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
            position: sticky;
            bottom: 0;
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

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .help-text {
            font-size: 0.7rem;
            color: #666;
            margin-top: 5px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
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

        @media (max-width: 992px) {
            .main-sections {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
            .steps-table {
                display: block;
                overflow-x: auto;
            }
            .info-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            .info-value {
                margin-left: 35px;
            }
            .small-edit-btn {
                align-self: flex-end;
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
            <h1><i class="fas fa-door-open"></i> Manage Admissions</h1>
        </div>

        <?php if($message): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php if($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- MAIN CONTENT SECTIONS (Welcome & Requirements) - FULL CONTENT VISIBLE -->
        <div class="main-sections">
            <!-- Welcome Section -->
            <div class="main-card">
                <div class="card-header">
                    <i class="fas fa-hand-wave"></i>
                    <h3>Welcome Section</h3>
                </div>
                <div class="card-body">
                    <div class="content-preview">
                        <strong><?php echo htmlspecialchars($welcome['title'] ?? 'Welcome Future Eagles!'); ?></strong>
                        <?php echo nl2br(htmlspecialchars($welcome['content'] ?? 'No content yet.')); ?>
                    </div>
                    <button class="edit-btn" onclick="openContentModal('welcome', '<?php echo htmlspecialchars($welcome['title'] ?? ''); ?>', <?php echo htmlspecialchars(json_encode($welcome['content'] ?? '')); ?>)">
                        <i class="fas fa-edit"></i> Edit Welcome Section
                    </button>
                </div>
            </div>

            <!-- Requirements Section -->
            <div class="main-card">
                <div class="card-header">
                    <i class="fas fa-list-check"></i>
                    <h3>Requirements</h3>
                </div>
                <div class="card-body">
                    <div class="content-preview">
                        <strong><?php echo htmlspecialchars($requirements['title'] ?? 'Requirements for Admission'); ?></strong>
                        <?php 
                            $req_content = $requirements['content'] ?? '';
                            $req_lines = explode("\n", $req_content);
                            if(count($req_lines) > 0 && trim($req_lines[0]) != ''):
                                echo "<ul>";
                                foreach($req_lines as $line) {
                                    if(trim($line)) {
                                        echo "<li>" . htmlspecialchars(trim($line)) . "</li>";
                                    }
                                }
                                echo "</ul>";
                            else:
                                echo "<p>No requirements added yet.</p>";
                            endif;
                        ?>
                    </div>
                    <button class="edit-btn" onclick="openContentModal('requirements', '<?php echo htmlspecialchars($requirements['title'] ?? ''); ?>', <?php echo htmlspecialchars(json_encode($requirements['content'] ?? '')); ?>)">
                        <i class="fas fa-edit"></i> Edit Requirements
                    </button>
                </div>
            </div>
        </div>

        <!-- SIMPLE INFO LIST (Enrollment & Classes) -->
        <div class="info-list">
            <div class="info-list-header">
                <h3><i class="fas fa-info-circle"></i> Key Dates</h3>
            </div>
            <div class="info-items">
                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-calendar-alt"></i>
                        Enrollment Period
                    </div>
                    <div class="info-value">
                        <?php echo nl2br(htmlspecialchars($enrollment['content'] ?? 'March 1 - July 15, 2025')); ?>
                    </div>
                    <button class="small-edit-btn" onclick="openContentModal('enrollment', '<?php echo htmlspecialchars($enrollment['title'] ?? ''); ?>', <?php echo htmlspecialchars(json_encode($enrollment['content'] ?? '')); ?>)">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                </div>
                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-chalkboard"></i>
                        Classes Start
                    </div>
                    <div class="info-value">
                        <?php echo nl2br(htmlspecialchars($classes['content'] ?? 'August 4, 2025')); ?>
                    </div>
                    <button class="small-edit-btn" onclick="openContentModal('classes', '<?php echo htmlspecialchars($classes['title'] ?? ''); ?>', <?php echo htmlspecialchars(json_encode($classes['content'] ?? '')); ?>)">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                </div>
            </div>
        </div>

        <!-- Admission Steps Section -->
        <div class="steps-section">
            <div class="steps-header">
                <h3><i class="fas fa-stairs"></i> Admission Steps</h3>
                <button class="add-btn" onclick="openStepModal()">
                    <i class="fas fa-plus"></i> Add Step
                </button>
            </div>
            <div style="overflow-x: auto;">
                <table class="steps-table">
                    <thead>
                        <tr>
                            <th>Step</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Order</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($steps) > 0): ?>
                            <?php foreach($steps as $step): ?>
                            <tr>
                                <td><span class="step-number-badge"><?php echo $step['step_number']; ?></span></td>
                                <td><strong><?php echo htmlspecialchars($step['title']); ?></strong></td>
                                <td><?php echo htmlspecialchars(substr($step['description'], 0, 80)); ?>...;</td>
                                <td><?php echo $step['display_order']; ?>;</td>
                                <td class="action-buttons">
                                    <button class="btn-edit" onclick="editStep(<?php echo htmlspecialchars(json_encode($step)); ?>)">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <a href="?delete_step=<?php echo $step['id']; ?>" class="btn-delete" onclick="return confirm('Delete this step?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 40px;">
                                    <i class="fas fa-stairs" style="font-size: 2rem; color: #ccc; margin-bottom: 10px; display: block;"></i>
                                    <p>No admission steps added yet.</p>
                                    <button class="add-btn" onclick="openStepModal()" style="margin-top: 10px;">Add First Step</button>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Content Edit Modal -->
    <div id="contentModal" class="modal">
        <div class="modal-content">
            <form method="POST" id="contentForm">
                <div class="modal-header">
                    <h2 id="contentModalTitle">Edit Content</h2>
                    <button type="button" class="modal-close" onclick="closeContentModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label><i class="fas fa-heading"></i> Title</label>
                        <input type="text" name="title" id="content_title" required>
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fas fa-align-left"></i> Content</label>
                        <textarea name="content" id="content_text" rows="8" required></textarea>
                        <div class="help-text" id="content_hint"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeContentModal()">Cancel</button>
                    <button type="submit" name="update_content" id="submitBtn" class="btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Step Modal -->
    <div id="stepModal" class="modal">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h2 id="stepModalTitle">Add Admission Step</h2>
                    <button type="button" class="modal-close" onclick="closeStepModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="step_id" id="step_id" value="">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label><i class="fas fa-hashtag"></i> Step Number</label>
                            <input type="number" name="step_number" id="step_number" required min="1" max="10">
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-sort-numeric-down"></i> Display Order</label>
                            <input type="number" name="step_display_order" id="step_display_order" value="<?php echo $next_display_order; ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fas fa-heading"></i> Step Title</label>
                        <input type="text" name="step_title" id="step_title" required placeholder="e.g., Submit Application">
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fas fa-align-left"></i> Description</label>
                        <textarea name="step_description" id="step_description" rows="4" required placeholder="Describe what happens in this step..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeStepModal()">Cancel</button>
                    <button type="submit" name="save_step" class="btn-primary">Save Step</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // ============================================
        // CONTENT MODAL
        // ============================================
        let currentSection = '';
        
        function openContentModal(section, title, content) {
            const modal = document.getElementById('contentModal');
            const modalTitle = document.getElementById('contentModalTitle');
            const titleInput = document.getElementById('content_title');
            const contentInput = document.getElementById('content_text');
            const submitBtn = document.getElementById('submitBtn');
            const hint = document.getElementById('content_hint');
            
            currentSection = section;
            
            // Decode content
            let decodedContent = content;
            if (typeof content === 'string' && content.startsWith('"')) {
                try {
                    decodedContent = JSON.parse(content);
                } catch(e) {}
            }
            
            if (section === 'welcome') {
                modalTitle.innerText = 'Edit Welcome Section';
                submitBtn.name = 'update_welcome';
                hint.innerHTML = 'Welcome message that appears at the top of the admissions page.';
            } else if (section === 'requirements') {
                modalTitle.innerText = 'Edit Requirements';
                submitBtn.name = 'update_requirements';
                hint.innerHTML = 'Enter each requirement on a new line. They will be displayed as a bullet list.';
            } else if (section === 'enrollment') {
                modalTitle.innerText = 'Edit Enrollment Period';
                submitBtn.name = 'update_enrollment';
                hint.innerHTML = 'Enter the enrollment period dates (e.g., March 1 - July 15, 2025)';
            } else if (section === 'classes') {
                modalTitle.innerText = 'Edit Classes Start Date';
                submitBtn.name = 'update_classes';
                hint.innerHTML = 'Enter the date when classes start (e.g., August 4, 2025)';
            }
            
            titleInput.value = title;
            contentInput.value = decodedContent;
            
            modal.classList.add('active');
        }
        
        function closeContentModal() {
            document.getElementById('contentModal').classList.remove('active');
        }
        
        // ============================================
        // STEP MODAL
        // ============================================
        function openStepModal() {
            const modal = document.getElementById('stepModal');
            const modalTitle = document.getElementById('stepModalTitle');
            
            modalTitle.innerText = 'Add Admission Step';
            document.getElementById('step_id').value = '';
            document.getElementById('step_number').value = '';
            document.getElementById('step_title').value = '';
            document.getElementById('step_description').value = '';
            document.getElementById('step_display_order').value = '<?php echo $next_display_order; ?>';
            
            modal.classList.add('active');
        }
        
        function editStep(step) {
            const modal = document.getElementById('stepModal');
            const modalTitle = document.getElementById('stepModalTitle');
            
            modalTitle.innerText = 'Edit Admission Step';
            document.getElementById('step_id').value = step.id;
            document.getElementById('step_number').value = step.step_number;
            document.getElementById('step_title').value = step.title;
            document.getElementById('step_description').value = step.description;
            document.getElementById('step_display_order').value = step.display_order;
            
            modal.classList.add('active');
        }
        
        function closeStepModal() {
            document.getElementById('stepModal').classList.remove('active');
        }
        
        // Close modals when clicking outside
        window.onclick = function(event) {
            const contentModal = document.getElementById('contentModal');
            const stepModal = document.getElementById('stepModal');
            if (event.target === contentModal) closeContentModal();
            if (event.target === stepModal) closeStepModal();
        }
    </script>
</body>
</html>