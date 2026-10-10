<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/bootstrap.php';

// 1. Guard Rule: Only allow POST submissions
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    http_response_code(405);
    exit('Method Not Allowed');
}

// 2. Security Check: Enforce anti-CSRF token verification before logging out
requireCsrf();

// 3. Clear all active array storage values in server memory
$_SESSION = [];

// 4. Overwrite and expire the session tracking browser cookie
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}

// 5. Explicitly destroy the structural lifecycle on the host server
session_destroy();

// 6. Redirect to login page using PRG pattern
header('Location: /login.php', true, 303);
exit;
