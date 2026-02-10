<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bethel International School - Home</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        :root {
            --primary-color: #002366; /* Royal Blue */
            --secondary-color: #0056b3; /* Blue */
            --accent-color: #FFD700; /* Gold */
            --light-color: #ffffff; /* White */
            --dark-color: #1a1a2e; /* Dark Blue */
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
        
        /* Header Styles */
        header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 15px 0;
            box-shadow: 0 3px 15px rgba(0, 35, 102, 0.2);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
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
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        
        .logo-icon i {
            font-size: 2.5rem;
            color: var(--primary-color);
        }
        
        .logo-text h1 {
            font-size: 1.8rem;
            margin-bottom: 5px;
            color: white;
        }
        
        .logo-text .school-name {
            font-weight: 700;
            color: var(--accent-color);
        }
        
        .logo-text p {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        nav ul {
            display: flex;
            list-style: none;
        }
        
        nav ul li {
            margin-left: 25px;
        }
        
        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
            font-size: 1.1rem;
            position: relative;
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
            transition: width 0.3s;
        }
        
        nav ul li a:hover::after {
            width: 100%;
        }
        
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
        }
        
        /* Hero Section */
        .hero {
            background: linear-gradient(rgba(0, 35, 102, 0.85), rgba(0, 86, 179, 0.9)), url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            text-align: center;
        }
        
        .hero h2 {
            font-size: 2.8rem;
            margin-bottom: 20px;
            color: var(--accent-color);
        }
        
        .hero p {
            font-size: 1.3rem;
            max-width: 800px;
            margin: 0 auto 30px;
        }
        
        .cta-button {
            display: inline-block;
            background-color: var(--accent-color);
            color: var(--primary-color);
            padding: 12px 30px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        
        .cta-button:hover {
            background-color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }
        
        /* Main Content */
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
            width: 80px;
            height: 4px;
            background: var(--accent-color);
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
        }
        
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin: 40px 0;
        }
        
        .feature-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0, 35, 102, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            border-top: 5px solid var(--accent-color);
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 35, 102, 0.15);
        }
        
        .feature-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        
        .feature-content {
            padding: 25px;
        }
        
        .feature-content h3 {
            color: var(--primary-color);
            margin-bottom: 15px;
            font-size: 1.5rem;
        }
        
        /* Announcements */
        .announcements {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            margin: 40px 0;
            box-shadow: 0 8px 20px rgba(0, 35, 102, 0.1);
            border-left: 5px solid var(--secondary-color);
        }
        
        .announcement-item {
            padding: 15px 0;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
        }
        
        .announcement-item:last-child {
            border-bottom: none;
        }
        
        .announcement-date {
            background-color: var(--primary-color);
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            margin-right: 20px;
            text-align: center;
            min-width: 100px;
        }
        
        .announcement-date .day {
            font-size: 1.5rem;
            font-weight: bold;
        }
        
        .announcement-date .month {
            font-size: 0.9rem;
            text-transform: uppercase;
        }
        
        /* Quick Links */
        .quick-links {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 40px 0;
        }
        
        .link-card {
            background: white;
            border-radius: 10px;
            padding: 30px 20px;
            text-align: center;
            box-shadow: 0 8px 20px rgba(0, 35, 102, 0.1);
            transition: all 0.3s;
            text-decoration: none;
            color: var(--dark-color);
            border-top: 5px solid var(--primary-color);
        }
        
        .link-card:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-5px);
            box-shadow: 0 15px 25px rgba(0, 35, 102, 0.2);
        }
        
        .link-card i {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: var(--secondary-color);
        }
        
        .link-card:hover i {
            color: var(--accent-color);
        }
        
        /* Footer */
        footer {
            background-color: var(--dark-color);
            color: white;
            padding: 50px 0 20px;
            margin-top: 60px;
            background: linear-gradient(135deg, var(--dark-color) 0%, var(--primary-color) 100%);
        }
        
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }
        
        .footer-column h3 {
            font-size: 1.3rem;
            margin-bottom: 20px;
            color: var(--accent-color);
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
            margin-top: 15px;
            font-style: italic;
            color: #bbb;
        }
        
        .social-icons {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
        
        .social-icons a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .social-icons a:hover {
            background-color: var(--accent-color);
            transform: translateY(-3px);
            color: var(--primary-color);
        }
        
        .copyright {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 0.9rem;
            color: #aaa;
        }
        
        /* Responsive Styles */
        @media (max-width: 992px) {
            .hero h2 {
                font-size: 2.2rem;
            }
            
            .hero p {
                font-size: 1.1rem;
            }
        }
        
        @media (max-width: 768px) {
            nav ul {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                background-color: var(--primary-color);
                flex-direction: column;
                padding: 20px 0;
                box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
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
            
            .hero h2 {
                font-size: 1.8rem;
            }
            
            .section-title {
                font-size: 1.8rem;
            }
        }
        
        @media (max-width: 576px) {
            .logo {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }
            
            .logo-text h1 {
                font-size: 1.5rem;
            }
            
            .header-container {
                flex-direction: column;
                gap: 15px;
            }
            
            .announcement-item {
                flex-direction: column;
                text-align: center;
            }
            
            .announcement-date {
                margin-right: 0;
                margin-bottom: 10px;
            }
            
            .logo-icon {
                width: 60px;
                height: 60px;
            }
        }
    </style>
</head>
<body>
    <!-- Header with Logo and Navigation -->
    <header>
        <div class="container header-container">
            <div class="logo">
                <div class="logo-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="logo-text">
                    <h1><span class="school-name">Bethel International School</span></h1>
                    <p>Pawing, Palo, Leyte</p>
                </div>
            </div>
            
            <button class="mobile-menu-btn" id="mobileMenuBtn">
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

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h2>Excellence in International Education</h2>
            <p>At Bethel International School, Pawing, Palo, Leyte, we nurture global citizens through quality education, holistic development, and values formation for students from kindergarten through senior high school.</p>
            <a href="#" class="cta-button">Explore Our Programs</a>
        </div>
    </section>

    <!-- Main Content -->
    <main class="container">
        <!-- Features Section -->
        <h2 class="section-title">Why Choose Bethel International School?</h2>
        <div class="features">
            <div class="feature-card">
                <img src="https://images.unsplash.com/photo-1588072432836-e10032774350?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1172&q=80" alt="Modern Science Lab">
                <div class="feature-content">
                    <h3>World-Class Facilities</h3>
                    <p>Our campus in Pawing, Palo features modern classrooms, science labs, sports facilities, and a well-stocked library to support holistic learning.</p>
                </div>
            </div>
            
            <div class="feature-card">
                <img src="https://images.unsplash.com/photo-1577896851231-70ef18881754?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" alt="Qualified Teachers">
                <div class="feature-content">
                    <h3>International Curriculum</h3>
                    <p>We offer an internationally-recognized curriculum combined with local context to prepare students for global opportunities.</p>
                </div>
            </div>
            
            <div class="feature-card">
                <img src="https://images.unsplash.com/photo-1546410531-bb4caa6b424d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1171&q=80" alt="Extracurricular Activities">
                <div class="feature-content">
                    <h3>Holistic Development</h3>
                    <p>We offer a wide range of sports, arts, music, and clubs to help students discover and develop their talents beyond the classroom.</p>
                </div>
            </div>
        </div>

        <!-- Announcements Section -->
        <h2 class="section-title">Latest Announcements</h2>
        <div class="announcements">
            <div class="announcement-item">
                <div class="announcement-date">
                    <div class="day">15</div>
                    <div class="month">June</div>
                </div>
                <div class="announcement-text">
                    <h3>Enrollment for SY 2024-2025</h3>
                    <p>Enrollment for the School Year 2024-2025 is now open. Visit our campus in Pawing, Palo, Leyte for inquiries.</p>
                </div>
            </div>
            
            <div class="announcement-item">
                <div class="announcement-date">
                    <div class="day">25</div>
                    <div class="month">June</div>
                </div>
                <div class="announcement-text">
                    <h3>International Education Week</h3>
                    <p>Join us for our International Education Week from June 25-29, featuring cultural exhibits and global education seminars.</p>
                </div>
            </div>
            
            <div class="announcement-item">
                <div class="announcement-date">
                    <div class="day">05</div>
                    <div class="month">July</div>
                </div>
                <div class="announcement-text">
                    <h3>Parent-Teacher Conference</h3>
                    <p>The first quarter Parent-Teacher Conference will be held on July 5, 2024 at the school auditorium.</p>
                </div>
            </div>
        </div>

        <!-- Quick Links Section -->
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
                <i class="fas fa-utensils"></i>
                <h3>Cafeteria Menu</h3>
                <p>Weekly lunch schedule</p>
            </a>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>Bethel International School</h3>
                    <p>Pawing, Palo, Leyte<br>Philippines</p>
                    <p class="school-location">Serving the community since 2005</p>
                    <p>Phone: (053) 123-4567<br>Email: info@bethelinternational.edu.ph</p>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
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
                <p>&copy; 2024 Bethel International School, Pawing, Palo, Leyte. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mainNav = document.getElementById('mainNav');
        
        mobileMenuBtn.addEventListener('click', function() {
            mainNav.classList.toggle('active');
            
            // Change icon based on menu state
            const icon = this.querySelector('i');
            if (mainNav.classList.contains('active')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
        
        // Close mobile menu when clicking on a link
        const navLinks = document.querySelectorAll('#mainNav a');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                mainNav.classList.remove('active');
                mobileMenuBtn.querySelector('i').classList.remove('fa-times');
                mobileMenuBtn.querySelector('i').classList.add('fa-bars');
            });
        });
        
        // Welcome message for Bethel International School
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                alert("Welcome to Bethel International School, Pawing, Palo, Leyte! Enrollment for SY 2024-2025 is now open.");
            }, 1000);
        });
    </script>
</body>
</html>
