<?php
declare(strict_types=1);

// Initialize secure session management and helpers
require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__ . '/../src/helpers.php';

// If already logged in, skip this page and go straight to bookings
if (isset($_SESSION['user'])) {
    header('Location: /bookings.php', true, 303);
    exit;
}

// Check if there is an error message saved in the session from a failed login attempt
$errorMessage = $_SESSION['login_error'] ?? null;
unset($_SESSION['login_error']); // Clear it immediately so it doesn't stay forever
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CampusCare</title>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
    <main id="main" style="max-width: 400px; margin: 2rem auto; padding: 1rem; border: 1px solid #ccc; border-radius: 8px;">
        <h1>CampusCare Login</h1>

        <?php if ($errorMessage): ?>
            <div style="background-color: #fce8e6; color: #c5221f; padding: 0.75rem; border-radius: 4px; margin-bottom: 1rem;" role="alert">
                <?= e($errorMessage) ?>
            </div>
        <?php endif; ?>

        <form action="/login-process.php" method="post" novalidate>
            <!-- 1. The mandatory anti-CSRF token hidden field -->
            <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">

            <div style="margin-bottom: 1rem;">
                <label playbook-focus for="email" style="display: block; margin-bottom: 0.25rem;">Email Address</label>
                <input type="email" id="email" name="email" required style="width: 100%; padding: 0.5rem; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 1rem;">
                <label playbook-focus for="password" style="display: block; margin-bottom: 0.25rem;">Password</label>
                <input type="password" id="password" name="password" required style="width: 100%; padding: 0.5rem; box-sizing: border-box;">
            </div>

            <button type="submit" style="padding: 0.5rem 1rem; background-color: #17365d; color: white; border: none; border-radius: 4px; cursor: pointer;">
                Sign In
            </button>
        </form>
    </main>
</body>
</html>
