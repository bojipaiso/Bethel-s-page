<?php
// admin/manage-calendar.php - COMPLETE with manual toggle for calendar
require_once '../includes/auth.php';
requireAdmin();
require_once '../includes/db.php';

$message = '';
$error = '';

// ============================================
// MANUAL TOGGLE FOR CALENDAR COMING SOON
// ============================================
if(isset($_GET['enable_calendar_coming_soon'])) {
    $pdo->prepare("UPDATE school_settings SET setting_value = '1' WHERE setting_key = 'calendar_coming_soon'")->execute();
    $message = "🔴 Coming Soon page enabled for Calendar. Calendar PDFs are now hidden.";
    header("Location: manage-calendar.php");
    exit();
}

if(isset($_GET['disable_calendar_coming_soon'])) {
    $pdo->prepare("UPDATE school_settings SET setting_value = '0' WHERE setting_key = 'calendar_coming_soon'")->execute();
    $message = "🟢 Coming Soon page disabled for Calendar. Calendar PDFs are now visible.";
    header("Location: manage-calendar.php");
    exit();
}

// ============================================
// PDF CALENDAR UPLOAD
// ============================================
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload_pdf'])) {
    $title = $_POST['title'];
    $school_year = $_POST['school_year'];
    $is_current = isset($_POST['is_current']) ? 1 : 0;
    
    if(isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] == 0) {
        $allowed = ['pdf'];
        $filename = $_FILES['pdf_file']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if(in_array($ext, $allowed)) {
            $upload_dir = '../uploads/calendar/';
            if(!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            
            $new_filename = 'academic_calendar_' . $school_year . '_' . time() . '.pdf';
            $upload_path = $upload_dir . $new_filename;
            
            if(move_uploaded_file($_FILES['pdf_file']['tmp_name'], $upload_path)) {
                if($is_current == 1) {
                    $stmt = $pdo->prepare("UPDATE calendar_pdfs SET is_current = 0");
                    $stmt->execute();
                }
                
                $stmt = $pdo->prepare("INSERT INTO calendar_pdfs (title, school_year, pdf_url, is_current) VALUES (?, ?, ?, ?)");
                $stmt->execute([$title, $school_year, $upload_path, $is_current]);
                
                // Auto-disable coming soon when first calendar PDF is uploaded
                $count = $pdo->query("SELECT COUNT(*) FROM calendar_pdfs")->fetchColumn();
                if($count == 0) {
                    $pdo->prepare("UPDATE school_settings SET setting_value = '0' WHERE setting_key = 'calendar_coming_soon'")->execute();
                }
                $message = "✅ Calendar PDF uploaded successfully!";
            } else {
                $error = "Failed to upload file.";
            }
        } else {
            $error = "Only PDF files are allowed.";
        }
    } else {
        $error = "Please select a PDF file to upload.";
    }
}

// ============================================
// DELETE PDF
// ============================================
if(isset($_GET['delete_pdf'])) {
    $id = $_GET['delete_pdf'];
    
    $stmt = $pdo->prepare("SELECT pdf_url FROM calendar_pdfs WHERE id = ?");
    $stmt->execute([$id]);
    $pdf = $stmt->fetch();
    
    if($pdf && file_exists('../' . $pdf['pdf_url'])) {
        unlink('../' . $pdf['pdf_url']);
    }
    
    $stmt = $pdo->prepare("DELETE FROM calendar_pdfs WHERE id = ?");
    $stmt->execute([$id]);
    
    // Check if any PDFs remain
    $count = $pdo->query("SELECT COUNT(*) FROM calendar_pdfs WHERE status='active'")->fetchColumn();
    if($count == 0) {
        $pdo->prepare("UPDATE school_settings SET setting_value = '1' WHERE setting_key = 'calendar_coming_soon'")->execute();
        $message = "Calendar PDF deleted. Coming Soon page re-enabled (no calendars available).";
    } else {
        $message = "Calendar PDF deleted successfully!";
    }
}

// ============================================
// SET CURRENT PDF
// ============================================
if(isset($_GET['set_current'])) {
    $id = $_GET['set_current'];
    
    $pdo->prepare("UPDATE calendar_pdfs SET is_current = 0")->execute();
    $pdo->prepare("UPDATE calendar_pdfs SET is_current = 1 WHERE id = ?")->execute([$id]);
    $message = "Current calendar updated!";
}

// ============================================
// CALENDAR EVENTS CRUD
// ============================================

// Add/Edit event
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_event'])) {
    $id = $_POST['id'] ?? 0;
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $description = $_POST['description'];
    $event_type = $_POST['event_type'];
    $display_order = $_POST['display_order'];
    
    if($id > 0) {
        $stmt = $pdo->prepare("UPDATE academic_calendar SET event_name=?, event_date=?, description=?, event_type=?, display_order=? WHERE id=?");
        $stmt->execute([$event_name, $event_date, $description, $event_type, $display_order, $id]);
        $message = "Event updated!";
    } else {
        $stmt = $pdo->prepare("INSERT INTO academic_calendar (event_name, event_date, description, event_type, display_order) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$event_name, $event_date, $description, $event_type, $display_order]);
        $message = "Event added!";
    }
}

