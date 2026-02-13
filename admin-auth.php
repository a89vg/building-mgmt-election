<?php
/**
 * Admin Authentication Guard
 * Include this file at the top of any admin page to ensure the user is logged in
 */

session_start();

// Load admin configuration (not used here, but available for admin pages)
require_once __DIR__ . '/config/admin.php';

// Check if admin is logged in, redirect to login if not
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin-login.php');
    exit;
}
?>
