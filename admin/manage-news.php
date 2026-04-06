<?php
// admin/manage-news.php - REDESIGNED with modern layout
require_once '../includes/auth.php';
requireAdmin();
require_once '../includes/db.php';

$message = '';

// Delete article
if(isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM news_articles WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    $message = "Article deleted successfully!";
}

// Toggle status
if(isset($_GET['toggle_status'])) {
    $stmt = $pdo->prepare("UPDATE news_articles SET status = IF(status='published', 'draft', 'published') WHERE id = ?");
    $stmt->execute([$_GET['toggle_status']]);
    $message = "Status updated!";
}

// Toggle highlight
if(isset($_GET['toggle_highlight'])) {
    $stmt = $pdo->prepare("UPDATE news_articles SET is_highlight = IF(is_highlight=1, 0, 1) WHERE id = ?");
    $stmt->execute([$_GET['toggle_highlight']]);
    $message = "Highlight status updated!";
}

// Fetch all articles
$articles = $pdo->query("SELECT * FROM news_articles ORDER BY is_highlight DESC, highlight_order ASC, published_date DESC, created_at DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage News & Articles - Bethel School</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #002366;
            --secondary-color: #0056b3;
            --accent-color: #FFD700;
            --dark-color: #1a1a2e;
            --gray-light: #f8f9fa;
            --gray-border: #e0e0e0;
        }

        body {
            font-family: 'Segoe UI', Roboto, system-ui, sans-serif;
            background: var(--gray-light);
            color: var(--dark-color);
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
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
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
            font-size: 0.85rem;
            margin-top: 5px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 35, 102, 0.3);
        }

        /* Alert Messages */
        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 25px;
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

        .alert-success i {
            color: #28a745;
            font-size: 1.2rem;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 16px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--gray-border);
            transition: all 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(0, 35, 102, 0.1);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 5px;
        }

        .stat-label {
            color: #666;
            font-size: 0.8rem;
        }

        /* Table Container */
        .table-container {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--gray-border);
        }

        .table-header {
            padding: 18px 25px;
            background: var(--gray-light);
            border-bottom: 1px solid var(--gray-border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .table-header h2 {
            font-size: 1.1rem;
            color: var(--primary-color);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .table-header h2 i {
            color: var(--accent-color);
        }

        .search-box {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .search-box input {
            padding: 8px 15px;
            border: 1px solid var(--gray-border);
            border-radius: 8px;
            font-size: 0.85rem;
            width: 250px;
        }

        .search-box button {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 8px;
            cursor: pointer;
        }

        /* Table */
        .news-table {
            width: 100%;
            border-collapse: collapse;
        }

        .news-table th {
            text-align: left;
            padding: 15px 20px;
            background: var(--gray-light);
            font-weight: 600;
            color: var(--primary-color);
            font-size: 0.85rem;
            border-bottom: 1px solid var(--gray-border);
        }

        .news-table td {
            padding: 15px 20px;
            border-bottom: 1px solid var(--gray-border);
            vertical-align: middle;
            font-size: 0.85rem;
        }

        .news-table tr:hover {
            background: rgba(0, 35, 102, 0.02);
        }

        /* Image Preview in Table */
        .table-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid var(--gray-border);
        }

        .no-image {
            width: 50px;
            height: 50px;
            background: var(--gray-light);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            font-size: 0.7rem;
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .badge-highlight {
            background: var(--accent-color);
            color: var(--primary-color);
        }

        .badge-published {
            background: #d4edda;
            color: #155724;
        }

        .badge-draft {
            background: #f8d7da;
            color: #721c24;
        }

        .badge-news {
            background: #cfe2ff;
            color: #084298;
        }

        .badge-event {
            background: #fff3cd;
            color: #856404;
        }

        .badge-announcement {
            background: #d1ecf1;
            color: #0c5460;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
            cursor: pointer;
            border: none;
        }

        .btn-edit {
            background: var(--primary-color);
            color: white;
        }

        .btn-edit:hover {
            background: var(--secondary-color);
            transform: translateY(-1px);
        }

        .btn-toggle {
            background: #ffc107;
            color: #000;
        }

        .btn-toggle:hover {
            background: #e0a800;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
        }

        .btn-delete:hover {
            background: #c82333;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state i {
            font-size: 3rem;
            color: var(--accent-color);
            margin-bottom: 15px;
        }

        .empty-state h3 {
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #666;
            margin-bottom: 20px;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .news-table {
                display: block;
                overflow-x: auto;
            }
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .table-header {
                flex-direction: column;
                align-items: flex-start;
            }
            .search-box {
                width: 100%;
            }
            .search-box input {
                flex: 1;
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
            <div>
                <h1><i class="fas fa-newspaper"></i> Manage News & Articles</h1>
                <p>Create, edit, and manage all your news articles, events, and announcements</p>
            </div>
            <a href="edit-news.php" class="btn-primary">
                <i class="fas fa-plus-circle"></i> Write New Article
            </a>
        </div>

        <?php if($message): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <span><?php echo $message; ?></span>
            </div>
        <?php endif; ?>

        <!-- Statistics Cards -->
        <?php 
            $total = count($articles);
            $published = count(array_filter($articles, function($a) { return $a['status'] == 'published'; }));
            $highlights = count(array_filter($articles, function($a) { return $a['is_highlight'] == 1; }));
            $drafts = count(array_filter($articles, function($a) { return $a['status'] == 'draft'; }));
        ?>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?php echo $total; ?></div>
                <div class="stat-label">Total Articles</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $published; ?></div>
                <div class="stat-label">Published</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $highlights; ?></div>
                <div class="stat-label">⭐ Highlights</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $drafts; ?></div>
                <div class="stat-label">Drafts</div>
            </div>
        </div>

        <!-- Articles Table -->
        <div class="table-container">
            <div class="table-header">
                <h2><i class="fas fa-list-ul"></i> All Articles</h2>
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Search articles..." onkeyup="searchTable()">
                    <button onclick="searchTable()"><i class="fas fa-search"></i></button>
                </div>
            </div>

            <div style="overflow-x: auto;">
                <table class="news-table" id="articlesTable">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Highlight</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($articles) > 0): ?>
                            <?php foreach($articles as $article): ?>
                            <tr>
                                <td>
                                    <?php if(!empty($article['image_url'])): ?>
                                        <img src="<?php echo htmlspecialchars($article['image_url']); ?>" class="table-image" alt="Image" onerror="this.src='https://placehold.co/50x50/002366/FFD700?text=?'">
                                    <?php else: ?>
                                        <div class="no-image"><i class="fas fa-image"></i></div>
                                    <?php endif; ?>
                                </td>
                                <td style="max-width: 250px;">
                                    <strong><?php echo htmlspecialchars(substr($article['title'], 0, 50)); ?></strong>
                                    <?php if(strlen($article['title']) > 50): ?>...<?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge badge-<?php echo $article['category']; ?>">
                                        <?php 
                                            switch($article['category']) {
                                                case 'news': echo '📰 News'; break;
                                                case 'event': echo '🎉 Event'; break;
                                                case 'announcement': echo '📢 Announcement'; break;
                                                case 'feature': echo '⭐ Feature'; break;
                                                default: echo $article['category'];
                                            }
                                        ?>
                                    </span>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($article['published_date'] ?? $article['created_at'])); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $article['status']; ?>">
                                        <?php echo $article['status'] == 'published' ? '📗 Published' : '📘 Draft'; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if($article['is_highlight'] == 1): ?>
                                        <span class="badge badge-highlight"><i class="fas fa-star"></i> Highlighted</span>
                                    <?php else: ?>
                                        <span style="color: #999;">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="action-buttons">
                                    <a href="edit-news.php?id=<?php echo $article['id']; ?>" class="btn-icon btn-edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="?toggle_highlight=<?php echo $article['id']; ?>" class="btn-icon btn-toggle" onclick="return confirm('Toggle highlight status?')">
                                        <i class="fas fa-star"></i> Highlight
                                    </a>
                                    <a href="?toggle_status=<?php echo $article['id']; ?>" class="btn-icon btn-toggle">
                                        <i class="fas fa-eye-slash"></i> Toggle
                                    </a>
                                    <a href="?delete=<?php echo $article['id']; ?>" class="btn-icon btn-delete" onclick="return confirm('Delete this article? This cannot be undone!')">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="empty-state">
                                    <i class="fas fa-newspaper"></i>
                                    <h3>No Articles Yet</h3>
                                    <p>Get started by writing your first news article!</p>
                                    <a href="edit-news.php" class="btn-primary" style="display: inline-flex;">
                                        <i class="fas fa-plus-circle"></i> Write First Article
                                    </a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function searchTable() {
            let input = document.getElementById('searchInput');
            let filter = input.value.toLowerCase();
            let table = document.getElementById('articlesTable');
            let rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) {
                let titleCell = rows[i].getElementsByTagName('td')[1];
                if (titleCell) {
                    let titleText = titleCell.textContent || titleCell.innerText;
                    if (titleText.toLowerCase().indexOf(filter) > -1) {
                        rows[i].style.display = '';
                    } else {
                        rows[i].style.display = 'none';
                    }
                }
            }
        }
    </script>
</body>
</html>