<?php
// admin/manage-academics.php
require_once '../includes/auth.php';
requireAdmin();
require_once '../includes/db.php';

$message = '';
$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'programs';

// ============================================
// ACADEMIC PROGRAMS CRUD
// ============================================
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_program'])) {
    $id = isset($_POST['program_id']) ? intval($_POST['program_id']) : 0;
    $level_id = intval($_POST['level_id']);
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $features = trim($_POST['features']);
    $display_order = intval($_POST['display_order']);
    $icon_class = trim($_POST['icon_class']);
    
    $features_array = array_filter(array_map('trim', explode("\n", $features)));
    $features_json = json_encode(array_values($features_array));
    
    if ($id > 0) {
        $stmt = $pdo->prepare("UPDATE academic_programs SET level_id=?, title=?, description=?, features=?, display_order=?, icon_class=? WHERE id=?");
        $stmt->execute([$level_id, $title, $description, $features_json, $display_order, $icon_class, $id]);
        $message = "✅ Program updated successfully!";
    } else {
        $stmt = $pdo->prepare("INSERT INTO academic_programs (level_id, title, description, features, display_order, icon_class) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$level_id, $title, $description, $features_json, $display_order, $icon_class]);
        $message = "✅ Program added successfully!";
    }
    header("Location: manage-academics.php?tab=" . $active_tab);
    exit();
}

if (isset($_GET['delete_program'])) {
    $id = intval($_GET['delete_program']);
    $stmt = $pdo->prepare("DELETE FROM academic_programs WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: manage-academics.php?tab=programs");
    exit();
}

if (isset($_GET['toggle_program'])) {
    $id = intval($_GET['toggle_program']);
    $stmt = $pdo->prepare("UPDATE academic_programs SET status = IF(status='active', 'inactive', 'active') WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: manage-academics.php?tab=programs");
    exit();
}

// ============================================
// SPECIAL PROGRAMS CRUD
// ============================================
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_special'])) {
    $id = isset($_POST['special_id']) ? intval($_POST['special_id']) : 0;
    $title = trim($_POST['special_title']);
    $description = trim($_POST['special_description']);
    $icon_class = trim($_POST['icon_class']);
    $display_order = intval($_POST['special_display_order']);
    
    if ($id > 0) {
        $stmt = $pdo->prepare("UPDATE special_programs SET title=?, description=?, icon_class=?, display_order=? WHERE id=?");
        $stmt->execute([$title, $description, $icon_class, $display_order, $id]);
        $message = "✅ Special program updated!";
    } else {
        $stmt = $pdo->prepare("INSERT INTO special_programs (title, description, icon_class, display_order) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $description, $icon_class, $display_order]);
        $message = "✅ Special program added!";
    }
    header("Location: manage-academics.php?tab=special");
    exit();
}

if (isset($_GET['delete_special'])) {
    $id = intval($_GET['delete_special']);
    $stmt = $pdo->prepare("DELETE FROM special_programs WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: manage-academics.php?tab=special");
    exit();
}

if (isset($_GET['toggle_special'])) {
    $id = intval($_GET['toggle_special']);
    $stmt = $pdo->prepare("UPDATE special_programs SET status = IF(status='active', 'inactive', 'active') WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: manage-academics.php?tab=special");
    exit();
}

// ============================================
// FETCH DATA
// ============================================
$levels = $pdo->query("SELECT * FROM academic_levels ORDER BY display_order")->fetchAll();
$programs = $pdo->query("SELECT p.*, l.level_name FROM academic_programs p LEFT JOIN academic_levels l ON p.level_id = l.id ORDER BY l.display_order, p.display_order")->fetchAll();
$special_programs = $pdo->query("SELECT * FROM special_programs ORDER BY display_order")->fetchAll();

