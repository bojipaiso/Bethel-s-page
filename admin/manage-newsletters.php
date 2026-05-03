<?php
// admin/manage-newsletters.php (cleaned)
require_once '../includes/auth.php';
requireAdmin();
require_once '../includes/db.php';

$message = '';

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload_newsletter'])) {
    $title = trim($_POST['title']);
    $issue_number = trim($_POST['issue_number']);
    $month = $_POST['month'];
    $year = intval($_POST['year']);
    $summary = trim($_POST['summary']);
    $status = $_POST['status'];
    if(isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] == 0) {
        $upload_dir = '../uploads/newsletters/';
        if(!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9]/', '_', $_FILES['pdf_file']['name']);
        move_uploaded_file($_FILES['pdf_file']['tmp_name'], $upload_dir . $filename);
        $pdf_url = 'uploads/newsletters/' . $filename;
        $stmt = $pdo->prepare("INSERT INTO newsletters (title, issue_number, month, year, pdf_url, summary, status, published_date) VALUES (?, ?, ?, ?, ?, ?, ?, CURDATE())");
        $stmt->execute([$title, $issue_number, $month, $year, $pdf_url, $summary, $status]);
        $message = "Newsletter uploaded!";
        header("Location: manage-newsletters.php");
        exit();
    }
}
if(isset($_GET['delete'])) {
    $stmt = $pdo->prepare("SELECT pdf_url FROM newsletters WHERE id = ?");
    $stmt->execute([intval($_GET['delete'])]);
    $nl = $stmt->fetch();
    if($nl && $nl['pdf_url'] && file_exists('../' . $nl['pdf_url'])) unlink('../' . $nl['pdf_url']);
    $stmt = $pdo->prepare("DELETE FROM newsletters WHERE id = ?");
    $stmt->execute([intval($_GET['delete'])]);
    $message = "Newsletter deleted!";
    header("Location: manage-newsletters.php");
    exit();
}
if(isset($_GET['toggle_status'])) {
    $stmt = $pdo->prepare("UPDATE newsletters SET status = IF(status='published', 'draft', 'published') WHERE id = ?");
    $stmt->execute([intval($_GET['toggle_status'])]);
    $message = "Status toggled!";
    header("Location: manage-newsletters.php");
    exit();
}

$newsletters = $pdo->query("SELECT * FROM newsletters ORDER BY year DESC, published_date DESC")->fetchAll();
$coming_soon = $pdo->query("SELECT setting_value FROM school_settings WHERE setting_key = 'newsletter_coming_soon'")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Newsletters - Bethel School</title>
    <link rel="stylesheet" href="../css/admin-style.css">
    <style>
        .coming-soon-status { background: <?php echo $coming_soon == '1' ? '#fff3cd' : '#d4edda'; ?>; color: <?php echo $coming_soon == '1' ? '#856404' : '#155724'; ?>; padding: 10px 15px; border-radius: 5px; margin-bottom: 20px; border-left: 4px solid <?php echo $coming_soon == '1' ? '#ffc107' : '#28a745'; ?>; }
    </style>
</head>
<body>
<div class="admin-wrapper">
    <nav class="admin-nav"><div class="admin-nav-container"><div class="admin-logo">Bethel CMS</div><div class="admin-user"><a href="dashboard.php">Dashboard</a> | <a href="logout.php">Logout</a></div></div></nav>
    <div class="admin-container">
        <div class="page-header"><h1>Manage Newsletters</h1></div>
        <div class="coming-soon-status"><strong>📰 Coming Soon Page Status:</strong> <?php echo $coming_soon == '1' ? '🔴 Currently ACTIVE (no newsletters) ' : '🟢 Currently DISABLED (newsletters available)'; ?></div>
        <?php if($message): ?><div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>
        <div class="form-container"><h2>Upload New Newsletter</h2><form method="POST" enctype="multipart/form-data"><div class="form-row"><div class="form-group"><label>Title</label><input type="text" name="title" required></div><div class="form-group"><label>Issue Number</label><input type="text" name="issue_number" placeholder="Vol. 1, Issue 1"></div></div><div class="form-row"><div class="form-group"><label>Month</label><select name="month"><option>January</option><option>February</option><option>March</option><option>April</option><option>May</option><option>June</option><option>July</option><option>August</option><option>September</option><option>October</option><option>November</option><option>December</option></select></div><div class="form-group"><label>Year</label><input type="number" name="year" value="<?php echo date('Y'); ?>"></div></div><div class="form-group"><label>Summary</label><textarea name="summary" rows="3"></textarea></div><div class="form-group"><label>PDF File</label><input type="file" name="pdf_file" accept=".pdf" required></div><div class="form-group"><label>Status</label><select name="status"><option value="published">Published</option><option value="draft">Draft</option></select></div><button type="submit" name="upload_newsletter" class="btn-primary">Upload</button></form></div>
        <div class="data-table-container"><h2>Published Newsletters</h2><table class="data-table"><thead><tr><th>Title</th><th>Issue</th><th>Date</th><th>Status</th><th>Actions</th></tr></thead><tbody><?php foreach($newsletters as $nl): ?><tr><td><?php echo htmlspecialchars($nl['title']); ?></td><td><?php echo htmlspecialchars($nl['issue_number']); ?></td><td><?php echo $nl['month'] . ' ' . $nl['year']; ?></td><td><span class="status-badge status-<?php echo $nl['status']; ?>"><?php echo $nl['status']; ?></span></td><td class="actions"><?php if($nl['pdf_url']): ?><a href="../<?php echo $nl['pdf_url']; ?>" target="_blank" class="btn-edit">View PDF</a><?php endif; ?> <a href="?toggle_status=<?php echo $nl['id']; ?>" class="btn-toggle">Toggle</a> <a href="?delete=<?php echo $nl['id']; ?>" class="btn-delete" onclick="return confirm('Delete?')">Delete</a></td></tr><?php endforeach; ?></tbody></table></div>
    </div>
</div>
</body>
</html>