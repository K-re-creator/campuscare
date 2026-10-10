<?php
declare(strict_types=1);
// Inject temporary development runtime environment keys directly into memory
putenv('DB_HOST=127.0.0.1');
putenv('DB_NAME=campuscare');
putenv('DB_USER=root');
putenv('DB_PASSWORD='); // <-- Insert your local MySQL root password inside these quotes if you have one set up

// 2. DEFENSIVE SECURITY: Turn off public inline tracking dumps completely (Lab 10 requirement)
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL); 

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params([
        'lifetime' => 0,                      // Expires when browser closes
        'path'     => '/',
        'secure'   => !empty($_SERVER['HTTPS']), // Require HTTPS in production environments
        'httponly' => true,                   // Stop JavaScript cookie interception
        'samesite' => 'Lax',                  // Defend against third-party request leaks
    ]);
    session_start();
}

// 4. LAB 11 HARDENING: Inject Security Policy Response Headers globally
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self'; img-src 'self'; base-uri 'self'; frame-ancestors 'none'; object-src 'none';");

/**
 * Generates or fetches a cryptographically secure token for forms.
 */
function csrfToken(): string
{
    if (!isset($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

/**
 * Validates the inbound post token against the session baseline.
 */
function requireCsrf(): void
{
    $sent = (string)($_POST['csrf'] ?? '');
    if (!isset($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], $sent)) {
        http_response_code(403);
        exit('Request could not be verified (CSRF token mismatch).');
    }
}

/**
 * Guard tool ensuring anonymous clients are forced back to a login screen.
 */
function requireUser(): array
{
    if (!isset($_SESSION['user'])) {
        header('Location: /login.php', true, 303);
        exit;
    }
    return $_SESSION['user'];
}
