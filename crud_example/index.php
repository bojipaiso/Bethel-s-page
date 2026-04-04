<?php
require_once 'config/db.php';

// Fetch all users
$sql = "SELECT * FROM users ORDER BY created_at DESC";
$stmt = $pdo->query($sql);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Management</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #4CAF50; color: white; }
        .btn { padding: 5px 10px; text-decoration: none; display: inline-block; margin: 2px; }
        .edit { background-color: #2196F3; color: white; }
        .delete { background-color: #f44336; color: white; }
        .add { background-color: #4CAF50; color: white; padding: 10px; }
    </style>
</head>
<body>
    <h2>User List</h2>
    <a href="create.php" class="btn add">Add New User</a>
    <br><br>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($users as $user): ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo htmlspecialchars($user['name']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td><?php echo htmlspecialchars($user['phone']); ?></td>
                <td><?php echo $user['created_at']; ?></td>
                <td>
                    <a href="edit.php?id=<?php echo $user['id']; ?>" class="btn edit">Edit</a>
                    <a href="delete.php?id=<?php echo $user['id']; ?>" class="btn delete" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>