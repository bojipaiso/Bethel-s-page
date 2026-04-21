<?php
// about.php - COMPLETE UPDATED VERSION with database-driven core values
require_once 'includes/db.php';
$page_title = 'About Us | Bethel International School';

// Fetch about content
$about_content = [];
$sections = $pdo->query("SELECT * FROM about_content WHERE status='active' ORDER BY display_order")->fetchAll();
foreach($sections as $section) {
    $about_content[$section['section']] = $section;
}

// Fetch statistics
$statistics = $pdo->query("SELECT * FROM about_stats WHERE status='active' ORDER BY display_order")->fetchAll();

// Fetch core values from database
$core_values = $pdo->query("SELECT * FROM core_values WHERE status = 'active' ORDER BY display_order ASC")->fetchAll();
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
            --gray-light: #f8fafc;
            --gray-border: #dee2e6;
            --text-dark: #1a1a2e;
            --text-gray: #2d3748;
            --story-bg: #fafaf5;
            --card-shadow: 0 10px 25px rgba(0, 35, 102, 0.08);
        }

        body {
            line-height: 1.7;
            color: var(--text-dark);
            background-color: var(--gray-light);
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header Styles */
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
            font-weight: 700;
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

        /* Page Banner */
        .page-banner {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 60px 0;
            text-align: center;
            margin-bottom: 40px;
        }

        .page-banner h1 {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: var(--accent-color);
            font-weight: 700;
        }

        .page-banner p {
            font-size: 1.2rem;
            max-width: 800px;
            margin: 0 auto;
            opacity: 0.9;
        }

        /* Section Cards */
        .section-card {
            background: white;
            border-radius: 16px;
            margin-bottom: 30px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
        }

        /* Mission + Vision Side by Side */
        .mission-vision-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
        }

        .mission-card, .vision-card {
            padding: 50px 45px;
        }

        .mission-card {
            border-right: 1px solid var(--gray-border);
        }

        .section-icon {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .section-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #b8860b;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .mission-card h2, .vision-card h2 {
            font-size: 1.8rem;
            color: var(--primary-color);
            margin-bottom: 20px;
            font-weight: 700;
        }

        .mission-text, .vision-text {
            color: var(--text-gray);
            line-height: 1.8;
            font-size: 1rem;
            font-weight: 500;
        }

        .mission-quote {
            margin-top: 20px;
            font-style: italic;
            color: #b8860b;
            border-left: 3px solid var(--accent-color);
            padding-left: 18px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Our Story Card - Background closer to white */
        .story-card {
            background: var(--story-bg);
            border-radius: 16px;
            padding: 50px 45px;
            text-align: center;
            box-shadow: var(--card-shadow);
            margin-bottom: 30px;
        }

        .story-card h2 {
            font-size: 1.8rem;
            color: var(--primary-color);
            margin-bottom: 15px;
            font-weight: 700;
        }

        .story-subtitle {
            color: #b8860b;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .story-text {
            color: var(--text-gray);
            line-height: 1.8;
            font-size: 1rem;
            font-weight: 500;
            text-align: left;
            max-width: 800px;
            margin: 0 auto;
        }

        /* Core Values Card */
        .values-card {
            background: white;
            border-radius: 16px;
            padding: 50px 45px;
            box-shadow: var(--card-shadow);
            margin-bottom: 30px;
        }

        .values-card h2 {
            text-align: center;
            font-size: 1.8rem;
            color: var(--primary-color);
            margin-bottom: 12px;
            font-weight: 700;
        }

        .values-intro {
            text-align: center;
            color: var(--text-gray);
            max-width: 600px;
            margin: 0 auto 40px;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .values-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 20px;
        }

        .value-item {
            text-align: center;
            padding: 25px 15px;
            background: var(--gray-light);
            border-radius: 12px;
            transition: all 0.3s ease;
            border: 1px solid var(--gray-border);
        }

        .value-item:hover {
            transform: translateY(-3px);
            background: white;
            border-color: var(--accent-color);
            box-shadow: 0 5px 15px rgba(0, 35, 102, 0.1);
        }

        .value-icon {
            width: 55px;
            height: 55px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        }

        .value-icon i {
            font-size: 1.3rem;
            color: var(--primary-color);
        }

        .value-item h3 {
            font-size: 1rem;
            color: var(--primary-color);
            margin-bottom: 8px;
            font-weight: 700;
        }

        .value-item p {
            font-size: 0.85rem;
            color: var(--text-gray);
            line-height: 1.5;
            font-weight: 500;
        }

        /* Statistics Card */
        .stats-card {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border-radius: 16px;
            padding: 50px 45px;
            text-align: center;
            margin-bottom: 30px;
            box-shadow: var(--card-shadow);
        }

        .stats-card h2 {
            font-size: 1.8rem;
            margin-bottom: 8px;
            color: white;
            font-weight: 700;
        }

        .stats-subtitle {
            color: rgba(255,255,255,0.7);
            margin-bottom: 40px;
            font-size: 0.95rem;
            font-weight: 400;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
            max-width: 900px;
            margin: 0 auto;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--accent-color);
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.65);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 500;
        }

        /* Footer */
        footer {
            background: linear-gradient(145deg, var(--dark-color) 0%, var(--primary-color) 100%);
            color: white;
            padding: 50px 0 20px;
            margin-top: 20px;
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
            font-weight: 700;
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
            gap: 12px;
            margin-top: 15px;
        }

        .social-icons a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            transition: all 0.3s;
        }

        .social-icons a svg {
            width: 16px;
            height: 16px;
            fill: var(--accent-color);
        }

        .social-icons a:hover {
            background: var(--accent-color);
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

        @media (max-width: 992px) {
            .logo-text h1 { font-size: 1.4rem; }
            .logo-icon { width: 50px; height: 50px; }
            .mission-vision-grid { grid-template-columns: 1fr; }
            .mission-card { border-right: none; border-bottom: 1px solid var(--gray-border); }
            .mission-card, .vision-card { padding: 35px; }
            .story-card, .values-card, .stats-card { padding: 35px; }
            .values-grid { grid-template-columns: repeat(3, 1fr); }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .page-banner h1 { font-size: 2rem; }
        }

        @media (max-width: 768px) {
            .header-container { flex-direction: row; justify-content: space-between; }
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
            .mission-card h2, .vision-card h2 { font-size: 1.5rem; }
            .values-grid { grid-template-columns: repeat(2, 1fr); }
            .stats-grid { grid-template-columns: 1fr; gap: 20px; }
            .footer-content { grid-template-columns: 1fr; text-align: center; }
            .footer-column h3 { border-left: none; padding-left: 0; text-align: center; }
            .hours-item { justify-content: center; gap: 20px; }
            .social-icons { justify-content: center; }
        }

        @media (max-width: 576px) {
            .logo { gap: 10px; }
            .logo-icon { width: 45px; height: 45px; }
            .logo-text h1 { font-size: 1.2rem; }
            .page-banner { padding: 40px 0; }
            .page-banner h1 { font-size: 1.6rem; }
            .values-grid { grid-template-columns: 1fr; }
            .mission-card, .vision-card { padding: 25px; }
            .story-card, .values-card, .stats-card { padding: 25px; }
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
                    <li><a href="about.php" class="active">About Us</a></li>
                    <li><a href="academics.php">Academics</a></li>
                    <li><a href="admissions.php">Admissions</a></li>
                    <li><a href="news.php">News & Events</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Page Banner -->
    <section class="page-banner">
        <div class="container">
            <h1>About Bethel International School</h1>
            <p>Excellence in Education, Rooted in Faith and Filipino Values</p>
        </div>
    </section>

    <div class="container">
        <!-- Mission + Vision Card -->
        <div class="section-card">
            <div class="mission-vision-grid">
                <div class="mission-card">
                    <div class="section-icon">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <div class="section-label">Our Purpose</div>
                    <h2><?php echo htmlspecialchars($about_content['mission']['title'] ?? 'Mission'); ?></h2>
                    <div class="mission-text">
                        <?php echo nl2br(htmlspecialchars($about_content['mission']['content'] ?? 'To provide holistic, internationally-competitive education that nurtures students\' intellectual, spiritual, and social development while preserving Filipino values and cultural heritage.')); ?>
                    </div>
                    <div class="mission-quote">
                        "Guiding every student to soar like an eagle"
                    </div>
                </div>
                <div class="vision-card">
                    <div class="section-icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <div class="section-label">Our Aspiration</div>
                    <h2><?php echo htmlspecialchars($about_content['vision']['title'] ?? 'Vision'); ?></h2>
                    <div class="vision-text">
                        <?php echo nl2br(htmlspecialchars($about_content['vision']['content'] ?? 'To be a leading Christian educational institution in the Visayas, producing globally competitive, values-driven leaders who soar like eagles in their chosen fields.')); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Our Story Card -->
        <div class="story-card">
            <div class="story-subtitle">Since 2001</div>
            <h2><?php echo htmlspecialchars($about_content['history']['title'] ?? 'Our Story'); ?></h2>
            <div class="story-text">
                <?php echo nl2br(htmlspecialchars($about_content['history']['content'] ?? 'Founded in 2001, Bethel International School began with a simple yet powerful vision: to provide quality education that combines international standards with Filipino values. Located in the peaceful community of Pawing, Palo, Leyte, our school has grown from humble beginnings to become one of the region\'s most respected educational institutions.')); ?>
            </div>
        </div>

        <!-- Core Values Card - UPDATED TO USE DATABASE -->
        <div class="values-card">
            <h2><?php echo htmlspecialchars($about_content['core_values']['title'] ?? 'Our Core Values'); ?></h2>
            <div class="values-intro">
                These values guide everything we do, shaping our students into well-rounded individuals.
            </div>
            <div class="values-grid">
                <?php if(count($core_values) > 0): ?>
                    <?php foreach($core_values as $value): ?>
                    <div class="value-item">
                        <div class="value-icon">
                            <i class="<?php echo htmlspecialchars($value['icon_class'] ?: 'fas fa-star'); ?>"></i>
                        </div>
                        <h3><?php echo htmlspecialchars($value['title']); ?></h3>
                        <p><?php echo nl2br(htmlspecialchars($value['description'])); ?></p>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Default fallback values -->
                    <div class="value-item">
                        <div class="value-icon"><i class="fas fa-trophy"></i></div>
                        <h3>Excellence</h3>
                        <p>We strive for the highest standards in academics, character, and service.</p>
                    </div>
                    <div class="value-item">
                        <div class="value-icon"><i class="fas fa-hands-helping"></i></div>
                        <h3>Faith</h3>
                        <p>We nurture spiritual growth and moral integrity based on Christian values.</p>
                    </div>
                    <div class="value-item">
                        <div class="value-icon"><i class="fas fa-heart"></i></div>
                        <h3>Service</h3>
                        <p>We develop compassionate leaders who serve their communities.</p>
                    </div>
                    <div class="value-item">
                        <div class="value-icon"><i class="fas fa-globe-asia"></i></div>
                        <h3>Global Citizenship</h3>
                        <p>We prepare students to thrive in an interconnected world while staying rooted in Filipino culture.</p>
                    </div>
                    <div class="value-item">
                        <div class="value-icon"><i class="fas fa-lightbulb"></i></div>
                        <h3>Innovation</h3>
                        <p>We embrace creativity and adapt to changing educational needs.</p>
                    </div>
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

    <a href="admin/login.php" class="admin-login-btn">Admin</a>

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