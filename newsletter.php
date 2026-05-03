<?php
// newsletter.php
require_once 'includes/db.php';

$newsletters = $pdo->query("SELECT * FROM newsletters WHERE status='published' ORDER BY year DESC, published_date DESC")->fetchAll();
$has_newsletters = count($newsletters) > 0;

$coming_soon = $pdo->query("SELECT setting_value FROM school_settings WHERE setting_key = 'newsletter_coming_soon'")->fetchColumn();
if($coming_soon == '1') {
    header("Location: coming-soon.php?resource=newsletter");
    exit();
}

$page_title = 'School Newsletter | Bethel International School';
$banner_title = 'School Newsletter';
$banner_subtitle = 'Stay informed with our monthly updates and announcements';
$show_banner = true;

$additional_css = "
    .newsletter-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 30px; }
    .newsletter-card { background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 5px 20px rgba(0,0,0,0.05); border: 1px solid var(--gray-border); transition: all 0.3s; }
    .newsletter-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,35,102,0.1); border-color: var(--accent-color); }
    .newsletter-icon { background: linear-gradient(135deg, #f8f9fa, #e9ecef); padding: 35px; text-align: center; transition: all 0.3s; }
    .newsletter-card:hover .newsletter-icon { background: linear-gradient(135deg, #e9ecef, #dee2e6); }
    .newsletter-icon i { font-size: 2.8rem; color: var(--primary-color); transition: all 0.3s; }
    .newsletter-card:hover .newsletter-icon i { transform: scale(1.05); }
    .newsletter-content { padding: 25px; }
    .newsletter-title { color: var(--primary-color); font-size: 1.2rem; margin-bottom: 8px; font-weight: 600; }
    .newsletter-date { color: #888; font-size: 0.85rem; margin-bottom: 15px; display: flex; align-items: center; gap: 6px; }
    .newsletter-summary { color: #666; font-size: 0.9rem; line-height: 1.5; margin-bottom: 20px; }
    .btn-download { display: inline-flex; align-items: center; justify-content: center; gap: 8px; width: 100%; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white; padding: 12px 20px; border-radius: 8px; text-decoration: none; font-weight: 500; transition: all 0.3s; }
    .btn-download:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,35,102,0.3); }
    .no-newsletter { text-align: center; padding: 60px; background: white; border-radius: 16px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); border: 1px solid var(--gray-border); }
    .no-newsletter i { font-size: 4rem; color: var(--accent-color); margin-bottom: 20px; }
    .btn-home { display: inline-flex; align-items: center; gap: 10px; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white; padding: 12px 30px; border-radius: 50px; text-decoration: none; font-weight: 600; margin-top: 20px; }
    @media (max-width: 768px) { .newsletter-grid { grid-template-columns: 1fr; } }
";

include 'includes/header.php';
?>

<div class="container newsletter-section" style="padding: 60px 0;">
    <?php if($has_newsletters): ?>
        <div class="newsletter-grid">
            <?php foreach($newsletters as $nl): ?>
                <div class="newsletter-card">
                    <div class="newsletter-icon"><i class="fas fa-newspaper"></i></div>
                    <div class="newsletter-content">
                        <h3 class="newsletter-title"><?php echo htmlspecialchars($nl['title']); ?></h3>
                        <div class="newsletter-date"><i class="far fa-calendar-alt"></i> <?php echo $nl['month'] . ' ' . $nl['year']; ?><?php if(isset($nl['issue_number']) && !empty($nl['issue_number'])): ?> | Issue: <?php echo htmlspecialchars($nl['issue_number']); ?><?php endif; ?></div>
                        <?php if(isset($nl['summary']) && !empty($nl['summary'])): ?><p class="newsletter-summary"><?php echo nl2br(htmlspecialchars($nl['summary'])); ?></p><?php endif; ?>
                        <a href="<?php echo $nl['pdf_url']; ?>" class="btn-download" target="_blank"><i class="fas fa-download"></i> Download PDF</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="no-newsletter">
            <i class="fas fa-newspaper"></i>
            <h3>No Newsletters Available Yet</h3>
            <p>Check back soon for our latest newsletter updates!</p>
            <a href="index.php" class="btn-home"><i class="fas fa-home"></i> Return Home</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>