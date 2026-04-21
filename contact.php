<?php
// contact.php
require_once 'includes/db.php';
$page_title = 'Contact Us | Bethel International School';

$success_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
    $success_message = "Thank you for your message! We'll get back to you soon.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Roboto, system-ui, sans-serif;
        }

        :root {
            --primary-color: #002366;
            --secondary-color: #0056b3;
            --accent-color: #FFD700;
            --light-color: #ffffff;
            --dark-color: #1a1a2e;
        }

        body {
            line-height: 1.6;
            color: var(--dark-color);
            background-color: #f8fafc;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 15px 0;
            box-shadow: 0 3px 15px rgba(0, 35, 102, 0.3);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo-icon {
            background-color: var(--accent-color);
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            overflow: hidden;
            flex-shrink: 0;
        }

        .eagle-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
        }

        .eagle-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .logo-text {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .logo-text h1 {
            font-size: 1.6rem;
            margin: 0;
            color: white;
            line-height: 1.2;
        }

        .logo-text .school-name {
            font-weight: 700;
            color: var(--accent-color);
        }

        .logo-text p {
            font-size: 0.8rem;
            opacity: 0.9;
            margin: 0;
            line-height: 1.3;
        }

        nav ul {
            display: flex;
            list-style: none;
            gap: 5px;
            margin: 0;
            padding: 0;
            align-items: center;
        }

        nav ul li {
            margin-left: 20px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
            font-size: 1rem;
            position: relative;
            padding: 5px 0;
            white-space: nowrap;
        }

        nav ul li a:hover {
            color: var(--accent-color);
        }

        nav ul li a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 3px;
            background: var(--accent-color);
            left: 0;
            bottom: -5px;
            transition: width 0.3s ease;
        }

        nav ul li a:hover::after,
        nav ul li a.active::after {
            width: 100%;
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.8rem;
            cursor: pointer;
            padding: 5px;
        }

        .page-banner {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 60px 0;
            text-align: center;
        }

        .page-banner h1 {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: var(--accent-color);
        }

        .page-banner p {
            font-size: 1.2rem;
            max-width: 800px;
            margin: 0 auto;
        }

        .contact-content {
            padding: 60px 0;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
        }

        .contact-info {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 35, 102, 0.1);
        }

        .contact-info h2 {
            color: var(--primary-color);
            margin-bottom: 25px;
            font-size: 1.8rem;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            transition: transform 0.3s;
        }

        .info-item:hover {
            transform: translateX(5px);
        }

        .info-icon {
            width: 50px;
            height: 50px;
            background: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent-color);
            font-size: 1.3rem;
        }

        .info-text h3 {
            color: var(--primary-color);
            margin-bottom: 5px;
        }

        .info-text p {
            color: #666;
        }

        .contact-form {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 35, 102, 0.1);
        }

        .contact-form h2 {
            color: var(--primary-color);
            margin-bottom: 25px;
            font-size: 1.8rem;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        .form-group textarea {
            resize: vertical;
        }

        .submit-btn {
            background: var(--primary-color);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }

        .submit-btn:hover {
            background: var(--secondary-color);
        }

        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .business-hours {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #eee;
        }

        .business-hours h3 {
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        /* Footer Styles */
        footer {
            background: linear-gradient(145deg, var(--dark-color) 0%, var(--primary-color) 100%);
            color: white;
            padding: 50px 0 20px;
            margin-top: 70px;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-column h3 {
            font-size: 1.2rem;
            margin-bottom: 20px;
            color: var(--accent-color);
            border-left: 4px solid var(--accent-color);
            padding-left: 12px;
        }

        .footer-column p {
            margin-bottom: 10px;
            color: #ddd;
            font-size: 0.9rem;
        }

        .footer-column i {
            margin-right: 10px;
            color: var(--accent-color);
            width: 25px;
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 12px;
        }

        .footer-links li a {
            color: #ddd;
            text-decoration: none;
            transition: color 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }

        .footer-links li a:hover {
            color: var(--accent-color);
            transform: translateX(5px);
        }

        .hours-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            color: #ddd;
            font-size: 0.9rem;
        }

        .hours-item span:first-child {
            font-weight: 600;
        }

        .emergency-number {
            background: rgba(255, 215, 0, 0.15);
            padding: 12px;
            border-radius: 10px;
            margin-top: 15px;
            text-align: center;
            border: 1px solid var(--accent-color);
        }

        .emergency-number p {
            margin-bottom: 5px;
            font-size: 0.85rem;
        }

        .emergency-number a {
            color: var(--accent-color);
            text-decoration: none;
            font-size: 1.1rem;
            font-weight: bold;
        }

        .social-icons {
            display: flex;
            flex-direction: row;
            gap: 12px;
            margin-top: 15px;
        }

        .social-icons a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            background-color: rgba(255,255,255,0.1);
            border-radius: 50%;
            transition: all 0.3s;
        }

        .social-icons a svg {
            width: 16px;
            height: 16px;
            display: block;
            fill: var(--accent-color);
            transition: fill 0.3s ease;
        }

        .social-icons a:hover {
            background-color: var(--accent-color);
            transform: translateY(-3px);
        }

        .social-icons a:hover svg {
            fill: white;
        }

        .copyright {
            text-align: center;
            padding-top: 25px;
            border-top: 1px solid rgba(255,255,255,0.1);
            font-size: 0.85rem;
            color: #aaa;
        }

        .admin-login-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: var(--primary-color);
            color: white;
            padding: 8px 12px;
            border-radius: 50px;
            text-decoration: none;
            font-size: 12px;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .admin-login-btn:hover {
            background: var(--accent-color);
            color: var(--primary-color);
        }

        @media (max-width: 992px) {
            .logo-text h1 { font-size: 1.4rem; }
            .logo-icon { width: 50px; height: 50px; }
        }

        @media (max-width: 768px) {
            .header-container {
                flex-direction: row;
                justify-content: space-between;
            }
            .logo { flex: 1; }
            nav ul {
                display: none;
                flex-direction: column;
                background-color: var(--primary-color);
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                padding: 20px 0;
                box-shadow: 0 10px 15px rgba(0,0,0,0.2);
                border-radius: 0 0 20px 20px;
                z-index: 99;
            }
            nav ul.active { display: flex; }
            nav ul li { margin: 0; text-align: center; padding: 12px 0; }
            .mobile-menu-btn { display: block; }
            .contact-grid { grid-template-columns: 1fr; }
            .footer-content { grid-template-columns: 1fr; text-align: center; gap: 30px; }
            .footer-column h3 { border-left: none; padding-left: 0; text-align: center; }
            .hours-item { justify-content: center; gap: 20px; }
            .social-icons { justify-content: center; }
            .footer-links li a { justify-content: center; }
        }

        @media (max-width: 576px) {
            .logo { gap: 10px; }
            .logo-icon { width: 45px; height: 45px; }
            .logo-text h1 { font-size: 1.2rem; }
            .logo-text p { font-size: 0.65rem; }
        }
    </style>
</head>
<body>
    <header>
        <div class="container header-container">
            <div class="logo">
                <div class="logo-icon">
                    <div class="eagle-icon">
                      <img src="images/bethel-logo.png" alt="Bethel International School Logo">
                    </div>
                </div>
                <div class="logo-text">
                    <h1><span class="school-name">Bethel International School</span></h1>
                    <p>Pawing, Palo, Leyte</p>
                </div>
            </div>
            
            <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="menu">
                <i class="fas fa-bars"></i>
            </button>
            
            <nav>
                <ul id="mainNav">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="academics.php">Academics</a></li>
                    <li><a href="admissions.php">Admissions</a></li>
                    <li><a href="news.php">News & Events</a></li>
                    <li><a href="contact.php" class="active">Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="page-banner">
        <div class="container">
            <h1>Contact Us</h1>
            <p>We'd Love to Hear from You! Reach Out to Bethel International School</p>
        </div>
    </section>

    <div class="container contact-content">
        <?php if($success_message): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <div class="contact-grid">
            <!-- Contact Information -->
            <div class="contact-info">
                <h2><i class="fas fa-map-marker-alt" style="color: var(--accent-color);"></i> Get in Touch</h2>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="info-text">
                        <h3>Our Address</h3>
                        <p>Pawing, Palo, Leyte<br>Philippines 6501</p>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <div class="info-text">
                        <h3>Phone Numbers</h3>
                        <p>Main: 0917-173-0284<br>Office: (053) 123-4567</p>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="info-text">
                        <h3>Email Addresses</h3>
                        <p>secretary@bethel.edu.ph<br>admissions@bethel.edu.ph</p>
                    </div>
                </div>
                
                <div class="business-hours">
                    <h3>Office Hours</h3>
                    <div class="hours-item">
                        <span>Monday - Friday:</span>
                        <span>8:00 AM - 5:00 PM</span>
                    </div>
                    <div class="hours-item">
                        <span>Saturday:</span>
                        <span>9:00 AM - 12:00 PM</span>
                    </div>
                    <div class="hours-item">
                        <span>Sunday:</span>
                        <span>Closed</span>
                    </div>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div class="contact-form">
                <h2><i class="fas fa-paper-plane" style="color: var(--accent-color);"></i> Send Us a Message</h2>
                
                <form method="POST">
                    <div class="form-group">
                        <label>Your Name *</label>
                        <input type="text" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Email Address *</label>
                        <input type="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Subject *</label>
                        <select name="subject" required>
                            <option value="">Select Subject</option>
                            <option value="Admissions">Admissions Inquiry</option>
                            <option value="Academic">Academic Concerns</option>
                            <option value="Events">Events & Activities</option>
                            <option value="Feedback">Feedback / Suggestion</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Message *</label>
                        <textarea name="message" rows="6" required placeholder="Write your message here..."></textarea>
                    </div>
                    
                    <button type="submit" name="send_message" class="submit-btn">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </form>
                
                <div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 10px; text-align: center;">
                    <i class="fas fa-clock" style="color: var(--accent-color); font-size: 2rem;"></i>
                    <p style="margin-top: 10px;"><strong>Response Time:</strong> We typically respond within 24-48 hours on business days.</p>
                </div>
            </div>
        </div>
    </div>

     <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <!-- School Info -->
                <div class="footer-column">
                    <h3>Bethel International School</h3>
                    <p><i class="fas fa-map-marker-alt"></i> Pawing, Palo, Leyte, Philippines 6501</p>
                    <p><i class="fas fa-phone-alt"></i> 0917-173-0284</p>
                    <p><i class="fas fa-envelope"></i> secretary@bethel.edu.ph</p>
                    <div class="social-icons">
                        <a href="https://www.facebook.com/BethelInternationalSchool" aria-label="Facebook">
                            <svg viewBox="0 0 24 24" width="16" height="16">
                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
                            </svg>
                        </a>
                        <a href="https://www.instagram.com/bethel.sc/" aria-label="Instagram">
                            <svg viewBox="0 0 24 24" width="16" height="16">
                                <path d="M12 2c2.7 0 3 .1 4.1.2 3.2.2 4.8 1.8 5 5 .1 1.1.1 1.4.1 4.8s0 3.7-.1 4.8c-.2 3.2-1.8 4.8-5 5-1.1.1-1.4.1-4.1.1s-3 0-4.1-.1c-3.2-.2-4.8-1.8-5-5-.1-1.1-.1-1.4-.1-4.8s0-3.7.1-4.8c.2-3.2 1.8-4.8 5-5C9 2.1 9.3 2 12 2zm0 3.8c-3.4 0-6.2 2.8-6.2 6.2s2.8 6.2 6.2 6.2 6.2-2.8 6.2-6.2-2.8-6.2-6.2-6.2zm0 10.2c-2.2 0-4-1.8-4-4s1.8-4 4-4 4 1.8 4 4-1.8 4-4 4zm6.4-11.8c-.8 0-1.4.6-1.4 1.4s.6 1.4 1.4 1.4 1.4-.6 1.4-1.4-.6-1.4-1.4-1.4z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Resources - PDF Links -->
                <div class="footer-column">
                    <h3>Resources</h3>
                    <ul class="footer-links">
                        <li><a href="calendar.php"><i class="fas fa-calendar-alt"></i> Academic Calendar</a></li>
                        <li><a href="newsletter.php"><i class="fas fa-newspaper"></i> School Newsletter</a></li>
                    </ul>
                </div>

                <!-- Hours -->
                <div class="footer-column">
                    <h3>Hours</h3>
                    <div class="hours-item"><span>Mon-Fri:</span><span>8AM - 5PM</span></div>
                    <div class="hours-item"><span>Sat:</span><span>9AM - 12PM</span></div>
                    <div class="hours-item"><span>Sun:</span><span>Closed</span></div>
                    <div class="emergency-number">
                        <p>Emergency</p>
                        <a href="tel:+639171730284">0917-173-0284</a>
                    </div>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; 2026 Bethel International School, Pawing, Palo, Leyte. All Rights Reserved.</p>
                <p style="font-size: 0.75rem; margin-top: 8px;">The Philippine Eagle symbolizes our commitment to strength, vision, and soaring excellence.</p>
            </div>
        </div>
    </footer>

    <a href="admin/login.php" class="admin-login-btn">🔧 Admin</a>

    <script>
        const mobileBtn = document.getElementById('mobileMenuBtn');
        const mainNav = document.getElementById('mainNav');
        
        if(mobileBtn && mainNav) {
            mobileBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                mainNav.classList.toggle('active');
                const icon = this.querySelector('i');
                if (mainNav.classList.contains('active')) {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-times');
                } else {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            });

            document.querySelectorAll('#mainNav a').forEach(link => {
                link.addEventListener('click', function() {
                    mainNav.classList.remove('active');
                    const icon = mobileBtn.querySelector('i');
                    if(icon) {
                        icon.classList.remove('fa-times');
                        icon.classList.add('fa-bars');
                    }
                });
            });
        }
    </script>
</body>
</html>