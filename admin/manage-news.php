<?php
// admin/manage-news.php - UPDATED with star icon toggle
require_once '../includes/auth.php';
requireAdmin();
require_once '../includes/db.php';

$message = '';
$error = '';

// ============================================
// HANDLE DELETE
// ============================================
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $pdo->prepare("DELETE FROM news_articles WHERE id = ?");
    if ($stmt->execute([$id])) {
        $message = "✅ Article deleted successfully!";
    } else {
        $error = "❌ Failed to delete article.";
    }
    header("Location: manage-news.php");
    exit();
}

// ============================================
// HANDLE TOGGLE STATUS
// ============================================
if (isset($_GET['toggle_status'])) {
    $id = intval($_GET['toggle_status']);
    $stmt = $pdo->prepare("UPDATE news_articles SET status = IF(status='published', 'draft', 'published') WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: manage-news.php");
    exit();
}

// ============================================
// HANDLE TOGGLE HIGHLIGHT (AJAX friendly)
// ============================================
if (isset($_GET['toggle_highlight'])) {
    $id = intval($_GET['toggle_highlight']);
    $stmt = $pdo->prepare("UPDATE news_articles SET is_highlight = IF(is_highlight=1, 0, 1) WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: manage-news.php");
    exit();
}

// ============================================
// HANDLE SET FEATURED
// ============================================
if (isset($_GET['set_featured'])) {
    $id = intval($_GET['set_featured']);
    $pdo->prepare("UPDATE news_articles SET is_featured = 0")->execute();
    $stmt = $pdo->prepare("UPDATE news_articles SET is_featured = 1 WHERE id = ?");
    $stmt->execute([$id]);
    $message = "⭐ Featured article updated!";
    header("Location: manage-news.php");
    exit();
}

// ============================================
// HANDLE SAVE (ADD/EDIT)
// ============================================
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_article'])) {
    $id = isset($_POST['article_id']) ? intval($_POST['article_id']) : 0;
    $title = trim($_POST['title']);
    $excerpt = trim($_POST['excerpt']);
    $content = trim($_POST['content']);
    $category = $_POST['category'];
    $status = $_POST['status'];
    $published_date = $_POST['published_date'];
    $is_highlight = isset($_POST['is_highlight']) ? 1 : 0;
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $highlight_order = intval($_POST['highlight_order']);
    $image_url = trim($_POST['image_url']);
    
    if ($is_featured == 1) {
        $pdo->prepare("UPDATE news_articles SET is_featured = 0")->execute();
    }
    
    if ($id > 0) {
        $stmt = $pdo->prepare("UPDATE news_articles SET title=?, excerpt=?, content=?, category=?, status=?, published_date=?, is_highlight=?, is_featured=?, highlight_order=?, image_url=? WHERE id=?");
        $result = $stmt->execute([$title, $excerpt, $content, $category, $status, $published_date, $is_highlight, $is_featured, $highlight_order, $image_url, $id]);
        if ($result) {
            $message = "✅ Article updated successfully!";
        } else {
            $error = "❌ Failed to update article.";
        }
    } else {
        $stmt = $pdo->prepare("INSERT INTO news_articles (title, excerpt, content, category, status, published_date, is_highlight, is_featured, highlight_order, image_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $result = $stmt->execute([$title, $excerpt, $content, $category, $status, $published_date, $is_highlight, $is_featured, $highlight_order, $image_url]);
        if ($result) {
            $message = "✅ Article added successfully!";
        } else {
            $error = "❌ Failed to add article.";
        }
    }
    header("Location: manage-news.php");
    exit();
}

