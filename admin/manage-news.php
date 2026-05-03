<?php
// admin/manage-news.php – star toggle works, stars visible
require_once '../includes/auth.php';
requireAdmin();
require_once '../includes/db.php';

$message = '';

// Delete, toggle status, toggle highlight, set featured
if(isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM news_articles WHERE id = ?");
    $stmt->execute([intval($_GET['delete'])]);
    $message = "Article deleted!";
    header("Location: manage-news.php");
    exit();
}
if(isset($_GET['toggle_status'])) {
    $stmt = $pdo->prepare("UPDATE news_articles SET status = IF(status='published', 'draft', 'published') WHERE id = ?");
    $stmt->execute([intval($_GET['toggle_status'])]);
    $message = "Status updated!";
    header("Location: manage-news.php");
    exit();
}
if(isset($_GET['toggle_highlight'])) {
    $stmt = $pdo->prepare("UPDATE news_articles SET is_highlight = IF(is_highlight=1, 0, 1) WHERE id = ?");
    $stmt->execute([intval($_GET['toggle_highlight'])]);
    $message = "Highlight toggled!";
    header("Location: manage-news.php");
    exit();
}
if(isset($_GET['set_featured'])) {
    $pdo->prepare("UPDATE news_articles SET is_featured = 0")->execute();
    $stmt = $pdo->prepare("UPDATE news_articles SET is_featured = 1 WHERE id = ?");
    $stmt->execute([intval($_GET['set_featured'])]);
    $message = "Featured article updated!";
    header("Location: manage-news.php");
    exit();
}

