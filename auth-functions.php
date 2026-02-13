<?php
// auth-functions.php - Shared authentication functions
// Used by both auth.php (protected pages) and login.php (login handler)

// Configuration - set via environment variables
define('AUTH_USERNAME', getenv('AUTH_USERNAME') ?: 'user');
define('AUTH_PASSWORD', getenv('AUTH_PASSWORD') ?: 'changeme');

// Check if user is authenticated (includes both regular users and admins)
function isAuthenticated() {
    return (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true)
        || (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true);
}

// Authenticate user with username and password
function authenticate($username, $password) {
    if ($username === AUTH_USERNAME && $password === AUTH_PASSWORD) {
        $_SESSION['authenticated'] = true;
        $_SESSION['login_time'] = time();
        return true;
    }
    return false;
}

// Logout user
function logout() {
    $_SESSION = array();
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    session_destroy();
}
?>
