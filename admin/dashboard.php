<?php
// admin/dashboard.php
require_once '../includes/auth.php';
requireAdmin();
require_once '../includes/db.php';

// Get counts
$announcementCount = $pdo->query("SELECT COUNT(*) FROM announcements WHERE status='active'")->fetchColumn();
$featureCount = $pdo->query("SELECT COUNT(*) FROM features WHERE status='active'")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Bethel School</title>
    <link rel="stylesheet" href="../css/admin-style.css">
</head>
<body>
    <div class="admin-wrapper">
        <nav class="admin-nav">
            <div class="admin-nav-container">
                <div class="admin-logo">Bethel CMS</div>
                <div class="admin-user">
                    Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?> |
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        </nav>
        
        <div class="admin-container">
            <div class="dashboard-header">
                <h1>Dashboard</h1>
                <p>Manage your website content</p>
            </div>
            
            <div class="dashboard-stats">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $announcementCount; ?></div>
                    <div class="stat-label">Active Announcements</div>
                    <a href="manage-announcements.php" class="stat-link">Manage →</a>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number"><?php echo $featureCount; ?></div>
                    <div class="stat-label">Active Features</div>
                    <a href="manage-features.php" class="stat-link">Manage →</a>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number">1</div>
                    <div class="stat-label">Hero Section</div>
                    <a href="manage-hero.php" class="stat-link">Edit →</a>
                </div>
            </div>
            
            <div class="quick-actions">
                <h3>Quick Actions</h3>
                <div class="action-buttons">
                    <a href="manage-announcements.php?action=add" class="btn-primary">+ Add Announcement</a>
                    <a href="manage-features.php?action=add" class="btn-primary">+ Add Feature</a>
                    <a href="manage-hero.php" class="btn-secondary">Edit Hero Section</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>