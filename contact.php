<?php
// contact.php - with full form styling
require_once 'includes/db.php';

$success_message = '';
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_message'])) {
    $success_message = "Thank you for your message! We'll get back to you soon.";
}

$page_title = 'Contact Us | Bethel International School';
$banner_title = 'Contact Us';
$banner_subtitle = 'We’d Love to Hear from You!';
$show_banner = true;

$additional_css = "
    .contact-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; }
    .contact-info { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,35,102,0.1); }
    .contact-info h2 { color: var(--primary-color); margin-bottom: 25px; }
    .info-item { display: flex; align-items: center; gap: 15px; margin-bottom: 25px; padding: 15px; background: #f8f9fa; border-radius: 10px; }
    .info-icon { width: 50px; height: 50px; background: var(--primary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--accent-color); font-size: 1.3rem; }
    .info-text h3 { color: var(--primary-color); margin-bottom: 5px; }
    .contact-form { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,35,102,0.1); }
    .contact-form h2 { color: var(--primary-color); margin-bottom: 25px; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #333; font-size: 0.85rem; }
    .form-group label i { color: var(--accent-color); margin-right: 6px; }
    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 0.9rem;
        transition: all 0.3s;
        font-family: inherit;
    }
    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(0,35,102,0.1);
    }
    .form-group textarea { resize: vertical; min-height: 100px; }
    .submit-btn {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 12px 30px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .submit-btn:hover {
        background: var(--secondary-color);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,35,102,0.3);
    }
    .alert { padding: 15px; border-radius: 5px; margin-bottom: 20px; background: #d4edda; color: #155724; border-left: 4px solid #28a745; }
    @media (max-width: 768px) { .contact-grid { grid-template-columns: 1fr; } }
";

include 'includes/header.php';
?>

<div class="container contact-content" style="padding: 60px 0;">
    <?php if($success_message): ?>
        <div class="alert"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <div class="contact-grid">
        <div class="contact-info">
            <h2><i class="fas fa-map-marker-alt"></i> Get in Touch</h2>
            <div class="info-item">
                <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
                <div class="info-text"><h3>Our Address</h3><p>Pawing, Palo, Leyte, Philippines 6501</p></div>
            </div>
            <div class="info-item">
                <div class="info-icon"><i class="fas fa-phone-alt"></i></div>
                <div class="info-text"><h3>Phone Numbers</h3><p>0917-173-0284<br>(053) 123-4567</p></div>
            </div>
            <div class="info-item">
                <div class="info-icon"><i class="fas fa-envelope"></i></div>
                <div class="info-text"><h3>Email Addresses</h3><p>secretary@bethel.edu.ph<br>admissions@bethel.edu.ph</p></div>
            </div>
            <div class="hours-item" style="display: flex; justify-content: space-between; border-bottom: 1px solid #eee; padding: 12px 0;"><span>Mon-Fri:</span><span>8AM - 5PM</span></div>
            <div class="hours-item" style="display: flex; justify-content: space-between; border-bottom: 1px solid #eee; padding: 12px 0;"><span>Sat:</span><span>9AM - 12PM</span></div>
            <div class="hours-item" style="display: flex; justify-content: space-between; padding: 12px 0;"><span>Sun:</span><span>Closed</span></div>
        </div>

        <div class="contact-form">
            <h2><i class="fas fa-paper-plane"></i> Send Us a Message</h2>
            <form method="POST">
                <div class="form-group"><label><i class="fas fa-user"></i> Your Name *</label><input type="text" name="name" required></div>
                <div class="form-group"><label><i class="fas fa-envelope"></i> Email Address *</label><input type="email" name="email" required></div>
                <div class="form-group"><label><i class="fas fa-tag"></i> Subject *</label>
                    <select name="subject" required>
                        <option value="">Select Subject</option>
                        <option value="Admissions">Admissions Inquiry</option>
                        <option value="Academic">Academic Concerns</option>
                        <option value="Events">Events & Activities</option>
                        <option value="Feedback">Feedback / Suggestion</option>
                    </select>
                </div>
                <div class="form-group"><label><i class="fas fa-align-left"></i> Message *</label><textarea name="message" rows="6" required placeholder="Write your message here..."></textarea></div>
                <button type="submit" name="send_message" class="submit-btn"><i class="fas fa-paper-plane"></i> Send Message</button>
            </form>
            <div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 10px; text-align: center;">
                <i class="fas fa-clock" style="color: var(--accent-color); font-size: 2rem;"></i>
                <p style="margin-top: 10px;"><strong>Response Time:</strong> We typically respond within 24-48 hours on business days.</p>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>