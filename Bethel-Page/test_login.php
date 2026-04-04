<?php
// test_login.php - Run this to diagnose issues
require_once 'includes/db.php';

echo "<h2>Login Diagnostic Test</h2>";

// Test 1: Database connection
echo "<h3>Test 1: Database Connection</h3>";
try {
    $pdo->query("SELECT 1");
    echo "✅ Database connected successfully<br>";
} catch(Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "<br>";
}

// Test 2: Check if table exists
echo "<h3>Test 2: Admin Users Table</h3>";
$result = $pdo->query("SHOW TABLES LIKE 'admin_users'");
if($result->rowCount() > 0) {
    echo "✅ admin_users table exists<br>";
} else {
    echo "❌ admin_users table does NOT exist! Run setup.sql<br>";
}

// Test 3: Check admin user
echo "<h3>Test 3: Admin User</h3>";
$stmt = $pdo->query("SELECT * FROM admin_users");
$users = $stmt->fetchAll();

if(count($users) > 0) {
    echo "✅ Found " . count($users) . " admin user(s)<br>";
    foreach($users as $user) {
        echo "- Username: " . $user['username'] . "<br>";
        echo "  Password hash: " . substr($user['password'], 0, 20) . "...<br>";
    }
} else {
    echo "❌ No admin users found! Run this SQL:<br>";
    echo "<pre>INSERT INTO admin_users (username, password, email) VALUES ('admin', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@bethel.edu.ph');</pre>";
}

// Test 4: Password verification
echo "<h3>Test 4: Password Test (if you have admin user)</h3>";
if(count($users) > 0) {
    $test_password = 'admin123';
    $stored_hash = $users[0]['password'];
    
    if(password_verify($test_password, $stored_hash)) {
        echo "✅ Password 'admin123' is correct for user: " . $users[0]['username'] . "<br>";
    } else {
        echo "❌ Password 'admin123' is NOT correct for user: " . $users[0]['username'] . "<br>";
        echo "You may need to reset the password.<br>";
    }
}
?>