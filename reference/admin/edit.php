<?php
// admin/edit.php
require_once '../includes/auth.php';
require_once '../includes/db.php';

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE id = :id");
$stmt->execute([':id' => $id]);
$post = $stmt->fetch();

if(!$post) {
    header("Location: dashboard.php");
    exit();
}

$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $excerpt = trim($_POST['excerpt']);
    $category = $_POST['category'];
    $status = $_POST['status'];
    $image_name = $post['image'];
    
    // Handle image upload
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if(in_array($ext, $allowed)) {
            // Delete old image
            if($image_name && file_exists('../uploads/' . $image_name)) {
                unlink('../uploads/' . $image_name);
            }
            
            $image_name = time() . '_' . preg_replace('/[^a-zA-Z0-9]/', '_', $filename);
            $upload_path = '../uploads/' . $image_name;
            move_uploaded_file($_FILES['image']['tmp_name'], $upload_path);
        }
    }
    
    $stmt = $pdo->prepare("UPDATE blog_posts 
                           SET title = :title, content = :content, excerpt = :excerpt, 
                               category = :category, status = :status, image = :image 
                           WHERE id = :id");
    
    if($stmt->execute([
        ':title' => $title,
        ':content' => $content,
        ':excerpt' => $excerpt,
        ':category' => $category,
        ':status' => $status,
        ':image' => $image_name,
        ':id' => $id
    ])) {
        $_SESSION['message'] = "Post updated successfully!";
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Failed to update post";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .edit-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input[type="text"],
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .form-group textarea {
            height: 300px;
        }
        .current-image {
            max-width: 200px;
            margin-top: 10px;
        }
        button {
            background: #4caf50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .error {
            background: #fee;
            color: #c33;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="edit-container">
        <h1>Edit Post</h1>
        
        <?php if($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Title *</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Category *</label>
                <select name="category" required>
                    <option value="">Select Category</option>
                    <option value="Design" <?php echo $post['category'] == 'Design' ? 'selected' : ''; ?>>Design</option>
                    <option value="Development" <?php echo $post['category'] == 'Development' ? 'selected' : ''; ?>>Development</option>
                    <option value="Tutorial" <?php echo $post['category'] == 'Tutorial' ? 'selected' : ''; ?>>Tutorial</option>
                    <option value="Inspiration" <?php echo $post['category'] == 'Inspiration' ? 'selected' : ''; ?>>Inspiration</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Excerpt</label>
                <textarea name="excerpt" rows="3"><?php echo htmlspecialchars($post['excerpt']); ?></textarea>
            </div>
            
            <div class="form-group">
                <label>Content *</label>
                <textarea name="content" required><?php echo htmlspecialchars($post['content']); ?></textarea>
            </div>
            
            <div class="form-group">
                <label>Featured Image</label>
                <?php if($post['image']): ?>
                    <div class="current-image">
                        <img src="../uploads/<?php echo $post['image']; ?>" alt="Current image" style="max-width: 100%;">
                        <p>Current image</p>
                    </div>
                <?php endif; ?>
                <input type="file" name="image" accept="image/*">
                <small>Leave empty to keep current image</small>
            </div>
            
            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="published" <?php echo $post['status'] == 'published' ? 'selected' : ''; ?>>Published</option>
                    <option value="draft" <?php echo $post['status'] == 'draft' ? 'selected' : ''; ?>>Draft</option>
                </select>
            </div>
            
            <button type="submit">Update Post</button>
            <a href="dashboard.php" style="margin-left: 10px;">Cancel</a>
        </form>
    </div>
</body>
</html>