<?php
// admin/manage-hero.php
require_once '../includes/auth.php';
requireAdmin();
require_once '../includes/db.php';

// Get current hero content
$hero = $pdo->query("SELECT * FROM hero_content ORDER BY id DESC LIMIT 1")->fetch();

// Handle update
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_hero'])) {
    $title = $_POST['title'];
    $subtitle = $_POST['subtitle'];
    $cta_text = $_POST['cta_text'];
    $cta_link = $_POST['cta_link'];
    
    // Handle background image upload
    $background_image = $hero['background_image'] ?? '';
    if(isset($_FILES['background_image']) && $_FILES['background_image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $filename = $_FILES['background_image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if(in_array($ext, $allowed)) {
            if(!is_dir('../uploads')) {
                mkdir('../uploads', 0777, true);
            }
            
            $new_filename = 'hero_' . time() . '.' . $ext;
            $upload_path = '../uploads/' . $new_filename;
            
            if(move_uploaded_file($_FILES['background_image']['tmp_name'], $upload_path)) {
                $background_image = 'uploads/' . $new_filename;
                
                // Delete old background if exists
                if($hero && $hero['background_image'] && file_exists('../' . $hero['background_image'])) {
                    unlink('../' . $hero['background_image']);
                }
            }
        }
    }
    
    if($hero) {
        $stmt = $pdo->prepare("UPDATE hero_content SET title=?, subtitle=?, cta_text=?, cta_link=?, background_image=? WHERE id=?");
        $stmt->execute([$title, $subtitle, $cta_text, $cta_link, $background_image, $hero['id']]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO hero_content (title, subtitle, cta_text, cta_link, background_image) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$title, $subtitle, $cta_text, $cta_link, $background_image]);
    }
    
    $message = "Hero section updated!";
    
    // Refresh data
    $hero = $pdo->query("SELECT * FROM hero_content ORDER BY id DESC LIMIT 1")->fetch();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Hero Section - Bethel School</title>
    <link rel="stylesheet" href="../css/admin-style.css">
    <style>
        .preview-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
            border: 2px dashed #dee2e6;
        }
        .preview-box h3 {
            margin-bottom: 15px;
            color: #002366;
        }
        .hero-preview {
            background: linear-gradient(rgba(0, 35, 102, 0.85), rgba(0, 86, 179, 0.9));
            padding: 40px;
            border-radius: 10px;
            color: white;
            text-align: center;
        }
        .hero-preview h2 {
            color: #FFD700;
            margin-bottom: 15px;
        }
        .current-image-preview {
            max-width: 100%;
            margin-top: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <nav class="admin-nav">
            <div class="admin-nav-container">
                <div class="admin-logo">Bethel CMS</div>
                <div class="admin-user">
                    <a href="dashboard.php">Dashboard</a> |
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        </nav>
        
        <div class="admin-container">
            <div class="page-header">
                <h1>Manage Hero Section</h1>
                <a href="dashboard.php" class="btn-secondary">← Back to Dashboard</a>
            </div>
            
            <?php if(isset($message)): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <div class="form-container">
                <h2>Edit Hero Banner Content</h2>
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Main Title</label>
                        <input type="text" name="title" required value="<?php echo htmlspecialchars($hero['title'] ?? '🦅 Soaring to Excellence in International Education'); ?>">
                        <small>You can use emojis like 🦅</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Subtitle / Description</label>
                        <textarea name="subtitle" rows="4" required><?php echo htmlspecialchars($hero['subtitle'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Button Text</label>
                            <input type="text" name="cta_text" value="<?php echo htmlspecialchars($hero['cta_text'] ?? 'Explore Our Programs'); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label>Button Link</label>
                            <input type="text" name="cta_link" value="<?php echo htmlspecialchars($hero['cta_link'] ?? '#'); ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Background Image (Optional)</label>
                        <?php if($hero && $hero['background_image']): ?>
                            <div class="current-image-preview">
                                <img src="../<?php echo $hero['background_image']; ?>" alt="Current background" style="max-width: 100%; max-height: 200px;">
                                <p>Current background image</p>
                            </div>
                        <?php endif; ?>
                        <input type="file" name="background_image" accept="image/*">
                        <small>Recommended size: 1920x1080px. Leave empty to keep current.</small>
                    </div>
                    
                    <button type="submit" name="update_hero" class="btn-primary">Update Hero Section</button>
                </form>
            </div>
            
            <!-- Live Preview -->
            <div class="preview-box">
                <h3>Live Preview</h3>
                <div class="hero-preview">
                    <h2><?php echo htmlspecialchars($hero['title'] ?? '🦅 Soaring to Excellence in International Education'); ?></h2>
                    <p><?php echo htmlspecialchars($hero['subtitle'] ?? 'Inspired by the majesty of the Philippine Eagle...'); ?></p>
                    <a href="#" class="cta-button" style="display: inline-block; background: #FFD700; color: #002366; padding: 10px 30px; border-radius: 50px; text-decoration: none; font-weight: bold;"><?php echo htmlspecialchars($hero['cta_text'] ?? 'Explore Our Programs'); ?></a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>