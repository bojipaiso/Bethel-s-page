<?php
require_once 'config/db.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    
    $sql = "INSERT INTO users (name, email, phone) VALUES (:name, :email, :phone)";
    $stmt = $pdo->prepare($sql);
    
    $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':phone' => $phone
    ]);
    
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add User</title>
</head>
<body>
    <h2>Add New User</h2>
    <form method="POST">
        <label>Name:</label>
        <input type="text" name="name" required><br><br>
        
        <label>Email:</label>
        <input type="email" name="email" required><br><br>
        
        <label>Phone:</label>
        <input type="text" name="phone"><br><br>
        
        <button type="submit">Save</button>
        <a href="index.php">Cancel</a>
    </form>
</body>
</html>