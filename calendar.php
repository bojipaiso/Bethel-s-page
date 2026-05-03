<?php
// calendar.php
require_once 'includes/db.php';

$calendar_pdfs = $pdo->query("SELECT * FROM calendar_pdfs WHERE status='active' ORDER BY is_current DESC, school_year DESC")->fetchAll();
$has_calendars = count($calendar_pdfs) > 0;

$coming_soon = $pdo->query("SELECT setting_value FROM school_settings WHERE setting_key = 'calendar_coming_soon'")->fetchColumn();
if($coming_soon == '1') {
    header("Location: coming-soon.php?resource=calendar");
    exit();
}

$page_title = 'Academic Calendar | Bethel International School';
$banner_title = 'Academic Calendar';
$banner_subtitle = 'Download and view important dates for the school year';
$show_banner = true;

$additional_css = "
    .calendar-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 30px; }
    .calendar-card { background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 5px 20px rgba(0,0,0,0.05); border: 1px solid var(--gray-border); transition: all 0.3s; }
    .calendar-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,35,102,0.1); border-color: var(--accent-color); }
    .calendar-card.current { border: 2px solid var(--accent-color); position: relative; }
    .calendar-card.current::before { content: '★ CURRENT'; position: absolute; top: 15px; right: 15px; background: var(--accent-color); color: var(--primary-color); padding: 4px 12px; border-radius: 20px; font-size: 0.7rem; font-weight: bold; z-index: 1; }
    .calendar-icon { background: linear-gradient(135deg, #f8f9fa, #e9ecef); padding: 35px; text-align: center; transition: all 0.3s; }
    .calendar-card:hover .calendar-icon { background: linear-gradient(135deg, #e9ecef, #dee2e6); }
    .calendar-icon i { font-size: 2.8rem; color: var(--primary-color); transition: all 0.3s; }
    .calendar-card:hover .calendar-icon i { transform: scale(1.05); }
    .calendar-content { padding: 25px; }
    .calendar-title { color: var(--primary-color); font-size: 1.2rem; margin-bottom: 8px; font-weight: 600; }
    .calendar-year { color: #888; font-size: 0.85rem; margin-bottom: 20px; display: flex; align-items: center; gap: 6px; }
    .btn-download { display: inline-flex; align-items: center; justify-content: center; gap: 8px; width: 100%; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white; padding: 12px 20px; border-radius: 8px; text-decoration: none; font-weight: 500; transition: all 0.3s; }
    .btn-download:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,35,102,0.3); }
    .no-calendar { text-align: center; padding: 60px; background: white; border-radius: 16px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); border: 1px solid var(--gray-border); }
    .no-calendar i { font-size: 4rem; color: var(--accent-color); margin-bottom: 20px; }
    .btn-home { display: inline-flex; align-items: center; gap: 10px; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white; padding: 12px 30px; border-radius: 50px; text-decoration: none; font-weight: 600; margin-top: 20px; }
    @media (max-width: 768px) { .calendar-grid { grid-template-columns: 1fr; } }
";

include 'includes/header.php';
?>

<div class="container calendar-section" style="padding: 60px 0;">
    <?php if($has_calendars): ?>
        <div class="calendar-grid">
            <?php foreach($calendar_pdfs as $calendar): ?>
                <div class="calendar-card <?php echo $calendar['is_current'] ? 'current' : ''; ?>">
                    <div class="calendar-icon"><i class="fas fa-calendar-alt"></i></div>
                    <div class="calendar-content">
                        <h3 class="calendar-title"><?php echo htmlspecialchars($calendar['title']); ?></h3>
                        <div class="calendar-year"><i class="fas fa-calendar-week"></i> School Year: <?php echo htmlspecialchars($calendar['school_year']); ?></div>
                        <a href="<?php echo $calendar['pdf_url']; ?>" class="btn-download" target="_blank"><i class="fas fa-download"></i> Download PDF</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="no-calendar">
            <i class="fas fa-calendar-times"></i>
            <h3>No Calendar Available Yet</h3>
            <p>The academic calendar is currently being prepared. Please check back soon!</p>
            <a href="index.php" class="btn-home"><i class="fas fa-home"></i> Return Home</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>