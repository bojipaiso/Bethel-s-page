<?php
// admissions.php (corrected spacing)
require_once 'includes/db.php';

$welcome = $pdo->query("SELECT * FROM admissions_content WHERE section = 'welcome'")->fetch();
$enrollment = $pdo->query("SELECT * FROM admissions_content WHERE section = 'enrollment_period'")->fetch();
$classes = $pdo->query("SELECT * FROM admissions_content WHERE section = 'classes_start'")->fetch();
$requirements = $pdo->query("SELECT * FROM admissions_content WHERE section = 'requirements'")->fetch();
$steps = $pdo->query("SELECT * FROM admission_steps WHERE status='active' ORDER BY display_order ASC")->fetchAll();

$page_title = 'Admissions | Bethel International School';
$banner_title = 'Admissions';
$banner_subtitle = 'Begin Your Journey to Excellence at Bethel International School';
$show_banner = true;

$additional_css = "
    .info-box { background: white; border-radius: 15px; padding: 30px; margin-bottom: 40px; box-shadow: 0 10px 25px rgba(0,35,102,0.1); border-left: 5px solid var(--accent-color); }
    .info-box h2 { color: var(--primary-color); margin-bottom: 20px; font-size: 1.5rem; }
    .steps { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 25px; margin: 40px 0; }
    .step { text-align: center; padding: 25px; background: white; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,35,102,0.1); transition: transform 0.3s; }
    .step:hover { transform: translateY(-5px); }
    .step-number { width: 45px; height: 45px; background: var(--primary-color); color: var(--accent-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; font-weight: bold; margin: 0 auto 15px; }
    .step h3 { color: var(--primary-color); margin-bottom: 10px; font-size: 1.1rem; }
    .step p { color: #666; font-size: 0.9rem; }
    .requirements-list { list-style: none; margin-top: 20px; }
    .requirements-list li { padding: 10px 0; border-bottom: 1px solid #eee; display: flex; align-items: center; gap: 10px; }
    .requirements-list li i { color: var(--accent-color); width: 25px; }
    .cta-box { background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white; padding: 40px; border-radius: 15px; text-align: center; margin-top: 60px; }  /* Increased margin-top from default */
    .cta-box h2 { color: var(--accent-color); margin-bottom: 20px; }
    .cta-box p { margin-bottom: 25px; }
    .cta-box .btn { display: inline-block; background: var(--accent-color); color: var(--primary-color); padding: 12px 30px; border-radius: 50px; text-decoration: none; font-weight: bold; transition: transform 0.3s; }
    .cta-box .btn:hover { transform: scale(1.05); }
    @media (max-width: 768px) { .steps { grid-template-columns: 1fr; } .cta-box { padding: 25px; margin-top: 40px; } }
";

include 'includes/header.php';
?>

<div class="container admissions-content" style="padding: 60px 0;">
    <div class="info-box">
        <h2><?php echo htmlspecialchars(isset($welcome['title']) ? $welcome['title'] : 'Welcome Future Eagles!'); ?></h2>
        <p><?php echo nl2br(htmlspecialchars(isset($welcome['content']) ? $welcome['content'] : 'Bethel International School is now accepting applications for School Year 2025-2026. We invite you to become part of our growing community of learners who are committed to academic excellence, character development, and holistic growth.')); ?></p>
        <p><strong>Enrollment Period:</strong> <?php echo nl2br(htmlspecialchars(isset($enrollment['content']) ? $enrollment['content'] : 'March 1 - July 15, 2025')); ?></p>
        <p><strong>Classes Start:</strong> <?php echo nl2br(htmlspecialchars(isset($classes['content']) ? $classes['content'] : 'August 4, 2025')); ?></p>
    </div>

    <h2 class="section-title" style="margin: 40px 0 30px; text-align: center; color: var(--primary-color);">Admission Process</h2>
    <div class="steps">
        <?php if(count($steps) > 0): ?>
            <?php foreach($steps as $step): ?>
            <div class="step">
                <div class="step-number"><?php echo $step['step_number']; ?></div>
                <h3><?php echo htmlspecialchars($step['title']); ?></h3>
                <p><?php echo nl2br(htmlspecialchars($step['description'])); ?></p>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="step"><div class="step-number">1</div><h3>Submit Application</h3><p>Fill out the application form and submit required documents.</p></div>
            <div class="step"><div class="step-number">2</div><h3>Entrance Assessment</h3><p>Schedule and take the entrance examination.</p></div>
            <div class="step"><div class="step-number">3</div><h3>Interview</h3><p>Parent and student interview with administration.</p></div>
            <div class="step"><div class="step-number">4</div><h3>Enrollment</h3><p>Complete requirements and pay initial fees.</p></div>
        <?php endif; ?>
    </div>

    <div class="info-box">
        <h2><?php echo htmlspecialchars(isset($requirements['title']) ? $requirements['title'] : 'Requirements for Admission'); ?></h2>
        <ul class="requirements-list">
            <?php 
            $req_content = isset($requirements['content']) ? $requirements['content'] : '';
            $req_lines = explode("\n", $req_content);
            if(count($req_lines) > 0 && trim($req_lines[0]) != ''):
                foreach($req_lines as $line):
                    if(trim($line)):
            ?>
                <li><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars(trim($line)); ?></li>
            <?php 
                    endif;
                endforeach;
            else:
            ?>
                <li><i class="fas fa-check-circle"></i> Completed Application Form</li>
                <li><i class="fas fa-check-circle"></i> PSA Birth Certificate (Original & Photocopy)</li>
                <li><i class="fas fa-check-circle"></i> Report Card (SF9) from previous school</li>
                <li><i class="fas fa-check-circle"></i> Good Moral Certificate</li>
                <li><i class="fas fa-check-circle"></i> 2 pcs. 2x2 ID picture (white background)</li>
                <li><i class="fas fa-check-circle"></i> Medical Certificate</li>
            <?php endif; ?>
        </ul>
    </div>

    <div class="cta-box">
        <h2>Ready to Join the Bethel Family?</h2>
        <p>Take the first step towards a bright future. Contact us today to schedule a campus tour!</p>
        <a href="contact.php" class="btn">Inquire Now →</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>