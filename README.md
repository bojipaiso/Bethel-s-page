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
