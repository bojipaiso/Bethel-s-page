<?php
require_once 'config.php';

$id = $_GET['id'] ?? null;

if ($id) {
    try {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        header("Location: index.php?msg=User deleted successfully");
        exit();
    } catch(PDOException $e) {
        header("Location: index.php?msg=Error deleting user");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>