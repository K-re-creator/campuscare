<?php
declare(strict_types=1);

/**
 * Returns a globally shared, securely configured PDO instance.
 */
function database(): PDO
{
    static $pdo;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    // Pull configuration details from secure environment variables
    $host = getenv('DB_HOST') ?: '127.0.0.1';
    $name = getenv('DB_NAME') ?: 'campuscare';
    
    // Check if the variables are explicitly set (allows empty strings for passwords)
    $user = getenv('DB_USER');
    $pass = getenv('DB_PASSWORD');

    if ($user === false) {
        throw new RuntimeException('Database environment configuration: DB_USER is missing.');
    }
    
    if ($pass === false) {
        throw new RuntimeException('Database environment configuration: DB_PASSWORD is missing.');
    }

    $pdo = new PDO(
        "mysql:host={$host};dbname={$name};charset=utf8mb4",
        $user,
        $pass,
        [
            // Force PDO to throw strict exceptions for query syntax errors
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            // Fetch records as clean, associative arrays by default
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            // Critical Security: Turn OFF simulated prepared statements
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );

    return $pdo;
}
