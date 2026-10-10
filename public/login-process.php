<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__ . '/../src/helpers.php';

// 1. Guard Rule: Only allow POST submissions
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    http_response_code(405);
    exit('Method Not Allowed');
}

// 2. Security Check: Enforce CSRF token verification
requireCsrf();

// CLEANUP: Trim whitespace from BOTH email and password fields to prevent paste errors
$email = trim((string)($_POST['email'] ?? ''));
$password = trim((string)($_POST['password'] ?? ''));

// 3. Test Credentials Configuration
$mockEmail = 'student@example.test';
$mockPassword = 'password123'; 

// 4. Generate the target baseline hash
$mockHash = password_hash($mockPassword, PASSWORD_DEFAULT);

// 5. Verify credentials safely
if ($email === $mockEmail && password_verify($password, $mockHash)) {
    // Success: Rotate session ID to prevent fixation hijack vectors
    session_regenerate_id(true);
    
    // Set up user context
    $_SESSION['user'] = [
        'id' => 1,
        'email' => $mockEmail,
        'role' => 'customer'
    ];
    
    header('Location: /bookings.php', true, 303);
    exit;
} else {
    // Failure path
    $_SESSION['login_error'] = 'Email or password is incorrect.';
    header('Location: /login.php', true, 303);
    exit;
}