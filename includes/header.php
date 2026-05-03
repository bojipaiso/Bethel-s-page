<?php
// includes/header.php
// $page_title should be defined before including this file
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Bethel International School'; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <?php if (isset($additional_css)): ?>
        <style><?php echo $additional_css; ?></style>
    <?php endif; ?>
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
                    <li><a href="index.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'class="active"' : ''; ?>>Home</a></li>
                    <li><a href="about.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'about.php') ? 'class="active"' : ''; ?>>About Us</a></li>
                    <li><a href="academics.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'academics.php') ? 'class="active"' : ''; ?>>Academics</a></li>
                    <li><a href="admissions.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'admissions.php') ? 'class="active"' : ''; ?>>Admissions</a></li>
                    <li><a href="news.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'news.php') ? 'class="active"' : ''; ?>>News & Events</a></li>
                    <li><a href="contact.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'contact.php') ? 'class="active"' : ''; ?>>Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <?php if (isset($show_banner) && $show_banner !== false): ?>
    <section class="page-banner">
        <div class="container">
            <h1><?php echo $banner_title ?? $page_title ?? 'Welcome'; ?></h1>
            <p><?php echo $banner_subtitle ?? ''; ?></p>
        </div>
    </section>
    <?php endif; ?>