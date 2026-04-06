<?php
// admin/manage-newsletters.php - COMPLETE with manual toggle
require_once '../includes/auth.php';
requireAdmin();
require_once '../includes/db.php';

$message = '';

// ============================================
// MANUAL TOGGLE FOR COMING SOON
// ============================================
if(isset($_GET['enable_coming_soon'])) {
    $pdo->prepare("UPDATE school_settings SET setting_value = '1' WHERE setting_key = 'newsletter_coming_soon'")->execute();
    $message = "🔴 Coming Soon page enabled. Newsletters are now hidden.";
    header("Location: manage-newsletters.php");
    exit();
}

if(isset($_GET['disable_coming_soon'])) {
    $pdo->prepare("UPDATE school_settings SET setting_value = '0' WHERE setting_key = 'newsletter_coming_soon'")->execute();
    $message = "🟢 Coming Soon page disabled. Newsletters are now visible.";
    header("Location: manage-newsletters.php");
    exit();
}

// ============================================
// UPLOAD NEWSLETTER
// ============================================
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload_newsletter'])) {
    $title = $_POST['title'];
    $issue_number = $_POST['issue_number'];
    $month = $_POST['month'];
    $year = $_POST['year'];
    $summary = $_POST['summary'];
    $status = $_POST['status'];
    
    $pdf_url = '';
    if(isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] == 0) {
        $upload_dir = '../uploads/newsletters/';
        if(!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        
        $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9]/', '_', $_FILES['pdf_file']['name']);
        if(move_uploaded_file($_FILES['pdf_file']['tmp_name'], $upload_dir . $filename)) {
            $pdf_url = 'uploads/newsletters/' . $filename;
            
            // Auto-disable coming soon when first newsletter is uploaded
            $count = $pdo->query("SELECT COUNT(*) FROM newsletters")->fetchColumn();
            if($count == 0) {
                $pdo->prepare("UPDATE school_settings SET setting_value = '0' WHERE setting_key = 'newsletter_coming_soon'")->execute();
            }
        }
    }
    
    $stmt = $pdo->prepare("INSERT INTO newsletters (title, issue_number, month, year, pdf_url, summary, status, published_date) VALUES (?, ?, ?, ?, ?, ?, ?, CURDATE())");
    $stmt->execute([$title, $issue_number, $month, $year, $pdf_url, $summary, $status]);
    $message = "✅ Newsletter uploaded successfully!";
}

