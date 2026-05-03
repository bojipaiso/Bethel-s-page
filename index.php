<?php
// index.php
require_once 'includes/db.php';

$announcements = $pdo->query("SELECT * FROM announcements WHERE status='active' ORDER BY display_order ASC, created_at DESC LIMIT 5")->fetchAll();
$features = $pdo->query("SELECT * FROM features WHERE status='active' ORDER BY display_order ASC")->fetchAll();
$hero = $pdo->query("SELECT * FROM hero_content ORDER BY id DESC LIMIT 1")->fetch();

$hero_title = isset($hero['title']) ? $hero['title'] : '🦅 Soaring to Excellence in International Education';
$hero_subtitle = isset($hero['subtitle']) ? $hero['subtitle'] : 'Inspired by the majesty of the Philippine Eagle, Bethel International School in Pawing, Palo, Leyte nurtures global citizens with strong Filipino values, academic excellence, and holistic development from kindergarten through senior high school.';
$hero_cta_link = isset($hero['cta_link']) ? $hero['cta_link'] : 'academics.php';
$hero_cta_text = isset($hero['cta_text']) ? $hero['cta_text'] : 'Explore Our Programs';
$hero_bg = isset($hero['background_image']) ? $hero['background_image'] : 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=1170';

$page_title = 'Home | Bethel International School';
$show_banner = false;

// Page-specific CSS
$additional_css = "
    .section-title {
        text-align: center;
        margin: 50px 0 30px;
        color: var(--primary-color);
        font-size: 2.2rem;
        position: relative;
        padding-bottom: 15px;
    }
    .section-title::after {
        content: '';
        position: absolute;
        width: 90px;
        height: 4px;
        background: var(--accent-color);
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        border-radius: 4px;
    }
    .features {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        margin: 40px 0;
    }
    .feature-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(0, 35, 102, 0.1);
        transition: transform 0.3s, box-shadow 0.3s;
        border-top: 5px solid var(--accent-color);
    }
    .feature-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 30px rgba(0, 35, 102, 0.2);
    }
    .feature-card img {
        width: 100%;
        height: 210px;
        object-fit: cover;
    }
    .feature-content {
        padding: 25px;
    }
    .feature-content h3 {
        color: var(--primary-color);
        margin-bottom: 12px;
        font-size: 1.5rem;
    }
    .announcements {
        background-color: white;
        border-radius: 20px;
        padding: 30px;
        margin: 40px 0;
        box-shadow: 0 10px 25px rgba(0, 35, 102, 0.08);
        border-left: 6px solid var(--secondary-color);
    }
    .announcement-item {
        padding: 18px 0;
        border-bottom: 1px solid #edf2f7;
        display: flex;
        align-items: center;
        gap: 20px;
    }
    .announcement-item:last-child {
        border-bottom: none;
    }
    .announcement-date {
        background-color: var(--primary-color);
        color: white;
        padding: 10px 15px;
        border-radius: 12px;
        text-align: center;
        min-width: 100px;
    }
    .announcement-date .day {
        font-size: 1.8rem;
        font-weight: 800;
        line-height: 1.2;
    }
    .announcement-date .month {
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .announcement-text h3 {
        color: var(--primary-color);
        margin-bottom: 6px;
        font-size: 1.3rem;
    }
    .hero {
        background: linear-gradient(rgba(0,35,102,0.85), rgba(0,86,179,0.9)), 
                    url('{$hero_bg}');
        background-size: cover;
        background-position: center 30%;
        color: white;
        padding: 100px 0;
        text-align: center;
    }
    .hero h2 {
        font-size: 2.8rem;
        margin-bottom: 20px;
        color: var(--accent-color);
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }
    .hero p {
        font-size: 1.3rem;
        max-width: 800px;
        margin: 0 auto 30px;
        font-weight: 400;
    }
    .cta-button {
        display: inline-block;
        background-color: var(--accent-color);
        color: var(--primary-color);
        padding: 12px 40px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 700;
        font-size: 1.2rem;
        transition: all 0.3s;
        box-shadow: 0 4px 12px rgba(0,0,0,0.25);
    }
    .cta-button:hover {
        background-color: white;
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.3);
    }
    @media (max-width: 768px) {
        .hero { padding: 70px 0; }
        .hero h2 { font-size: 1.9rem; }
        .section-title { font-size: 1.9rem; }
        .announcement-item { flex-direction: column; text-align: center; }
    }
