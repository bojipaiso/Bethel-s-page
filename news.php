<?php
// news.php
require_once 'includes/db.php';

$featured = $pdo->query("SELECT * FROM news_articles WHERE status='published' AND is_featured = 1 LIMIT 1")->fetch();
$highlights = $pdo->query("SELECT * FROM news_articles WHERE status='published' AND is_highlight = 1 AND is_featured = 0 ORDER BY highlight_order ASC, published_date DESC LIMIT 3")->fetchAll();
$regular_news = $pdo->query("SELECT * FROM news_articles WHERE status='published' AND is_highlight = 0 AND is_featured = 0 ORDER BY published_date DESC, created_at DESC")->fetchAll();

$page_title = 'News & Events | Bethel International School';
$banner_title = 'News & Events';
$banner_subtitle = 'Stay Updated with the Latest Happenings at Bethel International School';
$show_banner = true;

$additional_css = "
    .featured-article { margin-bottom: 50px; }
    .featured-label { display: inline-block; background: #dc3545; color: white; padding: 5px 15px; border-radius: 30px; font-size: 0.7rem; font-weight: bold; text-transform: uppercase; margin-bottom: 20px; }
    .featured-card { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 15px 35px rgba(0,35,102,0.15); transition: transform 0.3s; }
    .featured-card:hover { transform: translateY(-5px); }
    .featured-image { min-height: 350px; background-size: cover; background-position: center; position: relative; }
    .featured-image-category { position: absolute; bottom: 15px; left: 15px; background: var(--accent-color); color: var(--primary-color); padding: 5px 15px; border-radius: 20px; font-size: 0.75rem; font-weight: bold; }
    .featured-content { padding: 35px 35px 35px 0; display: flex; flex-direction: column; justify-content: center; }
    .featured-date { color: #666; font-size: 0.85rem; margin-bottom: 15px; display: flex; align-items: center; gap: 8px; }
    .featured-date i { color: var(--accent-color); }
    .featured-title { font-size: 1.8rem; color: var(--primary-color); margin-bottom: 15px; font-weight: 700; }
    .featured-excerpt { color: #555; margin-bottom: 25px; }
    .btn-featured { display: inline-flex; align-items: center; gap: 8px; background: var(--primary-color); color: white; padding: 12px 25px; border-radius: 50px; text-decoration: none; font-weight: 600; transition: all 0.3s; width: fit-content; }
    .btn-featured:hover { background: var(--secondary-color); transform: translateY(-2px); gap: 12px; }
    .highlights-section { margin-bottom: 50px; }
    .highlights-title { font-size: 1.5rem; color: var(--primary-color); margin-bottom: 25px; padding-bottom: 10px; border-bottom: 2px solid var(--accent-color); display: inline-block; }
    .highlights-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 30px; }
    .highlight-card { background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,35,102,0.1); transition: transform 0.3s; border-left: 4px solid var(--accent-color); }
    .highlight-card:hover { transform: translateY(-5px); }
    .highlight-image { height: 200px; background-size: cover; background-position: center; position: relative; }
    .news-category { position: absolute; top: 12px; right: 12px; background: var(--accent-color); color: var(--primary-color); padding: 4px 12px; border-radius: 20px; font-size: 0.7rem; font-weight: bold; }
    .news-content { padding: 20px; }
    .news-date { color: #999; font-size: 0.75rem; margin-bottom: 8px; display: flex; align-items: center; gap: 5px; }
    .news-date i { color: var(--accent-color); }
    .news-excerpt { color: #666; font-size: 0.85rem; margin-bottom: 12px; }
    .read-more { font-size: 0.8rem; color: var(--secondary-color); text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 5px; }
    .read-more:hover { gap: 8px; }
    .section-divider { text-align: center; margin: 40px 0; position: relative; }
    .section-divider::before { content: ''; position: absolute; top: 50%; left: 0; right: 0; height: 1px; background: #ddd; z-index: 0; }
    .section-divider span { background: var(--body-bg); padding: 0 20px; color: var(--primary-color); font-weight: 600; position: relative; z-index: 1; }
    .news-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px; }
    .news-card { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.05); transition: all 0.3s; }
    .news-card:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,35,102,0.1); }
    .news-image { height: 160px; background-size: cover; background-position: center; position: relative; }
    .news-title { fontSize: 1rem; color: var(--primary-color); margin-bottom: 8px; font-weight: 600; line-height: 1.4; }
    @media (max-width: 768px) {
        .featured-card { grid-template-columns: 1fr; }
        .featured-image { min-height: 200px; }
        .featured-content { padding: 25px; }
        .featured-title { font-size: 1.3rem; }
        .highlights-grid, .news-grid { grid-template-columns: 1fr; }
    }
