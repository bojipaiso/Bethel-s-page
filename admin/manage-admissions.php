<?php
// admin/manage-admissions.php – buttons vertically stacked, no underline
require_once '../includes/auth.php';
requireAdmin();
require_once '../includes/db.php';

$message = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['update_welcome'])) {
        $stmt = $pdo->prepare("UPDATE admissions_content SET title = ?, content = ? WHERE section = 'welcome'");
        $stmt->execute([trim($_POST['title']), trim($_POST['content'])]);
        $message = "Welcome section updated!";
        header("Location: manage-admissions.php");
        exit();
    }
    if(isset($_POST['update_requirements'])) {
        $stmt = $pdo->prepare("UPDATE admissions_content SET title = ?, content = ? WHERE section = 'requirements'");
        $stmt->execute([trim($_POST['title']), trim($_POST['content'])]);
        $message = "Requirements updated!";
        header("Location: manage-admissions.php");
        exit();
    }
    if(isset($_POST['update_enrollment'])) {
        $stmt = $pdo->prepare("UPDATE admissions_content SET title = ?, content = ? WHERE section = 'enrollment_period'");
        $stmt->execute([trim($_POST['title']), trim($_POST['content'])]);
        $message = "Enrollment period updated!";
        header("Location: manage-admissions.php");
        exit();
    }
    if(isset($_POST['update_classes'])) {
        $stmt = $pdo->prepare("UPDATE admissions_content SET title = ?, content = ? WHERE section = 'classes_start'");
        $stmt->execute([trim($_POST['title']), trim($_POST['content'])]);
        $message = "Classes start updated!";
        header("Location: manage-admissions.php");
        exit();
    }
    if(isset($_POST['save_step'])) {
        $id = isset($_POST['step_id']) ? intval($_POST['step_id']) : 0;
        $step_number = intval($_POST['step_number']);
        $title = trim($_POST['step_title']);
        $description = trim($_POST['step_description']);
        $display_order = intval($_POST['step_display_order']);
        if($id > 0) {
            $stmt = $pdo->prepare("UPDATE admission_steps SET step_number=?, title=?, description=?, display_order=? WHERE id=?");
            $stmt->execute([$step_number, $title, $description, $display_order, $id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO admission_steps (step_number, title, description, display_order) VALUES (?, ?, ?, ?)");
            $stmt->execute([$step_number, $title, $description, $display_order]);
        }
        $message = "Step saved!";
        header("Location: manage-admissions.php");
        exit();
    }
}
if(isset($_GET['delete_step'])) {
    $stmt = $pdo->prepare("DELETE FROM admission_steps WHERE id = ?");
    $stmt->execute([intval($_GET['delete_step'])]);
    $message = "Step deleted!";
    header("Location: manage-admissions.php");
    exit();
}

$welcome = $pdo->query("SELECT * FROM admissions_content WHERE section = 'welcome'")->fetch();
$requirements = $pdo->query("SELECT * FROM admissions_content WHERE section = 'requirements'")->fetch();
$enrollment = $pdo->query("SELECT * FROM admissions_content WHERE section = 'enrollment_period'")->fetch();
$classes = $pdo->query("SELECT * FROM admissions_content WHERE section = 'classes_start'")->fetch();
$steps = $pdo->query("SELECT * FROM admission_steps ORDER BY display_order")->fetchAll();
$next_order = $pdo->query("SELECT COALESCE(MAX(display_order),0)+1 as next FROM admission_steps")->fetch();
$next_display_order = intval($next_order['next']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Admissions - Bethel School</title>
    <link rel="stylesheet" href="../css/admin-style.css">
    <style>
        .content-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(350px,1fr)); gap:25px; margin-bottom:30px; }
        .main-card { background:white; border-radius:16px; border:1px solid var(--gray-border); overflow:hidden; }
        .card-header { background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color:white; padding:15px 20px; }
        .card-body { padding:20px; }
        .content-preview { background:#f8f9fa; padding:15px; border-radius:8px; margin-bottom:15px; max-height:200px; overflow-y:auto; }
        .edit-btn { background:var(--primary-color); color:white; border:none; padding:6px 12px; border-radius:6px; cursor:pointer; }
        .info-list { background:white; border-radius:16px; border:1px solid var(--gray-border); margin-bottom:30px; }
        .info-list-header { padding:15px 20px; background:#f8f9fa; border-bottom:1px solid var(--gray-border); }
        .info-item { display:flex; justify-content:space-between; align-items:center; padding:12px 20px; border-bottom:1px solid #eee; }
        .small-edit-btn { background:none; border:none; color:var(--secondary-color); cursor:pointer; }
        .steps-section { background:white; border-radius:16px; border:1px solid var(--gray-border); }
        .steps-header { padding:15px 20px; background:#f8f9fa; border-bottom:1px solid var(--gray-border); display:flex; justify-content:space-between; }
        .steps-table { width:100%; border-collapse:collapse; }
        .steps-table th, .steps-table td { padding:12px; border-bottom:1px solid #eee; text-align:left; }
        .step-number-badge { display:inline-block; width:28px; height:28px; background:var(--primary-color); color:var(--accent-color); border-radius:50%; text-align:center; line-height:28px; }
        /* Stack action buttons vertically */
        .steps-table td .actions {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .btn-step-edit, .btn-step-delete {
            display: inline-block;
            width: 100%;
            text-align: center;
            padding: 5px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }
        .btn-step-edit { background: #28a745; color: white; }
        .btn-step-delete { background: #dc3545; color: white; }
        .btn-step-delete:hover { text-decoration: none; }
    </style>
</head>
<body>
<div class="admin-wrapper">
    <nav class="admin-nav"><div class="admin-nav-container"><div class="admin-logo">Bethel CMS</div><div class="admin-user"><a href="dashboard.php">Dashboard</a> | <a href="logout.php">Logout</a></div></div></nav>
    <div class="admin-container">
        <div class="page-header"><h1>Manage Admissions</h1></div>
        <?php if($message): ?><div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>
        <div class="content-grid">
            <div class="main-card"><div class="card-header"><h3>Welcome Section</h3></div><div class="card-body"><div class="content-preview"><strong><?php echo htmlspecialchars($welcome['title']); ?></strong><br><?php echo nl2br(htmlspecialchars($welcome['content'])); ?></div><button class="edit-btn" onclick="openContentModal('welcome', '<?php echo htmlspecialchars($welcome['title']); ?>', <?php echo htmlspecialchars(json_encode($welcome['content'])); ?>)">Edit Welcome</button></div></div>
            <div class="main-card"><div class="card-header"><h3>Requirements</h3></div><div class="card-body"><div class="content-preview"><strong><?php echo htmlspecialchars($requirements['title']); ?></strong><br><ul><?php foreach(explode("\n", $requirements['content']) as $line) if(trim($line)) echo "<li>".htmlspecialchars(trim($line))."</li>"; ?></ul></div><button class="edit-btn" onclick="openContentModal('requirements', '<?php echo htmlspecialchars($requirements['title']); ?>', <?php echo htmlspecialchars(json_encode($requirements['content'])); ?>)">Edit Requirements</button></div></div>
        </div>
        <div class="info-list"><div class="info-list-header"><h3>Key Dates</h3></div><div class="info-item"><span>Enrollment Period</span><span><?php echo htmlspecialchars($enrollment['content']); ?></span><button class="small-edit-btn" onclick="openContentModal('enrollment', '<?php echo htmlspecialchars($enrollment['title']); ?>', <?php echo htmlspecialchars(json_encode($enrollment['content'])); ?>)">Edit</button></div><div class="info-item"><span>Classes Start</span><span><?php echo htmlspecialchars($classes['content']); ?></span><button class="small-edit-btn" onclick="openContentModal('classes', '<?php echo htmlspecialchars($classes['title']); ?>', <?php echo htmlspecialchars(json_encode($classes['content'])); ?>)">Edit</button></div></div>
        <div class="steps-section"><div class="steps-header"><h3>Admission Steps</h3><button class="btn-primary" onclick="openStepModal()">+ Add Step</button></div>
            <table class="steps-table"><thead><tr><th>Step</th><th>Title</th><th>Description</th><th>Order</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach($steps as $step): ?>
                <tr>
                    <td><span class="step-number-badge"><?php echo $step['step_number']; ?></span></td>
                    <td><strong><?php echo htmlspecialchars($step['title']); ?></strong></td>
                    <td><?php echo htmlspecialchars(substr($step['description'],0,80)); ?>...</td>
                    <td><?php echo $step['display_order']; ?></td>
                    <td class="actions">
                        <button class="btn-step-edit" onclick="editStep(<?php echo htmlspecialchars(json_encode($step)); ?>)">Edit</button>
                        <a href="?delete_step=<?php echo $step['id']; ?>" class="btn-step-delete" onclick="return confirm('Delete this step?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Content Modal -->
<div id="contentModal" class="modal">
    <div class="modal-content">
        <div class="modal-header"><h2 id="contentModalTitle">Edit Content</h2><button type="button" class="modal-close" onclick="closeContentModal()">&times;</button></div>
        <div class="modal-body"><input type="hidden" id="contentSection"><div class="form-group"><label>Title</label><input type="text" id="contentTitle" required></div><div class="form-group"><label>Content</label><textarea id="contentText" rows="6" required></textarea></div></div>
        <div class="modal-footer"><button class="btn-secondary" onclick="closeContentModal()">Cancel</button><button class="btn-primary" id="contentSubmitBtn">Save Changes</button></div>
    </div>
</div>
<!-- Step Modal -->
<div id="stepModal" class="modal">
    <div class="modal-content">
        <div class="modal-header"><h2 id="stepModalTitle">Add Step</h2><button type="button" class="modal-close" onclick="closeStepModal()">&times;</button></div>
        <div class="modal-body"><input type="hidden" id="step_id"><div class="form-row"><div class="form-group"><label>Step Number</label><input type="number" id="step_number" required></div><div class="form-group"><label>Display Order</label><input type="number" id="step_display_order" value="<?php echo $next_display_order; ?>"></div></div><div class="form-group"><label>Title</label><input type="text" id="step_title" required></div><div class="form-group"><label>Description</label><textarea id="step_description" rows="4" required></textarea></div></div>
        <div class="modal-footer"><button class="btn-secondary" onclick="closeStepModal()">Cancel</button><button class="btn-primary" id="stepSubmitBtn">Save Step</button></div>
    </div>
</div>
<script>
    const contentModal = document.getElementById('contentModal');
    const stepModal = document.getElementById('stepModal');
    let currentContentSection = '';
    function openContentModal(section, title, content) {
        currentContentSection = section;
        document.getElementById('contentModalTitle').innerText = 'Edit ' + section.charAt(0).toUpperCase() + section.slice(1);
        document.getElementById('contentTitle').value = title;
        document.getElementById('contentText').value = content;
        contentModal.classList.add('active');
    }
    function closeContentModal() { contentModal.classList.remove('active'); }
    document.getElementById('contentSubmitBtn').onclick = function() {
        let form = document.createElement('form');
        form.method = 'POST';
        let action = '';
        if(currentContentSection === 'welcome') action = 'update_welcome';
        else if(currentContentSection === 'requirements') action = 'update_requirements';
        else if(currentContentSection === 'enrollment') action = 'update_enrollment';
        else if(currentContentSection === 'classes') action = 'update_classes';
        form.innerHTML = '<input type="hidden" name="'+action+'" value="1"><input type="hidden" name="title" value="'+escapeHtml(document.getElementById('contentTitle').value)+'"><input type="hidden" name="content" value="'+escapeHtml(document.getElementById('contentText').value)+'">';
        document.body.appendChild(form);
        form.submit();
    };
    function openStepModal() {
        document.getElementById('stepModalTitle').innerText = 'Add Step';
        document.getElementById('step_id').value = '';
        document.getElementById('step_number').value = '';
        document.getElementById('step_title').value = '';
        document.getElementById('step_description').value = '';
        document.getElementById('step_display_order').value = '<?php echo $next_display_order; ?>';
        stepModal.classList.add('active');
    }
    function editStep(step) {
        document.getElementById('stepModalTitle').innerText = 'Edit Step';
        document.getElementById('step_id').value = step.id;
        document.getElementById('step_number').value = step.step_number;
        document.getElementById('step_title').value = step.title;
        document.getElementById('step_description').value = step.description;
        document.getElementById('step_display_order').value = step.display_order;
        stepModal.classList.add('active');
    }
    function closeStepModal() { stepModal.classList.remove('active'); }
    document.getElementById('stepSubmitBtn').onclick = function() {
        let form = document.createElement('form');
        form.method = 'POST';
        let id = document.getElementById('step_id').value;
        form.innerHTML = '<input type="hidden" name="save_step" value="1"><input type="hidden" name="step_id" value="'+id+'"><input type="hidden" name="step_number" value="'+document.getElementById('step_number').value+'"><input type="hidden" name="step_title" value="'+escapeHtml(document.getElementById('step_title').value)+'"><input type="hidden" name="step_description" value="'+escapeHtml(document.getElementById('step_description').value)+'"><input type="hidden" name="step_display_order" value="'+document.getElementById('step_display_order').value+'">';
        document.body.appendChild(form);
        form.submit();
    };
    function escapeHtml(str) { return str.replace(/[&<>]/g, function(m){ if(m==='&') return '&amp;'; if(m==='<') return '&lt;'; if(m==='>') return '&gt;'; return m;}); }
    window.onclick = function(e) { if(e.target === contentModal) closeContentModal(); if(e.target === stepModal) closeStepModal(); }
</script>
</body>
</html>