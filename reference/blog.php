<?php
// blog.php - Public blog listing page
$page_title = 'Blog | CreativePort';
require_once 'includes/db.php';
require_once 'includes/header.php';

// Handle search and filter
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';

$query = "SELECT * FROM blog_posts WHERE status = 'published'";
$params = [];

if(!empty($search)) {
    $query .= " AND (title LIKE :search OR content LIKE :search)";
    $params[':search'] = "%$search%";
}

if(!empty($category)) {
    $query .= " AND category = :category";
    $params[':category'] = $category;
}

$query .= " ORDER BY created_at DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$posts = $stmt->fetchAll();

// Get unique categories for filter
$categories = $pdo->query("SELECT DISTINCT category FROM blog_posts WHERE category IS NOT NULL")->fetchAll();
?>

<div class="blog-page">
    <div class="container">
        <!-- Hero Section -->
        <div class="blog-hero">
            <h1>Creative Insights & Tutorials</h1>
            <p>Explore the latest in web design, development, and creative thinking</p>
            
            <!-- Search Bar -->
            <form method="GET" class="search-form">
                <input type="text" name="search" placeholder="Search articles..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit">Search</button>
            </form>
        </div>

        <!-- Category Filter -->
        <div class="category-filters">
            <a href="blog.php" class="<?php echo empty($category) ? 'active' : ''; ?>">All</a>
            <?php foreach($categories as $cat): ?>
                <a href="?category=<?php echo urlencode($cat['category']); ?>" 
                   class="<?php echo $category == $cat['category'] ? 'active' : ''; ?>">
                    <?php echo htmlspecialchars($cat['category']); ?>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Blog Grid -->
        <div class="blog-grid">
            <?php if(count($posts) > 0): ?>
                <?php foreach($posts as $post): ?>
                    <article class="blog-card">
                        <?php if($post['image']): ?>
                            <div class="card-image">
                                <img src="uploads/<?php echo $post['image']; ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
                            </div>
                        <?php endif; ?>
                        <div class="card-content">
                            <div class="post-meta">
                                <span class="category"><?php echo htmlspecialchars($post['category']); ?></span>
                                <span class="date"><?php echo date('M d, Y', strtotime($post['created_at'])); ?></span>
                            </div>
                            <h2><?php echo htmlspecialchars($post['title']); ?></h2>
                            <p><?php echo htmlspecialchars($post['excerpt'] ?: substr($post['content'], 0, 150) . '...'); ?></p>
                            <a href="post.php?id=<?php echo $post['id']; ?>" class="read-more">Read More →</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-results">
                    <p>No blog posts found. Check back soon!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>