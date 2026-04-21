<?php
// admin/edit-news.php - ADDED featured article option
require_once '../includes/auth.php';
requireAdmin();
require_once '../includes/db.php';

$id = $_GET['id'] ?? 0;
$article = null;

if($id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM news_articles WHERE id = ?");
    $stmt->execute([$id]);
    $article = $stmt->fetch();
}

$message = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $excerpt = $_POST['excerpt'];
    $content = $_POST['content'];
    $category = $_POST['category'];
    $status = $_POST['status'];
    $published_date = $_POST['published_date'];
    $is_highlight = isset($_POST['is_highlight']) ? 1 : 0;
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $highlight_order = $_POST['highlight_order'] ?? 0;
    $image_url = trim($_POST['image_url']);
    
    // If this article is being set as featured, remove featured flag from all others
    if($is_featured == 1) {
        $pdo->prepare("UPDATE news_articles SET is_featured = 0")->execute();
    }
    
    if($id > 0) {
        $stmt = $pdo->prepare("UPDATE news_articles SET title=?, excerpt=?, content=?, category=?, status=?, published_date=?, is_highlight=?, is_featured=?, highlight_order=?, image_url=? WHERE id=?");
        $stmt->execute([$title, $excerpt, $content, $category, $status, $published_date, $is_highlight, $is_featured, $highlight_order, $image_url, $id]);
        $message = "Article updated successfully!";
    } else {
        $stmt = $pdo->prepare("INSERT INTO news_articles (title, excerpt, content, category, status, published_date, is_highlight, is_featured, highlight_order, image_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $excerpt, $content, $category, $status, $published_date, $is_highlight, $is_featured, $highlight_order, $image_url]);
        $message = "Article added successfully!";
        header("Location: manage-news.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $id ? 'Edit' : 'Add'; ?> Article - Bethel School</title>
    <link rel="stylesheet" href="../css/admin-style.css">
    <style>
        .form-container { max-width: 800px; margin: 0 auto; }
        .form-group textarea { min-height: 200px; }
        .feature-options, .highlight-options {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            border-left: 3px solid var(--accent-color);
        }
        .image-preview {
            margin-top: 10px;
            padding: 10px;
            background: #f0f0f0;
            border-radius: 5px;
            display: inline-block;
        }
        .image-preview img {
            max-width: 200px;
            max-height: 150px;
            border-radius: 5px;
        }
        .help-text {
            font-size: 0.8rem;
            color: #666;
            margin-top: 5px;
        }
        .current-image {
            margin-bottom: 10px;
        }
        .feature-note {
            background: #fff3cd;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
            font-size: 0.8rem;
            color: #856404;
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
                    <a href="manage-news.php">News</a> |
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        </nav>
        
        <div class="admin-container">
            <div class="form-container">
                <h1><?php echo $id ? 'Edit' : 'Add New'; ?> Article</h1>
                
                <?php if($message): ?>
                    <div class="alert alert-success"><?php echo $message; ?></div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="form-group">
                        <label>Title *</label>
                        <input type="text" name="title" required value="<?php echo htmlspecialchars($article['title'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Excerpt / Short Description</label>
                        <textarea name="excerpt" rows="3"><?php echo htmlspecialchars($article['excerpt'] ?? ''); ?></textarea>
                    </div>
                    
                    <!-- Image URL Field -->
                    <div class="form-group">
                        <label>Featured Image URL</label>
                        <input type="text" name="image_url" value="<?php echo htmlspecialchars($article['image_url'] ?? ''); ?>" 
                               placeholder="Images/news/your-image.jpg or https://example.com/image.jpg" 
                               style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                        <div class="help-text">
                            💡 You can use local path (Images/news/your-image.jpg) or external URL
                        </div>
                        
                        <?php if(isset($article['image_url']) && !empty($article['image_url'])): ?>
                            <div class="image-preview">
                                <p><strong>Current Image Preview:</strong></p>
                                <img src="<?php echo htmlspecialchars($article['image_url']); ?>" alt="Current image" onerror="this.src='https://placehold.co/200x150/002366/FFD700?text=Image+Not+Found'">
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label>Full Content *</label>
                        <textarea name="content" required rows="10"><?php echo htmlspecialchars($article['content'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Category</label>
                            <select name="category">
                                <option value="news" <?php echo ($article['category'] ?? '') == 'news' ? 'selected' : ''; ?>>News</option>
                                <option value="event" <?php echo ($article['category'] ?? '') == 'event' ? 'selected' : ''; ?>>Event</option>
                                <option value="announcement" <?php echo ($article['category'] ?? '') == 'announcement' ? 'selected' : ''; ?>>Announcement</option>
                                <option value="feature" <?php echo ($article['category'] ?? '') == 'feature' ? 'selected' : ''; ?>>Feature</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status">
                                <option value="published" <?php echo ($article['status'] ?? '') == 'published' ? 'selected' : ''; ?>>Published</option>
                                <option value="draft" <?php echo ($article['status'] ?? '') == 'draft' ? 'selected' : ''; ?>>Draft</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Published Date</label>
                        <input type="date" name="published_date" value="<?php echo $article['published_date'] ?? date('Y-m-d'); ?>">
                    </div>
                    
                    <!-- Featured Article Option -->
                    <div class="feature-options">
                        <div class="form-group">
                            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                <input type="checkbox" name="is_featured" value="1" <?php echo ($article['is_featured'] ?? 0) == 1 ? 'checked' : ''; ?>>
                                <strong>⭐ Set as Featured Article</strong>
                                <span style="font-size: 0.8rem; color: #666;">(Only ONE article can be featured - appears prominently at the top)</span>
                            </label>
                            <?php if(($article['is_featured'] ?? 0) == 1): ?>
                                <div class="feature-note">
                                    <i class="fas fa-info-circle"></i> This article is currently the featured story on the news page.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Highlight Options -->
                    <div class="highlight-options">
                        <div class="form-group">
                            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                <input type="checkbox" name="is_highlight" value="1" <?php echo ($article['is_highlight'] ?? 0) == 1 ? 'checked' : ''; ?>>
                                <strong>⭐ Feature as News Highlight</strong>
                                <span style="font-size: 0.8rem; color: #666;">(Highlights appear in a grid below the featured article)</span>
                            </label>
                        </div>
                        
                        <div class="form-group">
                            <label>Highlight Display Order</label>
                            <input type="number" name="highlight_order" value="<?php echo $article['highlight_order'] ?? 0; ?>" placeholder="Lower numbers appear first">
                            <small>Highlights with lower numbers will appear first. Leave 0 for default ordering.</small>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-primary">Save Article</button>
                    <a href="manage-news.php" class="btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>