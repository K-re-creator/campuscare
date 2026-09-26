<?php
declare(strict_types=1);

// Prevent session hijacking by configuring secure cookie policies
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params([
        'lifetime' => 0,                      // Cookie expires when the browser closes
        'path'     => '/',                    // Available across the entire domain
        'secure'   => !empty($_SERVER['HTTPS']), // True in production HTTPS, false for local HTTP testing
        'httponly' => true,                   // Blocks JavaScript access to the cookie (Mitigates XSS cookie theft)
        'samesite' => 'Lax',                  // Blocks cookie transmission on cross-site requests (Mitigates CSRF)
    ]);
    session_start();
}

/**
 * Generates or retrieves a cryptographically secure token for form verification.
 */
function csrfToken(): string
{
    if (!isset($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

/**
 * Intercepts state-changing requests and drops them if the CSRF token is invalid.
 */
function requireCsrf(): void
{
    $sent = (string)($_POST['csrf'] ?? '');
    if (!isset($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], $sent)) {
        http_response_code(403); // Forbidden
        exit('Request could not be verified due to missing or invalid CSRF token.');
    }
}

/**
 * Security Check: Ensures the user is logged in. 
 * If an anonymous visitor hits a page protected by this function, they get bounced straight to login.php.
 */
function requireUser(): array
{
    if (!isset($_SESSION['user']) || !is_array($_SESSION['user'])) {
        header('Location: /login.php', true, 303);
        exit;
    }
    return $_SESSION['user'];
}