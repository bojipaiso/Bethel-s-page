<?php
// includes/header.php - Your existing design
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Creative Portfolio'; ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="index.php" class="logo">CreativePort</a>
            <ul class="nav-menu">
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="blog.php">Blog</a></li>
                <li><a href="portfolio.php">Portfolio</a></li>
                <li><a href="contact.php">Contact</a></li>
                <?php if(isset($_SESSION['admin_logged_in'])): ?>
                    <li><a href="admin/dashboard.php" class="admin-link">Admin</a></li>
                    <li><a href="admin/logout.php">Logout</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    <main class="main-content">