// ============================================
// FETCH ALL ARTICLES
// ============================================
$articles = $pdo->query("SELECT * FROM news_articles ORDER BY is_featured DESC, is_highlight DESC, highlight_order ASC, published_date DESC, created_at DESC")->fetchAll();
$featured_article = $pdo->query("SELECT id, title FROM news_articles WHERE is_featured = 1 LIMIT 1")->fetch();
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
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px 20px;
        }

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
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--gray-border);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary-color);
        }

        .stat-label {
            color: #666;
            font-size: 0.8rem;
            margin-top: 5px;
        }

        .featured-alert {
            background: #fff3cd;
            color: #856404;
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-left: 4px solid #ffc107;
        }

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

        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .badge-featured {
            background: #dc3545;
            color: white;
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

        /* Star Toggle Button */
        .star-toggle {
            background: none;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
            padding: 5px;
            transition: all 0.2s;
        }

        .star-toggle.active {
            color: var(--accent-color);
        }

        .star-toggle.inactive {
            color: #ccc;
        }

        .star-toggle:hover {
            transform: scale(1.1);
        }

        .table-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
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

        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            align-items: center;
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
            cursor: pointer;
            border: none;
        }

        .btn-edit:hover {
            background: #218838;
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

        .btn-feature {
            background: #17a2b8;
            color: white;
            padding: 5px 12px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.75rem;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-feature:hover {
            background: #138496;
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
            cursor: pointer;
            border: none;
        }

        .btn-delete:hover {
            background: #c82333;
        }

        /* Modal Styles */
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
            max-width: 800px;
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
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
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
            position: sticky;
            bottom: 0;
        }

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

        .image-preview {
            margin-top: 10px;
            padding: 10px;
            background: var(--gray-light);
            border-radius: 8px;
            display: inline-block;
        }

        .image-preview img {
            max-width: 150px;
            border-radius: 8px;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .checkbox-group input {
            width: auto;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
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
                min-width: 800px;
            }
            .action-buttons {
                flex-wrap: wrap;
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
            <h1><i class="fas fa-newspaper"></i> Manage News & Articles</h1>
            <button class="add-button" onclick="openArticleModal()">
                <i class="fas fa-plus"></i> Write New Article
            </button>
        </div>

        <?php if($message): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php if($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php 
            $total = count($articles);
            $published = count(array_filter($articles, function($a) { return $a['status'] == 'published'; }));
            $featured_count = count(array_filter($articles, function($a) { return $a['is_featured'] == 1; }));
            $highlight_count = count(array_filter($articles, function($a) { return $a['is_highlight'] == 1; }));
        ?>
        <div class="stats-grid">
            <div class="stat-card"><div class="stat-number"><?php echo $total; ?></div><div class="stat-label">Total Articles</div></div>
            <div class="stat-card"><div class="stat-number"><?php echo $published; ?></div><div class="stat-label">Published</div></div>
            <div class="stat-card"><div class="stat-number"><?php echo $featured_count; ?></div><div class="stat-label">⭐ Featured</div></div>
            <div class="stat-card"><div class="stat-number"><?php echo $highlight_count; ?></div><div class="stat-label">Highlights</div></div>
        </div>

        <?php if($featured_article): ?>
        <div class="featured-alert">
            <i class="fas fa-star"></i>
            <strong>Currently Featured:</strong> "<?php echo htmlspecialchars($featured_article['title']); ?>" 
            <span style="margin-left: auto; font-size: 0.8rem;">(Only one article can be featured at a time)</span>
        </div>
        <?php endif; ?>

        <div class="data-table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Date</th>
                        <th>Featured</th>
                        <th>Highlight</th>
                        <th>Status</th>
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
                            <td><strong><?php echo htmlspecialchars(substr($article['title'], 0, 50)); ?></strong><?php if(strlen($article['title']) > 50) echo '...'; ?></td>
                            <td><span class="badge badge-<?php echo $article['category']; ?>"><?php echo ucfirst($article['category']); ?></span></td>
                            <td><?php echo date('M d, Y', strtotime($article['published_date'] ?? $article['created_at'])); ?></td>
                            <td>
                                <?php if($article['is_featured'] == 1): ?>
                                    <span class="badge badge-featured"><i class="fas fa-star"></i> Featured</span>
                                <?php else: ?>
                                    <a href="?set_featured=<?php echo $article['id']; ?>" class="btn-feature" onclick="return confirm('Set this as the featured article?')">
                                        <i class="fas fa-star"></i> Set Featured
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: center;">
                                <a href="?toggle_highlight=<?php echo $article['id']; ?>" class="star-toggle <?php echo $article['is_highlight'] == 1 ? 'active' : 'inactive'; ?>" title="Toggle Highlight">
                                    <i class="fas fa-star"></i>
                                </a>
                            </td>
                            <td><span class="badge badge-<?php echo $article['status']; ?>"><?php echo ucfirst($article['status']); ?></span></td>
                            <td class="action-buttons">
                                <button onclick='editArticle(<?php echo json_encode($article); ?>);' class="btn-edit"><i class="fas fa-edit"></i> Edit</button>
                                <a href="?toggle_status=<?php echo $article['id']; ?>" class="btn-toggle"><i class="fas fa-eye-slash"></i> Toggle</a>
                                <a href="?delete=<?php echo $article['id']; ?>" class="btn-delete" onclick="return confirm('Delete this article?')"><i class="fas fa-trash"></i> Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="8" style="text-align: center; padding: 60px;">
                            <i class="fas fa-newspaper" style="font-size: 3rem; color: var(--accent-color); margin-bottom: 15px; display: block;"></i>
                            <h3>No Articles Yet</h3>
                            <p>Get started by writing your first news article!</p>
                            <button onclick="openArticleModal()" class="btn-primary" style="margin-top: 15px;"><i class="fas fa-plus"></i> Write First Article</button>
                        </td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Article Modal -->
    <div id="articleModal" class="modal">
        <div class="modal-content">
            <form method="POST" action="manage-news.php">
                <div class="modal-header">
                    <h2 id="modalTitle">Add New Article</h2>
                    <button type="button" class="modal-close" onclick="closeModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="article_id" id="article_id" value="">
                    
                    <div class="form-group">
                        <label><i class="fas fa-heading"></i> Article Title *</label>
                        <input type="text" name="title" id="title" required placeholder="Enter a compelling title">
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fas fa-align-left"></i> Short Description / Excerpt</label>
                        <textarea name="excerpt" id="excerpt" rows="2" placeholder="Brief summary that appears on the news listing page..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fas fa-image"></i> Featured Image URL</label>
                        <input type="text" name="image_url" id="image_url" placeholder="https://placehold.co/800x600/002366/FFD700?text=Image+Title">
                        <div id="imagePreview" class="image-preview" style="display: none;"></div>
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fas fa-paragraph"></i> Full Content *</label>
                        <textarea name="content" id="content" rows="10" required placeholder="Write your article content here..."></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label><i class="fas fa-tag"></i> Category</label>
                            <select name="category" id="category">
                                <option value="news">📰 News</option>
                                <option value="event">🎉 Event</option>
                                <option value="announcement">📢 Announcement</option>
                                <option value="feature">⭐ Feature</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-calendar-day"></i> Published Date</label>
                            <input type="date" name="published_date" id="published_date" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label><i class="fas fa-eye"></i> Status</label>
                            <select name="status" id="status">
                                <option value="published">📗 Published (Visible to public)</option>
                                <option value="draft">📘 Draft (Hidden from public)</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="checkbox-group">
                        <input type="checkbox" name="is_featured" id="is_featured" value="1">
                        <label for="is_featured"><strong>⭐ Set as Featured Article</strong> <span style="color: #666; font-size: 0.8rem;">(Only ONE article can be featured)</span></label>
                    </div>
                    
                    <div class="checkbox-group">
                        <input type="checkbox" name="is_highlight" id="is_highlight" value="1">
                        <label for="is_highlight"><strong>⭐ Feature as News Highlight</strong> <span style="color: #666; font-size: 0.8rem;">(Highlights appear below featured article)</span></label>
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fas fa-sort-numeric-down"></i> Highlight Order</label>
                        <input type="number" name="highlight_order" id="highlight_order" value="0" placeholder="Lower numbers appear first">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeModal()">Cancel</button>
                    <button type="submit" name="save_article" class="btn-primary">Save Article</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('articleModal');
        
        function openArticleModal() {
            document.getElementById('modalTitle').innerText = 'Add New Article';
            document.getElementById('article_id').value = '';
            document.getElementById('title').value = '';
            document.getElementById('excerpt').value = '';
            document.getElementById('image_url').value = '';
            document.getElementById('content').value = '';
            document.getElementById('category').value = 'news';
            document.getElementById('published_date').value = '<?php echo date('Y-m-d'); ?>';
            document.getElementById('status').value = 'published';
            document.getElementById('is_featured').checked = false;
            document.getElementById('is_highlight').checked = false;
            document.getElementById('highlight_order').value = '0';
            document.getElementById('imagePreview').style.display = 'none';
            modal.classList.add('active');
        }
        
        function editArticle(article) {
            document.getElementById('modalTitle').innerText = 'Edit Article';
            document.getElementById('article_id').value = article.id;
            document.getElementById('title').value = article.title;
            document.getElementById('excerpt').value = article.excerpt || '';
            document.getElementById('image_url').value = article.image_url || '';
            document.getElementById('content').value = article.content;
            document.getElementById('category').value = article.category;
            document.getElementById('published_date').value = article.published_date || '<?php echo date('Y-m-d'); ?>';
            document.getElementById('status').value = article.status;
            document.getElementById('is_featured').checked = article.is_featured == 1;
            document.getElementById('is_highlight').checked = article.is_highlight == 1;
            document.getElementById('highlight_order').value = article.highlight_order || 0;
            
            if (article.image_url) {
                const preview = document.getElementById('imagePreview');
                preview.innerHTML = '<img src="' + article.image_url + '" alt="Preview" style="max-width: 150px; border-radius: 8px;"><p style="margin-top: 5px; font-size: 0.75rem;">Current image</p>';
                preview.style.display = 'block';
            } else {
                document.getElementById('imagePreview').style.display = 'none';
            }
            modal.classList.add('active');
        }
        
        function closeModal() { modal.classList.remove('active'); }
        
        document.getElementById('image_url').addEventListener('input', function() {
            const url = this.value;
            const preview = document.getElementById('imagePreview');
            if (url) {
                preview.innerHTML = '<img src="' + url + '" alt="Preview" style="max-width: 150px; border-radius: 8px;" onerror="this.src=\'https://placehold.co/150x150/002366/FFD700?text=Invalid+URL\'">';
                preview.style.display = 'block';
            } else {
                preview.style.display = 'none';
            }
        });
        
        window.onclick = function(event) { if (event.target === modal) closeModal(); }
    </script>
</body>
</html>