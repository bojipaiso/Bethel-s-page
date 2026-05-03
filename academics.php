<?php
// academics.php
require_once 'includes/db.php';

$levels = $pdo->query("SELECT * FROM academic_levels WHERE status='active' ORDER BY display_order")->fetchAll();
$programs = $pdo->query("SELECT p.*, l.level_name FROM academic_programs p LEFT JOIN academic_levels l ON p.level_id = l.id WHERE p.status='active' ORDER BY l.display_order, p.display_order")->fetchAll();
$special_programs = $pdo->query("SELECT * FROM special_programs WHERE status='active' ORDER BY display_order")->fetchAll();

$programs_by_level = [];
foreach($programs as $program) {
    $programs_by_level[$program['level_name']][] = $program;
}

$page_title = 'Academics | Bethel International School';
$banner_title = 'Academic Programs';
$banner_subtitle = 'Nurturing Minds, Shaping Futures - From Kindergarten to Senior High School';
$show_banner = true;

$additional_css = "
    .level-section { margin-bottom: 50px; }
    .level-title { font-size: 1.8rem; color: var(--primary-color); margin-bottom: 25px; padding-bottom: 10px; border-bottom: 3px solid var(--accent-color); display: inline-block; }
    .programs-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 25px; }
    .program-card { background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 5px 20px rgba(0,0,0,0.05); border: 1px solid var(--gray-border); transition: transform 0.3s; }
    .program-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,35,102,0.1); border-color: var(--accent-color); }
    .program-header { background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); padding: 25px; text-align: center; }
    .program-header i { font-size: 2rem; color: var(--accent-color); margin-bottom: 10px; display: block; }
    .program-header h3 { color: white; font-size: 1.3rem; }
    .program-body { padding: 25px; }
    .program-description { color: var(--text-gray); line-height: 1.7; margin-bottom: 20px; }
    .program-features { list-style: none; }
    .program-features li { padding: 8px 0; border-bottom: 1px solid var(--gray-border); display: flex; align-items: center; gap: 10px; font-size: 0.9rem; color: var(--text-gray); }
    .program-features li i { color: var(--accent-color); width: 20px; }
    .special-section { margin-top: 60px; padding: 50px 0; background: white; border-radius: 24px; }
    .special-title { text-align: center; font-size: 1.8rem; color: var(--primary-color); margin-bottom: 15px; }
    .special-subtitle { text-align: center; color: var(--text-gray); margin-bottom: 40px; }
    .special-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px; }
    .special-card { text-align: center; padding: 30px; background: var(--gray-light); border-radius: 16px; border: 1px solid var(--gray-border); transition: all 0.3s; }
    .special-card:hover { transform: translateY(-3px); border-color: var(--accent-color); background: white; }
    .special-card i { font-size: 2rem; color: var(--primary-color); margin-bottom: 15px; }
    .special-card h3 { font-size: 1.1rem; color: var(--primary-color); margin-bottom: 10px; }
    .special-card p { font-size: 0.85rem; color: var(--text-gray); }
    @media (max-width: 768px) {
        .programs-grid, .special-grid { grid-template-columns: 1fr; }
        .level-title { font-size: 1.5rem; }
    }
";

include 'includes/header.php';
?>

<div class="container" style="padding: 60px 0;">
    <?php foreach($levels as $level): ?>
        <?php if(isset($programs_by_level[$level['level_name']])): ?>
        <div class="level-section">
            <h2 class="level-title"><?php echo htmlspecialchars($level['level_name']); ?></h2>
            <div class="programs-grid">
                <?php foreach($programs_by_level[$level['level_name']] as $program): ?>
                <div class="program-card">
                    <div class="program-header">
                        <i class="<?php echo htmlspecialchars(isset($program['icon_class']) ? $program['icon_class'] : 'fas fa-graduation-cap'); ?>"></i>
                        <h3><?php echo htmlspecialchars($program['title']); ?></h3>
                    </div>
                    <div class="program-body">
                        <p class="program-description"><?php echo nl2br(htmlspecialchars($program['description'])); ?></p>
                        <?php 
                        $features = json_decode($program['features'], true);
                        if(is_array($features) && count($features) > 0):
                        ?>
                        <ul class="program-features">
                            <?php foreach($features as $feature): ?>
                            <li><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($feature); ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    <?php endforeach; ?>

    <?php if(count($special_programs) > 0): ?>
    <div class="special-section">
        <h2 class="special-title">Special Programs</h2>
        <p class="special-subtitle">Enriching experiences beyond the classroom</p>
        <div class="special-grid">
            <?php foreach($special_programs as $special): ?>
            <div class="special-card">
                <i class="<?php echo htmlspecialchars(isset($special['icon_class']) ? $special['icon_class'] : 'fas fa-star'); ?>"></i>
                <h3><?php echo htmlspecialchars($special['title']); ?></h3>
                <p><?php echo nl2br(htmlspecialchars($special['description'])); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>