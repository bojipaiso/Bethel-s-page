# Bethel-s-page
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bethel International School - Palo, Leyte</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        :root {
            --primary-color: #1a5f7a;
            --secondary-color: #159895;
            --accent-color: #57c5b6;
            --light-color: #f8f9fa;
            --dark-color: #333;
            --gray-color: #6c757d;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            line-height: 1.6;
            color: var(--dark-color);
            background-color: #f5f7fa;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        h1, h2, h3, h4 {
            margin-bottom: 15px;
            color: var(--primary-color);
        }

        p {
            margin-bottom: 15px;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .btn {
            display: inline-block;
            background-color: var(--secondary-color);
            color: white;
            padding: 12px 25px;
            border-radius: 4px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn:hover {
            background-color: var(--primary-color);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            text-align: center;
            margin-bottom: 40px;
            position: relative;
        }

        .section-title:after {
            content: '';
            position: absolute;
            width: 70px;
            height: 3px;
            background-color: var(--accent-color);
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
        }

        section {
            padding: 80px 0;
        }

        /* Header and Navigation */
        header {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo img {
            height: 60px;
            margin-right: 10px;
        }

        .logo-text h1 {
            font-size: 1.5rem;
            margin-bottom: 5px;
            color: var(--primary-color);
        }

        .logo-text p {
            font-size: 0.8rem;
            color: var(--gray-color);
            margin-bottom: 0;
        }

        .nav-links {
            display: flex;
            list-style: none;
        }

        .nav-links li {
            margin-left: 25px;
        }

        .nav-links a {
            font-weight: 600;
            color: var(--dark-color);
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: var(--secondary-color);
        }

        .mobile-menu-btn {
            display: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--primary-color);
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(rgba(26, 95, 122, 0.85), rgba(21, 152, 149, 0.9)), url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            text-align: center;
            padding: 180px 0 100px;
            margin-top: 80px;
        }

        .hero h1 {
            font-size: 3rem;
            margin-bottom: 20px;
            color: white;
        }

        .hero p {
            font-size: 1.2rem;
            max-width: 800px;
            margin: 0 auto 30px;
        }

        /* About Section */
        .about-content {
            display: flex;
            align-items: center;
            gap: 50px;
        }

        .about-text {
            flex: 1;
        }

        .about-image {
            flex: 1;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .about-image img {
            width: 100%;
            height: auto;
            display: block;
            transition: transform 0.5s;
        }

        .about-image:hover img {
            transform: scale(1.05);
        }

        /* Academics Section */
        .academics {
            background-color: var(--light-color);
        }

        .programs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .program-card {
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s;
        }

        .program-card:hover {
            transform: translateY(-10px);
        }

        .program-icon {
            background-color: var(--primary-color);
            color: white;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
        }

        .program-content {
            padding: 25px;
        }

        /* Campus Life */
        .campus-life-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }

        .life-card {
            text-align: center;
            padding: 30px 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .life-icon {
            font-size: 2.5rem;
            color: var(--secondary-color);
            margin-bottom: 20px;
        }

        /* News Section */
        .news {
            background-color: var(--light-color);
        }

        .news-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .news-card {
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .news-image {
            height: 200px;
            overflow: hidden;
        }

        .news-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }

        .news-card:hover .news-image img {
            transform: scale(1.05);
        }

        .news-content {
            padding: 25px;
        }

        .news-date {
            color: var(--gray-color);
            font-size: 0.9rem;
            margin-bottom: 10px;
            display: block;
        }

        /* Contact Section */
        .contact-container {
            display: flex;
            gap: 50px;
        }

        .contact-info {
            flex: 1;
        }

        .contact-details {
            margin-top: 30px;
        }

        .contact-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .contact-icon {
            background-color: var(--accent-color);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            flex-shrink: 0;
        }

        .contact-form {
            flex: 1;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }

        /* Footer */
        footer {
            background-color: var(--primary-color);
            color: white;
            padding: 60px 0 20px;
        }

        .footer-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-logo h3 {
            color: white;
            margin-bottom: 15px;
        }

        .footer-links h4, .footer-contact h4 {
            color: white;
            margin-bottom: 20px;
            font-size: 1.2rem;
        }

        .footer-links ul {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 10px;
        }

        .footer-links a:hover {
            color: var(--accent-color);
        }

        .social-icons {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .social-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transition: all 0.3s;
        }

        .social-icon:hover {
            background-color: var(--accent-color);
            transform: translateY(-5px);
        }

        .copyright {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .about-content, .contact-container {
                flex-direction: column;
            }
            
            .hero h1 {
                font-size: 2.5rem;
            }
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                background-color: white;
                flex-direction: column;
                padding: 20px;
                box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
            }

            .nav-links.active {
                display: flex;
            }

            .nav-links li {
                margin: 10px 0;
            }

            .mobile-menu-btn {
                display: block;
            }

            .hero {
                padding: 150px 0 80px;
            }

            .hero h1 {
                font-size: 2rem;
            }

            section {
                padding: 60px 0;
            }
        }

        @media (max-width: 576px) {
            .hero h1 {
                font-size: 1.8rem;
            }
            
            .btn {
                padding: 10px 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Header and Navigation -->
    <header>
        <div class="container header-container">
            <div class="logo">
                <!-- School logo placeholder -->
                <div style="width: 60px; height: 60px; background-color: var(--primary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 1.2rem;">BIS</div>
                <div class="logo-text">
                    <h1>Bethel International School</h1>
                    <p>Palo, Leyte - Excellence in Education</p>
                </div>
            </div>
            
            <div class="mobile-menu-btn" id="mobileMenuBtn">
                <i class="fas fa-bars"></i>
            </div>
            
            <ul class="nav-links" id="navLinks">
                <li><a href="#home">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#academics">Academics</a></li>
                <li><a href="#campus">Campus Life</a></li>
                <li><a href="#news">News & Events</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="container">
            <h1>Welcome to Bethel International School</h1>
            <p>A premier educational institution in Palo, Leyte dedicated to nurturing future leaders through holistic education, character development, and academic excellence.</p>
            <a href="#about" class="btn">Learn More About Us</a>
        </div>
    </section>

    <!-- About Section -->
    <section id="about">
        <div class="container">
            <h2 class="section-title">About Our School</h2>
            <div class="about-content">
                <div class="about-text">
                    <h3>Excellence in Education Since 1995</h3>
                    <p>Bethel International School in Palo, Leyte is committed to providing quality education that develops students intellectually, socially, emotionally, and spiritually. Our campus offers a safe, nurturing environment where students can thrive and reach their full potential.</p>
                    <p>We follow an enhanced curriculum that combines academic rigor with values formation, preparing students to become responsible global citizens. Our dedicated faculty and staff are passionate about helping each student discover their unique gifts and talents.</p>
                    <p>Located in the heart of Palo, our school serves families throughout Leyte province and beyond, offering programs from preschool to senior high school.</p>
                    <a href="#contact" class="btn">Visit Our Campus</a>
                </div>
                <div class="about-image">
                    <img src="https://images.unsplash.com/photo-1524178234883-043d5c3f3cf4?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Bethel International School Campus">
                </div>
            </div>
        </div>
    </section>

    <!-- Academics Section -->
    <section id="academics" class="academics">
        <div class="container">
            <h2 class="section-title">Academic Programs</h2>
            <div class="programs-grid">
                <div class="program-card">
                    <div class="program-icon">
                        <i class="fas fa-child"></i>
                    </div>
                    <div class="program-content">
                        <h3>Preschool & Kindergarten</h3>
                        <p>Our early childhood program focuses on developing social, emotional, cognitive, and physical skills through play-based learning and discovery.</p>
                    </div>
                </div>
                <div class="program-card">
                    <div class="program-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <div class="program-content">
                        <h3>Elementary School</h3>
                        <p>A strong foundation in core subjects with enrichment programs in arts, music, technology, and physical education.</p>
                    </div>
                </div>
                <div class="program-card">
                    <div class="program-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="program-content">
                        <h3>Junior & Senior High School</h3>
                        <p>Comprehensive academic programs with tracks in STEM, ABM, HUMSS, and TVL to prepare students for college and career paths.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Campus Life Section -->
    <section id="campus">
        <div class="container">
            <h2 class="section-title">Campus Life</h2>
            <div class="campus-life-grid">
                <div class="life-card">
                    <div class="life-icon">
                        <i class="fas fa-futbol"></i>
                    </div>
                    <h3>Athletics & Sports</h3>
                    <p>Basketball, volleyball, badminton, swimming, and other sports programs to develop teamwork and healthy lifestyles.</p>
                </div>
                <div class="life-card">
                    <div class="life-icon">
                        <i class="fas fa-music"></i>
                    </div>
                    <h3>Arts & Culture</h3>
                    <p>Music, dance, visual arts, and theater programs that celebrate creativity and Filipino cultural heritage.</p>
                </div>
                <div class="life-card">
                    <div class="life-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Clubs & Organizations</h3>
                    <p>Student government, science club, debate team, and various interest-based organizations.</p>
                </div>
                <div class="life-card">
                    <div class="life-icon">
                        <i class="fas fa-hands-helping"></i>
                    </div>
                    <h3>Community Service</h3>
                    <p>Outreach programs and community projects that teach students the value of service and social responsibility.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- News Section -->
    <section id="news" class="news">
        <div class="container">
            <h2 class="section-title">News & Events</h2>
            <div class="news-grid">
                <div class="news-card">
                    <div class="news-image">
                        <img src="https://images.unsplash.com/photo-1509062522246-3755977927d7?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="School Event">
                    </div>
                    <div class="news-content">
                        <span class="news-date">June 15, 2023</span>
                        <h3>Enrollment for SY 2023-2024 Now Open</h3>
                        <p>Bethel International School is now accepting applications for the upcoming school year. Limited slots available for all grade levels.</p>
                        <a href="#" class="btn" style="padding: 8px 15px; font-size: 0.9rem;">Read More</a>
                    </div>
                </div>
                <div class="news-card">
                    <div class="news-image">
                        <img src="https://images.unsplash.com/photo-1546410531-bb4caa6b424d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Science Fair">
                    </div>
                    <div class="news-content">
                        <span class="news-date">May 20, 2023</span>
                        <h3>Annual Science Fair Winners Announced</h3>
                        <p>Our students showcased innovative projects at the annual science fair, with three winners advancing to the regional competition.</p>
                        <a href="#" class="btn" style="padding: 8px 15px; font-size: 0.9rem;">Read More</a>
                    </div>
                </div>
                <div class="news-card">
                    <div class="news-image">
                        <img src="https://images.unsplash.com/photo-1519389950473-47ba0277781c?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Graduation">
                    </div>
                    <div class="news-content">
                        <span class="news-date">April 5, 2023</span>
                        <h3>Graduation Ceremony Schedule Released</h3>
                        <p>Join us as we celebrate the accomplishments of our graduating class of 2023. Ceremonies will be held on April 25-27.</p>
                        <a href="#" class="btn" style="padding: 8px 15px; font-size: 0.9rem;">Read More</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact">
        <div class="container">
            <h2 class="section-title">Contact Us</h2>
            <div class="contact-container">
                <div class="contact-info">
                    <h3>Get in Touch</h3>
                    <p>We welcome inquiries from prospective students and parents. Feel free to visit our campus or contact us through the information below.</p>
                    
                    <div class="contact-details">
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <h4>Location</h4>
                                <p>Brgy. San Joaquin, Palo, Leyte, Philippines 6501</p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div>
                                <h4>Phone Number</h4>
                                <p>(053) 323-1234</p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <h4>Email Address</h4>
                                <p>info@bethelpalo.edu.ph</p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <h4>Office Hours</h4>
                                <p>Monday to Friday: 7:30 AM - 5:00 PM<br>Saturday: 8:00 AM - 12:00 PM</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="contact-form">
                    <h3>Send Us a Message</h3>
                    <form id="contactForm">
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" id="name" class="form-control" placeholder="Your Name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" class="form-control" placeholder="Your Email" required>
                        </div>
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" id="subject" class="form-control" placeholder="Message Subject" required>
                        </div>
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea id="message" class="form-control" rows="5" placeholder="Your Message" required></textarea>
                        </div>
                        <button type="submit" class="btn">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-container">
                <div class="footer-logo">
                    <h3>Bethel International School</h3>
                    <p>Providing quality education in Palo, Leyte for over 25 years. Nurturing minds, building character, shaping futures.</p>
                    <div class="social-icons">
                        <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                
                <div class="footer-links">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="#home">Home</a></li>
                        <li><a href="#about">About Us</a></li>
                        <li><a href="#academics">Academics</a></li>
                        <li><a href="#campus">Student Life</a></li>
                        <li><a href="#news">News & Events</a></li>
                        <li><a href="#contact">Admissions</a></li>
                    </ul>
                </div>
                
                <div class="footer-contact">
                    <h4>Contact Info</h4>
                    <p><i class="fas fa-map-marker-alt"></i> Brgy. San Joaquin, Palo, Leyte</p>
                    <p><i class="fas fa-phone"></i> (053) 323-1234</p>
                    <p><i class="fas fa-envelope"></i> info@bethelpalo.edu.ph</p>
                </div>
            </div>
            
            <div class="copyright">
                <p>&copy; 2023 Bethel International School - Palo, Leyte. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const navLinks = document.getElementById('navLinks');
        
        mobileMenuBtn.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            mobileMenuBtn.innerHTML = navLinks.classList.contains('active') 
                ? '<i class="fas fa-times"></i>' 
                : '<i class="fas fa-bars"></i>';
        });
        
        // Close mobile menu when clicking a link
        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', () => {
                navLinks.classList.remove('active');
                mobileMenuBtn.innerHTML = '<i class="fas fa-bars"></i>';
            });
        });
        
        // Form submission
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Thank you for your message! We will get back to you soon.');
            this.reset();
        });
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if(targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if(targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>
</html>
