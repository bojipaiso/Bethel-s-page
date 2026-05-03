<?php
// admin/manage-calendar.php (cleaned)
require_once '../includes/auth.php';
requireAdmin();
require_once '../includes/db.php';

$message = '';

// PDF upload
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload_pdf'])) {
    $title = trim($_POST['title']);
    $school_year = trim($_POST['school_year']);
    $is_current = isset($_POST['is_current']) ? 1 : 0;
    if(isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] == 0) {
        $upload_dir = '../uploads/calendar/';
        if(!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        $filename = 'academic_calendar_' . preg_replace('/[^a-zA-Z0-9]/', '_', $school_year) . '_' . time() . '.pdf';
        move_uploaded_file($_FILES['pdf_file']['tmp_name'], $upload_dir . $filename);
        $pdf_url = 'uploads/calendar/' . $filename;
        if($is_current) $pdo->prepare("UPDATE calendar_pdfs SET is_current = 0")->execute();
        $stmt = $pdo->prepare("INSERT INTO calendar_pdfs (title, school_year, pdf_url, is_current) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $school_year, $pdf_url, $is_current]);
        $message = "PDF uploaded!";
        header("Location: manage-calendar.php");
        exit();
    }
}
// Delete PDF
if(isset($_GET['delete_pdf'])) {
    $stmt = $pdo->prepare("SELECT pdf_url FROM calendar_pdfs WHERE id = ?");
    $stmt->execute([intval($_GET['delete_pdf'])]);
    $pdf = $stmt->fetch();
    if($pdf && $pdf['pdf_url'] && file_exists('../' . $pdf['pdf_url'])) unlink('../' . $pdf['pdf_url']);
    $stmt = $pdo->prepare("DELETE FROM calendar_pdfs WHERE id = ?");
    $stmt->execute([intval($_GET['delete_pdf'])]);
    $message = "PDF deleted!";
    header("Location: manage-calendar.php");
    exit();
}
// Set current PDF
if(isset($_GET['set_current'])) {
    $pdo->prepare("UPDATE calendar_pdfs SET is_current = 0")->execute();
    $stmt = $pdo->prepare("UPDATE calendar_pdfs SET is_current = 1 WHERE id = ?");
    $stmt->execute([intval($_GET['set_current'])]);
    $message = "Current calendar updated!";
    header("Location: manage-calendar.php");
    exit();
}
// Event actions
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_event'])) {
    $id = isset($_POST['event_id']) ? intval($_POST['event_id']) : 0;
    $event_name = trim($_POST['event_name']);
    $event_date = $_POST['event_date'];
    $description = trim($_POST['description']);
    $event_type = $_POST['event_type'];
    $display_order = intval($_POST['display_order']);
    if($id > 0) {
        $stmt = $pdo->prepare("UPDATE academic_calendar SET event_name=?, event_date=?, description=?, event_type=?, display_order=? WHERE id=?");
        $stmt->execute([$event_name, $event_date, $description, $event_type, $display_order, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO academic_calendar (event_name, event_date, description, event_type, display_order) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$event_name, $event_date, $description, $event_type, $display_order]);
    }
    $message = "Event saved!";
    header("Location: manage-calendar.php");
    exit();
}
if(isset($_GET['delete_event'])) {
    $stmt = $pdo->prepare("DELETE FROM academic_calendar WHERE id = ?");
    $stmt->execute([intval($_GET['delete_event'])]);
    $message = "Event deleted!";
    header("Location: manage-calendar.php");
    exit();
}
if(isset($_GET['toggle_event'])) {
    $stmt = $pdo->prepare("UPDATE academic_calendar SET status = IF(status='active', 'inactive', 'active') WHERE id = ?");
    $stmt->execute([intval($_GET['toggle_event'])]);
    $message = "Event status toggled!";
    header("Location: manage-calendar.php");
    exit();
}

