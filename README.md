<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bethel International School – Soaring Eagle, Palo Leyte</title>
    <!-- Font Awesome 6 (free) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Roboto, system-ui, sans-serif;
        }

        :root {
            --primary-color: #002366;       /* Royal Blue */
            --secondary-color: #0056b3;      /* Rich Blue */
            --accent-color: #FFD700;          /* Gold */
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

        /* ----- header & navigation ----- */
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
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            overflow: hidden;
        }

        .eagle-icon {
            font-size: 2.8rem;
            color: var(--primary-color);
            transform: scaleX(-1);  /* eagle faces forward */
            line-height: 1;
        }

        .logo-text h1 {
            font-size: 1.8rem;
            margin-bottom: 5px;
            color: white;
            line-height: 1.2;
        }

        .logo-text .school-name {
            font-weight: 700;
            color: var(--accent-color);
        }

        .logo-text p {
            font-size: 0.95rem;
            opacity: 0.9;
        }

        nav ul {
            display: flex;
            list-style: none;
            gap: 5px;
        }

        nav ul li {
            margin-left: 20px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
            font-size: 1.1rem;
            position: relative;
            padding: 5px 0;
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
        }

        /* ----- hero ----- */
        .hero {
            background: linear-gradient(rgba(0, 35, 102, 0.85), rgba(0, 86, 179, 0.9)), 
                        url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80');
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

        /* ----- section titles ----- */
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

        /* ----- feature cards ----- */
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
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .eagle-theme {
            color: var(--primary-color);
        }

        .eagle-feature {
            position: relative;
        }

        .eagle-feature::before {
            content: "🦅";
            position: absolute;
            right: 18px;
            top: 18px;
            font-size: 2rem;
            opacity: 0.2;
            z-index: 1;
        }

        /* ----- announcements ----- */
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

        /* ----- quick links (cards) ----- */
        .quick-links {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 25px;
            margin: 40px 0 20px;
        }

        .link-card {
            background: white;
            border-radius: 20px;
            padding: 30px 20px;
            text-align: center;
            box-shadow: 0 8px 20px rgba(0, 35, 102, 0.08);
            transition: all 0.25s;
            text-decoration: none;
            color: var(--dark-color);
            border-top: 5px solid var(--primary-color);
        }

        .link-card:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-6px);
            box-shadow: 0 18px 28px rgba(0, 35, 102, 0.25);
        }

        .link-card i {
            font-size: 2.8rem;
            margin-bottom: 15px;
            color: var(--secondary-color);
            transition: color 0.2s;
        }

        .link-card:hover i {
            color: var(--accent-color);
        }

        .link-card h3 {
            font-size: 1.3rem;
            margin-bottom: 8px;
        }

        /* ----- footer ----- */
        footer {
            background: linear-gradient(145deg, var(--dark-color) 0%, var(--primary-color) 100%);
            color: white;
            padding: 50px 0 20px;
            margin-top: 70px;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-column h3 {
            font-size: 1.3rem;
            margin-bottom: 20px;
            color: var(--accent-color);
            border-left: 4px solid var(--accent-color);
            padding-left: 12px;
        }

        .footer-column ul {
            list-style: none;
        }

        .footer-column ul li {
            margin-bottom: 10px;
        }

        .footer-column ul li a {
            color: #ddd;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-column ul li a:hover {
            color: var(--accent-color);
        }

        .school-location {
            margin: 10px 0 15px;
            font-style: italic;
            color: #bbb;
        }

        .social-icons {
            display: flex;
            gap: 14px;
            margin-top: 20px;
        }

        .social-icons a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            background-color: rgba(255,255,255,0.1);
            border-radius: 50%;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
            font-size: 1.2rem;
        }

        .social-icons a:hover {
            background-color: var(--accent-color);
            transform: translateY(-4px);
            color: var(--primary-color);
        }

        .copyright {
            text-align: center;
            padding-top: 25px;
            border-top: 1px solid rgba(255,255,255,0.1);
            font-size: 0.95rem;
            color: #aaa;
        }

        /* ----- responsive ----- */
        @media (max-width: 992px) {
            .hero h2 { font-size: 2.2rem; }
            .hero p { font-size: 1.1rem; }
        }

        @media (max-width: 768px) {
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
            }
            nav ul.active {
                display: flex;
            }
            nav ul li {
                margin: 0;
                text-align: center;
                padding: 12px 0;
            }
            .mobile-menu-btn {
                display: block;
            }
            .hero {
                padding: 70px 0;
            }
            .hero h2 { font-size: 1.9rem; }
            .section-title { font-size: 1.9rem; }
        }

        @media (max-width: 576px) {
            .logo {
                flex-direction: column;
                text-align: center;
                gap: 6px;
            }
            .logo-text h1 { font-size: 1.5rem; }
            .header-container {
                flex-direction: column;
                gap: 10px;
            }
            .announcement-item {
                flex-direction: column;
                text-align: center;
            }
            .announcement-date {
                margin-right: 0;
                width: fit-content;
            }
            .logo-icon {
                width: 65px;
                height: 65px;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container header-container">
            <div class="logo">
                <div class="logo-icon">
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
                    <li><a href="#" class="active">Home</a></li>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Academics</a></li>
                    <li><a href="#">Admissions</a></li>
                    <li><a href="#">News & Events</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Hero with eagle inspiration -->
    <section class="hero">
        <div class="container">
            <h2>🦅 Soaring to Excellence in International Education</h2>
            <p>Inspired by the majesty of the Philippine Eagle, Bethel International School in Pawing, Palo, Leyte nurtures global citizens with strong Filipino values, academic excellence, and holistic development from kindergarten through senior high school.</p>
            <a href="#" class="cta-button">Explore Our Programs</a>
        </div>
    </section>

    <main class="container">

        <!-- features (three cards) -->
        <h2 class="section-title">Why Choose Bethel International School?</h2>
        <div class="features">
            <div class="feature-card eagle-feature">
                <img src="https://images.unsplash.com/photo-1588072432836-e10032774350?ixlib=rb-4.0.3&auto=format&fit=crop&w=1172&q=80" alt="Modern Science Lab" loading="lazy">
                <div class="feature-content">
                    <h3><span class="eagle-theme">World-Class Facilities</span></h3>
                    <p>Our campus in Pawing, Palo features modern classrooms, science labs, sports facilities, and a well-stocked library to support holistic learning and innovation.</p>
                </div>
            </div>
            
            <div class="feature-card eagle-feature">
                <img src="https://images.unsplash.com/photo-1577896851231-70ef18881754?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80" alt="Qualified Teachers" loading="lazy">
                <div class="feature-content">
                    <h3><span class="eagle-theme">International Curriculum</span></h3>
                    <p>We offer an internationally-recognized curriculum combined with Filipino values and context to prepare students for global opportunities while remaining rooted in Philippine heritage.</p>
                </div>
            </div>
            
            <div class="feature-card eagle-feature">
                <img src="https://images.unsplash.com/photo-1546410531-bb4caa6b424d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1171&q=80" alt="Extracurricular activities" loading="lazy">
                <div class="feature-content">
                    <h3><span class="eagle-theme">Soaring Talents Program</span></h3>
                    <p>Inspired by the Philippine Eagle, our Soaring Talents Program offers sports, arts, music, leadership, and cultural activities to help students discover and develop their unique talents.</p>
                </div>
            </div>
        </div>

        <!-- announcements -->
        <h2 class="section-title">Latest Announcements</h2>
        <div class="announcements">
            <div class="announcement-item">
                <div class="announcement-date">
                    <div class="day">15</div>
                    <div class="month">June</div>
                </div>
                <div class="announcement-text">
                    <h3>Enrollment for SY 2024-2025</h3>
                    <p>Enrollment for the School Year 2024-2025 is now open. Visit our campus in Pawing, Palo, Leyte for inquiries and campus tours.</p>
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
        </div>

        <!-- quick links with eagle flair -->
        <h2 class="section-title">Quick Links</h2>
        <div class="quick-links">
            <a href="#" class="link-card">
                <i class="fas fa-calendar-alt"></i>
                <h3>School Calendar</h3>
                <p>View important dates and events</p>
            </a>
            <a href="#" class="link-card">
                <i class="fas fa-user-graduate"></i>
                <h3>Student Portal</h3>
                <p>Access grades and assignments</p>
            </a>
            <a href="#" class="link-card">
                <i class="fas fa-bus"></i>
                <h3>Transportation</h3>
                <p>Bus routes and schedules</p>
            </a>
            <a href="#" class="link-card">
                <i class="fas fa-eagle"></i> 
                <h3>Eagle's Nest</h3>
                <p>Student achievements & gallery</p>
            </a>
        </div>
    </main>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>Bethel International School</h3>
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                        <span style="font-size: 2rem;">🦅</span>
                        <span>Pawing, Palo, Leyte<br>Philippines 6501</span>
                    </div>
                    <p class="school-location">Soaring to academic excellence since 2005</p>
                    <p>Phone: (067) 676-6767<br>Email: secretary@bethel.edu.ph</p>
                    <div class="social-icons">
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                
                <div class="footer-column">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="#">About Our School</a></li>
                        <li><a href="#">Academic Programs</a></li>
                        <li><a href="#">Admissions Process</a></li>
                        <li><a href="#">School Life</a></li>
                        <li><a href="#">Parent Resources</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h3>Important Info</h3>
                    <ul>
                        <li><a href="#">School Policies</a></li>
                        <li><a href="#">Health & Safety</a></li>
                        <li><a href="#">Employment Opportunities</a></li>
                        <li><a href="#">School Newsletter</a></li>
                        <li><a href="#">Contact Directory</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="copyright">
                <p>&copy; 2024 Bethel International School, Pawing, Palo, Leyte. All Rights Reserved. | The Philippine Eagle symbolizes our commitment to strength, vision, and soaring excellence.</p>
            </div>
        </div>
    </footer>

    <script>
        (function() {
            // Mobile menu toggle
            const mobileBtn = document.getElementById('mobileMenuBtn');
            const mainNav = document.getElementById('mainNav');
            
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

            // Close menu when link clicked
            document.querySelectorAll('#mainNav a').forEach(link => {
                link.addEventListener('click', function() {
                    mainNav.classList.remove('active');
                    const icon = mobileBtn.querySelector('i');
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                });
            });

            // subtle welcome alert (after 1.5s)
            setTimeout(function() {
                alert("🦅 Welcome to Bethel International School!\n\nPawing, Palo, Leyte — Enrollment for SY 2024-2025 is now open.");
            }, 1500);

            // small extra: ensure eagle pulse stays
            const eagle = document.querySelector('.eagle-icon');
            if (eagle) {
                eagle.classList.add('pulse-eagle');
            }

            // close menu if click outside (optional)
            document.addEventListener('click', function(event) {
                if (!mainNav.contains(event.target) && !mobileBtn.contains(event.target)) {
                    mainNav.classList.remove('active');
                    const icon = mobileBtn.querySelector('i');
                    if (icon) {
                        icon.classList.remove('fa-times');
                        icon.classList.add('fa-bars');
                    }
                }
            });
        })();
    </script>
</body>
</html>
