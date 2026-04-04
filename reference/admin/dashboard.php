<?php
// admin/dashboard.php
require_once '../includes/auth.php';
require_once '../includes/db.php';

$page_title = 'Admin Dashboard';

// Handle bulk actions
if(isset($_POST['bulk_action'])) {
    $ids = $_POST['post_ids'] ?? [];
    if(!empty($ids)) {
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        if($_POST['bulk_action'] == 'delete') {
            $stmt = $pdo->prepare("DELETE FROM blog_posts WHERE id IN ($placeholders)");
            $stmt->execute($ids);
            $_SESSION['message'] = "Posts deleted successfully!";
        } elseif($_POST['bulk_action'] == 'publish') {
            $stmt = $pdo->prepare("UPDATE blog_posts SET status = 'published' WHERE id IN ($placeholders)");
            $stmt->execute($ids);
            $_SESSION['message'] = "Posts published!";
        }
    }
}

// Fetch all posts
$stmt = $pdo->query("SELECT * FROM blog_posts ORDER BY created_at DESC");
$posts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .admin-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #eee;
        }
        .btn-primary {
            background: #667eea;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
        }
        .btn-danger {
            background: #f44336;
            color: white;
            padding: 5px 10px;
            border-radius: 3px;
            text-decoration: none;
            font-size: 12px;
        }
        .btn-edit {
            background: #4caf50;
            color: white;
            padding: 5px 10px;
            border-radius: 3px;
            text-decoration: none;
            font-size: 12px;
        }
        .posts-table {
            width: 100%;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .posts-table th {
            background: #f5f5f5;
            padding: 15px;
            text-align: left;
        }
        .posts-table td {
            padding: 15px;
            border-top: 1px solid #eee;
        }
        .status {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
        }
        .status.published {
            background: #d4edda;
            color: #155724;
        }
        .status.draft {
            background: #fff3cd;
            color: #856404;
        }
        .alert {
            padding: 15px;
            background: #d4edda;
            color: #155724;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .search-box {
            margin-bottom: 20px;
        }
        .search-box input {
            padding: 10px;
            width: 300px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <h1>Blog Management</h1>
            <div>
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?> | </span>
                <a href="logout.php">Logout</a>
            </div>
        </div>

        <?php if(isset($_SESSION['message'])): ?>
            <div class="alert"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
        <?php endif; ?>

        <div class="admin-actions">
            <a href="create.php" class="btn-primary">+ Create New Post</a>
        </div>

        <br>

        <form method="POST" id="bulkForm">
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Search posts..." onkeyup="searchTable()">
            </div>

            <div class="bulk-actions" style="margin-bottom: 10px;">
                <select name="bulk_action" id="bulkAction">
                    <option value="">Bulk Actions</option>
                    <option value="publish">Publish</option>
                    <option value="delete">Delete</option>
                </select>
                <button type="button" onclick="applyBulkAction()">Apply</button>
            </div>

            <table class="posts-table" id="postsTable">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Views</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($posts as $post): ?>
                    <tr>
                        <td><input type="checkbox" name="post_ids[]" value="<?php echo $post['id']; ?>" class="post-checkbox"></td>
                        <td><?php echo $post['id']; ?></td>
                        <td><?php echo htmlspecialchars($post['title']); ?></td>
                        <td><?php echo htmlspecialchars($post['category']); ?></td>
                        <td>
                            <span class="status <?php echo $post['status']; ?>">
                                <?php echo $post['status']; ?>
                            </span>
                        </td>
                        <td><?php echo $post['views']; ?></td>
                        <td><?php echo date('M d, Y', strtotime($post['created_at'])); ?></td>
                        <td>
                            <a href="edit.php?id=<?php echo $post['id']; ?>" class="btn-edit">Edit</a>
                            <a href="delete.php?id=<?php echo $post['id']; ?>" class="btn-danger" onclick="return confirm('Delete this post?')">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </form>
    </div>

    <script>
        // Select all checkbox
        document.getElementById('selectAll').addEventListener('change', function() {
            let checkboxes = document.querySelectorAll('.post-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });

        // Search functionality
        function searchTable() {
            let input = document.getElementById('searchInput');
            let filter = input.value.toLowerCase();
            let rows = document.querySelectorAll('#postsTable tbody tr');
            
            rows.forEach(row => {
                let title = row.cells[2].textContent.toLowerCase();
                if(title.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Bulk action
        function applyBulkAction() {
            let action = document.getElementById('bulkAction').value;
            if(!action) {
                alert('Please select an action');
                return;
            }
            
            let checked = document.querySelectorAll('.post-checkbox:checked');
            if(checked.length === 0) {
                alert('Please select at least one post');
                return;
            }
            
            if(confirm(`Are you sure you want to ${action} selected posts?`)) {
                document.getElementById('bulkForm').submit();
            }
        }
    </script>
</body>
</html>