$pdfs = $pdo->query("SELECT * FROM calendar_pdfs ORDER BY is_current DESC, school_year DESC")->fetchAll();
$events = $pdo->query("SELECT * FROM academic_calendar ORDER BY event_date ASC, display_order ASC")->fetchAll();
$edit_event = null;
if(isset($_GET['edit_event'])) {
    $stmt = $pdo->prepare("SELECT * FROM academic_calendar WHERE id = ?");
    $stmt->execute([intval($_GET['edit_event'])]);
    $edit_event = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Calendar - Bethel School</title>
    <link rel="stylesheet" href="../css/admin-style.css">
    <style>
        .section-divider { background: var(--primary-color); color: white; padding: 10px 15px; margin: 30px 0 20px; border-radius: 5px; }
        .pdf-list { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px,1fr)); gap: 15px; margin-top: 20px; }
        .pdf-card { background: #f8f9fa; border: 1px solid var(--gray-border); border-radius: 10px; padding: 15px; }
        .pdf-card.current { background: #d4edda; border-color: #28a745; }
        .badge-current { background: #28a745; color: white; padding: 2px 8px; border-radius: 20px; font-size: 0.7rem; }
        .btn-pdf-delete { background: #dc3545; color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.7rem; text-decoration: none; }
        .btn-pdf-current { background: #ffc107; color: #000; padding: 4px 8px; border-radius: 4px; font-size: 0.7rem; text-decoration: none; }
    </style>
</head>
<body>
<div class="admin-wrapper">
    <nav class="admin-nav"><div class="admin-nav-container"><div class="admin-logo">Bethel CMS</div><div class="admin-user"><a href="dashboard.php">Dashboard</a> | <a href="logout.php">Logout</a></div></div></nav>
    <div class="admin-container">
        <div class="page-header"><h1>Manage Calendar</h1></div>
        <?php if($message): ?><div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>
        <div class="section-divider"><h2>📄 PDF Calendars</h2></div>
        <div class="form-container"><h3>Upload New PDF</h3><form method="POST" enctype="multipart/form-data"><div class="form-row"><div class="form-group"><label>Title</label><input type="text" name="title" required></div><div class="form-group"><label>School Year</label><input type="text" name="school_year" required placeholder="2025-2026"></div></div><div class="form-group"><label>PDF File</label><input type="file" name="pdf_file" accept=".pdf" required></div><div class="form-group"><label><input type="checkbox" name="is_current" value="1"> Set as current calendar</label></div><button type="submit" name="upload_pdf" class="btn-primary">Upload PDF</button></form></div>
        <div class="pdf-list"><?php foreach($pdfs as $p): ?><div class="pdf-card <?php echo $p['is_current'] ? 'current' : ''; ?>"><?php if($p['is_current']): ?><div class="badge-current">✓ Current</div><?php endif; ?><strong><?php echo htmlspecialchars($p['title']); ?></strong><br>School Year: <?php echo htmlspecialchars($p['school_year']); ?><br><div style="margin-top:10px;"><a href="../<?php echo $p['pdf_url']; ?>" target="_blank" class="btn-edit">View PDF</a> <?php if(!$p['is_current']): ?><a href="?set_current=<?php echo $p['id']; ?>" class="btn-pdf-current">Set Current</a><?php endif; ?> <a href="?delete_pdf=<?php echo $p['id']; ?>" class="btn-pdf-delete" onclick="return confirm('Delete?')">Delete</a></div></div><?php endforeach; ?></div>
        <div class="section-divider"><h2>📆 Calendar Events</h2></div>
        <div class="form-container"><h3><?php echo $edit_event ? 'Edit Event' : 'Add Event'; ?></h3><form method="POST"><input type="hidden" name="event_id" value="<?php echo isset($edit_event['id']) ? $edit_event['id'] : ''; ?>"><div class="form-group"><label>Event Name</label><input type="text" name="event_name" required value="<?php echo isset($edit_event['event_name']) ? htmlspecialchars($edit_event['event_name']) : ''; ?>"></div><div class="form-row"><div class="form-group"><label>Event Date</label><input type="date" name="event_date" required value="<?php echo isset($edit_event['event_date']) ? $edit_event['event_date'] : ''; ?>"></div><div class="form-group"><label>Event Type</label><select name="event_type"><option value="regular">Regular</option><option value="holiday">Holiday</option><option value="exam">Exam</option><option value="event">Event</option><option value="deadline">Deadline</option></select></div></div><div class="form-group"><label>Description</label><textarea name="description" rows="3"><?php echo isset($edit_event['description']) ? htmlspecialchars($edit_event['description']) : ''; ?></textarea></div><div class="form-group"><label>Display Order</label><input type="number" name="display_order" value="<?php echo isset($edit_event['display_order']) ? $edit_event['display_order'] : 0; ?>"></div><button type="submit" name="save_event" class="btn-primary">Save Event</button><?php if($edit_event): ?><a href="manage-calendar.php" class="btn-secondary">Cancel</a><?php endif; ?></form></div>
        <div class="data-table-container"><table class="data-table"><thead><tr><th>Date</th><th>Event</th><th>Type</th><th>Status</th><th>Actions</th></tr></thead><tbody><?php foreach($events as $e): ?><tr><td><?php echo date('M d, Y', strtotime($e['event_date'])); ?></td><td><?php echo htmlspecialchars($e['event_name']); ?></td><td><?php echo $e['event_type']; ?></td><td><span class="status-badge status-<?php echo isset($e['status']) ? $e['status'] : 'active'; ?>"><?php echo isset($e['status']) ? $e['status'] : 'active'; ?></span></td><td class="actions"><a href="?edit_event=<?php echo $e['id']; ?>" class="btn-edit">Edit</a> <a href="?toggle_event=<?php echo $e['id']; ?>" class="btn-toggle">Toggle</a> <a href="?delete_event=<?php echo $e['id']; ?>" class="btn-delete" onclick="return confirm('Delete?')">Delete</a></td></tr><?php endforeach; ?></tbody></table></div>
    </div>
</div>
</body>
</html>