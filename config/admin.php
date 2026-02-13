<?php
/**
 * Admin Authentication Configuration
 * Centralizes admin credentials with secure password hashing
 */

// Admin username - set via environment variable
define('ADMIN_USERNAME', getenv('ADMIN_USERNAME') ?: 'admin');

// Admin password hash (generated with password_hash())
define('ADMIN_PASSWORD_HASH', getenv('ADMIN_PASSWORD_HASH') ?: '$2y$10$invalid_placeholder_hash_change_me');

/**
 * To change the password:
 * 1. Generate a new bcrypt hash using an online generator:
 *    - https://bcrypt-generator.com/ (recommended)
 *    - Use 10 rounds for bcrypt
 * 2. Set the ADMIN_PASSWORD_HASH environment variable with the new hash
 * 3. The hash should start with $2y$10$ or $2a$10$
 */

/**
 * Verify admin credentials
 * @param string $username
 * @param string $password
 * @return bool
 */
function verifyAdminCredentials($username, $password) {
    return $username === ADMIN_USERNAME && password_verify($password, ADMIN_PASSWORD_HASH);
}
