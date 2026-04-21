<?php
// index.php - COMPLETE VERSION with SVG social icons (100% reliable)
require_once 'includes/db.php';

// Fetch data from database
$announcements = $pdo->query("SELECT * FROM announcements WHERE status='active' ORDER BY display_order ASC, created_at DESC LIMIT 5")->fetchAll();
$features = $pdo->query("SELECT * FROM features WHERE status='active' ORDER BY display_order ASC")->fetchAll();
$hero = $pdo->query("SELECT * FROM hero_content ORDER BY id DESC LIMIT 1")->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bethel International School – Soaring Eagle, Palo Leyte</title>
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

        .hero {
            background: linear-gradient(rgba(0, 35, 102, 0.85), rgba(0, 86, 179, 0.9)), 
                        url('<?php echo $hero['background_image'] ?? 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80'; ?>');
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
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
        }

        .cta-button:hover {
            background-color: white;
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }

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
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
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

        /* Social Icons */
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
            .hero h2 { font-size: 2.2rem; }
            .hero p { font-size: 1.1rem; }
            .logo-text h1 { font-size: 1.4rem; }
            .logo-text p { font-size: 0.7rem; }
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
            .hero { padding: 70px 0; }
            .hero h2 { font-size: 1.9rem; }
            .section-title { font-size: 1.9rem; }
            .announcement-item { flex-direction: column; text-align: center; }
            .footer-content { grid-template-columns: 1fr; text-align: center; gap: 30px; }
            .footer-column h3 { border-left: none; padding-left: 0; text-align: center; }
            .hours-item { justify-content: center; gap: 20px; }
            .social-icons { justify-content: center; }
            .footer-links li a { justify-content: center; }=
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
                    <li><a href="index.php" class="active">Home</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="academics.php">Academics</a></li>
                    <li><a href="admissions.php">Admissions</a></li>
                    <li><a href="news.php">News & Events</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="hero">
        <div class="container">
            <h2><?php echo htmlspecialchars($hero['title'] ?? 'Soaring to Excellence in International Education'); ?></h2>
            <p><?php echo htmlspecialchars($hero['subtitle'] ?? 'Inspired by the majesty of the Philippine Eagle, Bethel International School in Pawing, Palo, Leyte nurtures global citizens with strong Filipino values, academic excellence, and holistic development from kindergarten through senior high school.'); ?></p>
            <a href="academics.php" class="cta-button"><?php echo htmlspecialchars($hero['cta_text'] ?? 'Explore Our Programs'); ?></a>
        </div>
    </section>

    <main class="container">
        <h2 class="section-title">Why Choose Bethel International School?</h2>
        <div class="features">
            <?php if(count($features) > 0): ?>
                <?php foreach($features as $feature): ?>
                <div class="feature-card">
                    <img src="<?php echo htmlspecialchars($feature['image_url'] ?: 'https://via.placeholder.com/800x600/002366/ffffff?text=Bethel+Feature'); ?>" alt="<?php echo htmlspecialchars($feature['title']); ?>" loading="lazy">
                    <div class="feature-content">
                        <h3><?php echo htmlspecialchars($feature['title']); ?></h3>
                        <p><?php echo htmlspecialchars($feature['description']); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="feature-card">
                    <img src="images/Campus.png" alt="Modern Science Lab" loading="lazy">
                    <div class="feature-content">
                        <h3>World-Class Facilities</h3>
                        <p>Our campus in Pawing, Palo features modern classrooms, science labs, sports facilities, and a well-stocked library to support holistic learning and innovation.</p>
                    </div>
                </div>
                <div class="feature-card">
                    <img src="images/International.jpg" alt="Qualified Teachers" loading="lazy">
                    <div class="feature-content">
                        <h3>International Curriculum</h3>
                        <p>We offer an internationally-recognized curriculum combined with Filipino values and context to prepare students for global opportunities while remaining rooted in Philippine heritage.</p>
                    </div>
                </div>
                <div class="feature-card">
                    <img src="images/Play.jpg" alt="Extracurricular activities" loading="lazy">
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
                        <p><?php echo htmlspecialchars($announcement['description']); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="announcement-item">
                    <div class="announcement-date">
                        <div class="day">15</div>
                        <div class="month">June</div>
                    </div>
                    <div class="announcement-text">
                        <h3>Enrollment for SY 2025-2026</h3>
                        <p>Enrollment for the School Year 2025-2026 is now open. Visit our campus in Pawing, Palo, Leyte for inquiries and campus tours.</p>
                    </div>
                </div>
                <div class="announcement-item">
                    <div class="announcement-date">
                        <div class="day">25</div>
                        <div class="month">June</div>
                    </div>
                    <div class="announcement-text">
                        <h3>Philippine Eagle Festival</h3>
                        <p>Join us for our annual Philippine Eagle Festival celebrating Filipino heritage and environmental conservation on June 25-29.</p>
                    </div>
                </div>
                <div class="announcement-item">
                    <div class="announcement-date">
                        <div class="day">12</div>
                        <div class="month">June</div>
                    </div>
                    <div class="announcement-text">
                        <h3>Independence Day Celebration</h3>
                        <p>Celebrate Philippine Independence Day with us on June 12 featuring cultural performances, historical exhibits, and patriotic activities.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

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
        
        if (mobileBtn && mainNav) {
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
                    if (icon) {
                        icon.classList.remove('fa-times');
                        icon.classList.add('fa-bars');
                    }
                });
            });
        }
    </script>
</body>
</html>