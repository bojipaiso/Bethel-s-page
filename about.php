<?php
// about.php
require_once 'includes/db.php';

$about_content = [];
$sections = $pdo->query("SELECT * FROM about_content WHERE status='active' ORDER BY display_order")->fetchAll();
foreach($sections as $section) {
    $about_content[$section['section']] = $section;
}
$statistics = $pdo->query("SELECT * FROM about_stats WHERE status='active' ORDER BY display_order")->fetchAll();
$core_values = $pdo->query("SELECT * FROM core_values WHERE status='active' ORDER BY display_order")->fetchAll();

$page_title = 'About Us | Bethel International School';
$banner_title = 'About Bethel International School';
$banner_subtitle = 'Excellence in Education, Rooted in Faith and Filipino Values';
$show_banner = true;

$additional_css = "
    .section-card { background: white; border-radius: 16px; margin-bottom: 30px; box-shadow: 0 10px 25px rgba(0,35,102,0.08); overflow: hidden; }
    .mission-vision-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0; }
    .mission-card, .vision-card { padding: 50px 45px; }
    .mission-card { border-right: 1px solid #dee2e6; }
    .section-icon { font-size: 2rem; color: var(--primary-color); margin-bottom: 15px; }
    .section-label { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 2px; color: #b8860b; font-weight: 700; margin-bottom: 12px; }
    .mission-card h2, .vision-card h2 { font-size: 1.8rem; color: var(--primary-color); margin-bottom: 20px; font-weight: 700; }
    .mission-text, .vision-text { color: #2d3748; line-height: 1.8; font-size: 1rem; }
    .mission-quote { margin-top: 20px; font-style: italic; color: #b8860b; border-left: 3px solid var(--accent-color); padding-left: 18px; }
    .story-card { background: #fafaf5; border-radius: 16px; padding: 50px 45px; text-align: center; margin-bottom: 30px; }
    .story-card h2 { font-size: 1.8rem; margin-bottom: 15px; }
    .story-subtitle { color: #b8860b; font-size: 0.8rem; text-transform: uppercase; margin-bottom: 20px; }
    .values-card { background: white; border-radius: 16px; padding: 50px 45px; margin-bottom: 30px; }
    .values-card h2 { text-align: center; font-size: 1.8rem; margin-bottom: 12px; }
    .values-intro { text-align: center; color: #2d3748; max-width: 600px; margin: 0 auto 40px; }
    .values-grid { display: grid; grid-template-columns: repeat(5,1fr); gap: 20px; }
    .value-item { text-align: center; padding: 25px 15px; background: #f8fafc; border-radius: 12px; border: 1px solid #dee2e6; transition: all 0.3s; }
    .value-item:hover { transform: translateY(-3px); background: white; border-color: var(--accent-color); }
    .value-icon { width: 55px; height: 55px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; box-shadow: 0 3px 10px rgba(0,0,0,0.05); }
    .value-icon i { font-size: 1.3rem; color: var(--primary-color); }
    .value-item h3 { font-size: 1rem; margin-bottom: 8px; font-weight: 700; }
    .value-item p { font-size: 0.85rem; color: #2d3748; }
    .stats-card { background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); border-radius: 16px; padding: 50px 45px; text-align: center; margin-bottom: 30px; }
    .stats-card h2 { color: white; font-size: 1.8rem; }
    .stats-subtitle { color: rgba(255,255,255,0.7); margin-bottom: 40px; }
    .stats-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 25px; max-width: 900px; margin: 0 auto; }
    .stat-item { text-align: center; }
    .stat-number { font-size: 2.2rem; font-weight: 700; color: var(--accent-color); margin-bottom: 5px; }
    .stat-label { font-size: 0.8rem; color: rgba(255,255,255,0.65); text-transform: uppercase; }
    @media (max-width: 992px) {
        .mission-vision-grid { grid-template-columns: 1fr; }
        .mission-card { border-right: none; border-bottom: 1px solid #dee2e6; }
        .values-grid { grid-template-columns: repeat(3,1fr); }
        .stats-grid { grid-template-columns: repeat(2,1fr); }
    }
    @media (max-width: 768px) {
        .mission-card, .vision-card, .story-card, .values-card, .stats-card { padding: 35px; }
        .values-grid { grid-template-columns: repeat(2,1fr); }
        .stats-grid { grid-template-columns: 1fr; }
    }
";

include 'includes/header.php';
?>

<div class="container">
    <!-- Mission + Vision Card -->
    <div class="section-card">
        <div class="mission-vision-grid">
            <div class="mission-card">
                <div class="section-icon"><i class="fas fa-bullseye"></i></div>
                <div class="section-label">Our Purpose</div>
                <h2><?php echo htmlspecialchars(isset($about_content['mission']['title']) ? $about_content['mission']['title'] : 'Mission'); ?></h2>
                <div class="mission-text"><?php echo nl2br(htmlspecialchars(isset($about_content['mission']['content']) ? $about_content['mission']['content'] : '')); ?></div>
                <div class="mission-quote">"Guiding every student to soar like an eagle"</div>
            </div>
            <div class="vision-card">
                <div class="section-icon"><i class="fas fa-eye"></i></div>
                <div class="section-label">Our Aspiration</div>
                <h2><?php echo htmlspecialchars(isset($about_content['vision']['title']) ? $about_content['vision']['title'] : 'Vision'); ?></h2>
                <div class="vision-text"><?php echo nl2br(htmlspecialchars(isset($about_content['vision']['content']) ? $about_content['vision']['content'] : '')); ?></div>
            </div>
        </div>
    </div>

    <!-- Our Story Card -->
    <div class="story-card">
        <div class="story-subtitle">Since 2001</div>
        <h2><?php echo htmlspecialchars(isset($about_content['history']['title']) ? $about_content['history']['title'] : 'Our Story'); ?></h2>
        <div class="story-text"><?php echo nl2br(htmlspecialchars(isset($about_content['history']['content']) ? $about_content['history']['content'] : '')); ?></div>
    </div>

    <!-- Core Values Card -->
    <div class="values-card">
        <h2><?php echo htmlspecialchars(isset($about_content['core_values']['title']) ? $about_content['core_values']['title'] : 'Our Core Values'); ?></h2>
        <div class="values-intro">These values guide everything we do, shaping our students into well‑rounded individuals.</div>
        <div class="values-grid">
            <?php if(count($core_values) > 0): ?>
                <?php foreach($core_values as $value): ?>
                <div class="value-item">
                    <div class="value-icon"><i class="<?php echo htmlspecialchars(isset($value['icon_class']) ? $value['icon_class'] : 'fas fa-star'); ?>"></i></div>
                    <h3><?php echo htmlspecialchars($value['title']); ?></h3>
                    <p><?php echo nl2br(htmlspecialchars($value['description'])); ?></p>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="value-item"><div class="value-icon"><i class="fas fa-trophy"></i></div><h3>Excellence</h3><p>We strive for the highest standards.</p></div>
                <div class="value-item"><div class="value-icon"><i class="fas fa-hands-helping"></i></div><h3>Faith</h3><p>We nurture spiritual growth.</p></div>
                <div class="value-item"><div class="value-icon"><i class="fas fa-heart"></i></div><h3>Service</h3><p>We develop compassionate leaders.</p></div>
                <div class="value-item"><div class="value-icon"><i class="fas fa-globe-asia"></i></div><h3>Global Citizenship</h3><p>We prepare students for an interconnected world.</p></div>
                <div class="value-item"><div class="value-icon"><i class="fas fa-lightbulb"></i></div><h3>Innovation</h3><p>We embrace creativity and change.</p></div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Statistics Card -->
    <?php if(count($statistics) > 0): ?>
    <div class="stats-card">
        <h2>At a Glance</h2>
        <div class="stats-subtitle">Our journey in numbers</div>
        <div class="stats-grid">
            <?php foreach($statistics as $stat): ?>
            <div class="stat-item">
                <div class="stat-number"><?php echo htmlspecialchars($stat['stat_number']); ?></div>
                <div class="stat-label"><?php echo htmlspecialchars($stat['stat_label']); ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>