$edit_program = null;
if (isset($_GET['edit_program'])) {
    $stmt = $pdo->prepare("SELECT * FROM academic_programs WHERE id = ?");
    $stmt->execute([$_GET['edit_program']]);
    $edit_program = $stmt->fetch();
}
$edit_special = null;
if (isset($_GET['edit_special'])) {
    $stmt = $pdo->prepare("SELECT * FROM special_programs WHERE id = ?");
    $stmt->execute([$_GET['edit_special']]);
    $edit_special = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Academics - Bethel School</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin-style.css">
    <style>
        .tabs { display: flex; gap: 10px; margin-bottom: 20px; border-bottom: 2px solid var(--gray-border); }
        .tab { padding: 8px 20px; background: white; text-decoration: none; color: #333; border-radius: 8px 8px 0 0; transition: 0.2s; }
        .tab.active { background: var(--primary-color); color: white; }
        .icon-preview { font-size: 1.5rem; width: 40px; text-align: center; }
        .icon-preview-lg { width: 50px; height: 50px; background: var(--gray-light); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; border: 1px solid var(--gray-border); }
        .icon-suggestions { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 8px; }
        .icon-suggestion { width: 36px; height: 36px; background: var(--gray-light); border-radius: 6px; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 1px solid var(--gray-border); transition: 0.2s; }
        .icon-suggestion:hover { background: var(--primary-color); color: white; }
        .modal.active { display: flex; }
    </style>
</head>
<body>
<div class="admin-wrapper">
    <nav class="admin-nav"><div class="admin-nav-container"><div class="admin-logo">Bethel <span>CMS</span></div><div class="admin-user"><a href="dashboard.php">Dashboard</a> | <a href="logout.php">Logout</a></div></div></nav>
    <div class="admin-container">
        <div class="page-header"><h1><i class="fas fa-graduation-cap"></i> Manage Academics</h1></div>
        <?php if($message): ?><div class="alert alert-success"><?php echo $message; ?></div><?php endif; ?>
        <div class="tabs">
            <a href="manage-academics.php?tab=programs" class="tab <?php echo $active_tab == 'programs' ? 'active' : ''; ?>">📚 Academic Programs</a>
            <a href="manage-academics.php?tab=special" class="tab <?php echo $active_tab == 'special' ? 'active' : ''; ?>">⭐ Special Programs</a>
        </div>

        <?php if ($active_tab == 'programs'): ?>
            <button class="btn-primary" onclick="openProgramModal()"><i class="fas fa-plus"></i> Add New Program</button>
            <div class="data-table-container">
                <table class="data-table">
                    <thead><tr><th>Icon</th><th>Level</th><th>Program</th><th>Description</th><th>Status</th><th>Order</th><th>Actions</th></tr></thead>
                    <tbody>
                        <?php foreach($programs as $p): ?>
                        <tr>
                            <td class="icon-preview"><i class="<?php echo htmlspecialchars($p['icon_class'] ?: 'fas fa-graduation-cap'); ?>"></i></td>
                            <td><?php echo htmlspecialchars($p['level_name']); ?></td>
                            <td><strong><?php echo htmlspecialchars($p['title']); ?></strong></td>
                            <td><?php echo htmlspecialchars(substr($p['description'],0,60)); ?>...</td>
                            <td><span class="status-badge status-<?php echo $p['status']; ?>"><?php echo $p['status']; ?></span></td>
                            <td><?php echo $p['display_order']; ?></td>
                            <td class="actions">
                                <button class="btn-edit" onclick='editProgram(<?php echo json_encode($p); ?>)'><i class="fas fa-edit"></i> Edit</button>
                                <a href="?toggle_program=<?php echo $p['id']; ?>&tab=programs" class="btn-toggle"><i class="fas fa-sync-alt"></i> Toggle</a>
                                <a href="?delete_program=<?php echo $p['id']; ?>&tab=programs" class="btn-delete" onclick="return confirm('Delete?')"><i class="fas fa-trash"></i> Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Modal for Academic Program -->
            <div id="programModal" class="modal">
                <div class="modal-content">
                    <form method="POST">
                        <div class="modal-header"><h2 id="programModalTitle">Add Program</h2><button type="button" class="modal-close" onclick="closeProgramModal()">&times;</button></div>
                        <div class="modal-body">
                            <input type="hidden" name="program_id" id="program_id">
                            <div class="form-group"><label>Level</label><select name="level_id" id="level_id" required><?php foreach($levels as $level): ?><option value="<?php echo $level['id']; ?>"><?php echo htmlspecialchars($level['level_name']); ?></option><?php endforeach; ?></select></div>
                            <div class="form-group"><label>Title</label><input type="text" name="title" id="title" required></div>
                            <div class="form-group"><label>Description</label><textarea name="description" id="description" rows="4" required></textarea></div>
                            <div class="form-group"><label>Features (one per line)</label><textarea name="features" id="features" rows="5"></textarea></div>
                            <div class="form-row">
                                <div class="form-group"><label>Display Order</label><input type="number" name="display_order" id="display_order" value="0"></div>
                                <div class="form-group"><label>Icon (Font Awesome)</label>
                                    <div class="icon-picker" style="display:flex; gap:10px; align-items:center;">
                                        <div class="icon-preview-lg" id="iconPreview"><i class="fas fa-graduation-cap"></i></div>
                                        <input type="text" name="icon_class" id="icon_class" placeholder="fas fa-graduation-cap" style="flex:1">
                                    </div>
                                    <div class="icon-suggestions">
                                        <div class="icon-suggestion" onclick="setIcon('fas fa-graduation-cap')"><i class="fas fa-graduation-cap"></i></div>
                                        <div class="icon-suggestion" onclick="setIcon('fas fa-flask')"><i class="fas fa-flask"></i></div>
                                        <div class="icon-suggestion" onclick="setIcon('fas fa-chart-line')"><i class="fas fa-chart-line"></i></div>
                                        <div class="icon-suggestion" onclick="setIcon('fas fa-laptop-code')"><i class="fas fa-laptop-code"></i></div>
                                        <div class="icon-suggestion" onclick="setIcon('fas fa-palette')"><i class="fas fa-palette"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer"><button type="button" class="btn-secondary" onclick="closeProgramModal()">Cancel</button><button type="submit" name="save_program" class="btn-primary">Save Program</button></div>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($active_tab == 'special'): ?>
            <button class="btn-primary" onclick="openSpecialModal()"><i class="fas fa-plus"></i> Add Special Program</button>
            <div class="data-table-container">
                <table class="data-table">
                    <thead><tr><th>Icon</th><th>Title</th><th>Description</th><th>Status</th><th>Order</th><th>Actions</th></tr></thead>
                    <tbody>
                        <?php foreach($special_programs as $sp): ?>
                        <tr>
                            <td class="icon-preview"><i class="<?php echo htmlspecialchars($sp['icon_class'] ?: 'fas fa-star'); ?>"></i></td>
                            <td><strong><?php echo htmlspecialchars($sp['title']); ?></strong></td>
                            <td><?php echo htmlspecialchars(substr($sp['description'],0,60)); ?>...</td>
                            <td><span class="status-badge status-<?php echo $sp['status']; ?>"><?php echo $sp['status']; ?></span></td>
                            <td><?php echo $sp['display_order']; ?></td>
                            <td class="actions">
                                <button class="btn-edit" onclick='editSpecial(<?php echo json_encode($sp); ?>)'><i class="fas fa-edit"></i> Edit</button>
                                <a href="?toggle_special=<?php echo $sp['id']; ?>&tab=special" class="btn-toggle"><i class="fas fa-sync-alt"></i> Toggle</a>
                                <a href="?delete_special=<?php echo $sp['id']; ?>&tab=special" class="btn-delete" onclick="return confirm('Delete?')"><i class="fas fa-trash"></i> Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Modal for Special Program -->
            <div id="specialModal" class="modal">
                <div class="modal-content">
                    <form method="POST">
                        <div class="modal-header"><h2 id="specialModalTitle">Add Special Program</h2><button type="button" class="modal-close" onclick="closeSpecialModal()">&times;</button></div>
                        <div class="modal-body">
                            <input type="hidden" name="special_id" id="special_id">
                            <div class="form-group"><label>Title</label><input type="text" name="special_title" id="special_title" required></div>
                            <div class="form-group"><label>Description</label><textarea name="special_description" id="special_description" rows="4" required></textarea></div>
                            <div class="form-row">
                                <div class="form-group"><label>Display Order</label><input type="number" name="special_display_order" id="special_display_order" value="0"></div>
                                <div class="form-group"><label>Icon</label>
                                    <div class="icon-picker" style="display:flex; gap:10px; align-items:center;">
                                        <div class="icon-preview-lg" id="specialIconPreview"><i class="fas fa-star"></i></div>
                                        <input type="text" name="icon_class" id="special_icon_class" placeholder="fas fa-star" style="flex:1">
                                    </div>
                                    <div class="icon-suggestions">
                                        <div class="icon-suggestion" onclick="setSpecialIcon('fas fa-globe')"><i class="fas fa-globe"></i></div>
                                        <div class="icon-suggestion" onclick="setSpecialIcon('fas fa-robot')"><i class="fas fa-robot"></i></div>
                                        <div class="icon-suggestion" onclick="setSpecialIcon('fas fa-music')"><i class="fas fa-music"></i></div>
                                        <div class="icon-suggestion" onclick="setSpecialIcon('fas fa-heart')"><i class="fas fa-heart"></i></div>
                                        <div class="icon-suggestion" onclick="setSpecialIcon('fas fa-dove')"><i class="fas fa-dove"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer"><button type="button" class="btn-secondary" onclick="closeSpecialModal()">Cancel</button><button type="submit" name="save_special" class="btn-primary">Save Program</button></div>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<script>
    function openProgramModal() { document.getElementById('programModal').classList.add('active'); document.getElementById('program_id').value = ''; document.getElementById('title').value = ''; document.getElementById('description').value = ''; document.getElementById('features').value = ''; document.getElementById('display_order').value = '0'; document.getElementById('icon_class').value = 'fas fa-graduation-cap'; document.getElementById('iconPreview').innerHTML = '<i class="fas fa-graduation-cap"></i>'; }
    function closeProgramModal() { document.getElementById('programModal').classList.remove('active'); }
    function editProgram(p) { document.getElementById('programModal').classList.add('active'); document.getElementById('programModalTitle').innerText = 'Edit Program'; document.getElementById('program_id').value = p.id; document.getElementById('level_id').value = p.level_id; document.getElementById('title').value = p.title; document.getElementById('description').value = p.description; if(p.features) { let f = JSON.parse(p.features); document.getElementById('features').value = f.join('\n'); } else { document.getElementById('features').value = ''; } document.getElementById('display_order').value = p.display_order; let icon = p.icon_class || 'fas fa-graduation-cap'; document.getElementById('icon_class').value = icon; document.getElementById('iconPreview').innerHTML = '<i class="'+icon+'"></i>'; }
    function setIcon(cls) { document.getElementById('icon_class').value = cls; document.getElementById('iconPreview').innerHTML = '<i class="'+cls+'"></i>'; }

    function openSpecialModal() { document.getElementById('specialModal').classList.add('active'); document.getElementById('special_id').value = ''; document.getElementById('special_title').value = ''; document.getElementById('special_description').value = ''; document.getElementById('special_display_order').value = '0'; document.getElementById('special_icon_class').value = 'fas fa-star'; document.getElementById('specialIconPreview').innerHTML = '<i class="fas fa-star"></i>'; }
    function closeSpecialModal() { document.getElementById('specialModal').classList.remove('active'); }
    function editSpecial(sp) { document.getElementById('specialModal').classList.add('active'); document.getElementById('specialModalTitle').innerText = 'Edit Special Program'; document.getElementById('special_id').value = sp.id; document.getElementById('special_title').value = sp.title; document.getElementById('special_description').value = sp.description; document.getElementById('special_display_order').value = sp.display_order; let icon = sp.icon_class || 'fas fa-star'; document.getElementById('special_icon_class').value = icon; document.getElementById('specialIconPreview').innerHTML = '<i class="'+icon+'"></i>'; }
    function setSpecialIcon(cls) { document.getElementById('special_icon_class').value = cls; document.getElementById('specialIconPreview').innerHTML = '<i class="'+cls+'"></i>'; }
    window.onclick = function(e) { if(e.target.classList.contains('modal')) { closeProgramModal(); closeSpecialModal(); } }
</script>
</body>
</html>