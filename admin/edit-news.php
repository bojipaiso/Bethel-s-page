<?php
// admin/edit-news.php - REDESIGNED with cleaner layout
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
    $highlight_order = $_POST['highlight_order'] ?? 0;
    $image_url = trim($_POST['image_url']);
    
    if($id > 0) {
        $stmt = $pdo->prepare("UPDATE news_articles SET title=?, excerpt=?, content=?, category=?, status=?, published_date=?, is_highlight=?, highlight_order=?, image_url=? WHERE id=?");
        $stmt->execute([$title, $excerpt, $content, $category, $status, $published_date, $is_highlight, $highlight_order, $image_url, $id]);
        $message = "Article updated successfully!";
    } else {
        $stmt = $pdo->prepare("INSERT INTO news_articles (title, excerpt, content, category, status, published_date, is_highlight, highlight_order, image_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $excerpt, $content, $category, $status, $published_date, $is_highlight, $highlight_order, $image_url]);
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
    <title><?php echo $id ? 'Edit' : 'Write'; ?> Article - Bethel School</title>
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
            max-width: 1200px;
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
            max-width: 900px;
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
            margin-bottom: 8px;
        }

        .page-header p {
            color: #666;
            font-size: 0.9rem;
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

        /* Form Sections */
        .form-section {
            background: white;
            border-radius: 16px;
            margin-bottom: 25px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--gray-border);
        }

        .section-header {
            padding: 18px 25px;
            background: var(--gray-light);
            border-bottom: 1px solid var(--gray-border);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .section-header i {
            font-size: 1.3rem;
            color: var(--accent-color);
            width: 30px;
        }

        .section-header h2 {
            font-size: 1.1rem;
            color: var(--primary-color);
            font-weight: 600;
        }

        .section-body {
            padding: 25px;
        }

        /* Form Groups */
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
            width: 18px;
        }

        .form-group input[type="text"],
        .form-group input[type="date"],
        .form-group input[type="number"],
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--gray-border);
            border-radius: 10px;
            font-size: 0.9rem;
            transition: all 0.3s;
            font-family: inherit;
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
            min-height: 150px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        /* Image Preview */
        .image-preview-area {
            margin-top: 15px;
            padding: 15px;
            background: var(--gray-light);
            border-radius: 10px;
        }

        .current-image {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .current-image img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 10px;
            border: 2px solid var(--accent-color);
        }

        .image-info {
            flex: 1;
        }

        .image-info code {
            background: #e9ecef;
            padding: 4px 8px;
            border-radius: 5px;
            font-size: 0.8rem;
            word-break: break-all;
        }

        .help-text {
            font-size: 0.75rem;
            color: #888;
            margin-top: 8px;
            display: flex;
            align-items: center;
            gap: 5px;
            flex-wrap: wrap;
        }

        .help-text i {
            color: var(--accent-color);
        }

        /* Highlight Options */
        .highlight-options {
            background: linear-gradient(135deg, rgba(255, 215, 0, 0.05), rgba(255, 215, 0, 0.02));
            border-radius: 12px;
            padding: 20px;
            border: 1px solid rgba(255, 215, 0, 0.2);
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            margin-bottom: 15px;
        }

        .checkbox-label input {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .checkbox-label strong {
            color: var(--primary-color);
        }

        .checkbox-label span {
            font-size: 0.8rem;
            color: #666;
            font-weight: normal;
        }

        /* Buttons */
        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--gray-border);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 12px 28px;
            border: none;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 35, 102, 0.3);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
            padding: 12px 28px;
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

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
            .form-actions {
                flex-direction: column;
            }
            .btn-primary, .btn-secondary {
                justify-content: center;
            }
            .section-body {
                padding: 20px;
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
                <a href="manage-news.php"><i class="fas fa-newspaper"></i> News</a>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </nav>

    <div class="admin-container">
        <div class="page-header">
            <h1><i class="fas fa-pen-fancy"></i> <?php echo $id ? 'Edit Article' : 'Write New Article'; ?></h1>
            <p><?php echo $id ? 'Update your existing article' : 'Create a new news article or event'; ?></p>
        </div>

        <?php if($message): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <span><?php echo $message; ?></span>
            </div>
        <?php endif; ?>

        <form method="POST">
            <!-- Basic Information Section -->
            <div class="form-section">
                <div class="section-header">
                    <i class="fas fa-info-circle"></i>
                    <h2>Basic Information</h2>
                </div>
                <div class="section-body">
                    <div class="form-group">
                        <label><i class="fas fa-heading"></i> Article Title *</label>
                        <input type="text" name="title" required placeholder="Enter a compelling title" value="<?php echo htmlspecialchars($article['title'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-align-left"></i> Short Description / Excerpt</label>
                        <textarea name="excerpt" rows="2" placeholder="Brief summary that appears on the news listing page..."><?php echo htmlspecialchars($article['excerpt'] ?? ''); ?></textarea>
                        <div class="help-text"><i class="fas fa-info-circle"></i> This appears on the main news page. Leave empty to auto-generate from content.</div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label><i class="fas fa-tag"></i> Category</label>
                            <select name="category">
                                <option value="news" <?php echo ($article['category'] ?? '') == 'news' ? 'selected' : ''; ?>>📰 News</option>
                                <option value="event" <?php echo ($article['category'] ?? '') == 'event' ? 'selected' : ''; ?>>🎉 Event</option>
                                <option value="announcement" <?php echo ($article['category'] ?? '') == 'announcement' ? 'selected' : ''; ?>>📢 Announcement</option>
                                <option value="feature" <?php echo ($article['category'] ?? '') == 'feature' ? 'selected' : ''; ?>>⭐ Feature</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-calendar-day"></i> Published Date</label>
                            <input type="date" name="published_date" value="<?php echo $article['published_date'] ?? date('Y-m-d'); ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label><i class="fas fa-eye"></i> Status</label>
                            <select name="status">
                                <option value="published" <?php echo ($article['status'] ?? '') == 'published' ? 'selected' : ''; ?>>📗 Published (Visible to public)</option>
                                <option value="draft" <?php echo ($article['status'] ?? '') == 'draft' ? 'selected' : ''; ?>>📘 Draft (Hidden from public)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Image Section -->
            <div class="form-section">
                <div class="section-header">
                    <i class="fas fa-image"></i>
                    <h2>Featured Image</h2>
                </div>
                <div class="section-body">
                    <div class="form-group">
                        <label><i class="fas fa-link"></i> Image URL</label>
                        <input type="text" name="image_url" placeholder="https://placehold.co/800x600/002366/FFD700?text=Image+Title" value="<?php echo htmlspecialchars($article['image_url'] ?? ''); ?>">
                        <div class="help-text">
                            <i class="fas fa-lightbulb"></i> 
                            Tips: Use <strong>https://placehold.co/800x600/002366/FFD700?text=Your+Text</strong> for placeholders, 
                            or upload to <strong>Images/news/</strong> folder
                        </div>
                    </div>

                    <?php if(isset($article['image_url']) && !empty($article['image_url'])): ?>
                    <div class="image-preview-area">
                        <div class="current-image">
                            <img src="<?php echo htmlspecialchars($article['image_url']); ?>" alt="Current image" onerror="this.src='https://placehold.co/100x100/002366/FFD700?text=Error'">
                            <div class="image-info">
                                <p><strong>Current Image:</strong></p>
                                <code><?php echo htmlspecialchars($article['image_url']); ?></code>
                                <p class="help-text" style="margin-top: 8px;"><i class="fas fa-sync-alt"></i> Leave the field above empty to keep this image, or enter a new URL to replace it.</p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Content Section -->
            <div class="form-section">
                <div class="section-header">
                    <i class="fas fa-file-alt"></i>
                    <h2>Article Content</h2>
                </div>
                <div class="section-body">
                    <div class="form-group">
                        <label><i class="fas fa-paragraph"></i> Full Content *</label>
                        <textarea name="content" required rows="12" placeholder="Write your article content here..."><?php echo htmlspecialchars($article['content'] ?? ''); ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Highlight Options Section -->
            <div class="form-section">
                <div class="section-header">
                    <i class="fas fa-star"></i>
                    <h2>Featured Highlight</h2>
                </div>
                <div class="section-body">
                    <div class="highlight-options">
                        <label class="checkbox-label">
                            <input type="checkbox" name="is_highlight" value="1" <?php echo ($article['is_highlight'] ?? 0) == 1 ? 'checked' : ''; ?>>
                            <strong>⭐ Feature this as a News Highlight</strong>
                            <span>(Highlights appear at the top of the News page with special styling)</span>
                        </label>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label><i class="fas fa-sort-numeric-down"></i> Highlight Order</label>
                            <input type="number" name="highlight_order" value="<?php echo $article['highlight_order'] ?? 0; ?>" placeholder="1, 2, 3...">
                            <div class="help-text"><i class="fas fa-info-circle"></i> Lower numbers appear first. Use 0 for default ordering.</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i> <?php echo $id ? 'Update Article' : 'Publish Article'; ?>
                </button>
                <a href="manage-news.php" class="btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</body>
</html>