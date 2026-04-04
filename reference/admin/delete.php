<?php
// admin/delete.php
require_once '../includes/auth.php';
require_once '../includes/db.php';

$id = $_GET['id'] ?? 0;

// Get image to delete
$stmt = $pdo->prepare("SELECT image FROM blog_posts WHERE id = :id");
$stmt->execute([':id' => $id]);
$post = $stmt->fetch();

if($post && $post['image'] && file_exists('../uploads/' . $post['image'])) {
    unlink('../uploads/' . $post['image']);
}

$stmt = $pdo->prepare("DELETE FROM blog_posts WHERE id = :id");
$stmt->execute([':id' => $id]);

$_SESSION['message'] = "Post deleted successfully!";
header("Location: dashboard.php");
exit();
?>