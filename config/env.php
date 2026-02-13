<?php
/**
 * Lightweight .env file loader
 * Reads KEY=value lines from .env and sets them via putenv().
 * Existing environment variables (e.g. from web server config) are NOT overwritten.
 */
function loadEnv(string $path): void {
    if (!is_file($path)) {
        return;
    }
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') {
            continue;
        }
        if (strpos($line, '=') === false) {
            continue;
        }
        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value, " \t\n\r\0\x0B\"'");
        if (!getenv($key)) {
            putenv("$key=$value");
        }
    }
}

loadEnv(__DIR__ . '/../.env');