// Delete event
if(isset($_GET['delete_event'])) {
    $stmt = $pdo->prepare("DELETE FROM academic_calendar WHERE id = ?");
    $stmt->execute([$_GET['delete_event']]);
    $message = "Event deleted!";
}

// Toggle event status
if(isset($_GET['toggle_event'])) {
    $stmt = $pdo->prepare("UPDATE academic_calendar SET status = IF(status='active', 'inactive', 'active') WHERE id = ?");
    $stmt->execute([$_GET['toggle_event']]);
    $message = "Event status updated!";
}

// Get event for editing
$edit_event = null;
if(isset($_GET['edit_event'])) {
    $stmt = $pdo->prepare("SELECT * FROM academic_calendar WHERE id = ?");
    $stmt->execute([$_GET['edit_event']]);
    $edit_event = $stmt->fetch();
}

// ============================================
// FETCH ALL DATA
// ============================================
$events = $pdo->query("SELECT * FROM academic_calendar ORDER BY event_date ASC, display_order ASC")->fetchAll();
$pdf_calendars = $pdo->query("SELECT * FROM calendar_pdfs ORDER BY is_current DESC, school_year DESC")->fetchAll();
$calendar_coming_soon = $pdo->query("SELECT setting_value FROM school_settings WHERE setting_key = 'calendar_coming_soon'")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Calendar - Bethel School</title>
    <link rel="stylesheet" href="../css/admin-style.css">
    <style>
        .toggle-control {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            border-left: 4px solid <?php echo $calendar_coming_soon == '1' ? '#ffc107' : '#28a745'; ?>;
        }
        .toggle-status {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .status-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
        }
        .status-active { background-color: #dc3545; box-shadow: 0 0 5px #dc3545; }
        .status-inactive { background-color: #28a745; box-shadow: 0 0 5px #28a745; }
        .btn-toggle-on { background: #dc3545; color: white; padding: 8px 16px; border-radius: 5px; text-decoration: none; font-weight: 500; display: inline-block; }
        .btn-toggle-off { background: #28a745; color: white; padding: 8px 16px; border-radius: 5px; text-decoration: none; font-weight: 500; display: inline-block; }
        .section-divider {
            background: var(--primary-color);
            color: white;
            padding: 10px 15px;
            margin: 30px 0 20px;
            border-radius: 5px;
        }
        .pdf-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        .pdf-card {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 15px;
        }
        .pdf-card.current {
            background: #d4edda;
            border-color: #28a745;
        }
        .badge-current {
            background: #28a745;
            color: white;
            padding: 3px 8px;
            border-radius: 20px;
            font-size: 0.7rem;
            display: inline-block;
            margin-bottom: 10px;
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
                <h1>Manage Calendar</h1>
                <p>Manage academic calendar events and PDF downloads</p>
            </div>
            
            <?php if($message): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>
            <?php if($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <!-- Toggle Control Panel for Calendar -->
            <div class="toggle-control">
                <div class="toggle-status">
                    <?php if($calendar_coming_soon == '1'): ?>
                        <span class="status-indicator status-active"></span>
                        <strong>🔴 Calendar Coming Soon Mode: ACTIVE</strong>
                        <span style="color: #666; font-size: 0.85rem;">(Visitors see "Coming Soon" page)</span>
                    <?php else: ?>
                        <span class="status-indicator status-inactive"></span>
                        <strong>🟢 Calendar Coming Soon Mode: INACTIVE</strong>
                        <span style="color: #666; font-size: 0.85rem;">(Visitors see calendar PDFs)</span>
                    <?php endif; ?>
                </div>
                <div>
                    <?php if($calendar_coming_soon == '1'): ?>
                        <a href="?disable_calendar_coming_soon=1" class="btn-toggle-off">
                            📄 Show Calendar Instead
                        </a>
                    <?php else: ?>
                        <a href="?enable_calendar_coming_soon=1" class="btn-toggle-on">
                            🔧 Enable Coming Soon Page
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- PDF CALENDAR SECTION -->
            <div class="section-divider">
                <h2>📄 PDF Calendar Downloads</h2>
            </div>
            
            <!-- Upload PDF Form -->
            <div class="form-container">
                <h3>Upload New Calendar PDF</h3>
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Title *</label>
                            <input type="text" name="title" required placeholder="e.g., Academic Calendar SY 2025-2026">
                        </div>
                        <div class="form-group">
                            <label>School Year *</label>
                            <input type="text" name="school_year" required placeholder="e.g., 2025-2026">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>PDF File *</label>
                        <input type="file" name="pdf_file" accept=".pdf" required>
                        <small>Upload PDF version of the academic calendar</small>
                    </div>
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="is_current" value="1"> Set as current calendar
                        </label>
                    </div>
                    <button type="submit" name="upload_pdf" class="btn-primary">Upload PDF</button>
                </form>
            </div>
            
            <!-- PDF Calendars List -->
            <h3>Existing Calendars</h3>
            <div class="pdf-list">
                <?php if(count($pdf_calendars) > 0): ?>
                    <?php foreach($pdf_calendars as $pdf): ?>
                        <div class="pdf-card <?php echo $pdf['is_current'] ? 'current' : ''; ?>">
                            <?php if($pdf['is_current']): ?>
                                <div class="badge-current">✓ Current Calendar</div>
                            <?php endif; ?>
                            <div class="pdf-title"><strong><?php echo htmlspecialchars($pdf['title']); ?></strong></div>
                            <div>School Year: <?php echo htmlspecialchars($pdf['school_year']); ?></div>
                            <div class="pdf-actions" style="margin-top: 10px;">
                                <a href="../<?php echo $pdf['pdf_url']; ?>" target="_blank" class="btn-edit">View PDF</a>
                                <?php if(!$pdf['is_current']): ?>
                                    <a href="?set_current=<?php echo $pdf['id']; ?>" class="btn-toggle">Set as Current</a>
                                <?php endif; ?>
                                <a href="?delete_pdf=<?php echo $pdf['id']; ?>" class="btn-delete" onclick="return confirm('Delete this calendar PDF?')">Delete</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="color: #666; padding: 20px; text-align: center;">No calendar PDFs uploaded yet.</p>
                <?php endif; ?>
            </div>
            
            <!-- CALENDAR EVENTS SECTION -->
            <div class="section-divider">
                <h2>📆 Calendar Events</h2>
            </div>
            
            <!-- Add/Edit Event Form -->
            <div class="form-container">
                <h3><?php echo $edit_event ? 'Edit Event' : 'Add New Event'; ?></h3>
                <form method="POST">
                    <input type="hidden" name="id" value="<?php echo $edit_event['id'] ?? ''; ?>">
                    
                    <div class="form-group">
                        <label>Event Name *</label>
                        <input type="text" name="event_name" required value="<?php echo htmlspecialchars($edit_event['event_name'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Event Date *</label>
                            <input type="date" name="event_date" required value="<?php echo $edit_event['event_date'] ?? ''; ?>">
                        </div>
                        <div class="form-group">
                            <label>Event Type</label>
                            <select name="event_type">
                                <option value="regular" <?php echo ($edit_event['event_type'] ?? '') == 'regular' ? 'selected' : ''; ?>>Regular</option>
                                <option value="holiday" <?php echo ($edit_event['event_type'] ?? '') == 'holiday' ? 'selected' : ''; ?>>Holiday</option>
                                <option value="exam" <?php echo ($edit_event['event_type'] ?? '') == 'exam' ? 'selected' : ''; ?>>Exam</option>
                                <option value="event" <?php echo ($edit_event['event_type'] ?? '') == 'event' ? 'selected' : ''; ?>>Special Event</option>
                                <option value="deadline" <?php echo ($edit_event['event_type'] ?? '') == 'deadline' ? 'selected' : ''; ?>>Deadline</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" rows="3"><?php echo htmlspecialchars($edit_event['description'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Display Order</label>
                        <input type="number" name="display_order" value="<?php echo $edit_event['display_order'] ?? 0; ?>">
                        <small>Lower numbers appear first</small>
                    </div>
                    
                    <button type="submit" name="save_event" class="btn-primary"><?php echo $edit_event ? 'Update Event' : 'Add Event'; ?></button>
                    <?php if($edit_event): ?>
                        <a href="manage-calendar.php" class="btn-secondary">Cancel Edit</a>
                    <?php endif; ?>
                </form>
            </div>
            
            <!-- Events List -->
            <div class="data-table-container">
                <h3>All Events</h3>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Event Name</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($events) > 0): ?>
                            <?php foreach($events as $event): ?>
                            <tr>
                                <td><?php echo date('M d, Y', strtotime($event['event_date'])); ?>;</td>
                                <td><?php echo htmlspecialchars($event['event_name']); ?>;</td>
                                <td><?php echo $event['event_type']; ?>;</td>
                                <td><span class="status-badge status-<?php echo $event['status'] ?? 'active'; ?>"><?php echo $event['status'] ?? 'active'; ?></span>;</td>
                                <td class="actions">
                                    <a href="?edit_event=<?php echo $event['id']; ?>" class="btn-edit">Edit</a>
                                    <a href="?toggle_event=<?php echo $event['id']; ?>" class="btn-toggle">Toggle</a>
                                    <a href="?delete_event=<?php echo $event['id']; ?>" class="btn-delete" onclick="return confirm('Delete this event?')">Delete</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align: center;">No events found. Add your first event above!</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>