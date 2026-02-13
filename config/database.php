<?php
/**
 * Database Configuration
 * PDO connection for MariaDB
 *
 * Environment detection:
 * - Development (local): uses staging database
 * - Production: uses production database
 */

require_once __DIR__ . '/env.php';

define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_CHARSET', 'utf8mb4');

/**
 * Detect if running in development environment
 * @return bool
 */
function isDevEnvironment() {
    // Check if running from CLI (for scripts, migrations, etc.)
    if (php_sapi_name() === 'cli') {
        // CLI on Windows = development
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }

    // Check server name for localhost
    $serverName = $_SERVER['SERVER_NAME'] ?? '';
    $httpHost = $_SERVER['HTTP_HOST'] ?? '';

    if ($serverName === 'localhost' || strpos($httpHost, 'localhost') !== false) {
        return true;
    }

    // Check document root for Windows paths (development)
    $docRoot = $_SERVER['DOCUMENT_ROOT'] ?? '';
    if (strpos($docRoot, 'E:\\') === 0 || strpos($docRoot, 'C:\\') === 0) {
        return true;
    }

    return false;
}

// Set database name based on environment
$defaultDbName = isDevEnvironment() ? 'myapp_staging' : 'myapp';
define('DB_NAME', getenv('DB_NAME') ?: $defaultDbName);

/**
 * Get database connection
 * @return PDO
 */
function getDbConnection() {
    static $pdo = null;

    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
            // Asegurar que la conexión use UTF-8
            $pdo->exec("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");
        } catch (PDOException $e) {
            error_log("Database connection error: " . $e->getMessage());
            http_response_code(500);
            die(json_encode(['error' => 'Error de conexión a la base de datos']));
        }
    }

    return $pdo;
}
