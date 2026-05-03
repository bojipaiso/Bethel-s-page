<?php
// admin/dashboard.php - Bethel Blue Theme, no "Manage News" quick action
require_once '../includes/auth.php';
requireAdmin();
require_once '../includes/db.php';

// Toggle Coming Soon modes (unchanged)
if(isset($_GET['toggle_newsletter'])) {
    $current = $pdo->query("SELECT setting_value FROM school_settings WHERE setting_key = 'newsletter_coming_soon'")->fetchColumn();
    $new = ($current == '1') ? '0' : '1';
    $pdo->prepare("UPDATE school_settings SET setting_value = ? WHERE setting_key = 'newsletter_coming_soon'")->execute([$new]);
    header("Location: dashboard.php");
    exit();
}
if(isset($_GET['toggle_calendar'])) {
    $current = $pdo->query("SELECT setting_value FROM school_settings WHERE setting_key = 'calendar_coming_soon'")->fetchColumn();
    $new = ($current == '1') ? '0' : '1';
    $pdo->prepare("UPDATE school_settings SET setting_value = ? WHERE setting_key = 'calendar_coming_soon'")->execute([$new]);
    header("Location: dashboard.php");
    exit();
}

// Statistics
$announcementCount = $pdo->query("SELECT COUNT(*) FROM announcements WHERE status='active'")->fetchColumn();
$featureCount = $pdo->query("SELECT COUNT(*) FROM features WHERE status='active'")->fetchColumn();
$newsCount = $pdo->query("SELECT COUNT(*) FROM news_articles WHERE status='published'")->fetchColumn();
$newsletterCount = $pdo->query("SELECT COUNT(*) FROM newsletters WHERE status='published'")->fetchColumn();
$calendarCount = $pdo->query("SELECT COUNT(*) FROM calendar_pdfs WHERE status='active'")->fetchColumn();
$unreadMessages = $pdo->query("SELECT COUNT(*) FROM contact_messages WHERE status='unread'")->fetchColumn();

$newsletter_coming_soon = $pdo->query("SELECT setting_value FROM school_settings WHERE setting_key = 'newsletter_coming_soon'")->fetchColumn();
$calendar_coming_soon = $pdo->query("SELECT setting_value FROM school_settings WHERE setting_key = 'calendar_coming_soon'")->fetchColumn();

$recent_messages = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Bethel School</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin-style.css">
    <style>
        /* Same as your original dashboard styles – unchanged */
        .dashboard-header {
            margin-bottom: 40px;
        }
        .dashboard-header h1 {
            color: var(--primary-color);
            font-size: 2rem;
            margin-bottom: 10px;
        }
        .dashboard-header p {
            color: #666;
            margin-bottom: 0;
        }
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
        }
        .section-title {
            font-size: 1.2rem;
            margin: 30px 0 15px;
            color: var(--primary-color);
            border-left: 4px solid var(--accent-color);
            padding-left: 12px;
        }
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
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0, 35, 102, 0.1);
        }
        .toggle-header {
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            background: linear-gradient(135deg, rgba(0, 35, 102, 0.02) 0%, rgba(0, 86, 179, 0.02) 100%);
            border-bottom: 1px solid #eee;
        }
        .toggle-icon {
            width: 55px;
            height: 55px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }
        .toggle-title h3 {
            font-size: 1rem;
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
        .status-text.active { color: #dc3545; }
        .status-text.inactive { color: #28a745; }
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
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }
        .toggle-btn.enable {
            background: #dc3545;
            color: white;
        }
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
        .messages-section {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0, 35, 102, 0.1);
        }
        .messages-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
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
        }
        .message-item.unread {
            background: #fff8e1;
            border-left: 3px solid var(--accent-color);
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
        .no-messages {
            text-align: center;
            padding: 40px;
            color: #999;
        }
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
            .quick-actions-grid {
                grid-template-columns: 1fr;
            }
            .toggle-body {
                flex-direction: column;
                text-align: center;
            }
            .message-item {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
<div class="admin-wrapper">
    <nav class="admin-nav">
        <div class="admin-nav-container">
            <div class="admin-logo">Bethel <span>CMS</span></div>
            <div class="admin-user">
                Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?> |
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="admin-container">
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

        <!-- Coming Soon Controls -->
        <h2 class="section-title">🔧 Coming Soon Controls</h2>
        <div class="toggles-grid">
            <div class="toggle-card">
                <div class="toggle-header">
                    <div class="toggle-icon"><i class="fas fa-envelope-open-text"></i></div>
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
                    <?php if($newsletter_coming_soon == '1'): ?>
                        <a href="?toggle_newsletter=1" class="toggle-btn disable"><i class="fas fa-eye"></i> Show Newsletters</a>
                    <?php else: ?>
                        <a href="?toggle_newsletter=1" class="toggle-btn enable"><i class="fas fa-eye-slash"></i> Enable Coming Soon</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="toggle-card">
                <div class="toggle-header">
                    <div class="toggle-icon"><i class="fas fa-calendar-alt"></i></div>
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
                    <?php if($calendar_coming_soon == '1'): ?>
                        <a href="?toggle_calendar=1" class="toggle-btn disable"><i class="fas fa-eye"></i> Show Calendar</a>
                    <?php else: ?>
                        <a href="?toggle_calendar=1" class="toggle-btn enable"><i class="fas fa-eye-slash"></i> Enable Coming Soon</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Quick Actions (without "Manage News") -->
        <h2 class="section-title">⚡ Quick Actions</h2>
        <div class="quick-actions-grid">
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
            <a href="manage-admissions.php" class="quick-action-card">
                <i class="fas fa-door-open"></i>
                <span>Admissions</span>
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
                <h3><i class="fas fa-envelope"></i> Recent Messages
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
                            <div class="message-preview"><?php echo htmlspecialchars(substr($msg['message'], 0, 80)); ?>...</div>
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
            <span><i class="fas fa-calendar"></i> Calendars: <?php echo $calendarCount; ?></span>
            <span><i class="fas fa-file-pdf"></i> Newsletters: <?php echo $newsletterCount; ?></span>
            <span><i class="fas fa-envelope"></i> Unread: <?php echo $unreadMessages; ?></span>
        </div>
    </div>
</div>
</body>
</html>