<?php
require_once 'config/db.php';

$id = $_GET['id'];

// Fetch user data
$sql = "SELECT * FROM users WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$user) {
    die("User not found");
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    
    $sql = "UPDATE users SET name = :name, email = :email, phone = :phone WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    
    $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':phone' => $phone,
        ':id' => $id
    ]);
    
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
</head>
<body>
    <h2>Edit User</h2>
    <form method="POST">
        <label>Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required><br><br>
        
        <label>Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br><br>
        
        <label>Phone:</label>
        <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>"><br><br>
        
        <button type="submit">Update</button>
        <a href="index.php">Cancel</a>
    </form>
</body>
</html>