// ============================================
// DELETE NEWSLETTER
// ============================================
if(isset($_GET['delete'])) {
    $stmt = $pdo->prepare("SELECT pdf_url FROM newsletters WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    $newsletter = $stmt->fetch();
    if($newsletter && $newsletter['pdf_url'] && file_exists('../' . $newsletter['pdf_url'])) {
        unlink('../' . $newsletter['pdf_url']);
    }
    
    $stmt = $pdo->prepare("DELETE FROM newsletters WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    
    // Check if any newsletters remain
    $count = $pdo->query("SELECT COUNT(*) FROM newsletters WHERE status='published'")->fetchColumn();
    if($count == 0) {
        $pdo->prepare("UPDATE school_settings SET setting_value = '1' WHERE setting_key = 'newsletter_coming_soon'")->execute();
        $message = "Newsletter deleted. Coming Soon page re-enabled (no newsletters available).";
    } else {
        $message = "Newsletter deleted successfully!";
    }
}

// ============================================
// TOGGLE STATUS
// ============================================
if(isset($_GET['toggle_status'])) {
    $stmt = $pdo->prepare("UPDATE newsletters SET status = IF(status='published', 'draft', 'published') WHERE id = ?");
    $stmt->execute([$_GET['toggle_status']]);
    $message = "Status updated!";
}

// ============================================
// FETCH DATA
// ============================================
$newsletters = $pdo->query("SELECT * FROM newsletters ORDER BY year DESC, published_date DESC")->fetchAll();
$coming_soon = $pdo->query("SELECT setting_value FROM school_settings WHERE setting_key = 'newsletter_coming_soon'")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Newsletters - Bethel School</title>
    <link rel="stylesheet" href="../css/admin-style.css">
    <style>
        .toggle-control {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            border-left: 4px solid <?php echo $coming_soon == '1' ? '#ffc107' : '#28a745'; ?>;
        }
        .toggle-status {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .status-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
        }
        .status-active { background-color: #dc3545; box-shadow: 0 0 5px #dc3545; }
        .status-inactive { background-color: #28a745; box-shadow: 0 0 5px #28a745; }
        .btn-toggle-on { background: #dc3545; color: white; }
        .btn-toggle-off { background: #28a745; color: white; }
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
                <h1>Manage Newsletters</h1>
                <p>Upload, manage, and control newsletter visibility</p>
            </div>
            
            <?php if($message): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <!-- Toggle Control Panel -->
            <div class="toggle-control">
                <div class="toggle-status">
                    <?php if($coming_soon == '1'): ?>
                        <span class="status-indicator status-active"></span>
                        <strong>🔴 Coming Soon Mode: ACTIVE</strong>
                        <span style="color: #666; font-size: 0.85rem;">(Visitors see "Coming Soon" page)</span>
                    <?php else: ?>
                        <span class="status-indicator status-inactive"></span>
                        <strong>🟢 Coming Soon Mode: INACTIVE</strong>
                        <span style="color: #666; font-size: 0.85rem;">(Visitors see newsletters)</span>
                    <?php endif; ?>
                </div>
                <div>
                    <?php if($coming_soon == '1'): ?>
                        <a href="?disable_coming_soon=1" class="btn-toggle-off" style="padding: 8px 16px; border-radius: 5px; text-decoration: none; font-weight: 500;">
                            📄 Show Newsletters Instead
                        </a>
                    <?php else: ?>
                        <a href="?enable_coming_soon=1" class="btn-toggle-on" style="padding: 8px 16px; border-radius: 5px; text-decoration: none; font-weight: 500;">
                            🔧 Enable Coming Soon Page
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Upload Form -->
            <div class="form-container">
                <h2>📤 Upload New Newsletter</h2>
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Title *</label>
                            <input type="text" name="title" required placeholder="e.g., March 2026 Newsletter">
                        </div>
                        <div class="form-group">
                            <label>Issue Number</label>
                            <input type="text" name="issue_number" placeholder="Vol. 1, Issue 1">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Month *</label>
                            <select name="month" required>
                                <option>January</option><option>February</option><option>March</option>
                                <option>April</option><option>May</option><option>June</option>
                                <option>July</option><option>August</option><option>September</option>
                                <option>October</option><option>November</option><option>December</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Year *</label>
                            <input type="number" name="year" value="<?php echo date('Y'); ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Summary / Description</label>
                        <textarea name="summary" rows="3" placeholder="Brief description of this newsletter issue..."></textarea>
                    </div>
                    <div class="form-group">
                        <label>PDF File *</label>
                        <input type="file" name="pdf_file" accept=".pdf" required>
                        <small>Upload PDF version of the newsletter</small>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status">
                            <option value="published">Published</option>
                            <option value="draft">Draft</option>
                        </select>
                    </div>
                    <button type="submit" name="upload_newsletter" class="btn-primary">Upload Newsletter</button>
                </form>
            </div>
            
            <!-- Newsletters List -->
            <div class="data-table-container">
                <h2>📚 Published Newsletters</h2>
                <?php if(count($newsletters) > 0): ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Issue</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($newsletters as $nl): ?>
                            <tr>
                                <td><?php echo $nl['id']; ?>;</td>
                                <td><?php echo htmlspecialchars($nl['title']); ?>;</td>
                                <td><?php echo $nl['issue_number']; ?>;</td>
                                <td><?php echo $nl['month'] . ' ' . $nl['year']; ?>;</td>
                                <td><span class="status-badge status-<?php echo $nl['status']; ?>"><?php echo $nl['status']; ?></span>;</td>
                                <td class="actions">
                                    <?php if($nl['pdf_url']): ?>
                                        <a href="../<?php echo $nl['pdf_url']; ?>" target="_blank" class="btn-edit">View PDF</a>
                                    <?php endif; ?>
                                    <a href="?toggle_status=<?php echo $nl['id']; ?>" class="btn-toggle">Toggle</a>
                                    <a href="?delete=<?php echo $nl['id']; ?>" class="btn-delete" onclick="return confirm('Delete this newsletter?')">Delete</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p style="text-align: center; padding: 40px;">No newsletters uploaded yet. Use the form above to add your first newsletter.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>