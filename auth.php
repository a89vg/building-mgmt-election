<?php
// auth.php - Authentication guard for protected pages
// Include this at the top of any page that requires authentication
session_start();

require_once __DIR__ . '/auth-functions.php';

// Require authentication - redirect to login if not authenticated
function requireAuth() {
    if (!isAuthenticated()) {
        $currentPage = $_SERVER['REQUEST_URI'];
        header('Location: login.php?redirect=' . urlencode($currentPage));
        exit;
    }
}

// Auto-require authentication for protected pages
requireAuth();
?>