// Save article (add/edit)
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_article'])) {
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
    
    if($is_featured == 1) {
        $pdo->prepare("UPDATE news_articles SET is_featured = 0")->execute();
    }
    
    if($id > 0) {
        $stmt = $pdo->prepare("UPDATE news_articles SET title=?, excerpt=?, content=?, category=?, status=?, published_date=?, is_highlight=?, is_featured=?, highlight_order=?, image_url=? WHERE id=?");
        $stmt->execute([$title, $excerpt, $content, $category, $status, $published_date, $is_highlight, $is_featured, $highlight_order, $image_url, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO news_articles (title, excerpt, content, category, status, published_date, is_highlight, is_featured, highlight_order, image_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $excerpt, $content, $category, $status, $published_date, $is_highlight, $is_featured, $highlight_order, $image_url]);
    }
    $message = $id ? "Article updated!" : "Article added!";
    header("Location: manage-news.php");
    exit();
}

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
    <link rel="stylesheet" href="../css/admin-style.css">
    <style>
        .star-toggle {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.2rem;
            padding: 0 5px;
            text-decoration: none;
            display: inline-block;
            color: #ccc;
        }
        .star-toggle.active {
            color: var(--accent-color);
        }
        .star-toggle.inactive {
            color: #ccc;
        }
        .star-toggle:hover {
            opacity: 0.8;
        }
        .table-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
        }
        .modal {
            display: none;
            position: fixed;
            top:0;
            left:0;
            width:100%;
            height:100%;
            background:rgba(0,0,0,0.5);
            z-index:1000;
            align-items:center;
            justify-content:center;
        }
        .modal.active { display: flex; }
        .modal-content {
            background:white;
            border-radius:20px;
            width:90%;
            max-width:800px;
            max-height:90vh;
            overflow-y:auto;
        }
        .modal-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color:white;
            padding:20px;
            display:flex;
            justify-content:space-between;
        }
        .modal-close { background:none; border:none; color:white; font-size:1.5rem; cursor:pointer; }
        .modal-body { padding:25px; }
        .modal-footer { padding:20px; background:#f8f9fa; display:flex; justify-content:flex-end; gap:10px; }
        .image-preview { margin-top:10px; }
        .image-preview img { max-width:150px; border-radius:5px; }
    </style>
</head>
<body>
<div class="admin-wrapper">
    <nav class="admin-nav"><div class="admin-nav-container"><div class="admin-logo">Bethel <span>CMS</span></div><div class="admin-user"><a href="dashboard.php">Dashboard</a> | <a href="logout.php">Logout</a></div></div></nav>
    <div class="admin-container">
        <div class="page-header"><h1>Manage News & Articles</h1><button class="btn-primary" onclick="openArticleModal()">+ Write New Article</button></div>
        <?php if($message): ?><div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>
        <div class="stats-grid" style="display:grid; grid-template-columns:repeat(auto-fit, minmax(150px,1fr)); gap:15px; margin-bottom:20px;">
            <div class="stat-card"><div class="stat-number"><?php echo count($articles); ?></div><div class="stat-label">Total</div></div>
            <div class="stat-card"><div class="stat-number"><?php echo count(array_filter($articles, function($a){return $a['status']=='published';})); ?></div><div class="stat-label">Published</div></div>
            <div class="stat-card"><div class="stat-number"><?php echo count(array_filter($articles, function($a){return $a['is_featured']==1;})); ?></div><div class="stat-label">Featured</div></div>
            <div class="stat-card"><div class="stat-number"><?php echo count(array_filter($articles, function($a){return $a['is_highlight']==1;})); ?></div><div class="stat-label">Highlights</div></div>
        </div>
        <?php if($featured_article): ?><div class="alert alert-info">⭐ Currently Featured: "<?php echo htmlspecialchars($featured_article['title']); ?>"</div><?php endif; ?>
        <div class="data-table-container">
            <table class="data-table">
                <thead><tr><th>Image</th><th>Title</th><th>Category</th><th>Date</th><th>Featured</th><th>Highlight</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    <?php foreach($articles as $a): ?>
                    <tr>
                        <td><?php if(!empty($a['image_url'])): ?><img src="<?php echo htmlspecialchars($a['image_url']); ?>" class="table-image"><?php else: ?>—<?php endif; ?></td>
                        <td><strong><?php echo htmlspecialchars(substr($a['title'],0,50)); ?></strong></td>
                        <td><span class="badge badge-<?php echo $a['category']; ?>"><?php echo ucfirst($a['category']); ?></span></td>
                        <td><?php echo date('M d, Y', strtotime(isset($a['published_date']) ? $a['published_date'] : $a['created_at'])); ?></td>
                        <td><?php if($a['is_featured']): ?><span class="badge badge-featured">Featured</span><?php else: ?><a href="?set_featured=<?php echo $a['id']; ?>" class="btn-feature">Set Featured</a><?php endif; ?></td>
                        <td>
                            <a href="?toggle_highlight=<?php echo $a['id']; ?>" class="star-toggle <?php echo $a['is_highlight'] ? 'active' : 'inactive'; ?>">
                                <i class="fas fa-star"></i>
                            </a>
                        </td>
                        <td><span class="status-badge status-<?php echo $a['status']; ?>"><?php echo ucfirst($a['status']); ?></span></td>
                        <td class="actions">
                            <button class="btn-edit" onclick='editArticle(<?php echo json_encode($a); ?>)'>Edit</button>
                            <a href="?toggle_status=<?php echo $a['id']; ?>" class="btn-toggle">Toggle</a>
                            <a href="?delete=<?php echo $a['id']; ?>" class="btn-delete" onclick="return confirm('Delete?')">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Article Modal -->
<div id="articleModal" class="modal">
    <div class="modal-content">
        <form method="POST">
            <div class="modal-header"><h2 id="modalTitle">Add New Article</h2><button type="button" class="modal-close" onclick="closeModal()">&times;</button></div>
            <div class="modal-body">
                <input type="hidden" name="article_id" id="article_id">
                <div class="form-group"><label>Title *</label><input type="text" name="title" id="title" required></div>
                <div class="form-group"><label>Excerpt</label><textarea name="excerpt" id="excerpt" rows="2"></textarea></div>
                <div class="form-group"><label>Image URL</label><input type="text" name="image_url" id="image_url" placeholder="https://..."><div id="imagePreview" class="image-preview"></div></div>
                <div class="form-group"><label>Content *</label><textarea name="content" id="content" rows="10" required></textarea></div>
                <div class="form-row"><div class="form-group"><label>Category</label><select name="category" id="category"><option value="news">News</option><option value="event">Event</option><option value="announcement">Announcement</option><option value="feature">Feature</option></select></div><div class="form-group"><label>Published Date</label><input type="date" name="published_date" id="published_date"></div></div>
                <div class="form-row"><div class="form-group"><label>Status</label><select name="status" id="status"><option value="published">Published</option><option value="draft">Draft</option></select></div></div>
                <div class="checkbox-group"><input type="checkbox" name="is_featured" id="is_featured" value="1"><label>⭐ Set as Featured Article (only one)</label></div>
                <div class="checkbox-group"><input type="checkbox" name="is_highlight" id="is_highlight" value="1"><label>⭐ Feature as Highlight</label></div>
                <div class="form-group"><label>Highlight Order</label><input type="number" name="highlight_order" id="highlight_order" value="0"></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn-secondary" onclick="closeModal()">Cancel</button><button type="submit" name="save_article" class="btn-primary">Save Article</button></div>
        </form>
    </div>
</div>
<script>
    const modal = document.getElementById('articleModal');
    function openArticleModal() { resetForm(); modal.classList.add('active'); }
    function closeModal() { modal.classList.remove('active'); }
    function editArticle(article) {
        document.getElementById('modalTitle').innerText = 'Edit Article';
        document.getElementById('article_id').value = article.id;
        document.getElementById('title').value = article.title;
        document.getElementById('excerpt').value = article.excerpt || '';
        document.getElementById('image_url').value = article.image_url || '';
        document.getElementById('content').value = article.content;
        document.getElementById('category').value = article.category;
        document.getElementById('published_date').value = article.published_date || '';
        document.getElementById('status').value = article.status;
        document.getElementById('is_featured').checked = article.is_featured == 1;
        document.getElementById('is_highlight').checked = article.is_highlight == 1;
        document.getElementById('highlight_order').value = article.highlight_order || 0;
        if(article.image_url) document.getElementById('imagePreview').innerHTML = '<img src="'+article.image_url+'">';
        else document.getElementById('imagePreview').innerHTML = '';
        modal.classList.add('active');
    }
    function resetForm() {
        document.getElementById('modalTitle').innerText = 'Add New Article';
        document.getElementById('article_id').value = '';
        document.getElementById('title').value = '';
        document.getElementById('excerpt').value = '';
        document.getElementById('image_url').value = '';
        document.getElementById('content').value = '';
        document.getElementById('category').value = 'news';
        document.getElementById('published_date').value = '';
        document.getElementById('status').value = 'published';
        document.getElementById('is_featured').checked = false;
        document.getElementById('is_highlight').checked = false;
        document.getElementById('highlight_order').value = 0;
        document.getElementById('imagePreview').innerHTML = '';
    }
    document.getElementById('image_url').addEventListener('input', function() {
        let url = this.value;
        let preview = document.getElementById('imagePreview');
        if(url) preview.innerHTML = '<img src="'+url+'" onerror="this.src=\'https://placehold.co/150x150/002366/FFD700?text=Invalid\'">';
        else preview.innerHTML = '';
    });
    window.onclick = function(e) { if(e.target === modal) closeModal(); }
</script>
</body>
</html>