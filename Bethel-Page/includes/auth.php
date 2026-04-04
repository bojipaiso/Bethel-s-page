<?php
// includes/auth.php
session_start();

function isAdminLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function requireAdmin() {
    if (!isAdminLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

function loginAdmin($username) {
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_username'] = $username;
}

function logoutAdmin() {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>