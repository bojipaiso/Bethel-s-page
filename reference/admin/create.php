<?php
// admin/create.php
require_once '../includes/auth.php';
require_once '../includes/db.php';

$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $excerpt = trim($_POST['excerpt']);
    $category = $_POST['category'];
    $status = $_POST['status'];
    
    // Handle image upload
    $image_name = '';
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if(in_array($ext, $allowed)) {
            $image_name = time() . '_' . preg_replace('/[^a-zA-Z0-9]/', '_', $filename);
            $upload_path = '../uploads/' . $image_name;
            
            if(!is_dir('../uploads')) {
                mkdir('../uploads', 0777, true);
            }
            
            if(move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                // Image uploaded successfully
            } else {
                $error = "Failed to upload image";
            }
        } else {
            $error = "Invalid image format. Allowed: jpg, jpeg, png, gif";
        }
    }
    
    if(empty($error)) {
        $stmt = $pdo->prepare("INSERT INTO blog_posts (title, content, excerpt, category, status, image) 
                               VALUES (:title, :content, :excerpt, :category, :status, :image)");
        
        if($stmt->execute([
            ':title' => $title,
            ':content' => $content,
            ':excerpt' => $excerpt,
            ':category' => $category,
            ':status' => $status,
            ':image' => $image_name
        ])) {
            $_SESSION['message'] = "Post created successfully!";
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Failed to create post";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Post</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .create-container {
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
        button {
            background: #667eea;
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
    <div class="create-container">
        <h1>Create New Blog Post</h1>
        
        <?php if($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Title *</label>
                <input type="text" name="title" required>
            </div>
            
            <div class="form-group">
                <label>Category *</label>
                <select name="category" required>
                    <option value="">Select Category</option>
                    <option value="Design">Design</option>
                    <option value="Development">Development</option>
                    <option value="Tutorial">Tutorial</option>
                    <option value="Inspiration">Inspiration</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Excerpt (Short description)</label>
                <textarea name="excerpt" rows="3" placeholder="Brief summary of your post..."></textarea>
            </div>
            
            <div class="form-group">
                <label>Content *</label>
                <textarea name="content" required placeholder="Write your blog post content here..."></textarea>
            </div>
            
            <div class="form-group">
                <label>Featured Image</label>
                <input type="file" name="image" accept="image/*">
            </div>
            
            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="published">Published</option>
                    <option value="draft">Draft</option>
                </select>
            </div>
            
            <button type="submit">Publish Post</button>
            <a href="dashboard.php" style="margin-left: 10px;">Cancel</a>
        </form>
    </div>
</body>
</html>