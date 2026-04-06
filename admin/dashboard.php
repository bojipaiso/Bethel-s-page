<?php
// admin/dashboard.php - BETHEL BLUE THEME with improved layout
require_once '../includes/auth.php';
requireAdmin();
require_once '../includes/db.php';

// ============================================
// HANDLE QUICK TOGGLES FROM DASHBOARD
// ============================================

// Toggle Newsletter Coming Soon
if(isset($_GET['toggle_newsletter'])) {
    $current = $pdo->query("SELECT setting_value FROM school_settings WHERE setting_key = 'newsletter_coming_soon'")->fetchColumn();
    $new_value = ($current == '1') ? '0' : '1';
    $pdo->prepare("UPDATE school_settings SET setting_value = ? WHERE setting_key = 'newsletter_coming_soon'")->execute([$new_value]);
    header("Location: dashboard.php");
    exit();
}

// Toggle Calendar Coming Soon
if(isset($_GET['toggle_calendar'])) {
    $current = $pdo->query("SELECT setting_value FROM school_settings WHERE setting_key = 'calendar_coming_soon'")->fetchColumn();
    $new_value = ($current == '1') ? '0' : '1';
    $pdo->prepare("UPDATE school_settings SET setting_value = ? WHERE setting_key = 'calendar_coming_soon'")->execute([$new_value]);
    header("Location: dashboard.php");
    exit();
}

// ============================================
// FETCH STATISTICS
// ============================================

$announcementCount = $pdo->query("SELECT COUNT(*) FROM announcements WHERE status='active'")->fetchColumn();
$featureCount = $pdo->query("SELECT COUNT(*) FROM features WHERE status='active'")->fetchColumn();
$newsCount = $pdo->query("SELECT COUNT(*) FROM news_articles WHERE status='published'")->fetchColumn();
$unreadMessages = $pdo->query("SELECT COUNT(*) FROM contact_messages WHERE status='unread'")->fetchColumn();
$newsletterCount = $pdo->query("SELECT COUNT(*) FROM newsletters WHERE status='published'")->fetchColumn();
$calendarCount = $pdo->query("SELECT COUNT(*) FROM calendar_pdfs WHERE status='active'")->fetchColumn();

// Get Coming Soon settings
$newsletter_coming_soon = $pdo->query("SELECT setting_value FROM school_settings WHERE setting_key = 'newsletter_coming_soon'")->fetchColumn();
$calendar_coming_soon = $pdo->query("SELECT setting_value FROM school_settings WHERE setting_key = 'calendar_coming_soon'")->fetchColumn();