";

include 'includes/header.php';
?>

<section class="hero">
    <div class="container">
        <h2><?php echo htmlspecialchars($hero_title); ?></h2>
        <p><?php echo nl2br(htmlspecialchars($hero_subtitle)); ?></p>
        <a href="<?php echo htmlspecialchars($hero_cta_link); ?>" class="cta-button"><?php echo htmlspecialchars($hero_cta_text); ?></a>
    </div>
</section>

<main class="container">
    <h2 class="section-title">Why Choose Bethel International School?</h2>
    <div class="features">
        <?php if(count($features) > 0): ?>
            <?php foreach($features as $feature): ?>
            <?php 
                $img_url = isset($feature['image_url']) ? $feature['image_url'] : 'https://via.placeholder.com/800x600/002366/ffffff?text=Bethel+Feature';
            ?>
            <div class="feature-card">
                <img src="<?php echo htmlspecialchars($img_url); ?>" alt="<?php echo htmlspecialchars($feature['title']); ?>">
                <div class="feature-content">
                    <h3><?php echo htmlspecialchars($feature['title']); ?></h3>
                    <p><?php echo nl2br(htmlspecialchars($feature['description'])); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- default features -->
            <div class="feature-card">
                <img src="Images/Campus.png" alt="Campus">
                <div class="feature-content">
                    <h3>World-Class Facilities</h3>
                    <p>Our campus in Pawing, Palo features modern classrooms, science labs, sports facilities, and a well-stocked library to support holistic learning and innovation.</p>
                </div>
            </div>
            <div class="feature-card">
                <img src="Images/International.jpg" alt="International">
                <div class="feature-content">
                    <h3>International Curriculum</h3>
                    <p>We offer an internationally‑recognized curriculum combined with Filipino values and context to prepare students for global opportunities while remaining rooted in Philippine heritage.</p>
                </div>
            </div>
            <div class="feature-card">
                <img src="Images/Play.jpg" alt="Play">
                <div class="feature-content">
                    <h3>Soaring Talents Program</h3>
                    <p>Inspired by the Philippine Eagle, our Soaring Talents Program offers sports, arts, music, leadership, and cultural activities to help students discover and develop their unique talents.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <h2 class="section-title">Latest Announcements</h2>
    <div class="announcements">
        <?php if(count($announcements) > 0): ?>
            <?php foreach($announcements as $announcement): ?>
            <div class="announcement-item">
                <div class="announcement-date">
                    <div class="day"><?php echo $announcement['day']; ?></div>
                    <div class="month"><?php echo htmlspecialchars($announcement['month']); ?></div>
                </div>
                <div class="announcement-text">
                    <h3><?php echo htmlspecialchars($announcement['title']); ?></h3>
                    <p><?php echo nl2br(htmlspecialchars($announcement['description'])); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="announcement-item">
                <div class="announcement-date"><div class="day">15</div><div class="month">June</div></div>
                <div class="announcement-text"><h3>Enrollment for SY 2025-2026</h3><p>Enrollment is now open. Visit our campus for inquiries.</p></div>
            </div>
            <div class="announcement-item">
                <div class="announcement-date"><div class="day">25</div><div class="month">June</div></div>
                <div class="announcement-text"><h3>Philippine Eagle Festival</h3><p>Join us for our annual Philippine Eagle Festival on June 25-29.</p></div>
            </div>
            <div class="announcement-item">
                <div class="announcement-date"><div class="day">12</div><div class="month">June</div></div>
                <div class="announcement-text"><h3>Independence Day Celebration</h3><p>Celebrate Philippine Independence Day with us on June 12.</p></div>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>