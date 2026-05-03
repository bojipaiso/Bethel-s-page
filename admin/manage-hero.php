<?php
// admin/manage-hero.php
require_once '../includes/auth.php';
requireAdmin();
require_once '../includes/db.php';

$message = '';
$hero = $pdo->query("SELECT * FROM hero_content ORDER BY id DESC LIMIT 1")->fetch();

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_hero'])) {
    $title = trim($_POST['title']);
    $subtitle = trim($_POST['subtitle']);
    $cta_text = trim($_POST['cta_text']);
    $cta_link = trim($_POST['cta_link']);
    $bg_image = isset($hero['background_image']) ? $hero['background_image'] : '';
    
    if(isset($_FILES['background_image']) && $_FILES['background_image']['error'] == 0) {
        $allowed = ['jpg','jpeg','png','gif','webp'];
        $ext = strtolower(pathinfo($_FILES['background_image']['name'], PATHINFO_EXTENSION));
        if(in_array($ext, $allowed)) {
            $upload_dir = '../uploads/hero/';
            if(!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            $filename = 'hero_' . time() . '.' . $ext;
            move_uploaded_file($_FILES['background_image']['tmp_name'], $upload_dir . $filename);
            $bg_image = 'uploads/hero/' . $filename;
            if(isset($hero['background_image']) && $hero['background_image'] && file_exists('../' . $hero['background_image'])) {
                unlink('../' . $hero['background_image']);
            }
        }
    }
    
    if($hero) {
        $stmt = $pdo->prepare("UPDATE hero_content SET title=?, subtitle=?, cta_text=?, cta_link=?, background_image=? WHERE id=?");
        $stmt->execute([$title, $subtitle, $cta_text, $cta_link, $bg_image, $hero['id']]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO hero_content (title, subtitle, cta_text, cta_link, background_image) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$title, $subtitle, $cta_text, $cta_link, $bg_image]);
    }
    $message = "Hero section updated!";
    header("Location: manage-hero.php");
    exit();
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
        .preview-box { background: #f8f9fa; padding: 20px; border-radius: 10px; margin-top: 20px; }
        .hero-preview { background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); padding: 30px; border-radius: 10px; color: white; text-align: center; }
        .hero-preview h2 { color: var(--accent-color); margin-bottom: 10px; }
        .current-image-preview { max-width: 100%; margin-top: 10px; border-radius: 5px; }
    </style>
</head>
<body>
<div class="admin-wrapper">
    <nav class="admin-nav"><div class="admin-nav-container"><div class="admin-logo">Bethel CMS</div><div class="admin-user"><a href="dashboard.php">Dashboard</a> | <a href="logout.php">Logout</a></div></div></nav>
    <div class="admin-container">
        <div class="page-header"><h1>Manage Hero Section</h1><a href="dashboard.php" class="btn-secondary">← Back to Dashboard</a></div>
        <?php if($message): ?><div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>
        <div class="form-container">
            <h2>Edit Hero Banner Content</h2>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group"><label>Main Title</label><input type="text" name="title" required value="<?php echo isset($hero['title']) ? htmlspecialchars($hero['title']) : ''; ?>"></div>
                <div class="form-group"><label>Subtitle</label><textarea name="subtitle" rows="4" required><?php echo isset($hero['subtitle']) ? htmlspecialchars($hero['subtitle']) : ''; ?></textarea></div>
                <div class="form-row">
                    <div class="form-group"><label>Button Text</label><input type="text" name="cta_text" value="<?php echo isset($hero['cta_text']) ? htmlspecialchars($hero['cta_text']) : ''; ?>"></div>
                    <div class="form-group"><label>Button Link</label><input type="text" name="cta_link" value="<?php echo isset($hero['cta_link']) ? htmlspecialchars($hero['cta_link']) : ''; ?>"></div>
                </div>
                <div class="form-group">
                    <label>Background Image (Optional)</label>
                    <?php if(isset($hero['background_image']) && $hero['background_image']): ?>
                        <div class="current-image-preview"><img src="../<?php echo $hero['background_image']; ?>" style="max-width:100%; max-height:200px;"><p>Current background image</p></div>
                    <?php endif; ?>
                    <input type="file" name="background_image" accept="image/*"><small>Recommended size: 1920x1080px. Leave empty to keep current.</small>
                </div>
                <button type="submit" name="update_hero" class="btn-primary">Update Hero Section</button>
            </form>
        </div>
        <div class="preview-box">
            <h3>Live Preview</h3>
            <div class="hero-preview">
                <h2><?php echo isset($hero['title']) ? htmlspecialchars($hero['title']) : 'Preview'; ?></h2>
                <p><?php echo isset($hero['subtitle']) ? htmlspecialchars($hero['subtitle']) : 'Preview text'; ?></p>
                <a href="#" style="display:inline-block; background: var(--accent-color); color: var(--primary-color); padding: 10px 20px; border-radius: 50px; text-decoration: none;"><?php echo isset($hero['cta_text']) ? htmlspecialchars($hero['cta_text']) : 'Button'; ?></a>
            </div>
        </div>
    </div>
</div>
</body>
</html>