// Get recent messages
$recent_messages = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Bethel School</title>
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
            --light-bg: #f8fafc;
        }

        body {
            background: var(--light-bg);
        }

        .admin-wrapper {
            min-height: 100vh;
        }

        /* Navigation - Bethel Blue */
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
            font-size: 1.5rem;
            font-weight: bold;
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
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px 20px;
        }

        /* Dashboard Header */
        .dashboard-header {
            margin-bottom: 30px;
        }

        .dashboard-header h1 {
            color: var(--primary-color);
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .dashboard-header p {
            color: #666;
        }

        /* Statistics Cards - Bethel Blue */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 35, 102, 0.08);
            transition: transform 0.3s, box-shadow 0.3s;
            border-top: 3px solid var(--accent-color);
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(0, 35, 102, 0.12);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 5px;
        }

        .stat-label {
            color: #666;
            font-size: 0.85rem;
            margin-bottom: 10px;
        }

        .stat-link {
            display: inline-block;
            color: var(--secondary-color);
            text-decoration: none;
            font-size: 0.8rem;
            font-weight: 500;
            transition: color 0.3s;
        }

        .stat-link:hover {
            color: var(--primary-color);
        }

        /* Section Titles */
        .section-title {
            font-size: 1.2rem;
            margin: 30px 0 15px;
            color: var(--primary-color);
            border-left: 4px solid var(--accent-color);
            padding-left: 12px;
        }

        /* Coming Soon Toggles - Bethel Blue Theme */
        .toggles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .toggle-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 35, 102, 0.08);
            transition: transform 0.3s;
            border: 1px solid rgba(0, 35, 102, 0.1);
        }

        .toggle-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 35, 102, 0.15);
        }

        .toggle-header {
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            border-bottom: 1px solid #eee;
            background: linear-gradient(135deg, rgba(0, 35, 102, 0.02) 0%, rgba(0, 86, 179, 0.02) 100%);
        }

        .toggle-icon {
            width: 55px;
            height: 55px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
        }

        .toggle-icon.newsletter {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
        }

        .toggle-icon.calendar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
        }

        .toggle-title h3 {
            font-size: 1.1rem;
            margin-bottom: 5px;
            color: var(--primary-color);
        }

        .toggle-title p {
            font-size: 0.75rem;
            color: #888;
        }

        .toggle-body {
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .toggle-status-badge {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .status-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            animation: pulse 1.5s infinite;
        }

        .status-dot.active {
            background: #dc3545;
            box-shadow: 0 0 5px #dc3545;
        }

        .status-dot.inactive {
            background: #28a745;
            box-shadow: 0 0 5px #28a745;
            animation: none;
        }

        @keyframes pulse {
            0% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.6; transform: scale(0.9); }
            100% { opacity: 1; transform: scale(1); }
        }

        .status-text {
            font-weight: 500;
            font-size: 0.85rem;
        }

        .status-text.active {
            color: #dc3545;
        }

        .status-text.inactive {
            color: #28a745;
        }

        .toggle-btn {
            padding: 8px 20px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.85rem;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .toggle-btn.disable {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
        }

        .toggle-btn.enable {
            background: #dc3545;
            color: white;
        }

        .toggle-btn:hover {
            transform: scale(1.02);
            opacity: 0.9;
        }

        /* Quick Actions - Bethel Blue */
        .quick-actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }

        .quick-action-card {
            background: white;
            padding: 18px;
            border-radius: 10px;
            text-align: center;
            text-decoration: none;
            color: var(--dark-color);
            transition: all 0.3s;
            border: 1px solid #e0e0e0;
            display: block;
        }

        .quick-action-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 35, 102, 0.1);
            border-color: var(--accent-color);
        }

        .quick-action-card i {
            font-size: 1.8rem;
            color: var(--primary-color);
            margin-bottom: 8px;
        }

        .quick-action-card span {
            display: block;
            font-size: 0.85rem;
            font-weight: 500;
        }

        /* Messages Section - Bethel Blue */
        .messages-section {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 35, 102, 0.08);
            border: 1px solid rgba(0, 35, 102, 0.1);
        }

        .messages-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .messages-header h3 {
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .unread-badge {
            background: #dc3545;
            color: white;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
        }

        .message-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            transition: background 0.2s;
        }

        .message-item:hover {
            background: #f8f9fa;
        }

        .message-item.unread {
            background: #fff8e1;
            border-left: 3px solid var(--accent-color);
        }

        .message-info {
            flex: 1;
        }

        .message-name {
            font-weight: 600;
            color: var(--primary-color);
        }

        .message-date {
            font-size: 0.7rem;
            color: #999;
            margin-left: 10px;
        }

        .message-preview {
            font-size: 0.8rem;
            color: #666;
            margin-top: 4px;
        }

        .message-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .message-status {
            font-size: 0.7rem;
            padding: 3px 10px;
            border-radius: 20px;
            background: #dc3545;
            color: white;
        }

        .message-status.read {
            background: #28a745;
        }

        .view-link {
            color: var(--secondary-color);
            text-decoration: none;
            font-size: 0.8rem;
        }

        .view-link:hover {
            text-decoration: underline;
        }

        .no-messages {
            text-align: center;
            padding: 40px;
            color: #999;
        }

        /* Footer Stats - Bethel Blue */
        .footer-stats {
            margin-top: 30px;
            padding: 15px 20px;
            background: white;
            border-radius: 10px;
            text-align: center;
            font-size: 0.8rem;
            color: #666;
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
            border: 1px solid rgba(0, 35, 102, 0.1);
        }

        .footer-stats i {
            color: var(--accent-color);
            margin-right: 5px;
        }

        @media (max-width: 768px) {
            .toggle-body {
                flex-direction: column;
                text-align: center;
            }
            .message-item {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }
            .footer-stats {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
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
            <!-- Dashboard Header -->
            <div class="dashboard-header">
                <h1>Dashboard</h1>
                <p>Welcome to your website management panel. Manage content, view statistics, and control site settings.</p>
            </div>
            
            <!-- Statistics Cards -->
            <div class="stats-grid">
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
                    <div class="stat-number"><?php echo $newsCount; ?></div>
                    <div class="stat-label">Published Articles</div>
                    <a href="manage-news.php" class="stat-link">Manage →</a>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number"><?php echo $newsletterCount; ?></div>
                    <div class="stat-label">Newsletters</div>
                    <a href="manage-newsletters.php" class="stat-link">Manage →</a>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number"><?php echo $calendarCount; ?></div>
                    <div class="stat-label">Calendar PDFs</div>
                    <a href="manage-calendar.php" class="stat-link">Manage →</a>
                </div>
            </div>
            
            <!-- Coming Soon Toggles - Bethel Blue Theme -->
            <h2 class="section-title">🔧 Coming Soon Controls</h2>
            <div class="toggles-grid">
                <!-- Newsletter Toggle -->
                <div class="toggle-card">
                    <div class="toggle-header">
                        <div class="toggle-icon newsletter">
                            <i class="fas fa-newspaper"></i>
                        </div>
                        <div class="toggle-title">
                            <h3>Newsletter Section</h3>
                            <p>Control visitor access to newsletters</p>
                        </div>
                    </div>
                    <div class="toggle-body">
                        <div class="toggle-status-badge">
                            <?php if($newsletter_coming_soon == '1'): ?>
                                <span class="status-dot active"></span>
                                <span class="status-text active">🔴 Coming Soon Mode: ACTIVE</span>
                            <?php else: ?>
                                <span class="status-dot inactive"></span>
                                <span class="status-text inactive">🟢 Coming Soon Mode: INACTIVE</span>
                            <?php endif; ?>
                        </div>
                        <div>
                            <?php if($newsletter_coming_soon == '1'): ?>
                                <a href="?toggle_newsletter=1" class="toggle-btn disable">
                                    <i class="fas fa-eye"></i> Show Newsletters
                                </a>
                            <?php else: ?>
                                <a href="?toggle_newsletter=1" class="toggle-btn enable">
                                    <i class="fas fa-eye-slash"></i> Enable Coming Soon
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Calendar Toggle -->
                <div class="toggle-card">
                    <div class="toggle-header">
                        <div class="toggle-icon calendar">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="toggle-title">
                            <h3>Calendar Section</h3>
                            <p>Control visitor access to calendar PDFs</p>
                        </div>
                    </div>
                    <div class="toggle-body">
                        <div class="toggle-status-badge">
                            <?php if($calendar_coming_soon == '1'): ?>
                                <span class="status-dot active"></span>
                                <span class="status-text active">🔴 Coming Soon Mode: ACTIVE</span>
                            <?php else: ?>
                                <span class="status-dot inactive"></span>
                                <span class="status-text inactive">🟢 Coming Soon Mode: INACTIVE</span>
                            <?php endif; ?>
                        </div>
                        <div>
                            <?php if($calendar_coming_soon == '1'): ?>
                                <a href="?toggle_calendar=1" class="toggle-btn disable">
                                    <i class="fas fa-eye"></i> Show Calendar
                                </a>
                            <?php else: ?>
                                <a href="?toggle_calendar=1" class="toggle-btn enable">
                                    <i class="fas fa-eye-slash"></i> Enable Coming Soon
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <h2 class="section-title">⚡ Quick Actions</h2>
            <div class="quick-actions-grid">
                <a href="manage-announcements.php?action=add" class="quick-action-card">
                    <i class="fas fa-plus-circle"></i>
                    <span>Add Announcement</span>
                </a>
                <a href="manage-features.php?action=add" class="quick-action-card">
                    <i class="fas fa-plus-circle"></i>
                    <span>Add Feature</span>
                </a>
                <a href="edit-news.php" class="quick-action-card">
                    <i class="fas fa-newspaper"></i>
                    <span>Write Article</span>
                </a>
                <a href="manage-hero.php" class="quick-action-card">
                    <i class="fas fa-image"></i>
                    <span>Edit Hero</span>
                </a>
                <a href="manage-about.php" class="quick-action-card">
                    <i class="fas fa-info-circle"></i>
                    <span>Edit About Us</span>
                </a>
                <a href="manage-academics.php" class="quick-action-card">
                    <i class="fas fa-graduation-cap"></i>
                    <span>Academics</span>
                </a>
                <a href="manage-calendar.php" class="quick-action-card">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Calendar</span>
                </a>
                <a href="manage-newsletters.php" class="quick-action-card">
                    <i class="fas fa-envelope-open-text"></i>
                    <span>Newsletter</span>
                </a>
            </div>
            
            <!-- Recent Contact Messages -->
            <h2 class="section-title">📬 Contact Messages</h2>
            <div class="messages-section">
                <div class="messages-header">
                    <h3>
                        <i class="fas fa-envelope"></i> Recent Messages
                        <?php if($unreadMessages > 0): ?>
                            <span class="unread-badge"><?php echo $unreadMessages; ?> Unread</span>
                        <?php endif; ?>
                    </h3>
                    <a href="manage-messages.php" style="color: white; font-size: 0.85rem;">View All →</a>
                </div>
                
                <?php if(count($recent_messages) > 0): ?>
                    <?php foreach($recent_messages as $msg): ?>
                        <div class="message-item <?php echo $msg['status'] == 'unread' ? 'unread' : ''; ?>">
                            <div class="message-info">
                                <span class="message-name"><?php echo htmlspecialchars($msg['name']); ?></span>
                                <span class="message-date"><?php echo date('M d, Y H:i', strtotime($msg['created_at'])); ?></span>
                                <div class="message-preview">
                                    <?php echo htmlspecialchars(substr($msg['message'], 0, 80)); ?>...
                                </div>
                            </div>
                            <div class="message-actions">
                                <?php if($msg['status'] == 'unread'): ?>
                                    <span class="message-status">Unread</span>
                                <?php else: ?>
                                    <span class="message-status read">Read</span>
                                <?php endif; ?>
                                <a href="view-message.php?id=<?php echo $msg['id']; ?>" class="view-link">View →</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-messages">
                        <i class="fas fa-inbox" style="font-size: 2rem; color: #ccc; margin-bottom: 10px; display: block;"></i>
                        <p>No messages yet. Contact form submissions will appear here.</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Footer Stats -->
            <div class="footer-stats">
                <span><i class="fas fa-bullhorn"></i> Announcements: <?php echo $announcementCount; ?></span>
                <span><i class="fas fa-star"></i> Features: <?php echo $featureCount; ?></span>
                <span><i class="fas fa-newspaper"></i> Articles: <?php echo $newsCount; ?></span>
                <span><i class="fas fa-envelope"></i> Unread: <?php echo $unreadMessages; ?></span>
                <span><i class="fas fa-calendar"></i> Calendars: <?php echo $calendarCount; ?></span>
                <span><i class="fas fa-file-pdf"></i> Newsletters: <?php echo $newsletterCount; ?></span>
            </div>
        </div>
    </div>
</body>
</html>