";

include 'includes/header.php';
?>

<div class="container news-section" style="padding: 60px 0;">
    <?php if($featured): ?>
    <div class="featured-article">
        <div class="featured-label"><i class="fas fa-star"></i> Featured Story</div>
        <div class="featured-card">
            <div class="featured-image" style="background-image: url('<?php echo isset($featured['image_url']) && !empty($featured['image_url']) ? htmlspecialchars($featured['image_url']) : 'https://placehold.co/800x600/002366/FFD700?text=Featured+Story'; ?>');">
                <div class="featured-image-category"><?php echo ucfirst($featured['category']); ?></div>
            </div>
            <div class="featured-content">
                <div class="featured-date"><i class="far fa-calendar-alt"></i> <?php echo date('F d, Y', strtotime(isset($featured['published_date']) ? $featured['published_date'] : $featured['created_at'])); ?></div>
                <h2 class="featured-title"><?php echo htmlspecialchars($featured['title']); ?></h2>
                <p class="featured-excerpt"><?php echo htmlspecialchars(substr(isset($featured['excerpt']) && !empty($featured['excerpt']) ? $featured['excerpt'] : $featured['content'], 0, 200)); ?>...</p>
                <a href="#" class="btn-featured">Read Full Story <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if(count($highlights) > 0): ?>
    <div class="highlights-section">
        <h2 class="highlights-title"><i class="fas fa-star"></i> More News</h2>
        <div class="highlights-grid">
            <?php foreach($highlights as $highlight): ?>
            <div class="highlight-card">
                <div class="highlight-image" style="background-image: url('<?php echo isset($highlight['image_url']) && !empty($highlight['image_url']) ? htmlspecialchars($highlight['image_url']) : 'https://placehold.co/800x600/002366/FFD700?text=News'; ?>');">
                    <span class="news-category"><?php echo ucfirst($highlight['category']); ?></span>
                </div>
                <div class="news-content">
                    <div class="news-date"><i class="far fa-calendar-alt"></i> <?php echo date('F d, Y', strtotime(isset($highlight['published_date']) ? $highlight['published_date'] : $highlight['created_at'])); ?></div>
                    <h3 class="news-title" style="font-size: 1.2rem;"><?php echo htmlspecialchars($highlight['title']); ?></h3>
                    <p class="news-excerpt"><?php echo htmlspecialchars(substr(isset($highlight['excerpt']) && !empty($highlight['excerpt']) ? $highlight['excerpt'] : $highlight['content'], 0, 100)); ?>...</p>
                    <a href="#" class="read-more">Read More →</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if(count($regular_news) > 0): ?>
    <div class="section-divider"><span>Latest Updates</span></div>
    <div class="news-grid">
        <?php foreach($regular_news as $article): ?>
        <div class="news-card">
            <div class="news-image" style="background-image: url('<?php echo isset($article['image_url']) && !empty($article['image_url']) ? htmlspecialchars($article['image_url']) : 'https://placehold.co/800x600/002366/FFD700?text=News'; ?>');">
                <span class="news-category"><?php echo ucfirst($article['category']); ?></span>
            </div>
            <div class="news-content">
                <div class="news-date"><i class="far fa-calendar-alt"></i> <?php echo date('F d, Y', strtotime(isset($article['published_date']) ? $article['published_date'] : $article['created_at'])); ?></div>
                <h3 class="news-title"><?php echo htmlspecialchars($article['title']); ?></h3>
                <p class="news-excerpt"><?php echo htmlspecialchars(substr(isset($article['excerpt']) && !empty($article['excerpt']) ? $article['excerpt'] : $article['content'], 0, 80)); ?>...</p>
                <a href="#" class="read-more">Read More →</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php elseif(!$featured && count($highlights) == 0): ?>
    <div class="empty-state" style="text-align: center; padding: 60px; background: white; border-radius: 20px;">
        <i class="fas fa-newspaper" style="font-size: 3rem; color: var(--accent-color); margin-bottom: 15px; display: block;"></i>
        <h3>No News Articles Yet</h3>
        <p>Check back soon for updates and announcements!</p>
        <a href="index.php" class="btn-home" style="display: inline-flex; align-items: center; gap: 8px; background: var(--primary-color); color: white; padding: 10px 25px; border-radius: 50px; text-decoration: none; margin-top: 20px;">Return Home</a>
    </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>