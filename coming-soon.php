<?php
// coming-soon.php - COMPLETE with manual toggle support
require_once 'includes/db.php';

$resource = $_GET['resource'] ?? 'page';
$title = '';
$message = '';
$icon = '';

// ============================================
// NEWSLETTER SECTION
// ============================================
if ($resource === 'newsletter') {
    $coming_soon_setting = $pdo->query("SELECT setting_value FROM school_settings WHERE setting_key = 'newsletter_coming_soon'")->fetchColumn();

    // If manually set to coming soon (1), show coming soon page regardless of content
    if ($coming_soon_setting === '1') {
        $title = 'School Newsletter';
        $message = 'Our monthly newsletter is being prepared. Subscribe to our mailing list to receive updates about school events, student achievements, and important announcements.';
        $icon = '📰';
    } else {
        // Check if newsletters exist
        $count = $pdo->query("SELECT COUNT(*) FROM newsletters WHERE status='published'")->fetchColumn();
        if ($count > 0) {
            header('Location: newsletter.php');
            exit();
        } else {
            $title = 'School Newsletter';
            $message = 'Our monthly newsletter is being prepared. Subscribe to our mailing list to receive updates about school events, student achievements, and important announcements.';
            $icon = '📰';
        }
    }
}

// ============================================
// CALENDAR SECTION
// ============================================
if ($resource === 'calendar') {
    $coming_soon_setting = $pdo->query("SELECT setting_value FROM school_settings WHERE setting_key = 'calendar_coming_soon'")->fetchColumn();

    if ($coming_soon_setting === '1') {
        $title = 'Academic Calendar';
        $message = 'Our academic calendar is currently being prepared. Please check back soon for important dates including enrollment periods, examination schedules, and school breaks.';
        $icon = '📅';
    } else {
        $count = $pdo->query("SELECT COUNT(*) FROM calendar_pdfs WHERE status='active'")->fetchColumn();
        if ($count > 0) {
            header('Location: calendar.php');
            exit();
        } else {
            $title = 'Academic Calendar';
            $message = 'Our academic calendar is currently being prepared. Please check back soon for important dates including enrollment periods, examination schedules, and school breaks.';
            $icon = '📅';
        }
    }
}

// ============================================
// DEFAULT PAGE
// ============================================
if ($resource === 'page') {
    $title = 'Coming Soon';
    $message = 'This page is currently under development. Please check back later for updates.';
    $icon = '🚧';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?> | Bethel International School</title>
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
        }

        body {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            width: 100%;
        }

        .card {
            background: white;
            border-radius: 20px;
            padding: 50px 40px;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            animation: fadeInUp 0.6s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo-icon {
            background-color: var(--accent-color);
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
        }

        .logo-icon span {
            font-size: 3rem;
        }

        h1 {
            color: var(--primary-color);
            font-size: 2rem;
            margin-bottom: 15px;
        }

        .divider {
            width: 60px;
            height: 3px;
            background: var(--accent-color);
            margin: 20px auto;
            border-radius: 3px;
        }

        p {
            color: #555;
            line-height: 1.6;
            margin-bottom: 20px;
            font-size: 1rem;
        }

        .progress-bar {
            background: #e0e0e0;
            height: 8px;
            border-radius: 10px;
            margin: 30px 0;
            overflow: hidden;
        }

        .progress-fill {
            background: var(--accent-color);
            width: 65%;
            height: 100%;
            border-radius: 10px;
            animation: loading 2s ease infinite;
        }

        @keyframes loading {
            0% {
                width: 30%;
            }
            50% {
                width: 70%;
            }
            100% {
                width: 30%;
            }
        }

        .btn-home {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: var(--primary-color);
            color: white;
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            margin-top: 10px;
        }

        .btn-home:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
        }

        .notification-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-top: 30px;
        }

        .email-input {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .email-input input {
            flex: 1;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 50px;
        }

        .email-input button {
            background: var(--accent-color);
            color: var(--primary-color);
            border: none;
            padding: 10px 20px;
            border-radius: 50px;
            cursor: pointer;
            font-weight: 600;
        }

        @media (max-width: 576px) {
            .card {
                padding: 30px 20px;
            }

            h1 {
                font-size: 1.5rem;
            }

            .email-input {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="logo-icon">
                <span><?php echo $icon; ?></span>
            </div>
            <h1><?php echo $title; ?></h1>
            <div class="divider"></div>
            <p><?php echo $message; ?></p>
            <div class="progress-bar">
                <div class="progress-fill"></div>
            </div>
            <p style="font-size: 0.9rem; color: #888;">
                <i class="fas fa-clock"></i> We're working hard to bring you this content soon!
            </p>
            <a href="index.php" class="btn-home">
                <i class="fas fa-home"></i> Return to Home
            </a>

            <div class="notification-box">
                <p>
                    <i class="fas fa-bell"></i> Get notified when this is ready:
                </p>
                <form class="email-input" method="POST">
                    <input type="email" placeholder="Your email address" required>
                    <button type="submit">Notify Me</button>
                </form>
                <p style="font-size: 0.75rem; margin-top: 10px;">
                    We'll email you when this page is live.
                </p>
            </div>
        </div>
    </div>
</body>
</html>