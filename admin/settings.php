<?php
// admin/settings.php - General settings page
require_once '../includes/auth.php';
requireAdmin();
require_once '../includes/db.php';

$message = '';

// Update settings
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newsletter_mode = $_POST['newsletter_mode'];
    $calendar_mode = $_POST['calendar_mode'];
    
    $pdo->prepare("UPDATE school_settings SET setting_value = ? WHERE setting_key = 'newsletter_coming_soon'")->execute([$newsletter_mode]);
    $pdo->prepare("UPDATE school_settings SET setting_value = ? WHERE setting_key = 'calendar_coming_soon'")->execute([$calendar_mode]);
    $message = "Settings updated!";
}

$newsletter_mode = $pdo->query("SELECT setting_value FROM school_settings WHERE setting_key = 'newsletter_coming_soon'")->fetchColumn();
$calendar_mode = $pdo->query("SELECT setting_value FROM school_settings WHERE setting_key = 'calendar_coming_soon'")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Bethel School</title>
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
                <h1>Website Settings</h1>
            </div>
            
            <?php if($message): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <div class="form-container">
                <h2>Coming Soon Page Controls</h2>
                <form method="POST">
                    <div class="form-group">
                        <label>📰 Newsletter Section</label>
                        <select name="newsletter_mode" class="form-control">
                            <option value="1" <?php echo $newsletter_mode == '1' ? 'selected' : ''; ?>>🔴 Show Coming Soon Page</option>
                            <option value="0" <?php echo $newsletter_mode == '0' ? 'selected' : ''; ?>>🟢 Show Newsletters (if available)</option>
                        </select>
                        <small>When "Show Coming Soon" is selected, visitors will see the coming soon page even if newsletters exist.</small>
                    </div>
                    
                    <div class="form-group">
                        <label>📅 Academic Calendar Section</label>
                        <select name="calendar_mode" class="form-control">
                            <option value="1" <?php echo $calendar_mode == '1' ? 'selected' : ''; ?>>🔴 Show Coming Soon Page</option>
                            <option value="0" <?php echo $calendar_mode == '0' ? 'selected' : ''; ?>>🟢 Show Calendar (if available)</option>
                        </select>
                        <small>When "Show Coming Soon" is selected, visitors will see the coming soon page even if calendar PDFs exist.</small>
                    </div>
                    
                    <button type="submit" class="btn-primary">Save Settings</button>
                </form>
            </div>
            
            <div class="info-box" style="margin-top: 20px; padding: 15px; background: #e8f4f8; border-radius: 8px;">
                <h3>💡 How it works:</h3>
                <ul style="margin-left: 20px; margin-top: 10px;">
                    <li><strong>Coming Soon ON</strong> = Visitors see the "Coming Soon" page</li>
                    <li><strong>Coming Soon OFF</strong> = Visitors see actual content (newsletters or calendar)</li>
                    <li>You can toggle this anytime without deleting your uploaded files</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>